<div class="customer-section" style="margin-top:8px; border-top:1px solid #f0f0f0; padding-top:8px;">
    <div style="font-size:12px; color:#888; margin-bottom:3px;">{{ __('orgportal::messages.customer_organization') }}</div>
    <div style="font-size:13px; font-weight:600;">
        <a href="{{ route('orgportal.admin.edit', ['id' => $member->organization_id]) }}" style="color:#3c8dbc;">{{ e($member->organization->name) }}</a>
    </div>
    @if ($member->unit)
        <div style="font-size:12px; color:#555; margin-top:2px;">{{ e($member->unit->name) }}</div>
    @endif
    <div style="font-size:12px; color:#777; margin-top:2px;">{{ $roleLabel }}</div>
</div>
