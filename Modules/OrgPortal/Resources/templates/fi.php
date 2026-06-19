<?php
/**
 * Default notification email templates — Finnish (fi).
 */
return [
    'new_ticket' => [
        'subject' => 'Uusi lippu {ticket_number} käyttäjältä {author_name}',
        'body' => '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                <p>Terve, {manager_name}!</p>
                <p>Organisaatiosi {org_name} jäsen on lähettänyt uuden tukipyynnön:</p>
                <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold; width: 120px;">Lähettäjä:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{author_name}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Aihe:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{subject}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Lippu #:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{ticket_number}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Päivä:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{created_datetime}</td>
                    </tr>
                </table>
                <div style="background-color: #f5f5f5; padding: 15px; border-left: 4px solid #0066cc; margin: 20px 0;">
                    {ticket_text}
                </div>
                <p><a href="{ticket_url}" style="display: inline-block; padding: 10px 20px; background-color: #0066cc; color: white; text-decoration: none; border-radius: 4px;">Näytä lippu</a></p>
                <p style="font-size: 12px; color: #999; margin-top: 30px;">Sait tämän sähköpostin, koska olet ottanut käyttöön ilmoitukset organisaatiollesi asiakasportaalissa.</p>
            </div>',
    ],
    'reply_agent' => [
        'subject' => 'Vastaus: {ticket_number} — {subject}',
        'body' => '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                <p>Terve, {manager_name}!</p>
                <p>Tukihenkilö on vastannut lippuun organisaatiossasi {org_name}:</p>
                <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold; width: 120px;">Asiakas:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{author_name}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Aihe:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{subject}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Lippu #:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{ticket_number}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Vastannut:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{reply_datetime}</td>
                    </tr>
                </table>
                <div style="background-color: #f5f5f5; padding: 15px; border-left: 4px solid #0066cc; margin: 20px 0;">
                    {reply_text}
                </div>
                <p><a href="{ticket_url}" style="display: inline-block; padding: 10px 20px; background-color: #0066cc; color: white; text-decoration: none; border-radius: 4px;">Näytä lippu</a></p>
                <p style="font-size: 12px; color: #999; margin-top: 30px;">Sait tämän sähköpostin, koska olet ottanut käyttöön ilmoitukset organisaatiollesi asiakasportaalissa.</p>
            </div>',
    ],
    'reply_customer' => [
        'subject' => 'Vastaus: {ticket_number} — {subject}',
        'body' => '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                <p>Terve, {manager_name}!</p>
                <p>Asiakas on vastannut lippuun organisaatiossasi {org_name}:</p>
                <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold; width: 120px;">Lähettäjä:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{author_name}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Aihe:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{subject}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Lippu #:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{ticket_number}</td>
                    </tr>
                    <tr>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd; font-weight: bold;">Vastannut:</td>
                        <td style="padding: 8px; border-bottom: 1px solid #ddd;">{reply_datetime}</td>
                    </tr>
                </table>
                <div style="background-color: #f5f5f5; padding: 15px; border-left: 4px solid #0066cc; margin: 20px 0;">
                    {reply_text}
                </div>
                <p><a href="{ticket_url}" style="display: inline-block; padding: 10px 20px; background-color: #0066cc; color: white; text-decoration: none; border-radius: 4px;">Näytä lippu</a></p>
                <p style="font-size: 12px; color: #999; margin-top: 30px;">Sait tämän sähköpostin, koska olet ottanut käyttöön ilmoitukset organisaatiollesi asiakasportaalissa.</p>
            </div>',
    ],
];
