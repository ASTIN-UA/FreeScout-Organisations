{{-- Notification subscriptions tree. Requires: $member, $mailbox_id, $units, $subsMap, $memberSubsMap --}}
@php
    use Modules\OrgPortal\Models\OrgNotificationSubscription as Sub;

    $events = [
        Sub::EVENT_NEW_TICKET     => __('orgportal::messages.notif_event_new_ticket'),
        Sub::EVENT_REPLY_AGENT    => __('orgportal::messages.notif_event_reply_agent'),
        Sub::EVENT_REPLY_CUSTOMER => __('orgportal::messages.notif_event_reply_customer'),
    ];

    $isGlobal      = $member->isGlobalManager();
    $memberSubsMap = $memberSubsMap ?? [];

    $subsChecked = function(array $subsMap, string $event, string $scope): bool {
        $parts = explode('_', $scope, 2);
        $type  = $parts[0];
        $id    = $parts[1] ?? '';
        // 'unit_nounit' maps to scope_type='unit', scope_id=NULL → stored key 'event:unit:'
        if ($id === 'nounit') $id = '';
        return !empty($subsMap[$event . ':' . $type . ':' . $id]);
    };

    // Units visible to this manager.
    $visibleUnits = $units->filter(function ($unit) use ($isGlobal, $member) {
        return $isGlobal || $member->unit_id === $unit->id;
    });

    // Per-unit members (excluding current manager).
    $unitMembersMap = [];
    foreach ($visibleUnits as $unit) {
        $others = $unit->members->filter(fn($m) => $m->id !== $member->id)->values();
        if ($others->isNotEmpty()) {
            $unitMembersMap[$unit->id] = $others;
        }
    }

    // Members without any unit (global manager sees them; unit manager never has such members).
    $noUnitMembers = $isGlobal
        ? ($members ?? collect())->filter(fn($m) => $m->is_active && is_null($m->unit_id) && $m->id !== $member->id)->values()
        : collect();

    // Deterministic avatar colour from a string.
    $avatarColor = function(string $seed): string {
        $palette = ['#5b8def','#7c5cef','#ef5c8a','#ef8a5c','#27ae8f','#e0a30b','#3aa3c9','#9b59b6'];
        return $palette[crc32($seed) % count($palette)];
    };
@endphp

