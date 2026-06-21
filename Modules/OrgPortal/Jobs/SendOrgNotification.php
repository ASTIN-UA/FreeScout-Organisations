<?php

namespace Modules\OrgPortal\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOrgNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $timeout = 120;

    public int $mailboxId;
    public string $toEmail;
    public string $subject;
    public string $body;

    public function __construct(int $mailboxId, string $toEmail, string $subject, string $body)
    {
        $this->mailboxId = $mailboxId;
        $this->toEmail   = $toEmail;
        $this->subject   = $subject;
        $this->body      = $body;
    }

    public function handle(): void
    {
        $mailbox = \App\Mailbox::find($this->mailboxId);

        if (!$mailbox) {
            \Log::error('[OrgPortal] SendOrgNotification: mailbox not found', ['mailboxId' => $this->mailboxId]);
            return;
        }

        try {
            \MailHelper::setMailDriver($mailbox);
            \Mail::to($this->toEmail)->send(
                new \Modules\OrgPortal\Mail\OrgNotificationMail($this->subject, $this->body)
            );
        } catch (\Exception $e) {
            \Log::error('[OrgPortal] SendOrgNotification FAILED', ['to' => $this->toEmail, 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
