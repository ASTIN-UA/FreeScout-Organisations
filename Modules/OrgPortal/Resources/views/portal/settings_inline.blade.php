{{-- Reusable partial: rendered on the standalone settings page and injected via eup.settings.after hook --}}
<div class="orgportal-settings-section" style="margin-top: 24px;">
    <h4>{{ __('orgportal::messages.organization') }}</h4>

    <form method="POST" action="{{ route('orgportal.portal.settings.save') }}">
        @csrf

        <div class="checkbox">
            <label>
                <input type="checkbox"
                       name="notify_on_new_ticket"
                       value="1"
                       {{ $member->notify_on_new_ticket ? 'checked' : '' }}>
                {{ __('orgportal::messages.notify_new_ticket_label') }}
            </label>
        </div>

        <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 8px;">
            {{ __('orgportal::messages.save') }}
        </button>
    </form>
</div>
