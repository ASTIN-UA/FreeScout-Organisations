@extends('endusrportal::portal')

@section('title', __('orgportal::messages.org_notification_settings'))

@section('content')
<div class="eup-content">

    <h2>{{ __('orgportal::messages.org_notification_settings') }}</h2>

    @if(session('flash_success'))
        <div class="alert alert-success">{{ session('flash_success') }}</div>
    @endif

    @include('orgportal::portal.settings_inline', ['member' => $member])

</div>
@endsection
