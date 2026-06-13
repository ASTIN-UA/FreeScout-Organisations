<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organisaties',
    'new_organization'       => 'Nieuwe Organisatie',
    'no_organizations'       => 'Nog geen organisaties.',
    'create_one'             => 'Maak er een',

    // Admin — mailbox scope
    'mailbox'                => 'Postvak',
    'global_scope'           => 'Globaal (alle postvakken)',
    'mailbox_scope_hint'     => 'Laat leeg om deze organisatie zichtbaar te maken in alle postvakken.',

    // Admin — create / edit form
    'organization_name'      => 'Naam Organisatie',
    'create_organization'    => 'Organisatie Aanmaken',
    'org_details'            => 'Organisatiegegevens',
    'cancel'                 => 'Annuleren',
    'save'                   => 'Opslaan',
    'back'                   => 'Terug',
    'edit'                   => 'Bewerken',
    'delete'                 => 'Verwijderen',
    'confirm_delete_org'     => 'Deze organisatie verwijderen?',

    // Admin — flash messages
    'org_created'            => 'Organisatie aangemaakt.',
    'org_updated'            => 'Organisatie bijgewerkt.',
    'org_deleted'            => 'Organisatie verwijderd.',

    // Admin — badge color
    'badge_color'            => 'Badgekleur',
    'color_default'          => 'Standaard (grijs)',
    'preview'                => 'Voorbeeld',

    // Admin — members table
    'name'                   => 'Naam',
    'email'                  => 'E-mailadres',
    'members'                => 'Leden',
    'role'                   => 'Rol',
    'member'                 => 'Lid',
    'manager'                => 'Manager',
    'deleted_customer'       => 'Verwijderde klant',
    'no_members'             => 'Nog geen leden.',
    'remove'                 => 'Verwijderen',
    'confirm_remove_member'  => 'Dit lid verwijderen?',

    // Admin — add member form
    'add_member'             => 'Lid Toevoegen',
    'search_customer'        => 'Zoek klant',
    'type_name_or_email'     => 'Typ naam of e-mailadres…',

    // Admin — member flash messages
    'role_updated'           => 'Rol bijgewerkt.',
    'member_added'           => 'Lid toegevoegd.',
    'member_removed'         => 'Lid verwijderd.',
    'already_member'         => 'Deze klant is al lid van de organisatie.',
    'already_in_org'         => 'Deze klant behoort al tot een andere organisatie.',

    // Portal — company tickets
    'company_tickets'        => 'Bedrijfstickets',
    'my_tickets'             => 'Mijn Tickets',
    'no_org_tickets'         => 'Geen tickets gevonden voor uw organisatie.',
    'unknown'                => 'Onbekend',
    'from'                   => 'Van',
    'subject'                => 'Onderwerp',
    'ticket_hash'            => 'Ticket #',
    'updated'                => 'Bijgewerkt',
    'no_subject'             => '(geen onderwerp)',
    'responsible'            => 'Verantwoordelijke',
    'author'                 => 'Auteur',
    'conv_status'            => 'Status',
    'kanban_state'           => 'Status',
    'search_ticket'          => 'Zoek ticket…',
    'filter_by_author'       => 'Toon tickets van deze auteur',
    'status_active'          => 'Actief',
    'status_pending'         => 'In behandeling',
    'status_closed'          => 'Gesloten',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Ondersteuningsteam',
    'customer'               => 'Klant',
    'reply'                  => 'Antwoord',
    'write_reply'            => 'Schrijf uw antwoord…',
    'send_reply'             => 'Antwoord Verzenden',
    'reply_sent'             => 'Antwoord verzonden.',
    'change_author'          => 'Auteur Wijzigen',
    'author_changed'         => 'Ticketauteur bijgewerkt.',

    // Portal — settings
    'org_notification_settings' => 'Instellingen Organisatiemeldingen',
    'organization'           => 'Organisatie',
    'notify_new_ticket_label'=> 'Ontvang e-mailmelding wanneer een lid van mijn organisatie een nieuw ticket opent',
    'settings_saved'         => 'Instellingen opgeslagen.',

    // EUP nav
    'org_settings_nav'       => 'Org-instellingen',

    // Conversation badge & search
    'filter_by_org'          => 'Toon alle tickets van deze organisatie',
    'all_organizations'      => 'Alle organisaties',
    'remove_filter'          => 'Filter verwijderen',

    // Customer edit form
    'customer_organization'  => 'Organisatie',
    'no_organization'        => '— Geen —',
    'customer_role'          => 'Rol in organisatie',
    'view_org_tickets'       => 'Organisatietickets Bekijken',

    // Module settings
    'settings'               => 'Instellingen',
    'module_settings'        => 'OrgPortal-instellingen',
    'display_settings'       => 'Weergave-instellingen',
    'show_badge_conversation'=> 'Organisatiebadge op ticketpagina weergeven (bij tags)',
    'show_badge_kanban'      => 'Organisatiebadge op Kanban-kaarten weergeven',

    // Kanban filter
    'kanban_filter_org'           => 'Organisatie',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Statusfilters Bedrijfstickets',
    'company_filters_hint'        => 'Selecteer welke Kanban-kolommen als selectievakjes op de pagina Bedrijfstickets verschijnen. U kunt het label aanpassen dat aan portalgebruikers wordt getoond.',
    'filter_column_id'            => 'Kolom-ID',
    'filter_label'                => 'Label',
    'filter_add'                  => 'Filter toevoegen',
    'filter_board'                => 'Bord',
    'company_filters_no_boards'   => 'Geen Kanban-borden gevonden. Maak eerst een bord aan.',

    // User permission
    'perm_manage_organizations' => 'Organisaties beheren toestaan',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API-documentatie',

    // Ticket actions
    'close_ticket'                  => 'Ticket Sluiten',
    'close_ticket_confirm'          => 'Weet u zeker dat u dit ticket wilt sluiten?',
    'ticket_closed'                 => 'Ticket is gesloten.',
    'ticket_closed_label'           => 'Gesloten',
    'ticket_closed_reply_reopens'   => 'Dit ticket is gesloten. Een antwoord verzenden zal het heropenen.',
    'attach_files'                  => 'Bijlagen',
    'attach_files_hint'             => 'Tot :count bestanden, max :max MB elk',
    'attach_add_more'               => 'Voeg nog een bestand toe',
    'status_open'                   => 'Geopend',

    // Errors
    'access_denied'          => 'Toegang geweigerd. Managerrol vereist.',

    // Email
    'email_hello'            => 'Hallo',
    'email_new_ticket_intro' => 'Een nieuw ondersteuningsticket is ingediend door een lid van uw organisatie:',
    'email_new_ticket_footer'=> 'U ontvangt deze e-mail omdat u meldingen over nieuwe tickets voor uw organisatie in het Klantportaal hebt ingeschakeld.',
    'new_ticket_from'        => 'Nieuw ticket van :name',
    'email_from'             => 'Van',
    'email_subject'          => 'Onderwerp',
    'email_ticket_number'    => 'Ticket #',
    'view_ticket'            => 'Ticket Bekijken',

    // Org units & notifications
    'activate'               => 'Activeren',
    'add_unit'               => 'Structurele eenheid toevoegen',
    'apply'                  => 'Toepassen',
    'can_manage_org'         => 'Beheert volledige organisatie',
    'can_manage_org_hint'    => 'Stelt deze globale manager in staat andere leden van de globale manager af te schermen vanuit het portaal.',
    'cannot_deactivate_self' => 'U kunt jezelf niet deactiveren.',
    'cannot_grant_global'    => 'U mag geen globale managers toewijzen.',
    'confirm_deactivate'     => 'Dit lid deactiveren? Ze ontvangen geen tickettoewijzingen meer.',
    'confirm_delete_unit'    => 'Deze eenheid verwijderen? De leden worden niet toegewezen en eenheidmanagers worden gedegradeerd naar leden.',
    'deactivate'             => 'Deactiveren',
    'email_reply_agent_intro'    => 'Een nieuw antwoord van een agent is toegevoegd aan een ticket in uw organisatie:',
    'email_reply_customer_intro' => 'Een klant heeft gereageerd op een ticket in uw organisatie:',
    'email_reply_subject'        => 'Re: :number — :subject',
    'global_grant_hint'      => 'Stel een eenheid in om een eenheidsbeheerder te maken. Het bevorderen tot globale manager vereist beheerder toestemming.',
    'macro_author_name'      => 'Naam ticketauteur',
    'macro_created_date'     => 'Aanmaakdatum',
    'macro_created_datetime' => 'Aanmaakdatum & -tijd',
    'macro_created_time'     => 'Aanmaaktijd',
    'macro_manager_name'     => 'Ontvangerstnaam',
    'macro_org_name'         => 'Organisatienaam',
    'macro_reply_date'       => 'Antwoorddatum',
    'macro_reply_datetime'   => 'Antwoorddatum & -tijd',
    'macro_reply_time'       => 'Antwoordtijd',
    'macro_subject'          => 'Ticketonderwerp',
    'macro_ticket_number'    => 'Ticketnummer',
    'macro_ticket_url'       => 'Ticket-URL',
    'macro_unit_name'        => 'Eenheidsnaam',
    'member_activated'       => 'Lid opnieuw ingeschakeld.',
    'member_deactivated'     => 'Lid gedeactiveerd.',
    'member_status'          => 'Status',
    'member_unit'            => 'Structurele eenheid',
    'member_updated'         => 'Lid bijgewerkt.',
    'no_unit'                => 'Gehele organisatie',
    'no_units'               => 'Nog geen eenheden.',
    'notif_event_new_ticket'     => 'Nieuw ticket',
    'notif_event_reply_agent'    => 'Antwoord agent',
    'notif_event_reply_customer' => 'Antwoord klant',
    'notif_hint'                 => 'Vink het selectievakje aan om e-mailmeldingen te ontvangen voor tickets uit het geselecteerde bereik.',
    'notif_reply_triggers'       => 'Triggers voor antwoordmeldingen',
    'notif_scope'                => 'Bereik',
    'notif_scope_org'            => 'Gehele organisatie',
    'notif_trigger_agent'        => 'Waarschuwen voor antwoorden van agenten',
    'notif_trigger_customer'     => 'Waarschuwen voor antwoorden van klanten',
    'notif_trigger_hint'         => 'Deze instellingen gelden wereldwijd. Managers abonneren zich op specifieke bereiken op de instellingenpagina van het portaal.',
    'org_settings_title'     => 'Organisatie-instellingen',
    'perm_manage_templates'     => 'Beheer van berichtsjablonen toestaan',
    'rename'                 => 'Hernoemen',
    'role_global_manager'    => 'Globale manager',
    'role_manager_scoped'    => 'Manager',
    'role_member'            => 'Lid',
    'role_unit_manager'      => 'Eenheidsmanager',
    'select_member'          => 'Lid selecteren',
    'status_member_active'   => 'Actief',
    'status_member_inactive' => 'Gedeactiveerd',
    'tab_notifications'      => 'Meldingen',
    'tab_units'              => 'Structurele Eenheden',
    'tpl_body'                   => 'Berichttekst',
    'tpl_fallback_hint'          => '(laat leeg om de ingebouwde sjabloon te gebruiken)',
    'tpl_heading'                => 'E-mailsjabloon',
    'tpl_insert_macro'           => 'Variabele invoegen…',
    'tpl_load_default'           => 'Standaardsjabloon laden',
    'tpl_subject'                => 'Onderwerp',
    'tpl_subject_placeholder'    => 'Laat leeg om standaard te gebruiken',
    'tpl_tab_title'              => 'Berichtsjablonen',
    'unit_created'           => 'Eenheid aangemaakt.',
    'unit_deleted'           => 'Eenheid verwijderd.',
    'unit_exists'            => 'Een eenheid met deze naam bestaat al.',
    'unit_name'              => 'Naam structurele eenheid',
    'unit_name_placeholder'  => 'bijv. Verkoopafdeling',
    'unit_updated'           => 'Eenheid bijgewerkt.',
];
