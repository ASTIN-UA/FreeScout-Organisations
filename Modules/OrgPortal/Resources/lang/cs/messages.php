<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organizace',
    'new_organization'       => 'Nová organizace',
    'no_organizations'       => 'Zatím žádné organizace.',
    'create_one'             => 'Vytvořit jednu',

    // Admin — mailbox scope
    'mailbox'                => 'Poštovní schránka',
    'global_scope'           => 'Globální (všechny poštovní schránky)',
    'mailbox_scope_hint'     => 'Nechte prázdné, aby byla tato organizace viditelná ve všech poštovních schránkách.',

    // Admin — create / edit form
    'organization_name'      => 'Název organizace',
    'create_organization'    => 'Vytvořit organizaci',
    'org_details'            => 'Podrobnosti organizace',
    'cancel'                 => 'Zrušit',
    'save'                   => 'Uložit',
    'back'                   => 'Zpět',
    'edit'                   => 'Upravit',
    'delete'                 => 'Smazat',
    'confirm_delete_org'     => 'Smazat tuto organizaci?',

    // Admin — flash messages
    'org_created'            => 'Organizace vytvořena.',
    'org_updated'            => 'Organizace aktualizována.',
    'org_deleted'            => 'Organizace smazána.',

    // Admin — badge color
    'badge_color'            => 'Barva odznáku',
    'color_default'          => 'Výchozí (šedá)',
    'preview'                => 'Náhled',

    // Admin — members table
    'name'                   => 'Jméno',
    'email'                  => 'Email',
    'members'                => 'Členové',
    'role'                   => 'Role',
    'member'                 => 'Člen',
    'manager'                => 'Správce',
    'deleted_customer'       => 'Smazaný zákazník',
    'no_members'             => 'Zatím žádní členové.',
    'remove'                 => 'Odebrat',
    'confirm_remove_member'  => 'Odebrat tohoto člena?',

    // Admin — add member form
    'add_member'             => 'Přidat člena',
    'search_customer'        => 'Hledat zákazníka',
    'type_name_or_email'     => 'Zadejte jméno nebo email…',

    // Admin — member flash messages
    'role_updated'           => 'Role aktualizována.',
    'member_added'           => 'Člen přidán.',
    'member_removed'         => 'Člen odstraněn.',
    'already_member'         => 'Tento zákazník je již členem organizace.',
    'already_in_org'         => 'Tento zákazník již patří do jiné organizace.',

    // Portal — company tickets
    'company_tickets'        => 'Firemní lístky',
    'my_tickets'             => 'Moje lístky',
    'no_org_tickets'         => 'Pro vaši organizaci nebyl nalezen žádný lístek.',
    'unknown'                => 'Neznámé',
    'from'                   => 'Od',
    'subject'                => 'Předmět',
    'ticket_hash'            => 'Lístek č.',
    'updated'                => 'Aktualizováno',
    'no_subject'             => '(bez předmětu)',
    'responsible'            => 'Zodpovědný',
    'author'                 => 'Autor',
    'conv_status'            => 'Stav',
    'kanban_state'           => 'Status',
    'search_ticket'          => 'Hledat lístek…',
    'filter_by_author'       => 'Zobrazit lístky od tohoto autora',
    'status_active'          => 'Aktivní',
    'status_pending'         => 'Čekající',
    'status_closed'          => 'Uzavřeno',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Tým podpory',
    'customer'               => 'Zákazník',
    'reply'                  => 'Odpověď',
    'write_reply'            => 'Napište svou odpověď…',
    'send_reply'             => 'Odeslat odpověď',
    'reply_sent'             => 'Odpověď odeslána.',
    'change_author'          => 'Změnit autora',
    'author_changed'         => 'Autor lístku aktualizován.',

    // Portal — settings
    'org_notification_settings' => 'Nastavení oznámení organizace',
    'organization'           => 'Organizace',
    'notify_new_ticket_label'=> 'Dostávat e-mailové oznámení, když člen mé organizace otevře nový lístek',
    'settings_saved'         => 'Nastavení uloženo.',

    // EUP nav
    'org_settings_nav'       => 'Nastavení org.',

    // Conversation badge & search
    'filter_by_org'          => 'Zobrazit všechny lístky z této organizace',
    'all_organizations'      => 'Všechny organizace',
    'remove_filter'          => 'Odebrat filtr',

    // Customer edit form
    'customer_organization'  => 'Organizace',
    'no_organization'        => '— Žádná —',
    'customer_role'          => 'Role v organizaci',
    'view_org_tickets'       => 'Zobrazit lístky organizace',

    // Module settings
    'settings'               => 'Nastavení',
    'module_settings'        => 'Nastavení OrgPortal',
    'display_settings'       => 'Nastavení zobrazení',
    'show_badge_conversation'=> 'Zobrazit odznak organizace na stránce lístku (vedle značek)',
    'show_badge_kanban'      => 'Zobrazit odznak organizace na kartách Kanban',

    // Kanban filter
    'kanban_filter_org'           => 'Organizace',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Filtry stavu firemních lístků',
    'company_filters_hint'        => 'Vyberte, které sloupce Kanban se zobrazí jako zaškrtávací pole na stránce Firemní lístky. Můžete přizpůsobit popisek zobrazený uživatelům portálu.',
    'filter_column_id'            => 'ID sloupce',
    'filter_label'                => 'Popisek',
    'filter_add'                  => 'Přidat filtr',
    'filter_board'                => 'Deska',
    'company_filters_no_boards'   => 'Nebyly nalezeny žádné desky Kanban. Nejdříve vytvořte desku.',

    // User permission
    'perm_manage_organizations' => 'Povolit správu organizací',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API Docs',

    // Ticket actions
    'close_ticket'                  => 'Zavřít lístek',
    'close_ticket_confirm'          => 'Jste si jisti, že chcete zavřít tento lístek?',
    'ticket_closed'                 => 'Lístek byl zavřen.',
    'ticket_closed_label'           => 'Zavřeno',
    'ticket_closed_reply_reopens'   => 'Tento lístek je zavřen. Odeslání odpovědi jej znovu otevře.',
    'attach_files'                  => 'Přílohy',
    'attach_files_hint'             => 'Až :count souborů, maximálně :max MB každý',
    'attach_add_more'               => 'Přidat další soubor',
    'status_open'                   => 'Otevřeno',

    // Errors
    'access_denied'          => 'Přístup odepřen. Vyžaduje se role správce.',

    // Email
    'email_hello'            => 'Dobrý den',
    'email_new_ticket_intro' => 'Člen vaší organizace odeslal nový lístek podpory:',
    'email_new_ticket_footer'=> 'Obdrželi jste tento e-mail, protože máte povolena oznámení o nových lístcích pro vaši organizaci v Portálu zákazníka.',
    'new_ticket_from'        => 'Nový lístek od :name',
    'email_from'             => 'Od',
    'email_subject'          => 'Předmět',
    'email_ticket_number'    => 'Lístek č.',
    'view_ticket'            => 'Zobrazit lístek',
];
