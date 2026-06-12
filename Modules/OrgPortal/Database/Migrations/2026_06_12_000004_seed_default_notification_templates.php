<?php

use Illuminate\Database\Migrations\Migration;

// TODO(locale): templates are seeded in English; in a future iteration,
// render subject/body through App::setLocale($managerLocale) at send time
// so managers receive notifications in the portal's configured language.

class SeedDefaultNotificationTemplates extends Migration
{
    private function defaultTemplates(): array
    {
        return [
            'new_ticket' => [
                'subject' => 'New ticket {ticket_number} from {author_name}',
                'body'    => $this->wrapBody(
                    '<p>Hello, <strong>{manager_name}</strong>!</p>'
                    . '<p>A new support ticket has been submitted by a member of your organization <strong>{org_name}</strong>:</p>'
                    . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
                    .   '<tr>'
                    .     '<td style="color:#666;width:140px;padding:6px 0;">From:</td>'
                    .     '<td style="padding:6px 0;"><strong>{author_name}</strong>'
                    .       '<span style="color:#999;font-size:12px;margin-left:8px;">({unit_name})</span></td>'
                    .   '</tr>'
                    .   '<tr>'
                    .     '<td style="color:#666;padding:6px 0;">Subject:</td>'
                    .     '<td style="padding:6px 0;"><strong>{subject}</strong></td>'
                    .   '</tr>'
                    .   '<tr>'
                    .     '<td style="color:#666;padding:6px 0;">Ticket #:</td>'
                    .     '<td style="padding:6px 0;">{ticket_number}</td>'
                    .   '</tr>'
                    .   '<tr>'
                    .     '<td style="color:#666;padding:6px 0;">Date:</td>'
                    .     '<td style="padding:6px 0;">{created_datetime}</td>'
                    .   '</tr>'
                    . '</table>'
                    . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">View Ticket</a></p>'
                ),
            ],
            'reply_agent' => [
                'subject' => 'Re: {ticket_number} — {subject}',
                'body'    => $this->wrapBody(
                    '<p>Hello, <strong>{manager_name}</strong>!</p>'
                    . '<p>A support agent has replied to a ticket in your organization <strong>{org_name}</strong>:</p>'
                    . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
                    .   '<tr>'
                    .     '<td style="color:#666;width:140px;padding:6px 0;">Customer:</td>'
                    .     '<td style="padding:6px 0;"><strong>{author_name}</strong>'
                    .       '<span style="color:#999;font-size:12px;margin-left:8px;">({unit_name})</span></td>'
                    .   '</tr>'
                    .   '<tr>'
                    .     '<td style="color:#666;padding:6px 0;">Subject:</td>'
                    .     '<td style="padding:6px 0;"><strong>{subject}</strong></td>'
                    .   '</tr>'
                    .   '<tr>'
                    .     '<td style="color:#666;padding:6px 0;">Ticket #:</td>'
                    .     '<td style="padding:6px 0;">{ticket_number}</td>'
                    .   '</tr>'
                    .   '<tr>'
                    .     '<td style="color:#666;padding:6px 0;">Replied at:</td>'
                    .     '<td style="padding:6px 0;">{reply_datetime}</td>'
                    .   '</tr>'
                    . '</table>'
                    . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">View Ticket</a></p>'
                ),
            ],
            'reply_customer' => [
                'subject' => 'Re: {ticket_number} — {subject}',
                'body'    => $this->wrapBody(
                    '<p>Hello, <strong>{manager_name}</strong>!</p>'
                    . '<p>A customer has replied to a ticket in your organization <strong>{org_name}</strong>:</p>'
                    . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
                    .   '<tr>'
                    .     '<td style="color:#666;width:140px;padding:6px 0;">From:</td>'
                    .     '<td style="padding:6px 0;"><strong>{author_name}</strong>'
                    .       '<span style="color:#999;font-size:12px;margin-left:8px;">({unit_name})</span></td>'
                    .   '</tr>'
                    .   '<tr>'
                    .     '<td style="color:#666;padding:6px 0;">Subject:</td>'
                    .     '<td style="padding:6px 0;"><strong>{subject}</strong></td>'
                    .   '</tr>'
                    .   '<tr>'
                    .     '<td style="color:#666;padding:6px 0;">Ticket #:</td>'
                    .     '<td style="padding:6px 0;">{ticket_number}</td>'
                    .   '</tr>'
                    .   '<tr>'
                    .     '<td style="color:#666;padding:6px 0;">Replied at:</td>'
                    .     '<td style="padding:6px 0;">{reply_datetime}</td>'
                    .   '</tr>'
                    . '</table>'
                    . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">View Ticket</a></p>'
                ),
            ],
        ];
    }

    private function wrapBody(string $content): string
    {
        return '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . $content
            . '<p style="margin-top:32px;font-size:12px;color:#999;">You received this email because you enabled notifications for your organization in the Customer Portal.</p>'
            . '</div>';
    }

    public function up()
    {
        $templates = \Modules\OrgPortal\Http\Controllers\OrgPortalAdminController::defaultTemplates(
            config('app.locale', 'en')
        );

        foreach ($templates as $event => $tpl) {
            $subjectKey = 'orgportal.tpl_' . $event . '_subject';
            $bodyKey    = 'orgportal.tpl_' . $event . '_body';

            if (empty(\Option::get($subjectKey))) {
                \Option::set($subjectKey, $tpl['subject']);
            }
            if (empty(\Option::get($bodyKey))) {
                \Option::set($bodyKey, $tpl['body']);
            }
        }
    }

    public function down()
    {
        foreach (['new_ticket', 'reply_agent', 'reply_customer'] as $event) {
            \Option::remove('orgportal.tpl_' . $event . '_subject');
            \Option::remove('orgportal.tpl_' . $event . '_body');
        }
    }
}
