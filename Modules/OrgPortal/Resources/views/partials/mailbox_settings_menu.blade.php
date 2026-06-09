<li @if(Route::is('orgportal.admin.mailbox-settings')) class="active" @endif>
    <a href="{{ route('orgportal.admin.mailbox-settings', ['id' => $mailbox->id]) }}">
        <i class="glyphicon glyphicon-briefcase"></i> {{ __('orgportal::messages.module_settings') }}
    </a>
</li>
