{{-- Notification subscriptions tree. Requires: $member, $mailbox_id, $units, $subsMap --}}
@php
    use Modules\OrgPortal\Models\OrgNotificationSubscription as Sub;

    $events = [
        Sub::EVENT_NEW_TICKET     => __('orgportal::messages.notif_event_new_ticket'),
        Sub::EVENT_REPLY_AGENT    => __('orgportal::messages.notif_event_reply_agent'),
        Sub::EVENT_REPLY_CUSTOMER => __('orgportal::messages.notif_event_reply_customer'),
    ];

    $isGlobal = $member->isGlobalManager();

    // Build tree rows: each row has 'label', 'scope_type', 'scope_id_key' (org | unit_X)
    $rows = [];
    if ($isGlobal) {
        $rows[] = [
            'label'    => __('orgportal::messages.notif_scope_org'),
            'scope'    => 'org',
            'indent'   => false,
            'bold'     => true,
        ];
    }
    foreach ($units as $unit) {
        if (!$isGlobal && $member->unit_id !== $unit->id) continue;
        $rows[] = [
            'label'  => $unit->name,
            'scope'  => 'unit_' . $unit->id,
            'indent' => $isGlobal,
            'bold'   => false,
        ];
    }

    $subsChecked = function(array $subsMap, string $event, string $scope): bool {
        $parts = explode('_', $scope, 2);
        $type  = $parts[0];
        $id    = $parts[1] ?? '';
        return !empty($subsMap[$event . ':' . $type . ':' . $id]);
    };
@endphp

<form method="POST"
      action="{{ route('orgportal.portal.settings.save', ['mailbox_id' => $mailbox_id]) }}">
    {{ csrf_field() }}

    <div>
        <table class="table table-bordered" style="margin-bottom:16px;">
            <thead>
                <tr>
                    <th style="min-width:160px;">{{ __('orgportal::messages.notif_scope') }}</th>
                    @foreach($events as $eKey => $eLabel)
                        <th class="text-center" style="white-space:nowrap;width:110px;">{{ $eLabel }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td style="{{ $row['indent'] ? 'padding-left:24px;' : '' }}{{ $row['bold'] ? 'font-weight:bold;' : '' }}">
                            {{ $row['label'] }}
                        </td>
                        @foreach($events as $eKey => $eLabel)
                            <td class="text-center">
                                <input type="checkbox"
                                       name="subs[{{ $eKey }}][{{ $row['scope'] }}]"
                                       value="1"
                                       {{ $subsChecked($subsMap, $eKey, $row['scope']) ? 'checked' : '' }}>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <small class="text-muted">{{ __('orgportal::messages.notif_hint') }}</small>

    <div style="margin-top:12px;">
        <button type="submit" class="btn btn-primary">
            {{ __('orgportal::messages.save') }}
        </button>
    </div>
</form>

@if($isGlobal)
<script {!! \Helper::cspNonceAttr() !!}>
(function () {
    var events = @json(array_keys($events));

    events.forEach(function (eKey) {
        var orgCb    = document.querySelector('input[name="subs[' + eKey + '][org]"]');
        var unitCbs  = document.querySelectorAll('input[name^="subs[' + eKey + '][unit_"]');

        if (!orgCb) return;

        // "Вся організація" toggled → mirror to all units
        orgCb.addEventListener('change', function () {
            var state = this.checked;
            unitCbs.forEach(function (cb) { cb.checked = state; });
        });

        // Any unit unchecked → uncheck "Вся організація"
        unitCbs.forEach(function (cb) {
            cb.addEventListener('change', function () {
                if (!this.checked) {
                    orgCb.checked = false;
                }
            });
        });
    });
})();
</script>
@endif
