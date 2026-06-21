<?php
/**
 * Default notification email templates — Georgian (ka).
 */
return [

    'new_ticket' => [
        'subject' => 'ახალი განაცხადი {ticket_number} — {author_name}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>გამარჯობა, <strong>{manager_name}</strong>!</p>'
            . '<p>თქვენი ორგანიზაციის <strong>{org_name}</strong> წევრმა ახალი განაცხადი გახსნა:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">გამომგზავნი:</td>'
            .       '<td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">თემა:</td>'
            .       '<td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">განაცხადი №:</td>'
            .       '<td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">თარიღი:</td>'
            .       '<td style="padding:6px 0;">{created_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{ticket_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">განაცხადის ნახვა</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">თქვენ მიიღეთ ეს წერილი, რადგან ჩართეთ შეტყობინებები თქვენი ორგანიზაციისთვის კლიენტის პორტალში.</p>'
            . '</div>',
    ],

    'reply_agent' => [
        'subject' => 'Re: {ticket_number} — {subject}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>გამარჯობა, <strong>{manager_name}</strong>!</p>'
            . '<p>მხარდაჭერის აგენტმა უპასუხა განაცხადს თქვენს ორგანიზაციაში <strong>{org_name}</strong>:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">კლიენტი:</td>'
            .       '<td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">თემა:</td>'
            .       '<td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">განაცხადი №:</td>'
            .       '<td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">პასუხის დრო:</td>'
            .       '<td style="padding:6px 0;">{reply_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">განაცხადის ნახვა</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">თქვენ მიიღეთ ეს წერილი, რადგან ჩართეთ შეტყობინებები თქვენი ორგანიზაციისთვის კლიენტის პორტალში.</p>'
            . '</div>',
    ],

    'reply_customer' => [
        'subject' => 'Re: {ticket_number} — {subject}',
        'body'    =>
            '<div style="font-family:Arial,sans-serif;font-size:14px;color:#333;max-width:600px;">'
            . '<p>გამარჯობა, <strong>{manager_name}</strong>!</p>'
            . '<p>კლიენტმა უპასუხა განაცხადს თქვენს ორგანიზაციაში <strong>{org_name}</strong>:</p>'
            . '<table style="width:100%;border-collapse:collapse;margin:16px 0;font-size:14px;">'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">გამომგზავნი:</td>'
            .       '<td style="padding:6px 0;"><strong>{author_name}</strong> <span style="color:#999;font-size:12px;">({unit_name})</span></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">თემა:</td>'
            .       '<td style="padding:6px 0;"><strong>{subject}</strong></td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">განაცხადი №:</td>'
            .       '<td style="padding:6px 0;">{ticket_number}</td></tr>'
            .   '<tr><td style="color:#666;width:140px;padding:6px 0;">პასუხის დრო:</td>'
            .       '<td style="padding:6px 0;">{reply_datetime}</td></tr>'
            . '</table>'
            . '<div style="border-left:3px solid #d1d5db;padding:8px 16px;margin:16px 0;color:#374151;">{reply_text}</div>'
            . '<p><a href="{ticket_url}" style="display:inline-block;padding:10px 22px;background:#3b82f6;color:#fff;text-decoration:none;border-radius:4px;font-weight:bold;">განაცხადის ნახვა</a></p>'
            . '<p style="margin-top:32px;font-size:12px;color:#999;">თქვენ მიიღეთ ეს წერილი, რადგან ჩართეთ შეტყობინებები თქვენი ორგანიზაციისთვის კლიენტის პორტალში.</p>'
            . '</div>',
    ],

];
