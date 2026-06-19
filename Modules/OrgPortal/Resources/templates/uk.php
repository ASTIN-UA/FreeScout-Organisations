<?php
/**
 * Default notification email templates — Ukrainian (uk).
 */
return [

    'new_ticket' => [
        'subject' => 'Нова заявка {ticket_number} від {author_name}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>Доброго дня, <strong>{manager_name}</strong>!</p>'
            . '<p>Учасник вашої організації <strong>{org_name}</strong> відкрив нову заявку:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Від:</td>'
            .       '<td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Тема:</td>'
            .       '<td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Заявка №:</td>'
            .       '<td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Дата:</td>'
            .       '<td style="padding:6px 0;">{created_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{ticket_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">Переглянути заявку</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">Ви отримали цей лист, оскільки увімкнули сповіщення для вашої організації в Клієнтському порталі.</p>'
            . '</div>',
    ],

    'reply_agent' => [
        'subject' => 'Re: {ticket_number} — {subject}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>Доброго дня, <strong>{manager_name}</strong>!</p>'
            . '<p>Агент підтримки відповів на заявку у вашій організації <strong>{org_name}</strong>:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Клієнт:</td>'
            .       '<td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Тема:</td>'
            .       '<td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Заявка №:</td>'
            .       '<td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Час відповіді:</td>'
            .       '<td style="padding:6px 0;">{reply_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">Переглянути заявку</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">Ви отримали цей лист, оскільки увімкнули сповіщення для вашої організації в Клієнтському порталі.</p>'
            . '</div>',
    ],

    'reply_customer' => [
        'subject' => 'Re: {ticket_number} — {subject}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>Доброго дня, <strong>{manager_name}</strong>!</p>'
            . '<p>Клієнт відповів на заявку у вашій організації <strong>{org_name}</strong>:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Від:</td>'
            .       '<td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Тема:</td>'
            .       '<td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Заявка №:</td>'
            .       '<td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">Час відповіді:</td>'
            .       '<td style="padding:6px 0;">{reply_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">Переглянути заявку</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">Ви отримали цей лист, оскільки увімкнули сповіщення для вашої організації в Клієнтському порталі.</p>'
            . '</div>',
    ],

];
