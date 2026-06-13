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

    // Org units & notifications
    'activate'               => 'Aktivovať',
    'add_unit'               => 'Pridať organizačnú jednotku',
    'apply'                  => 'Aplikovať',
    'can_manage_org'         => 'Spravuje celú organizáciu',
    'can_manage_org_hint'    => 'Umožňuje tomuto globálnemu správcovi povýšiť ďalších členov na globálneho správcu z portálu.',
    'cannot_deactivate_self' => 'Nemôžete deaktivovať sami seba.',
    'cannot_grant_global'    => 'Nemáte povolenie prideľovať globálnych správcov.',
    'confirm_deactivate'     => 'Deaktivovať tohto člena? Nebudú už dostávať pridelené lístky.',
    'confirm_delete_unit'    => 'Odstrániť túto jednotku? Jej členovia budú nepriradení a správcovia jednotky degradovaní na členov.',
    'deactivate'             => 'Deaktivovať',
    'email_reply_agent_intro'    => 'Nová odpoveď agenta bola pridaná k lístku vo vašej organizácii:',
    'email_reply_customer_intro' => 'Zákazník odpovedal na lístek vo vašej organizácii:',
    'email_reply_subject'        => 'Re: :number — :subject',
    'global_grant_hint'      => 'Nastavte jednotku, aby ste vytvorili správcu jednotky. Povýšenie na globálneho správcu si vyžaduje oprávnenie správcu.',
    'macro_author_name'      => 'Meno autora lístku',
    'macro_created_date'     => 'Dátum vytvorenia',
    'macro_created_datetime' => 'Dátum a čas vytvorenia',
    'macro_created_time'     => 'Čas vytvorenia',
    'macro_manager_name'     => 'Meno príjemcu',
    'macro_org_name'         => 'Názov organizácie',
    'macro_reply_date'       => 'Dátum odpovede',
    'macro_reply_datetime'   => 'Dátum a čas odpovede',
    'macro_reply_time'       => 'Čas odpovede',
    'macro_subject'          => 'Predmet lístku',
    'macro_ticket_number'    => 'Číslo lístku',
    'macro_ticket_url'       => 'URL lístku',
    'macro_unit_name'        => 'Názov jednotky',
    'member_activated'       => 'Člen bol opätovne aktivovaný.',
    'member_deactivated'     => 'Člen bol deaktivovaný.',
    'member_status'          => 'Stav',
    'member_unit'            => 'Organizačná jednotka',
    'member_updated'         => 'Člen bol aktualizovaný.',
    'no_unit'                => 'Celá organizácia',
    'no_units'               => 'Zatiaľ žiadne jednotky.',
    'notif_event_new_ticket'     => 'Nový lístek',
    'notif_event_reply_agent'    => 'Odpoveď agenta',
    'notif_event_reply_customer' => 'Odpoveď zákazníka',
    'notif_hint'                 => 'Zaškrtnite pole, aby ste dostávali e-mailové upozornenia na lístky zo zvoleného rozsahu.',
    'notif_reply_triggers'       => 'Spúšťače upozornení na odpovede',
    'notif_scope'                => 'Rozsah',
    'notif_scope_org'            => 'Celá organizácia',
    'notif_trigger_agent'        => 'Upozorniť na odpovede agenta',
    'notif_trigger_customer'     => 'Upozorniť na odpovede zákazníka',
    'notif_trigger_hint'         => 'Tieto nastavenia sa vzťahujú na celý systém. Správcovia sa počas tejto porady prihlasujú na špecifické rozsahy na stránke nastavení portálu.',
    'org_settings_title'     => 'Nastavenia organizácie',
    'perm_manage_templates'     => 'Povoliť správu šablón upozornení',
    'rename'                 => 'Premenovať',
    'role_global_manager'    => 'Globálny správca',
    'role_manager_scoped'    => 'Správca',
    'role_member'            => 'Člen',
    'role_unit_manager'      => 'Správca jednotky',
    'select_member'          => 'Vybrať člena',
    'status_member_active'   => 'Aktívny',
    'status_member_inactive' => 'Deaktivovaný',
    'tab_notifications'      => 'Upozornenia',
    'tab_units'              => 'Organizačné jednotky',
    'tpl_body'                   => 'Obsah správy',
    'tpl_fallback_hint'          => '(nechajte prázdne, aby sa použila vstavená šablóna)',
    'tpl_heading'                => 'Emailová šablóna',
    'tpl_insert_macro'           => 'Vložiť premennú…',
    'tpl_load_default'           => 'Načítať predvolenú šablónu',
    'tpl_subject'                => 'Predmet',
    'tpl_subject_placeholder'    => 'Nechajte prázdne, aby sa použil predvolený',
    'tpl_tab_title'              => 'Šablóny upozornení',
    'unit_created'           => 'Jednotka vytvorená.',
    'unit_deleted'           => 'Jednotka odstránená.',
    'unit_exists'            => 'Jednotka s týmto menom už existuje.',
    'unit_name'              => 'Názov organizačnej jednotky',
    'unit_name_placeholder'  => 'napr. Oddelenie predaja',
    'unit_updated'           => 'Jednotka aktualizovaná.',
];
