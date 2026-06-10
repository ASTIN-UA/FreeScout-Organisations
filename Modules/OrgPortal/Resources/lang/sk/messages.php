<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organizácie',
    'new_organization'       => 'Nová organizácia',
    'no_organizations'       => 'Zatiaľ žiadne organizácie.',
    'create_one'             => 'Vytvoriť jednu',

    // Admin — mailbox scope
    'mailbox'                => 'Poštová schránka',
    'global_scope'           => 'Globálna (všetky poštové schránky)',
    'mailbox_scope_hint'     => 'Nechajte prázdne, aby bola táto organizácia viditeľná vo všetkých poštových schránkach.',

    // Admin — create / edit form
    'organization_name'      => 'Názov organizácie',
    'create_organization'    => 'Vytvoriť organizáciu',
    'org_details'            => 'Podrobnosti organizácie',
    'cancel'                 => 'Zrušiť',
    'save'                   => 'Uložiť',
    'back'                   => 'Späť',
    'edit'                   => 'Upraviť',
    'delete'                 => 'Odstrániť',
    'confirm_delete_org'     => 'Odstrániť túto organizáciu?',

    // Admin — flash messages
    'org_created'            => 'Organizácia vytvorená.',
    'org_updated'            => 'Organizácia aktualizovaná.',
    'org_deleted'            => 'Organizácia odstránená.',

    // Admin — badge color
    'badge_color'            => 'Farba odznáku',
    'color_default'          => 'Predvolená (sivá)',
    'preview'                => 'Náhľad',

    // Admin — members table
    'name'                   => 'Meno',
    'email'                  => 'Email',
    'members'                => 'Členovia',
    'role'                   => 'Rola',
    'member'                 => 'Člen',
    'manager'                => 'Správca',
    'deleted_customer'       => 'Vymazaný zákazník',
    'no_members'             => 'Zatiaľ žiadni členovia.',
    'remove'                 => 'Odstrániť',
    'confirm_remove_member'  => 'Odstrániť tohto člena?',

    // Admin — add member form
    'add_member'             => 'Pridať člena',
    'search_customer'        => 'Hľadať zákazníka',
    'type_name_or_email'     => 'Zadajte meno alebo email…',

    // Admin — member flash messages
    'role_updated'           => 'Rola aktualizovaná.',
    'member_added'           => 'Člen pridaný.',
    'member_removed'         => 'Člen odstránený.',
    'already_member'         => 'Tento zákazník je už členom organizácie.',
    'already_in_org'         => 'Tento zákazník už patrí do inej organizácie.',

    // Portal — company tickets
    'company_tickets'        => 'Firemné lístky',
    'my_tickets'             => 'Moje lístky',
    'no_org_tickets'         => 'Pre vašu organizáciu nebol nájdený žiadny lístek.',
    'unknown'                => 'Neznáme',
    'from'                   => 'Od',
    'subject'                => 'Predmet',
    'ticket_hash'            => 'Lístek č.',
    'updated'                => 'Aktualizované',
    'no_subject'             => '(bez predmetu)',
    'responsible'            => 'Zodpovedný',
    'author'                 => 'Autor',
    'conv_status'            => 'Stav',
    'kanban_state'           => 'Status',
    'search_ticket'          => 'Hľadať lístek…',
    'filter_by_author'       => 'Zobraziť lístky od tohto autora',
    'status_active'          => 'Aktívny',
    'status_pending'         => 'Čakajúci',
    'status_closed'          => 'Zatvorené',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Tím podpory',
    'customer'               => 'Zákazník',
    'reply'                  => 'Odpoveď',
    'write_reply'            => 'Napíšte vašu odpoveď…',
    'send_reply'             => 'Odoslať odpoveď',
    'reply_sent'             => 'Odpoveď odoslaná.',
    'change_author'          => 'Zmeniť autora',
    'author_changed'         => 'Autor lístku aktualizovaný.',

    // Portal — settings
    'org_notification_settings' => 'Nastavenia upozornení organizácie',
    'organization'           => 'Organizácia',
    'notify_new_ticket_label'=> 'Dostávať e-mailové upozornenie, keď člen mojej organizácie otvorí nový lístek',
    'settings_saved'         => 'Nastavenia uložené.',

    // EUP nav
    'org_settings_nav'       => 'Nastavenia org.',

    // Conversation badge & search
    'filter_by_org'          => 'Zobraziť všetky lístky z tejto organizácie',
    'all_organizations'      => 'Všetky organizácie',
    'remove_filter'          => 'Odstrániť filter',

    // Customer edit form
    'customer_organization'  => 'Organizácia',
    'no_organization'        => '— Žiadna —',
    'customer_role'          => 'Rola v organizácii',
    'view_org_tickets'       => 'Zobraziť lístky organizácie',

    // Module settings
    'settings'               => 'Nastavenia',
    'module_settings'        => 'Nastavenia OrgPortal',
    'display_settings'       => 'Nastavenia zobrazenia',
    'show_badge_conversation'=> 'Zobraziť odznak organizácie na stránke lístku (vedľa značiek)',
    'show_badge_kanban'      => 'Zobraziť odznak organizácie na kartách Kanban',

    // Kanban filter
    'kanban_filter_org'           => 'Organizácia',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Filtre stavu firemných lístkov',
    'company_filters_hint'        => 'Vyberte, ktoré stĺpce Kanban sa zobrazia ako zaškrtávacie políčka na stránke Firemné lístky. Môžete prispôsobiť nálepku zobrazovanú používateľom portálu.',
    'filter_column_id'            => 'ID stĺpca',
    'filter_label'                => 'Nálepka',
    'filter_add'                  => 'Pridať filter',
    'filter_board'                => 'Tabuľa',
    'company_filters_no_boards'   => 'Nepodarilo sa nájsť žiadne tabuľy Kanban. Najskôr vytvorte tabuľu.',

    // User permission
    'perm_manage_organizations' => 'Povoliť správu organizácií',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API Docs',

    // Ticket actions
    'close_ticket'                  => 'Zatvoriť lístek',
    'close_ticket_confirm'          => 'Ste si istí, že chcete zatvoriť tento lístek?',
    'ticket_closed'                 => 'Lístek bol zatvorený.',
    'ticket_closed_label'           => 'Zatvorené',
    'ticket_closed_reply_reopens'   => 'Tento lístek je zatvorený. Odoslanie odpovede ho znovu otvorí.',
    'attach_files'                  => 'Prílohy',
    'attach_files_hint'             => 'Až :count súborov, maximálne :max MB každý',
    'attach_add_more'               => 'Pridať ďalší súbor',
    'status_open'                   => 'Otvorené',

    // Errors
    'access_denied'          => 'Prístup odmietnutý. Vyžaduje sa rola správcu.',

    // Email
    'email_hello'            => 'Dobrý deň',
    'email_new_ticket_intro' => 'Člen vašej organizácie odoslal nový lístek podpory:',
    'email_new_ticket_footer'=> 'Dostali ste tento e-mail, pretože máte povolené upozornenia o nových lístkoch pre vašu organizáciu na Portáli zákazníka.',
    'new_ticket_from'        => 'Nový lístek od :name',
    'email_from'             => 'Od',
    'email_subject'          => 'Predmet',
    'email_ticket_number'    => 'Lístek č.',
    'view_ticket'            => 'Zobraziť lístek',
];
