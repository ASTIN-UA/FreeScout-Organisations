<?php

namespace Modules\OrgPortal\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Customer;
use App\Conversation;

class OrgNewTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public Customer $manager;
    public Customer $author;
    public Conversation $conversation;

    public function __construct(Customer $manager, Customer $author, Conversation $conversation)
    {
        $this->manager      = $manager;
        $this->author       = $author;
        $this->conversation = $conversation;
    }

    public function build()
    {
        $authorName    = $this->author->getFullName(__('orgportal::messages.unknown'));
        $encodedMailbox = \EndUserPortal::encodeMailboxId($this->conversation->mailbox_id);
        $ticketUrl     = route('orgportal.portal.ticket', [
            'mailbox_id'      => $encodedMailbox,
            'conversation_id' => $this->conversation->id,
        ]);

        return $this
            ->subject(__('orgportal::messages.new_ticket_from', ['name' => $authorName]))
            ->view('orgportal::emails.new_ticket')
            ->with([
                'manager'      => $this->manager,
                'author'       => $this->author,
                'conversation' => $this->conversation,
                'ticketUrl'    => $ticketUrl,
            ]);
    }
}
