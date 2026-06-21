<?php
/**
 * Default notification email templates — Slovak (sk).
 *
 * Each event has 'subject' (plain text, macros allowed) and
 * 'body' (HTML, macros allowed).
 *
 * Available macros:
 *   {manager_name}  {author_name}  {org_name}     {unit_name}
 *   {subject}       {ticket_number} {ticket_url}
 *   {created_date}  {created_time}  {created_datetime}
 *   {reply_date}    {reply_time}    {reply_datetime}
 *   {ticket_text}   {reply_text}
 */
return [

    'new_ticket' => [
        'subject' => 'Nový tiket {ticket_number} od {author_name}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>Ahoj, <strong>{manager_name}</strong>!</p>'
            . '<p>Člen vašej organizácie <strong>{org_name}</strong> vytvoril nový support tiket:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Od:</td>'
            .       '<td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Predmet:</td>'
            .       '<td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Tiket č.:</td>'
            .       '<td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Dátum:</td>'
            .       '<td style="padding:6px 0;">{created_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{ticket_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">Zobraziť tiket</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">Tento e-mail ste dostali, pretože máte povolené notifikácie pre vašu organizáciu v Portáli zákazníkov.</p>'
            . '</div>',
    ],

    'reply_agent' => [
        'subject' => 'Re: {ticket_number} — {subject}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>Ahoj, <strong>{manager_name}</strong>!</p>'
            . '<p>Support agent odpovedal na tiket v organizácii <strong>{org_name}</strong>:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Zákazník:</td>'
            .       '<td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Predmet:</td>'
            .       '<td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Tiket č.:</td>'
            .       '<td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Odpoveď:</td>'
            .       '<td style="padding:6px 0;">{reply_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">Zobraziť tiket</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">Tento e-mail ste dostali, pretože máte povolené notifikácie pre vašu organizáciu v Portáli zákazníkov.</p>'
            . '</div>',
    ],

    'reply_customer' => [
        'subject' => 'Re: {ticket_number} — {subject}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>Ahoj, <strong>{manager_name}</strong>!</p>'
            . '<p>Zákazník odpovedal na tiket v organizácii <strong>{org_name}</strong>:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Od:</td>'
            .       '<td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Predmet:</td>'
            .       '<td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Tiket č.:</td>'
            .       '<td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Odpoveď:</td>'
            .       '<td style="padding:6px 0;">{reply_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">Zobraziť tiket</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">Tento e-mail ste dostali, pretože máte povolené notifikácie pre vašu organizáciu v Portáli zákazníkov.</p>'
            . '</div>',
    ],

];
