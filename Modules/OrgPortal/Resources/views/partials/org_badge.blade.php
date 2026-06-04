{{-- Org badge rendered via conversation.after_subject hook --}}
<a href="{{ $searchUrl }}"
   class="orgportal-org-badge"
   title="{{ __('orgportal::messages.filter_by_org') }}">
    <span class="glyphicon glyphicon-briefcase" style="font-size:11px;margin-right:3px;"></span>{{ $organization->name }}
</a>
