{{-- Reusable settings form; requires $member and $mailbox_id --}}
<form method="POST"
      action="{{ route('orgportal.portal.settings.save', ['mailbox_id' => $mailbox_id]) }}">
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

    <button type="submit" class="btn btn-primary" style="margin-top:12px;">
        {{ __('orgportal::messages.save') }}
    </button>
</form>
