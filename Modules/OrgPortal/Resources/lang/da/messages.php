<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organisationer',
    'new_organization'       => 'Ny organisation',
    'no_organizations'       => 'Ingen organisationer endnu.',
    'create_one'             => 'Opret en',

    // Admin — mailbox scope
    'mailbox'                => 'Postkasse',
    'global_scope'           => 'Global (alle postkasser)',
    'mailbox_scope_hint'     => 'Lad være tom for at gøre denne organisation synlig i alle postkasser.',

    // Admin — create / edit form
    'organization_name'      => 'Organisationsnavn',
    'create_organization'    => 'Opret organisation',
    'org_details'            => 'Organisationsdetaljer',
    'cancel'                 => 'Annuller',
    'save'                   => 'Gem',
    'back'                   => 'Tilbage',
    'edit'                   => 'Rediger',
    'delete'                 => 'Slet',
    'confirm_delete_org'     => 'Slet denne organisation?',

    // Admin — flash messages
    'org_created'            => 'Organisation oprettet.',
    'org_updated'            => 'Organisation opdateret.',
    'org_deleted'            => 'Organisation slettet.',

    // Admin — badge color
    'badge_color'            => 'Badgfarve',
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
    'no_members'             => 'Ingen medlemmer endnu.',
    'remove'                 => 'Fjern',
    'confirm_remove_member'  => 'Fjern dette medlem?',

    // Admin — add member form
    'add_member'             => 'Tilføj medlem',
    'search_customer'        => 'Søg kunde',
    'type_name_or_email'     => 'Skriv navn eller email…',

    // Admin — member flash messages
    'role_updated'           => 'Rolle opdateret.',
    'member_added'           => 'Medlem tilføjet.',
    'member_removed'         => 'Medlem fjernet.',
    'already_member'         => 'Denne kunde er allerede medlem af organisationen.',
    'already_in_org'         => 'Denne kunde tilhører allerede en anden organisation.',

    // Portal — company tickets
    'company_tickets'        => 'Virksomhedsbilletter',
    'my_tickets'             => 'Mine billetter',
    'no_org_tickets'         => 'Ingen billetter fundet for din organisation.',
    'unknown'                => 'Ukendt',
    'from'                   => 'Fra',
    'subject'                => 'Emne',
    'ticket_hash'            => 'Billet #',
    'updated'                => 'Opdateret',
    'no_subject'             => '(intet emne)',
    'responsible'            => 'Ansvarlig',
    'author'                 => 'Forfatter',
    'conv_status'            => 'Status',
    'kanban_state'           => 'Tilstand',
    'search_ticket'          => 'Søg billet…',
    'filter_by_author'       => 'Vis billetter fra denne forfatter',
    'status_active'          => 'Aktiv',
    'status_pending'         => 'Afventende',
    'status_closed'          => 'Lukket',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Supportteam',
    'customer'               => 'Kunde',
    'reply'                  => 'Svar',
    'write_reply'            => 'Skriv dit svar…',
    'send_reply'             => 'Send svar',
    'reply_sent'             => 'Svar sendt.',
    'change_author'          => 'Skift forfatter',
    'author_changed'         => 'Billetforfatter opdateret.',

    // Portal — settings
    'org_notification_settings' => 'Organisationsnotifikationsindstillinger',
    'organization'           => 'Organisation',
    'notify_new_ticket_label'=> 'Modtag emailnotifikation når et medlem af min organisation åbner en ny billet',
    'settings_saved'         => 'Indstillinger gemt.',

    // EUP nav
    'org_settings_nav'       => 'Organisationsindstillinger',

    // Conversation badge & search
    'filter_by_org'          => 'Vis alle billetter fra denne organisation',
    'all_organizations'      => 'Alle organisationer',
    'remove_filter'          => 'Fjern filter',

    // Customer edit form
    'customer_organization'  => 'Organisation',
    'no_organization'        => '— Ingen —',
    'customer_role'          => 'Rolle i organisation',
    'view_org_tickets'       => 'Se organisationsbilletter',

    // Module settings
    'settings'               => 'Indstillinger',
    'module_settings'        => 'OrgPortal-indstillinger',
    'display_settings'       => 'Visningsindstillinger',
    'show_badge_conversation'=> 'Vis organisationsbadge på billetside (ved siden af tags)',
    'show_badge_kanban'      => 'Vis organisationsbadge på Kanban-kort',

    // Kanban filter
    'kanban_filter_org'           => 'Organisation',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Virksomhedsbilletter statusfiltre',
    'company_filters_hint'        => 'Vælg hvilke Kanban-kolonner der vises som filterafkrydsningsfelter på siden Virksomhedsbilletter. Du kan tilpasse det etiketnavn, der vises til portalbrugere.',
    'filter_column_id'            => 'Kolonne-ID',
    'filter_label'                => 'Etiket',
    'filter_add'                  => 'Tilføj filter',
    'filter_board'                => 'Tavle',
    'company_filters_no_boards'   => 'Ingen Kanban-tavler fundet. Opret en tavle først.',

    // User permission
    'perm_manage_organizations' => 'Tillad håndtering af organisationer',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API-dokumentation',

    // Ticket actions
    'close_ticket'                  => 'Luk billet',
    'close_ticket_confirm'          => 'Er du sikker på, at du vil lukke denne billet?',
    'ticket_closed'                 => 'Billet er lukket.',
    'ticket_closed_label'           => 'Lukket',
    'ticket_closed_reply_reopens'   => 'Denne billet er lukket. Hvis du sender et svar, åbnes den igen.',
    'attach_files'                  => 'Vedhæftelser',
    'attach_files_hint'             => 'Op til :count filer, max :max MB hver',
    'attach_add_more'               => 'Tilføj anden fil',
    'status_open'                   => 'Åben',

    // Errors
    'access_denied'          => 'Adgang nægtet. Lederrolle påkrævet.',

    // Email
    'email_hello'            => 'Hej',
    'email_new_ticket_intro' => 'En ny supportbillet er indsendt af et medlem af din organisation:',
    'email_new_ticket_footer'=> 'Du modtog denne email, fordi du aktiverede notifikationer for nye billetter for din organisation i kundeportalen.',
    'new_ticket_from'        => 'Ny billet fra :name',
    'email_from'             => 'Fra',
    'email_subject'          => 'Emne',
    'email_ticket_number'    => 'Billet #',
    'view_ticket'            => 'Se billet',
];
