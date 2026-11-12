<?php

namespace Modules\OrgPortal\Services;

/**
 * The placeholders of the notification templates ({ticket_url}, {org_name}…).
 *
 * A saved template goes through HTMLPurifier, which percent-encodes the braces
 * of a link's address: href="{ticket_url}" came out as href="%7Bticket_url%7D",
 * so the macro was never replaced and the «View ticket» button of the e-mail led
 * nowhere. restore() puts the braces back; it is applied when a template is
 * saved, when it is rendered (for what is already stored) and once to the
 * stored templates (migration 2026_11_12_000001).
 */
class TemplateMacros
{
    const NAMES = [
        'manager_name', 'author_name', 'org_name', 'unit_name', 'subject', 'ticket_number', 'ticket_url',
        'created_date', 'created_time', 'created_datetime', 'reply_date', 'reply_time', 'reply_datetime',
        'reply_text', 'ticket_text',
    ];

    /**
     * Turns the encoded forms of a placeholder (%7Bname%7D, &#123;name&#125;,
     * &lcub;name&rcub;) back into {name}; only the known placeholders.
     */
    public static function restore(string $text): string
    {
        $names = implode('|', array_map(function ($name) { return preg_quote($name, '/'); }, self::NAMES));

        return preg_replace_callback(
            '/(?:%7B|&#0*123;|&#x0*7b;|&lcub;|&lbrace;)(' . $names . ')(?:%7D|&#0*125;|&#x0*7d;|&rcub;|&rbrace;)/i',
            function ($m) {
                return '{' . strtolower($m[1]) . '}';
            },
            $text
        );
    }
}
