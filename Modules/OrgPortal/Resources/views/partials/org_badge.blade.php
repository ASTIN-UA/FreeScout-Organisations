{{-- Org badge rendered via conversation.after_subject hook --}}
<a href="{{ $searchUrl }}"
   class="orgportal-org-badge"
   title="{{ __('orgportal::messages.filter_by_org') }}">
    <span class="glyphicon glyphicon-briefcase" style="font-size:11px;margin-right:3px;"></span>{{ $organization->name }}
</a>

<style>
.orgportal-org-badge {
    display: inline-block;
    padding: 2px 8px;
    margin: 0 0 2px 4px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: 500;
    line-height: 1.6;
    text-decoration: none;
    vertical-align: middle;
    color: #fff;
    background-color: #5b9bd5;
    border: 1px solid #4a8ac4;
    transition: background-color .15s;
}
.orgportal-org-badge:hover,
.orgportal-org-badge:focus {
    color: #fff;
    background-color: #4a8ac4;
    text-decoration: none;
}
</style>
