<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organisationer',
    'new_organization'       => 'Ny organisation',
    'no_organizations'       => 'Inga organisationer ännu.',
    'create_one'             => 'Skapa en',

    // Admin — mailbox scope
    'mailbox'                => 'Brevlåda',
    'global_scope'           => 'Global (alla brevlådor)',
    'mailbox_scope_hint'     => 'Lämna tom för att göra denna organisation synlig i alla brevlådor.',

    // Admin — create / edit form
    'organization_name'      => 'Organisationsnamn',
    'create_organization'    => 'Skapa organisation',
    'org_details'            => 'Organisationsdetaljer',
    'cancel'                 => 'Avbryt',
    'save'                   => 'Spara',
    'back'                   => 'Tillbaka',
    'edit'                   => 'Redigera',
    'delete'                 => 'Ta bort',
    'confirm_delete_org'     => 'Ta bort denna organisation?',

    // Admin — flash messages
    'org_created'            => 'Organisation skapad.',
    'org_updated'            => 'Organisation uppdaterad.',
    'org_deleted'            => 'Organisation borttagen.',

    // Admin — badge color
    'badge_color'            => 'Märkesfärg',
    'color_default'          => 'Standard (grå)',
    'preview'                => 'Förhandsvisning',

    // Admin — members table
    'name'                   => 'Namn',
    'email'                  => 'Email',
    'members'                => 'Medlemmar',
    'role'                   => 'Roll',
    'member'                 => 'Medlem',
    'manager'                => 'Ledare',
    'deleted_customer'       => 'Borttagen kund',
    'no_members'             => 'Inga medlemmar ännu.',
    'remove'                 => 'Ta bort',
    'confirm_remove_member'  => 'Ta bort denna medlem?',

    // Admin — add member form
    'add_member'             => 'Lägg till medlem',
    'search_customer'        => 'Sök kund',
    'type_name_or_email'     => 'Skriv namn eller email…',

    // Admin — member flash messages
    'role_updated'           => 'Roll uppdaterad.',
    'member_added'           => 'Medlem tillagd.',
    'member_removed'         => 'Medlem borttagen.',
    'already_member'         => 'Denna kund är redan medlem i organisationen.',
    'already_in_org'         => 'Denna kund tillhör redan en annan organisation.',

    // Portal — company tickets
    'company_tickets'        => 'Företagsbiljetter',
    'my_tickets'             => 'Mina biljetter',
    'no_org_tickets'         => 'Inga biljetter hittades för din organisation.',
    'unknown'                => 'Okänd',
    'from'                   => 'Från',
    'subject'                => 'Ämne',
    'ticket_hash'            => 'Biljett #',
    'updated'                => 'Uppdaterad',
    'no_subject'             => '(inget ämne)',
    'responsible'            => 'Ansvarig',
    'author'                 => 'Författare',
    'conv_status'            => 'Status',
    'kanban_state'           => 'Status',
    'search_ticket'          => 'Sök biljett…',
    'filter_by_author'       => 'Visa biljetter från denna författare',
    'status_active'          => 'Aktiv',
    'status_pending'         => 'Väntande',
    'status_closed'          => 'Stängd',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Supportteam',
    'customer'               => 'Kund',
    'reply'                  => 'Svara',
    'write_reply'            => 'Skriv ditt svar…',
    'send_reply'             => 'Skicka svar',
    'reply_sent'             => 'Svar skickat.',
    'change_author'          => 'Ändra författare',
    'author_changed'         => 'Biljettpforffattare uppdaterad.',

    // Portal — settings
    'org_notification_settings' => 'Organisationsmeddelandeinställningar',
    'organization'           => 'Organisation',
    'notify_new_ticket_label'=> 'Motta e-postmeddelande när en medlem av min organisation öppnar en ny biljett',
    'settings_saved'         => 'Inställningar sparade.',

    // EUP nav
    'org_settings_nav'       => 'Organisationsinställningar',

    // Conversation badge & search
    'filter_by_org'          => 'Visa alla biljetter från denna organisation',
    'all_organizations'      => 'Alla organisationer',
    'remove_filter'          => 'Ta bort filter',

    // Customer edit form
    'customer_organization'  => 'Organisation',
    'no_organization'        => '— Ingen —',
    'customer_role'          => 'Roll i organisation',
    'view_org_tickets'       => 'Visa organisationsbiljetter',

    // Module settings
    'settings'               => 'Inställningar',
    'module_settings'        => 'OrgPortal-inställningar',
    'display_settings'       => 'Visningsinställningar',
    'show_badge_conversation'=> 'Visa organisationsmärke på biljettsida (vid taggarna)',
    'show_badge_kanban'      => 'Visa organisationsmärke på Kanban-kort',

    // Kanban filter
    'kanban_filter_org'           => 'Organisation',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Företagsbiljetter statusfilter',
    'company_filters_hint'        => 'Välj vilka Kanban-kolumner som visas som filtercheckboxar på sidan Företagsbiljetter. Du kan anpassa etiketten som visas för portalanvändare.',
    'filter_column_id'            => 'Kolumn-ID',
    'filter_label'                => 'Etikett',
    'filter_add'                  => 'Lägg till filter',
    'filter_board'                => 'Bräde',
    'company_filters_no_boards'   => 'Inga Kanban-brädor hittades. Skapa ett bräde först.',

    // User permission
    'perm_manage_organizations' => 'Tillåt hantering av organisationer',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API-dokumentation',

    // Ticket actions
    'close_ticket'                  => 'Stäng biljett',
    'close_ticket_confirm'          => 'Är du säker på att du vill stänga denna biljett?',
    'ticket_closed'                 => 'Biljetten har stängts.',
    'ticket_closed_label'           => 'Stängd',
    'ticket_closed_reply_reopens'   => 'Denna biljett är stängd. Att skicka ett svar öppnar den igen.',
    'attach_files'                  => 'Bilagor',
    'attach_files_hint'             => 'Upp till :count filer, max :max MB varje',
    'attach_add_more'               => 'Lägg till en fil till',
    'status_open'                   => 'Öppen',

    // Errors
    'access_denied'          => 'Åtkomst nekad. Lederroll krävs.',

    // Email
    'email_hello'            => 'Hej',
    'email_new_ticket_intro' => 'En ny supportbiljett har skickats av en medlem av din organisation:',
    'email_new_ticket_footer'=> 'Du mottog detta e-postmeddelande eftersom du aktiverade meddelanden för nya biljetter för din organisation i kundportalen.',
    'new_ticket_from'        => 'Ny biljett från :name',
    'email_from'             => 'Från',
    'email_subject'          => 'Ämne',
    'email_ticket_number'    => 'Biljett #',
    'view_ticket'            => 'Visa biljett',
];
