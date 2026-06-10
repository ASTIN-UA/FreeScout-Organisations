<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organizzazioni',
    'new_organization'       => 'Nuova Organizzazione',
    'no_organizations'       => 'Nessuna organizzazione ancora.',
    'create_one'             => 'Creane una',

    // Admin — mailbox scope
    'mailbox'                => 'Cassetta postale',
    'global_scope'           => 'Globale (tutte le cassette postali)',
    'mailbox_scope_hint'     => 'Lasciare vuoto per rendere questa organizzazione visibile in tutte le cassette postali.',

    // Admin — create / edit form
    'organization_name'      => 'Nome Organizzazione',
    'create_organization'    => 'Crea Organizzazione',
    'org_details'            => 'Dettagli Organizzazione',
    'cancel'                 => 'Annulla',
    'save'                   => 'Salva',
    'back'                   => 'Indietro',
    'edit'                   => 'Modifica',
    'delete'                 => 'Elimina',
    'confirm_delete_org'     => 'Eliminare questa organizzazione?',

    // Admin — flash messages
    'org_created'            => 'Organizzazione creata.',
    'org_updated'            => 'Organizzazione aggiornata.',
    'org_deleted'            => 'Organizzazione eliminata.',

    // Admin — badge color
    'badge_color'            => 'Colore distintivo',
    'color_default'          => 'Predefinito (grigio)',
    'preview'                => 'Anteprima',

    // Admin — members table
    'name'                   => 'Nome',
    'email'                  => 'Email',
    'members'                => 'Membri',
    'role'                   => 'Ruolo',
    'member'                 => 'Membro',
    'manager'                => 'Gestore',
    'deleted_customer'       => 'Cliente eliminato',
    'no_members'             => 'Nessun membro ancora.',
    'remove'                 => 'Rimuovi',
    'confirm_remove_member'  => 'Rimuovere questo membro?',

    // Admin — add member form
    'add_member'             => 'Aggiungi Membro',
    'search_customer'        => 'Cerca cliente',
    'type_name_or_email'     => 'Digita nome o email…',

    // Admin — member flash messages
    'role_updated'           => 'Ruolo aggiornato.',
    'member_added'           => 'Membro aggiunto.',
    'member_removed'         => 'Membro rimosso.',
    'already_member'         => 'Questo cliente è già un membro dell\'organizzazione.',
    'already_in_org'         => 'Questo cliente appartiene già a un\'altra organizzazione.',

    // Portal — company tickets
    'company_tickets'        => 'Ticket Aziendali',
    'my_tickets'             => 'I Miei Ticket',
    'no_org_tickets'         => 'Nessun ticket trovato per la tua organizzazione.',
    'unknown'                => 'Sconosciuto',
    'from'                   => 'Da',
    'subject'                => 'Oggetto',
    'ticket_hash'            => 'Ticket #',
    'updated'                => 'Aggiornato',
    'no_subject'             => '(nessun oggetto)',
    'responsible'            => 'Responsabile',
    'author'                 => 'Autore',
    'conv_status'            => 'Stato',
    'kanban_state'           => 'Fase',
    'search_ticket'          => 'Cerca ticket…',
    'filter_by_author'       => 'Mostra ticket da questo autore',
    'status_active'          => 'Attivo',
    'status_pending'         => 'In sospeso',
    'status_closed'          => 'Chiuso',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Team di Supporto',
    'customer'               => 'Cliente',
    'reply'                  => 'Rispondi',
    'write_reply'            => 'Scrivi la tua risposta…',
    'send_reply'             => 'Invia Risposta',
    'reply_sent'             => 'Risposta inviata.',
    'change_author'          => 'Cambia Autore',
    'author_changed'         => 'Autore del ticket aggiornato.',

    // Portal — settings
    'org_notification_settings' => 'Impostazioni Notifiche Organizzazione',
    'organization'           => 'Organizzazione',
    'notify_new_ticket_label'=> 'Ricevi notifica email quando un membro della mia organizzazione apre un nuovo ticket',
    'settings_saved'         => 'Impostazioni salvate.',

    // EUP nav
    'org_settings_nav'       => 'Impostazioni Org',

    // Conversation badge & search
    'filter_by_org'          => 'Mostra tutti i ticket di questa organizzazione',
    'all_organizations'      => 'Tutte le organizzazioni',
    'remove_filter'          => 'Rimuovi filtro',

    // Customer edit form
    'customer_organization'  => 'Organizzazione',
    'no_organization'        => '— Nessuno —',
    'customer_role'          => 'Ruolo nell\'organizzazione',
    'view_org_tickets'       => 'Visualizza Ticket Organizzazione',

    // Module settings
    'settings'               => 'Impostazioni',
    'module_settings'        => 'Impostazioni OrgPortal',
    'display_settings'       => 'Impostazioni Visualizzazione',
    'show_badge_conversation'=> 'Mostra distintivo organizzazione sulla pagina del ticket (vicino ai tag)',
    'show_badge_kanban'      => 'Mostra distintivo organizzazione sulle schede Kanban',

    // Kanban filter
    'kanban_filter_org'           => 'Organizzazione',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Filtri Stato Ticket Aziendali',
    'company_filters_hint'        => 'Seleziona quali colonne Kanban appaiono come caselle di controllo sulla pagina Ticket Aziendali. Puoi personalizzare l\'etichetta mostrata agli utenti del portale.',
    'filter_column_id'            => 'ID Colonna',
    'filter_label'                => 'Etichetta',
    'filter_add'                  => 'Aggiungi filtro',
    'filter_board'                => 'Bacheca',
    'company_filters_no_boards'   => 'Nessuna bacheca Kanban trovata. Crea prima una bacheca.',

    // User permission
    'perm_manage_organizations' => 'Consenti gestione organizzazioni',

    // ApiWebhooks settings page
    'api_docs_link'          => 'Documentazione API OrgPortal',

    // Ticket actions
    'close_ticket'                  => 'Chiudi Ticket',
    'close_ticket_confirm'          => 'Sei sicuro di voler chiudere questo ticket?',
    'ticket_closed'                 => 'Il ticket è stato chiuso.',
    'ticket_closed_label'           => 'Chiuso',
    'ticket_closed_reply_reopens'   => 'Questo ticket è chiuso. L\'invio di una risposta lo riaprirà.',
    'attach_files'                  => 'Allegati',
    'attach_files_hint'             => 'Fino a :count file, massimo :max MB ciascuno',
    'attach_add_more'               => 'Aggiungi un altro file',
    'status_open'                   => 'Aperto',

    // Errors
    'access_denied'          => 'Accesso negato. Ruolo gestore richiesto.',

    // Email
    'email_hello'            => 'Ciao',
    'email_new_ticket_intro' => 'Un nuovo ticket di supporto è stato inviato da un membro della tua organizzazione:',
    'email_new_ticket_footer'=> 'Hai ricevuto questa email perché hai abilitato le notifiche dei nuovi ticket per la tua organizzazione nel Portale Cliente.',
    'new_ticket_from'        => 'Nuovo ticket da :name',
    'email_from'             => 'Da',
    'email_subject'          => 'Oggetto',
    'email_ticket_number'    => 'Ticket #',
    'view_ticket'            => 'Visualizza Ticket',
];
