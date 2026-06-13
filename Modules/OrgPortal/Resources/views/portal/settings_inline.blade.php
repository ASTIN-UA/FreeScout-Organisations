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
@endphp

<form method="POST"
      action="{{ route('orgportal.portal.settings.save', ['mailbox_id' => $mailbox_id]) }}">
    {{ csrf_field() }}

    <table class="table table-bordered" style="margin-bottom:16px;">
        <thead>
            <tr>
                <th style="min-width:180px;">{{ __('orgportal::messages.notif_scope') }}</th>
                @foreach($events as $eKey => $eLabel)
                    <th class="text-center" style="white-space:nowrap;width:110px;">{{ $eLabel }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>

            {{-- "Вся організація" row (global manager only) --}}
            @if($isGlobal)
            <tr>
                <td style="font-weight:bold;">{{ __('orgportal::messages.notif_scope_org') }}</td>
                @foreach($events as $eKey => $eLabel)
                    <td class="text-center">
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
            @php $hasMembers = isset($unitMembersMap[$unit->id]); @endphp

            {{-- Unit row --}}
            <tr>
                <td style="{{ $isGlobal ? 'padding-left:24px;' : '' }}">
                    <span>{{ $unit->name }}</span>
                    @if($hasMembers)
                        <br>
                        <select class="form-control input-sm orgp-unit-select"
                                style="max-width:220px;margin-top:4px;"
                                data-unit="{{ $unit->id }}">
                            <option value="">— {{ __('orgportal::messages.select_member') }} —</option>
                            @foreach($unitMembersMap[$unit->id] as $um)
                                <option value="{{ $um->id }}">
                                    {{ optional($um->customer)->getFullName() ?: __('orgportal::messages.deleted_customer') }}
                                    @if($um->role === 'manager') ({{ __('orgportal::messages.role_manager_scoped') }}) @endif
                                </option>
                            @endforeach
                        </select>
                    @endif
                </td>
                @foreach($events as $eKey => $eLabel)
                    <td class="text-center">
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
                <tr class="orgp-member-row"
                    data-unit="{{ $unit->id }}"
                    data-member="{{ $um->id }}"
                    style="display:none;background:#f9f9f9;">
                    <td style="padding-left:{{ $isGlobal ? '48px' : '32px' }};color:#555;font-size:0.93em;">
                        {{ optional($um->customer)->getFullName() ?: __('orgportal::messages.deleted_customer') }}
                    </td>
                    @foreach($events as $eKey => $eLabel)
                        <td class="text-center">
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

    <small class="text-muted">{{ __('orgportal::messages.notif_hint') }}</small>

    <div style="margin-top:12px;">
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

    // Per-unit member dropdown → show selected member row, hide others in same unit
    document.querySelectorAll('.orgp-unit-select').forEach(function (sel) {
        sel.addEventListener('change', function () {
            var unitId   = this.dataset.unit;
            var memberId = this.value;
            document.querySelectorAll('.orgp-member-row[data-unit="' + unitId + '"]').forEach(function (row) {
                row.style.display = (memberId && row.dataset.member === memberId) ? '' : 'none';
            });
        });
    });
})();
</script>
