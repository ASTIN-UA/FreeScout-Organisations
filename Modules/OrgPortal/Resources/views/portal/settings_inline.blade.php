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

    .orgp-unit-toggle { display:inline-flex; align-items:center; gap:8px; cursor:pointer; color:#2c3e50; font-weight:600; text-decoration:none; user-select:none; }
    .orgp-unit-toggle:hover { color:#2168d3; text-decoration:none; }
    .orgp-unit-toggle:focus { outline:none; text-decoration:none; color:#2168d3; }
    .orgp-row-unit:hover td { background:#f7f9fc; }
    .orgp-chevron { width:0; height:0; border-top:4.5px solid transparent; border-bottom:4.5px solid transparent; border-left:6px solid #9aa5b4; transition:transform .15s ease; flex:0 0 auto; }
    .orgp-unit-toggle.is-open .orgp-chevron { transform:rotate(90deg); }
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
            <tr class="orgp-row-unit">
                <td style="{{ $unitPad }}">
                    @if($hasMembers)
                        <a href="#" class="orgp-unit-toggle" data-unit="{{ $unit->id }}">
                            <span class="orgp-chevron"></span>
                            <span>{{ $unit->name }}</span>
                            <span class="orgp-unit-count">{{ $unitMembersMap[$unit->id]->count() }}</span>
                        </a>
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
                                   {{ $subsChecked($memberSubsMap[$um->id] ?? [], $eKey, 'unit_' . $unit->id) ? 'checked' : '' }}>
                        </td>
                    @endforeach
                </tr>
                @endforeach
            @endif

            @endforeach

        </tbody>
    </table>

    <small class="orgp-hint">{{ __('orgportal::messages.notif_hint') }}</small>

    <div style="margin-top:14px;">
        <button type="submit" class="btn btn-primary">
            {{ __('orgportal::messages.save') }}
        </button>
    </div>
</form>

<script {!! \Helper::cspNonceAttr() !!}>
(function () {
    @if($isGlobal)
    // "Вся організація" → cascade to all unit checkboxes
    var eventKeys = @json(array_keys($events));
    eventKeys.forEach(function (eKey) {
        var orgCb   = document.querySelector('.orgp-org-cb[data-event="' + eKey + '"]');
        var unitCbs = document.querySelectorAll('.orgp-unit-cb[data-event="' + eKey + '"]');
        if (!orgCb) return;
        orgCb.addEventListener('change', function () {
            unitCbs.forEach(function (cb) { cb.checked = orgCb.checked; });
        });
        unitCbs.forEach(function (cb) {
            cb.addEventListener('change', function () {
                if (!this.checked) orgCb.checked = false;
            });
        });
    });
    @endif

    // Unit toggle → expand/collapse all member rows of that unit at once
    document.querySelectorAll('.orgp-unit-toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            var unitId = this.dataset.unit;
            var rows   = document.querySelectorAll('.orgp-member-row[data-unit="' + unitId + '"]');
            var expand = rows.length && rows[0].style.display === 'none';
            rows.forEach(function (row) { row.style.display = expand ? '' : 'none'; });
            this.classList.toggle('is-open', expand);
        });
    });
})();
</script>
