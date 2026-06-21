{{--
    Injected into the customer edit form via hook: customer.edit.after_fields
    JS is registered via 'javascript' action hook in OrgPortalServiceProvider (runs after jQuery).
--}}
@php
    $orgSearchBase  = rtrim(url(\Helper::getSubdirectory() . 'search'), '/');
    $orgTicketsUrl  = $currentOrgId
        ? $orgSearchBase . '?' . http_build_query(['f' => ['organization' => $currentOrgId]])
        : '#';
    $currentOrgObj  = $currentOrgId ? $organizations->firstWhere('id', $currentOrgId) : null;
    $currentOrgName = $currentOrgObj ? $currentOrgObj->name : '';
@endphp

<input type="hidden" name="orgportal_organization_id" id="orgportal_org_id" value="{{ $currentOrgId }}">

<div class="form-group">
    <label class="col-sm-2 control-label">
        {{ __('orgportal::messages.customer_organization') }}
    </label>
    <div class="col-sm-6" style="position:relative;">
        <div class="input-group">
            <input type="text"
                   id="orgportal_org_search"
                   class="form-control"
                   placeholder="{{ __('orgportal::messages.search_organizations') }}"
                   autocomplete="off"
                   value="{{ $currentOrgName }}"
                   data-search-url="{{ route('orgportal.admin.organizations.search') }}">
            <span class="input-group-btn">
                <button type="button" id="orgportal_org_clear" class="btn btn-default"
                        title="{{ __('orgportal::messages.no_organization') }}"
                        style="{{ $currentOrgId ? '' : 'display:none;' }}">
                    <span class="glyphicon glyphicon-remove"></span>
                </button>
            </span>
        </div>
        <ul id="orgportal_org_suggestions"
            style="position:absolute;z-index:9999;width:100%;display:none;
                   background:#fff;border:1px solid #ccc;border-radius:4px;
                   list-style:none;padding:0;margin:0;max-height:220px;overflow-y:auto;
                   top:100%;left:0;box-shadow:0 4px 8px rgba(0,0,0,.15);"></ul>
    </div>
</div>

<div class="form-group orgportal-role-row" style="{{ $currentOrgId ? '' : 'display:none;' }}">
    <label class="col-sm-2 control-label">
        {{ __('orgportal::messages.customer_role') }}
    </label>
    <div class="col-sm-6">
        <select name="orgportal_role" class="form-control">
            <option value="member"  {{ $currentRole === 'member'  ? 'selected' : '' }}>{{ __('orgportal::messages.member') }}</option>
            <option value="manager" {{ $currentRole === 'manager' ? 'selected' : '' }}>{{ __('orgportal::messages.manager') }}</option>
        </select>
    </div>
</div>

@if($currentOrgId)
<div class="form-group">
    <div class="col-sm-offset-2 col-sm-6">
        <a href="{{ $orgTicketsUrl }}" class="btn btn-default btn-sm" target="_blank">
            {{ __('orgportal::messages.view_org_tickets') }}
        </a>
    </div>
</div>
@endif
