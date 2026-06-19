<?php
/**
 * Default notification email templates — German (de).
 */
return [
    'new_ticket' => [
        'subject' => 'Neues Ticket {ticket_number} von {author_name}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>Hallo, <strong>{manager_name}</strong>!</p>'
            . '<p>Ein neues Support-Ticket wurde von einem Mitglied Ihrer Organisation <strong>{org_name}</strong> eingereicht:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Von:</td><td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Betreff:</td><td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Ticket-Nr.:</td><td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Datum:</td><td style="padding:6px 0;">{created_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{ticket_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">Ticket anzeigen</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">Sie haben diese E-Mail erhalten, weil Sie Benachrichtigungen für Ihre Organisation im Kundenportal aktiviert haben.</p>'
            . '</div>',
    ],
    'reply_agent' => [
        'subject' => 'Re: {ticket_number} — {subject}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>Hallo, <strong>{manager_name}</strong>!</p>'
            . '<p>Ein Support-Agent hat auf ein Ticket in Ihrer Organisation <strong>{org_name}</strong> geantwortet:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Kunde:</td><td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Betreff:</td><td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Ticket-Nr.:</td><td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Antwortet am:</td><td style="padding:6px 0;">{reply_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">Ticket anzeigen</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">Sie haben diese E-Mail erhalten, weil Sie Benachrichtigungen für Ihre Organisation im Kundenportal aktiviert haben.</p>'
            . '</div>',
    ],
    'reply_customer' => [
        'subject' => 'Re: {ticket_number} — {subject}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>Hallo, <strong>{manager_name}</strong>!</p>'
            . '<p>Ein Kunde hat auf ein Ticket in Ihrer Organisation <strong>{org_name}</strong> geantwortet:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Von:</td><td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Betreff:</td><td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Ticket-Nr.:</td><td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Antwortet am:</td><td style="padding:6px 0;">{reply_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">Ticket anzeigen</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">Sie haben diese E-Mail erhalten, weil Sie Benachrichtigungen für Ihre Organisation im Kundenportal aktiviert haben.</p>'
            . '</div>',
    ],
];