<style {!! \Helper::cspNonceAttr() !!}>
    .orgp-subs { border:1px solid #e4e8ee; border-radius:8px; overflow:hidden; width:100%; border-collapse:separate; border-spacing:0; margin-bottom:18px; font-size:14px; }
    .orgp-subs th, .orgp-subs td { padding:11px 14px; vertical-align:middle; }
    .orgp-subs thead th { background:#f7f9fc; color:#5a6573; font-weight:600; font-size:12px; text-transform:uppercase; letter-spacing:.03em; border-bottom:1px solid #e4e8ee; }
    .orgp-subs thead th.ev { text-align:center; width:120px; white-space:nowrap; }
    .orgp-subs tbody tr { border-bottom:1px solid #eef1f5; }
    .orgp-subs tbody tr:last-child { border-bottom:0; }
    .orgp-subs td.ev { text-align:center; }

    .orgp-row-org td { background:#fbfcfe; }
    .orgp-scope-org { font-weight:700; color:#2c3e50; }

    .orgp-row-unit.expandable { cursor:pointer; }
    .orgp-row-unit.expandable:hover td { background:#f7f9fc; }
    .orgp-unit-label { display:inline-flex; align-items:center; gap:8px; color:#2c3e50; font-weight:600; user-select:none; }
    .orgp-chevron { width:0; height:0; border-top:4.5px solid transparent; border-bottom:4.5px solid transparent; border-left:6px solid #9aa5b4; transition:transform .15s ease; flex:0 0 auto; }
    .orgp-row-unit.is-open .orgp-chevron { transform:rotate(90deg); }
    .orgp-unit-count { color:#9aa5b4; font-weight:500; font-size:13px; }
    .orgp-unit-name-plain { font-weight:600; color:#2c3e50; }

    .orgp-row-member td { background:#fafbfd; }
    .orgp-row-member:hover td { background:#f3f6fb; }
    .orgp-member-cell { display:flex; align-items:center; gap:9px; }
    .orgp-avatar { flex:0 0 auto; width:26px; height:26px; border-radius:50%; color:#fff; font-size:11px; font-weight:600; display:inline-flex; align-items:center; justify-content:center; text-transform:uppercase; }
    .orgp-member-name { color:#46505e; font-size:13.5px; }
    .orgp-member-tag { display:inline-block; margin-left:6px; padding:1px 7px; border-radius:10px; background:#e8edf4; color:#6a7686; font-size:11px; font-weight:600; vertical-align:middle; }

    .orgp-subs input[type=checkbox] { width:16px; height:16px; cursor:pointer; accent-color:#2168d3; margin:0; }
    .orgp-hint { color:#9aa5b4; font-size:13px; }
</style>

<form method="POST"
      action="{{ route('orgportal.portal.settings.save', ['mailbox_id' => $mailbox_id]) }}">
    {{ csrf_field() }}

    <div style="overflow-x:auto;-webkit-overflow-scrolling:touch;">
    <table class="orgp-subs">
        <thead>
            <tr>
                <th>{{ __('orgportal::messages.notif_scope') }}</th>
                @foreach($events as $eKey => $eLabel)
                    <th class="ev">{{ $eLabel }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>

            {{-- "Вся організація" row (global manager only) --}}
            @if($isGlobal)
            <tr class="orgp-row-org">
                <td><span class="orgp-scope-org">{{ __('orgportal::messages.notif_scope_org') }}</span></td>
                @foreach($events as $eKey => $eLabel)
                    <td class="ev">
                        <input type="checkbox"
                               name="subs[{{ $eKey }}][org]"
                               value="1"
                               class="orgp-org-cb" data-event="{{ $eKey }}"
                               {{ $subsChecked($subsMap, $eKey, 'org') ? 'checked' : '' }}>
                    </td>
                @endforeach
            </tr>
            @endif

            {{-- Unit rows --}}
            @foreach($visibleUnits as $unit)
            @php
                $hasMembers = isset($unitMembersMap[$unit->id]);
                $unitPad    = $isGlobal ? 'padding-left:30px;' : '';
            @endphp

            {{-- Unit row --}}
            <tr class="orgp-row-unit {{ $hasMembers ? 'expandable' : '' }}" data-unit="{{ $unit->id }}">
                <td style="{{ $unitPad }}">
                    @if($hasMembers)
                        <span class="orgp-unit-label">
                            <span class="orgp-chevron"></span>
                            <span>{{ $unit->name }}</span>
                            <span class="orgp-unit-count">{{ $unitMembersMap[$unit->id]->count() }}</span>
                        </span>
                    @else
                        <span class="orgp-unit-name-plain">{{ $unit->name }}</span>
                    @endif
                </td>
                @foreach($events as $eKey => $eLabel)
                    <td class="ev">
                        <input type="checkbox"
                               name="subs[{{ $eKey }}][unit_{{ $unit->id }}]"
                               value="1"
                               class="orgp-unit-cb" data-event="{{ $eKey }}" data-unit="{{ $unit->id }}"
                               {{ $subsChecked($subsMap, $eKey, 'unit_' . $unit->id) ? 'checked' : '' }}>
                    </td>
                @endforeach
            </tr>

            {{-- Hidden member rows for this unit --}}
            @if($hasMembers)
                @foreach($unitMembersMap[$unit->id] as $um)
                @php
                    $fullName = optional($um->customer)->getFullName() ?: __('orgportal::messages.deleted_customer');
                    $initials = mb_substr(trim($fullName), 0, 1);
                    $memPad   = $isGlobal ? 'padding-left:54px;' : 'padding-left:28px;';
                @endphp
                <tr class="orgp-row-member orgp-member-row"
                    data-unit="{{ $unit->id }}"
                    data-member="{{ $um->id }}"
                    style="display:none;">
                    <td style="{{ $memPad }}">
                        <span class="orgp-member-cell">
                            <span class="orgp-avatar" style="background:{{ $avatarColor($fullName) }};">{{ $initials }}</span>
                            <span class="orgp-member-name">{{ $fullName }}</span>
                            @if($um->role === 'manager')
                                <span class="orgp-member-tag">{{ __('orgportal::messages.role_manager_scoped') }}</span>
                            @endif
                        </span>
                    </td>
                    @foreach($events as $eKey => $eLabel)
                        <td class="ev">
                            <input type="checkbox"
                                   name="member_subs[{{ $um->id }}][{{ $eKey }}][unit_{{ $unit->id }}]"
                                   value="1"
                                   class="orgp-member-cb" data-event="{{ $eKey }}" data-unit="{{ $unit->id }}"
                                   {{ $subsChecked($memberSubsMap[$um->id] ?? [], $eKey, 'unit_' . $unit->id) ? 'checked' : '' }}>
                        </td>
                    @endforeach
                </tr>
                @endforeach
            @endif

            @endforeach

            {{-- "Без підрозділу" virtual group — members with unit_id = NULL (global manager only) --}}
            @if($isGlobal && $noUnitMembers->isNotEmpty())
            @php $noUnitPad = 'padding-left:30px;'; @endphp
            <tr class="orgp-row-unit {{ $noUnitMembers->count() > 1 ? 'expandable' : '' }}" data-unit="nounit">
                <td style="{{ $noUnitPad }}">
                    @if($noUnitMembers->count() > 1)
                        <span class="orgp-unit-label">
                            <span class="orgp-chevron"></span>
                            <span>{{ __('orgportal::messages.notif_scope_no_unit') }}</span>
                            <span class="orgp-unit-count">{{ $noUnitMembers->count() }}</span>
                        </span>
                    @else
                        <span class="orgp-unit-name-plain">{{ __('orgportal::messages.notif_scope_no_unit') }}</span>
                    @endif
                </td>
                @foreach($events as $eKey => $eLabel)
                    <td class="ev">
                        <input type="checkbox"
                               name="subs[{{ $eKey }}][unit_nounit]"
                               value="1"
                               class="orgp-unit-cb" data-event="{{ $eKey }}" data-unit="nounit"
                               {{ $subsChecked($subsMap, $eKey, 'unit_nounit') ? 'checked' : '' }}>
                    </td>
                @endforeach
            </tr>

            @foreach($noUnitMembers as $um)
            @php
                $fullName = optional($um->customer)->getFullName() ?: __('orgportal::messages.deleted_customer');
                $initials = mb_substr(trim($fullName), 0, 1);
            @endphp
            <tr class="orgp-row-member orgp-member-row"
                data-unit="nounit"
                data-member="{{ $um->id }}"
                style="{{ $noUnitMembers->count() > 1 ? 'display:none;' : '' }}">
                <td style="padding-left:54px;">
                    <span class="orgp-member-cell">
                        <span class="orgp-avatar" style="background:{{ $avatarColor($fullName) }};">{{ $initials }}</span>
                        <span class="orgp-member-name">{{ $fullName }}</span>
                        @if($um->role === 'manager')
                            <span class="orgp-member-tag">{{ __('orgportal::messages.role_manager_scoped') }}</span>
                        @endif
                    </span>
                </td>
                @foreach($events as $eKey => $eLabel)
                    <td class="ev">
                        <input type="checkbox"
                               name="member_subs[{{ $um->id }}][{{ $eKey }}][unit_nounit]"
                               value="1"
                               class="orgp-member-cb" data-event="{{ $eKey }}" data-unit="nounit"
                               {{ $subsChecked($memberSubsMap[$um->id] ?? [], $eKey, 'unit_nounit') ? 'checked' : '' }}>
                    </td>
                @endforeach
            </tr>
            @endforeach
            @endif

        </tbody>
    </table>
    </div>

    <small class="orgp-hint">{{ __('orgportal::messages.notif_hint') }}</small>

    <div style="margin-top:14px;">
        <button type="submit" class="btn btn-primary">
            {{ __('orgportal::messages.save') }}
        </button>
    </div>
</form>

<script {!! \Helper::cspNonceAttr() !!}>
(function () {
    var $$ = function (sel, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(sel));
    };

    // ── Fully transitive cascade per event column ────────────────────────────
    var eventKeys = @json(array_keys($events));
    eventKeys.forEach(function (eKey) {
        var orgCb    = document.querySelector('.orgp-org-cb[data-event="' + eKey + '"]');
        var unitCbs  = $$('.orgp-unit-cb[data-event="' + eKey + '"]');

        var membersOf = function (unitId) {
            return $$('.orgp-member-cb[data-event="' + eKey + '"][data-unit="' + unitId + '"]');
        };
        var unitCbOf = function (unitId) {
            return document.querySelector('.orgp-unit-cb[data-event="' + eKey + '"][data-unit="' + unitId + '"]');
        };

        // Recompute org from units (org checked ⇔ every unit checked).
        var reconcileOrg = function () {
            if (!orgCb) return;
            orgCb.checked = unitCbs.length > 0 && unitCbs.every(function (c) { return c.checked; });
        };
        // Recompute a unit from its members (unit checked ⇔ every member checked).
        var reconcileUnit = function (unitId) {
            var unitCb = unitCbOf(unitId);
            var mem    = membersOf(unitId);
            if (unitCb && mem.length > 0) {
                unitCb.checked = mem.every(function (c) { return c.checked; });
            }
        };

        // Org → every unit + every member.
        if (orgCb) {
            orgCb.addEventListener('change', function () {
                var s = orgCb.checked;
                unitCbs.forEach(function (u) {
                    u.checked = s;
                    membersOf(u.dataset.unit).forEach(function (m) { m.checked = s; });
                });
            });
        }

        // Unit → its members; then bubble up to org.
        unitCbs.forEach(function (u) {
            u.addEventListener('change', function () {
                var s = u.checked;
                membersOf(u.dataset.unit).forEach(function (m) { m.checked = s; });
                reconcileOrg();
            });
        });

        // Member → its unit → org.
        $$('.orgp-member-cb[data-event="' + eKey + '"]').forEach(function (m) {
            m.addEventListener('change', function () {
                reconcileUnit(m.dataset.unit);
                reconcileOrg();
            });
        });
    });

    // ── Expand / collapse: whole unit row is clickable ───────────────────────
    $$('.orgp-row-unit.expandable').forEach(function (row) {
        row.addEventListener('click', function (e) {
            // Don't toggle when interacting with the checkbox columns.
            if (e.target.closest('.ev')) return;
            var unitId   = this.dataset.unit;
            var memRows  = $$('.orgp-member-row[data-unit="' + unitId + '"]');
            var expand   = memRows.length && memRows[0].style.display === 'none';
            memRows.forEach(function (r) { r.style.display = expand ? '' : 'none'; });
            this.classList.toggle('is-open', expand);
        });
    });
})();
</script>
