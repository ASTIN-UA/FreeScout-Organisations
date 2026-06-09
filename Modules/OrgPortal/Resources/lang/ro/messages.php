<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organizații',
    'new_organization'       => 'Organizație nouă',
    'no_organizations'       => 'Nicio organizație încă.',
    'create_one'             => 'Creați una',

    // Admin — mailbox scope
    'mailbox'                => 'Căsuță poștală',
    'global_scope'           => 'Global (toate căsuțele)',
    'mailbox_scope_hint'     => 'Lăsați gol pentru a face această organizație vizibilă în toate căsuțele.',

    // Admin — create / edit form
    'organization_name'      => 'Numele organizației',
    'create_organization'    => 'Creați organizația',
    'org_details'            => 'Detalii organizație',
    'cancel'                 => 'Anulați',
    'save'                   => 'Salvați',
    'back'                   => 'Înapoi',
    'edit'                   => 'Editați',
    'delete'                 => 'Ștergeți',
    'confirm_delete_org'     => 'Ștergeți această organizație?',

    // Admin — flash messages
    'org_created'            => 'Organizația a fost creată.',
    'org_updated'            => 'Organizația a fost actualizată.',
    'org_deleted'            => 'Organizația a fost ștearsă.',

    // Admin — badge color
    'badge_color'            => 'Culoarea insignei',
    'color_default'          => 'Implicit (gri)',
    'preview'                => 'Previzualizare',

    // Admin — members table
    'name'                   => 'Nume',
    'email'                  => 'Email',
    'members'                => 'Membri',
    'role'                   => 'Rol',
    'member'                 => 'Membru',
    'manager'                => 'Manager',
    'deleted_customer'       => 'Client șters',
    'no_members'             => 'Niciun membru încă.',
    'remove'                 => 'Eliminați',
    'confirm_remove_member'  => 'Eliminați acest membru?',

    // Admin — add member form
    'add_member'             => 'Adăugați membru',
    'search_customer'        => 'Căutați client',
    'type_name_or_email'     => 'Introduceți numele sau emailul…',

    // Admin — member flash messages
    'role_updated'           => 'Rolul a fost actualizat.',
    'member_added'           => 'Membrul a fost adăugat.',
    'member_removed'         => 'Membrul a fost eliminat.',
    'already_member'         => 'Acest client este deja membru al organizației.',
    'already_in_org'         => 'Acest client aparține deja unei alte organizații.',

    // Portal — company tickets
    'company_tickets'        => 'Tichete companie',
    'my_tickets'             => 'Tichetele mele',
    'no_org_tickets'         => 'Nu s-au găsit tichete pentru organizația dvs.',
    'unknown'                => 'Necunoscut',
    'from'                   => 'De la',
    'subject'                => 'Subiect',
    'ticket_hash'            => 'Tichet #',
    'updated'                => 'Actualizat',
    'no_subject'             => '(fără subiect)',
    'responsible'            => 'Responsabil',
    'author'                 => 'Autor',
    'conv_status'            => 'Status',
    'kanban_state'           => 'Stare',
    'search_ticket'          => 'Căutați tichet…',
    'filter_by_author'       => 'Afișați tichetele acestui autor',
    'status_active'          => 'Activ',
    'status_pending'         => 'În așteptare',
    'status_closed'          => 'Închis',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Echipa de suport',
    'customer'               => 'Client',
    'reply'                  => 'Răspundeți',
    'write_reply'            => 'Scrieți răspunsul dvs.…',
    'send_reply'             => 'Trimiteți răspunsul',
    'reply_sent'             => 'Răspunsul a fost trimis.',
    'change_author'          => 'Schimbați autorul',
    'author_changed'         => 'Autorul tichetului a fost actualizat.',

    // Portal — settings
    'org_notification_settings' => 'Setări notificări organizație',
    'organization'           => 'Organizație',
    'notify_new_ticket_label'=> 'Primiți notificare email când un membru al organizației mele deschide un tichet nou',
    'settings_saved'         => 'Setările au fost salvate.',

    // EUP nav
    'org_settings_nav'       => 'Setări org.',

    // Conversation badge & search
    'filter_by_org'          => 'Afișați toate tichetele acestei organizații',
    'all_organizations'      => 'Toate organizațiile',
    'remove_filter'          => 'Eliminați filtrul',

    // Customer edit form
    'customer_organization'  => 'Organizație',
    'no_organization'        => '— Niciunul —',
    'customer_role'          => 'Rol în organizație',
    'view_org_tickets'       => 'Vedeți tichetele organizației',

    // Module settings
    'settings'               => 'Setări',
    'module_settings'        => 'Setări OrgPortal',
    'display_settings'       => 'Setări afișare',
    'show_badge_conversation'=> 'Afișați insigna organizației pe pagina tichetului (lângă etichete)',
    'show_badge_kanban'      => 'Afișați insigna organizației pe cardurile Kanban',

    // Kanban filter
    'kanban_filter_org'           => 'Organizație',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Filtre status tichete companie',
    'company_filters_hint'        => 'Selectați coloanele Kanban care apar ca filtre checkbox pe pagina Tichete companie. Puteți personaliza eticheta afișată utilizatorilor portalului.',
    'filter_column_id'            => 'ID coloană',
    'filter_label'                => 'Etichetă',
    'filter_add'                  => 'Adăugați filtru',
    'filter_board'                => 'Tablă',
    'company_filters_no_boards'   => 'Nu s-au găsit tablouri Kanban. Creați mai întâi un tablou.',

    // User permission
    'perm_manage_organizations' => 'Permiteți gestionarea organizațiilor',

    // ApiWebhooks settings page
    'api_docs_link'          => 'Documentație API OrgPortal',

    // Ticket actions
    'close_ticket'                  => 'Închideți tichetul',
    'close_ticket_confirm'          => 'Sigur doriți să închideți acest tichet?',
    'ticket_closed'                 => 'Tichetul a fost închis.',
    'ticket_closed_label'           => 'Închis',
    'ticket_closed_reply_reopens'   => 'Acest tichet este închis. Trimiterea unui răspuns îl va redeschide.',
    'attach_files'                  => 'Atașamente',
    'attach_files_hint'             => 'Până la :count fișiere, max :max MB fiecare',
    'attach_add_more'               => 'Adăugați alt fișier',
    'status_open'                   => 'Deschis',

    // Errors
    'access_denied'          => 'Acces refuzat. Este necesar rolul de manager.',

    // Email
    'email_hello'            => 'Bună ziua',
    'email_new_ticket_intro' => 'Un tichet de asistență nou a fost trimis de un membru al organizației dvs.:',
    'email_new_ticket_footer'=> 'Ați primit acest email deoarece ați activat notificările pentru tichete noi ale organizației dvs. în Portalul clienților.',
    'new_ticket_from'        => 'Tichet nou de la :name',
    'email_from'             => 'De la',
    'email_subject'          => 'Subiect',
    'email_ticket_number'    => 'Tichet #',
    'view_ticket'            => 'Vizualizați tichetul',
];
