{{--
    Org badge.

    $asLink (default true): when true, renders a clickable <a> linking to the
    org search filter. In the conversations list the badge is injected INSIDE
    the row's <a> link, so a nested <a> would be invalid HTML and break the
    layout — there we pass $asLink = false to render a non-clickable <span>.
--}}
@php $asLink = $asLink ?? true; @endphp
@if ($asLink)
<a href="{{ $searchUrl }}"
   class="orgportal-org-badge"
   data-org-id="{{ $organization->id }}"
   title="{{ __('orgportal::messages.filter_by_org') }}">
    <span class="glyphicon glyphicon-briefcase" style="margin-right:3px;"></span>{{ $organization->name }}
</a>
@else
<span class="orgportal-org-badge"
      data-org-id="{{ $organization->id }}"
      title="{{ $organization->name }}">
    <span class="glyphicon glyphicon-briefcase" style="margin-right:3px;"></span>{{ $organization->name }}
</span>
@endif
