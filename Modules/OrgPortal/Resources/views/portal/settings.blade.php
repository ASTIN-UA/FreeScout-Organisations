@extends('enduserportal::layouts.portal')

@section('title', __('orgportal::messages.org_notification_settings'))

@section('content')
<div id="eup-container">
    <div class="eup-container-padded">

        <div class="heading margin-bottom text-center">
            {{ __('orgportal::messages.org_notification_settings') }}
        </div>

        @if(session('flash_success'))
            <div class="alert alert-success">{{ session('flash_success') }}</div>
        @endif

        @include('orgportal::portal.settings_inline', [
            'member'     => $member,
            'mailbox_id' => $mailbox_id,
        ])

    </div>
</div>
@endsection
