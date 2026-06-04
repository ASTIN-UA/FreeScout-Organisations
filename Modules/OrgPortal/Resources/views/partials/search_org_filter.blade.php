{{--
    Injected into FreeScout search form via hook: search.display_filters
    Matches the col-sm-6 / label + select layout used by core filters.
--}}
<div class="col-sm-6">
    <div class="form-group {{ $currentOrgId ? 'search-filter-active' : '' }}">
        <label>{{ __('orgportal::messages.organization') }}</label>
        @if($currentOrgId)
            <a href="#" class="search-filter-remove" data-filter="organization"
               title="{{ __('orgportal::messages.remove_filter') }}">×</a>
        @endif
        <select name="f[organization]" class="form-control select-no-search">
            <option value="">{{ __('orgportal::messages.all_organizations') }}</option>
            @foreach($organizations as $org)
                <option value="{{ $org->id }}" {{ (string)$currentOrgId === (string)$org->id ? 'selected' : '' }}>
                    {{ $org->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
