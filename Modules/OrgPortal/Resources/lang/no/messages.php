<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organisasjoner',
    'new_organization'       => 'Ny organisasjon',
    'no_organizations'       => 'Ingen organisasjoner ennå.',
    'create_one'             => 'Opprett en',

    // Admin — mailbox scope
    'mailbox'                => 'Postkasse',
    'global_scope'           => 'Global (alle postkasser)',
    'mailbox_scope_hint'     => 'La stå tom for å gjøre denne organisasjonen synlig i alle postkasser.',

    // Admin — create / edit form
    'organization_name'      => 'Organisasjonsnavn',
    'create_organization'    => 'Opprett organisasjon',
    'org_details'            => 'Organisasjonsdetaljer',
    'cancel'                 => 'Avbryt',
    'save'                   => 'Lagre',
    'back'                   => 'Tilbake',
    'edit'                   => 'Rediger',
    'delete'                 => 'Slett',
    'confirm_delete_org'     => 'Slett denne organisasjonen?',

    // Admin — flash messages
    'org_created'            => 'Organisasjon opprettet.',
    'org_updated'            => 'Organisasjon oppdatert.',
    'org_deleted'            => 'Organisasjon slettet.',

    // Admin — badge color
    'badge_color'            => 'Badgesfarge',
    'color_default'          => 'Standard (grå)',
    'preview'                => 'Forhåndsvisning',

    // Admin — members table
    'name'                   => 'Navn',
    'email'                  => 'Email',
    'members'                => 'Medlemmer',
    'role'                   => 'Rolle',
    'member'                 => 'Medlem',
    'manager'                => 'Leder',
    'deleted_customer'       => 'Slettet kunde',
    'no_members'             => 'Ingen medlemmer ennå.',
    'remove'                 => 'Fjern',
    'confirm_remove_member'  => 'Fjern dette medlemmet?',

    // Admin — add member form
    'add_member'             => 'Legg til medlem',
    'search_customer'        => 'Søk etter kunde',
    'type_name_or_email'     => 'Skriv navn eller e-post…',

    // Admin — member flash messages
    'role_updated'           => 'Rolle oppdatert.',
    'member_added'           => 'Medlem lagt til.',
    'member_removed'         => 'Medlem fjernet.',
    'already_member'         => 'Denne kunden er allerede medlem av organisasjonen.',
    'already_in_org'         => 'Denne kunden tilhører allerede en annen organisasjon.',

    // Portal — company tickets
    'company_tickets'        => 'Bedriftsbilletter',
    'my_tickets'             => 'Mine billetter',
    'no_org_tickets'         => 'Ingen billetter funnet for organisasjonen din.',
    'unknown'                => 'Ukjent',
    'from'                   => 'Fra',
    'subject'                => 'Emne',
    'ticket_hash'            => 'Billett #',
    'updated'                => 'Oppdatert',
    'no_subject'             => '(ingen emne)',
    'responsible'            => 'Ansvarlig',
    'author'                 => 'Forfatter',
    'conv_status'            => 'Status',
    'kanban_state'           => 'Tilstand',
    'search_ticket'          => 'Søk etter billett…',
    'filter_by_author'       => 'Vis billetter fra denne forfatteren',
    'status_active'          => 'Aktiv',
    'status_pending'         => 'Ventende',
    'status_closed'          => 'Lukket',
    'status_spam'            => 'Søppel',

    // Portal — ticket view
    'support_team'           => 'Supportteam',
    'customer'               => 'Kunde',
    'reply'                  => 'Svar',
    'write_reply'            => 'Skriv ditt svar…',
    'send_reply'             => 'Send svar',
    'reply_sent'             => 'Svar sendt.',
    'change_author'          => 'Endre forfatter',
    'author_changed'         => 'Billetforfatter oppdatert.',

    // Portal — settings
    'org_notification_settings' => 'Organisasjonsmeldingsinnstillinger',
    'organization'           => 'Organisasjon',
    'notify_new_ticket_label'=> 'Motta e-postvarsel når et medlem av organisasjonen min åpner en ny billett',
    'settings_saved'         => 'Innstillinger lagret.',

    // EUP nav
    'org_settings_nav'       => 'Organisasjonsinnstillinger',

    // Conversation badge & search
    'filter_by_org'          => 'Vis alle billetter fra denne organisasjonen',
    'all_organizations'      => 'Alle organisasjoner',
    'remove_filter'          => 'Fjern filter',

    // Customer edit form
    'customer_organization'  => 'Organisasjon',
    'no_organization'        => '— Ingen —',
    'customer_role'          => 'Rolle i organisasjon',
    'view_org_tickets'       => 'Vis organisasjonsbilletter',

    // Module settings
    'settings'               => 'Innstillinger',
    'module_settings'        => 'OrgPortal-innstillinger',
    'display_settings'       => 'Visningsinnstillinger',
    'show_badge_conversation'=> 'Vis organisasjonsbadge på billetside (ved siden av merkelapper)',
    'show_badge_kanban'      => 'Vis organisasjonsbadge på Kanban-kort',

    // Kanban filter
    'kanban_filter_org'           => 'Organisasjon',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Bedriftsbilletter statusfilter',
    'company_filters_hint'        => 'Velg hvilke Kanban-kolonner som vises som filteravmerkingsbokser på siden Bedriftsbilletter. Du kan tilpasse etiketten som vises for portalbrukere.',
    'filter_column_id'            => 'Kolonne-ID',
    'filter_label'                => 'Etikett',
    'filter_add'                  => 'Legg til filter',
    'filter_board'                => 'Brett',
    'company_filters_no_boards'   => 'Ingen Kanban-brett funnet. Opprett et brett først.',

    // User permission
    'perm_manage_organizations' => 'Tillat håndtering av organisasjoner',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API-dokumentasjon',

    // Ticket actions
    'close_ticket'                  => 'Lukk billett',
    'close_ticket_confirm'          => 'Er du sikker på at du vil lukke denne billetten?',
    'ticket_closed'                 => 'Billetten er lukket.',
    'ticket_closed_label'           => 'Lukket',
    'ticket_closed_reply_reopens'   => 'Denne billetten er lukket. Å sende et svar vil åpne den på nytt.',
    'attach_files'                  => 'Vedlegg',
    'attach_files_hint'             => 'Opptil :count filer, maksimalt :max MB hver',
    'attach_add_more'               => 'Legg til en fil til',
    'status_open'                   => 'Åpen',

    // Errors
    'access_denied'          => 'Tilgang nektet. Lederrolle kreves.',

    // Email
    'email_hello'            => 'Hallo',
    'email_new_ticket_intro' => 'En ny supportbillett har blitt sendt inn av et medlem av organisasjonen din:',
    'email_new_ticket_footer'=> 'Du mottok denne e-posten fordi du aktiverte varslinger om nye billetter for organisasjonen din i kundeportalen.',
    'new_ticket_from'        => 'Ny billett fra :name',
    'email_from'             => 'Fra',
    'email_subject'          => 'Emne',
    'email_ticket_number'    => 'Billett #',
    'view_ticket'            => 'Vis billett',
];
