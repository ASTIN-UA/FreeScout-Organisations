<?php

namespace Modules\OrgPortal\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrgNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $htmlBody;
    public string $emailSubject;

    public function __construct(string $subject, string $htmlBody)
    {
        $this->emailSubject = $subject;
        $this->htmlBody     = $htmlBody;
    }

    public function build()
    {
        return $this
            ->subject($this->emailSubject)
            ->view('orgportal::emails.notification')
            ->with(['htmlBody' => $this->htmlBody]);
    }
}
