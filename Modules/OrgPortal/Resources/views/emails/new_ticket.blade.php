<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="font-family: Arial, sans-serif; font-size: 15px; color: #333; background: #f5f5f5; margin: 0; padding: 20px;">
<div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 6px; padding: 30px; border: 1px solid #e0e0e0;">

    <p>{{ __('orgportal::messages.email_hello') }},</p>

    <p>{{ __('orgportal::messages.email_new_ticket_intro') }}</p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <tr>
            <td style="padding: 6px 0; color: #666; width: 140px;">{{ __('orgportal::messages.email_from') }}:</td>
            <td style="padding: 6px 0;"><strong>{{ $author->getFullName() }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #666;">{{ __('orgportal::messages.email_subject') }}:</td>
            <td style="padding: 6px 0;"><strong>{{ $conversation->subject }}</strong></td>
        </tr>
        <tr>
            <td style="padding: 6px 0; color: #666;">{{ __('orgportal::messages.email_ticket_number') }}:</td>
            <td style="padding: 6px 0;">#{{ $conversation->number }}</td>
        </tr>
    </table>

    @if ($ticketUrl)
    <p style="margin-top: 24px;">
        <a href="{{ $ticketUrl }}"
           style="display: inline-block; padding: 10px 22px; background: #3b82f6; color: #fff;
                  text-decoration: none; border-radius: 4px; font-weight: bold;">
            {{ __('orgportal::messages.view_ticket') }}
        </a>
    </p>
    @endif

    <p style="margin-top: 32px; font-size: 12px; color: #999;">
        {{ __('orgportal::messages.email_new_ticket_footer') }}
    </p>
</div>
</body>
</html>
