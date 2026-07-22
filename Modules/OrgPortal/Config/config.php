<?php

return [
    'name' => 'OrgPortal',

    /*
    |--------------------------------------------------------------------------
    | Public email domains
    |--------------------------------------------------------------------------
    |
    | Domains that must never be bound to an organisation. Binding one would
    | pull every unrelated customer sharing that provider into the organisation
    | — and, through the end-user portal, into each other's tickets. That is a
    | data leak, not an inconvenience, so these are rejected at save time.
    |
    | Admins can extend (never shrink) this list via the orgportal.public_domains
    | option; see OrganizationDomain::publicDomains().
    |
    */
    'public_domains' => [
        // Global providers
        'gmail.com', 'googlemail.com',
        'outlook.com', 'hotmail.com', 'live.com', 'msn.com',
        'yahoo.com', 'yahoo.co.uk', 'ymail.com',
        'icloud.com', 'me.com', 'mac.com',
        'aol.com', 'gmx.com', 'gmx.net', 'web.de',
        'protonmail.com', 'proton.me', 'pm.me',
        'zoho.com', 'yandex.com', 'yandex.ru', 'mail.ru', 'inbox.ru', 'bk.ru', 'list.ru',
        'fastmail.com', 'hushmail.com', 'tutanota.com', 'tuta.io',
        'qq.com', '163.com', '126.com', 'naver.com', 'daum.net',

        // Ukraine
        'ukr.net', 'i.ua', 'meta.ua', 'bigmir.net', 'email.ua', 'online.ua',

        // Disposable / throwaway
        'mailinator.com', 'guerrillamail.com', '10minutemail.com',
        'tempmail.com', 'temp-mail.org', 'yopmail.com', 'trashmail.com',
        'sharklasers.com', 'getnada.com', 'dispostable.com',
    ],
];
