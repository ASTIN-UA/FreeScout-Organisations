{{--
    Reusable partial: rendered both on the standalone settings page
    and injected inline into EUP settings via the eup.settings.after hook.
--}}
<div class="orgportal-settings-section" style="margin-top: 24px;">
    <h4>{{ __('Organization') }}</h4>

    <form method="POST" action="{{ route('orgportal.portal.settings.save') }}">
        @csrf

        <div class="checkbox">
            <label>
                <input type="checkbox"
                       name="notify_on_new_ticket"
                       value="1"
                       {{ $member->notify_on_new_ticket ? 'checked' : '' }}>
                {{ __('Receive email notification when a member of my organization opens a new ticket') }}
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 8px;">
            {{ __('Save') }}
        </button>
    </form>
</div>
