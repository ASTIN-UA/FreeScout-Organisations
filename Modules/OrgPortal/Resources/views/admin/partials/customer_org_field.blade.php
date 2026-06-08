{{--
    Injected into the customer edit form via hook: customer.edit.after_fields
    Renders organization + role dropdowns using FreeScout's Bootstrap 3 form layout.
--}}
<div class="form-group">
    <label class="col-sm-2 control-label">
        {{ __('orgportal::messages.customer_organization') }}
    </label>
    <div class="col-sm-6">
        <select name="orgportal_organization_id" class="form-control">
            <option value="">{{ __('orgportal::messages.no_organization') }}</option>
            @foreach($organizations as $org)
                <option value="{{ $org->id }}"
                    {{ (string)$currentOrgId === (string)$org->id ? 'selected' : '' }}>
                    {{ $org->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group orgportal-role-row" style="{{ $currentOrgId ? '' : 'display:none;' }}">
    <label class="col-sm-2 control-label">
        {{ __('orgportal::messages.customer_role') }}
    </label>
    <div class="col-sm-6">
        <select name="orgportal_role" class="form-control">
            <option value="member"  {{ $currentRole === 'member'  ? 'selected' : '' }}>
                {{ __('orgportal::messages.member') }}
            </option>
            <option value="manager" {{ $currentRole === 'manager' ? 'selected' : '' }}>
                {{ __('orgportal::messages.manager') }}
            </option>
        </select>
    </div>
</div>

@php
    $orgSearchBase = rtrim(url(\Helper::getSubdirectory() . 'search'), '/');
    $orgTicketsUrl = $currentOrgId
        ? $orgSearchBase . '?' . http_build_query(['f' => ['organization' => $currentOrgId]])
        : '#';
@endphp
<div class="form-group orgportal-tickets-row" style="{{ $currentOrgId ? '' : 'display:none;' }}">
    <div class="col-sm-offset-2 col-sm-6">
        <a id="orgportal-tickets-link"
           href="{{ $orgTicketsUrl }}"
           class="btn btn-default btn-sm"
           target="_blank">
            {{ __('orgportal::messages.view_org_tickets') }}
        </a>
    </div>
</div>

<script>
(function () {
    var orgSelect   = document.querySelector('select[name="orgportal_organization_id"]');
    var roleRow     = document.querySelector('.orgportal-role-row');
    var ticketsRow  = document.querySelector('.orgportal-tickets-row');
    var ticketsLink = document.getElementById('orgportal-tickets-link');
    var baseUrl     = '{{ $orgSearchBase }}';

    if (!orgSelect || !roleRow) return;

    orgSelect.addEventListener('change', function () {
        var val = this.value;
        roleRow.style.display    = val ? '' : 'none';
        if (ticketsRow) {
            ticketsRow.style.display = val ? '' : 'none';
        }
        if (ticketsLink && val) {
            ticketsLink.href = baseUrl + '?f%5Borganization%5D=' + val;
        }
    });
})();
</script>
