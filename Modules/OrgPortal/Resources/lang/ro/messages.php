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

    // Org units & notifications
    'activate'               => 'Activați',
    'add_unit'               => 'Adăugați unitate structurală',
    'apply'                  => 'Aplicați',
    'can_manage_org'         => 'Gestionează întreaga organizație',
    'can_manage_org_hint'    => 'Permite acestui manager global să promoveze alți membri la manager global din portal.',
    'cannot_deactivate_self' => 'Nu vă puteți dezactiva pe dvs. însuși.',
    'cannot_grant_global'    => 'Nu aveți permisiunea să atribuiți manageri globali.',
    'confirm_deactivate'     => 'Dezactivați acest membru? Aceștia nu vor mai primi atribuiri de tichete.',
    'confirm_delete_unit'    => 'Ștergeți această unitate? Membrii săi vor fi neataşaţi și managerii de unitate retrograduți la membri.',
    'deactivate'             => 'Dezactivați',
    'email_reply_agent_intro'    => 'Un răspuns agent nou a fost adăugat la un tichet din organizația dvs.:',
    'email_reply_customer_intro' => 'Un client a răspuns la un tichet din organizația dvs.:',
    'email_reply_subject'        => 'Re: :number — :subject',
    'global_grant_hint'      => 'Setați o unitate pentru a face un manager de unitate. Promovarea la manager global necesită permisiune de admin.',
    'macro_author_name'      => 'Numele autorului tichetului',
    'macro_created_date'     => 'Data creării',
    'macro_created_datetime' => 'Data & ora creării',
    'macro_created_time'     => 'Ora creării',
    'macro_manager_name'     => 'Numele destinatarului',
    'macro_org_name'         => 'Numele organizației',
    'macro_reply_date'       => 'Data răspunsului',
    'macro_reply_datetime'   => 'Data & ora răspunsului',
    'macro_reply_text'       => 'Textul răspunsului',
    'macro_ticket_text'      => 'Textul tichetului',
    'macro_reply_time'       => 'Ora răspunsului',
    'macro_subject'          => 'Subiectul tichetului',
    'macro_ticket_number'    => 'Numărul tichetului',
    'macro_ticket_url'       => 'URL tichetul',
    'macro_unit_name'        => 'Numele unității',
    'member_activated'       => 'Membru reactivat.',
    'member_deactivated'     => 'Membru dezactivat.',
    'member_status'          => 'Status',
    'member_unit'            => 'Unitate structurală',
    'member_updated'         => 'Membru actualizat.',
    'no_unit'                => 'Întreaga organizație',
    'no_units'               => 'Nicio unitate încă.',
    'notif_event_new_ticket'     => 'Tichet nou',
    'notif_event_reply_agent'    => 'Răspuns agent',
    'notif_event_reply_customer' => 'Răspuns client',
    'notif_hint'                 => 'Bifați caseta pentru a primi notificări email pentru tichete din scopul selectat.',
    'notif_reply_triggers'       => 'Declanșatori notificare răspuns',
    'notif_scope'                => 'Domeniu',
    'notif_scope_org'            => 'Întreaga organizație',
    'notif_trigger_agent'        => 'Notificați la răspunsuri agent',
    'notif_trigger_customer'     => 'Notificați la răspunsuri client',
    'notif_trigger_hint'         => 'Aceste setări se aplică global. Managerii se abonează la domenii specifice pe pagina setărilor portalului.',
    'org_settings_title'     => 'Setări organizație',
    'perm_manage_templates'     => 'Permiteți gestionarea șabloanelor de notificare',
    'rename'                 => 'Redenumiți',
    'role_global_manager'    => 'Manager global',
    'role_manager_scoped'    => 'Manager',
    'role_member'            => 'Membru',
    'role_unit_manager'      => 'Manager de unitate',
    'select_member'          => 'Selectați membru',
    'status_member_active'   => 'Activ',
    'status_member_inactive' => 'Dezactivat',
    'tab_notifications'      => 'Notificări',
    'tab_units'              => 'Unități structurale',
    'tpl_body'                   => 'Corpul mesajului',
    'tpl_fallback_hint'          => '(lăsați gol pentru a utiliza șablonul încorporat)',
    'tpl_heading'                => 'Șablon email',
    'tpl_insert_macro'           => 'Inserați variabilă…',
    'tpl_load_default'           => 'Încărcați șablonul implicit',
    'tpl_subject'                => 'Subiect',
    'tpl_subject_placeholder'    => 'Lăsați gol pentru a utiliza implicit',
    'tpl_tab_title'              => 'Șabloane notificare',
    'unit_created'           => 'Unitate creată.',
    'unit_deleted'           => 'Unitate ștearsă.',
    'unit_exists'            => 'O unitate cu acest nume există deja.',
    'unit_name'              => 'Numele unității structurale',
    'unit_name_placeholder'  => 'de ex. Departament vânzări',
    'unit_updated'           => 'Unitate actualizată.',
    'author_not_read'        => 'Autorul nu a citit încă acest răspuns',
    'manager_org_label'      => 'Manager organizație',
    'manager_viewed_when'    => 'vizualizat :when',
    'notif_scope_no_unit'    => 'Nicio unitate',
    'system_tab_title'       => 'Sistem',
    'system_attribution_heading' => 'Atribuire tichet',
    'system_attribution_more' => 'detalii',
    'system_attribution_desc' => 'Implicit, portalul decide care tichete poate vedea un manager privind lista actuală de membri ai organizației – dacă un client este în organizație, tichetele acestuia sunt vizibile. Acest lucru funcționează bine până când clienții se mută între organizații sau pleacă: un client transferat la o altă organizație și-ar pierde brusc toate tichetele vechi, iar un client care pleacă și-ar retrage complet tichetele din portal. Atribuirea tichetelor rezolvă aceasta prin stocarea unui instantaneu al organizației și unității direct pe fiecare tichet în momentul creării sale. Tichetul rămâne vizibil în organizația inițială chiar și după ce clientul s-a mutat. Exemplu: John a trimis 10 tichete în timp ce era la "Acme Corp". El a fost mutat mai târziu la "Beta LLC". Cu atribuire activată, toate cele 10 tichete rămân vizibile în portalul "Acme Corp" – și tichetele viitoare apar în "Beta LLC". Fără atribuire, toate cele 10 tichete ar dispărea din "Acme Corp" în momentul în care John se mută. Sarcina din fundal rulează la fiecare 5 minute și procesează până la 1 000 de tichete pe trecere pentru a atribui istoricul existent.',
    'system_tickets_attributed' => 'tichete atribuite',
    'system_tickets_pending' => ':count tichete încă în așteptare pentru atribuire',
    'system_backfill_complete' => 'Toate tichetele atribuite – gata pentru vizibilitate bazată pe instantaneu.',
    'system_run_backfill' => 'Rulați backfill acum',
    'system_cron_hint' => 'Procesează până la 2 000 de tichete imediat (cron rulează automat la fiecare 5 minute).',
    'system_backfill_done' => 'Backfill completat: :count tichete procesate.',
    'system_snapshot_warning' => 'Există încă tichete nezvicite. Vă recomandăm să activați vizibilitatea bazată pe instantaneu abia după ce contorul atinge 0 – altfel aceste tichete pot dispărea temporar din portal.',
    'system_snapshot_label' => 'Activați vizibilitatea tichetelor bazată pe instantaneu',
    'system_snapshot_hint' => 'Când este activat, portalul afișează tichete după instantaneu org_id stocat în loc de lista de membri activi. Un remiză sigură pentru tichetele nezvicite rămâne activ în permanență.',
    'system_lang_heading' => 'Selector de limbă portal',
    'system_lang_desc' => 'Adaugă o listă derulantă selector de limbă la bara de navigare a portalului client. Limba aleasă este salvată în profilul clientului și utilizată la trimiterea de notificări prin e-mail. Nu are efect atunci când modulul EupSwLang este activ (utilizați setările EupSwLang).',
    'system_lang_enable' => 'Activați selectorul de limbă pe portal',
    'system_lang_enable_hint' => 'Afișează o pictogramă de glob în bara de navigare a portalului care permite clienților să schimbe limba portalului.',
    'system_lang_locales' => 'Limbi disponibile',
    'system_lang_locales_hint' => 'Doar limbile bifate apar în selector. Lăsați toate bifate pentru a afișa fiecare limbă disponibilă.',

    // Notification bell
    'notifications'        => 'Notificări',
    'no_notifications'     => 'Fără notificări noi',
    'notif_new_ticket'     => 'a creat un tichet',
    'notif_new_reply'      => 'a răspuns la conversație',
    'notif_customer_reply' => 'a răspuns la conversație',
    'notif_mark_all_read'  => 'Marchează tot ca citit',
    'notif_today'          => 'Astăzi',
    'notif_yesterday'      => 'Ieri',

    // Organizations — activation / deactivation
    'org_deactivated'                   => 'Organizație dezactivată.',
    'org_activated'                     => 'Organizație activată.',
    'col_tickets'                       => 'Tichete',
    'col_tags'                          => 'Etichete',
    'col_status'                        => 'Status',
    'org_status_active'                 => 'Activ',
    'org_status_inactive'               => 'Inactiv',
    'filter_active'                     => 'Activ',
    'filter_inactive'                   => 'Inactiv',
    'filter_all'                        => 'Toate',
    'btn_tickets'                       => 'Tichete',
    'btn_deactivate'                    => 'Dezactivați',
    'btn_activate'                      => 'Activați',
    'deactivate_no_snapshot'            => 'Activați vizibilitatea instantaneu în Setări sistem pentru a dezactiva organizații.',
    'confirm_deactivate_org'            => 'Dezactivați această organizație? Nu va mai fi accesibilă pe portal.',
    'confirm_activate_org'              => 'Activați această organizație?',
    'search_organizations'              => 'Căutați organizații…',
    'unit_not_found'                    => 'Unitate negăsită în această organizație.',
    'show_deactivated'                  => 'Afișați dezactivate',
    'show_org_in_profile'               => 'Afișați blocul de organizație în profilul clientului în tichete',

    // System settings — backfill preflight
    'system_preflight_heading'          => 'Ce se va întâmpla când rulați backfill:',
    'system_preflight_pending'          => 'Tichete în așteptare atribuire',
    'system_preflight_orgs_with_tags'   => ':n din :total organizații au bindinguri de etichete configurate',
    'system_preflight_orgs_no_tags'     => ':n organizații nu au bindinguri de etichete',
    'system_preflight_will_tag'         => 'vor fi atribuite prin etichetă',
    'system_preflight_will_member'      => 'vor reveni la membru / rămân neataşate',

    // System settings — backfill summary
    'system_backfill_summary_heading'   => 'Backfill completat:',
    'system_backfill_summary_processed' => ':n tichete procesate în această trecere.',
    'system_backfill_summary_by_tag'    => 'atribuite prin binding de etichete',
    'system_backfill_summary_by_member' => 'atribuite prin membri activi',
    'system_backfill_summary_unmatched' => 'niciun potrivire găsit (org_id rămâne gol)',

    // System settings — controls
    'system_save_settings'              => 'Salvați setările',
    'system_reset_attribution'          => 'Resetați toată atribuirea',
    'system_reset_confirm'              => 'Aceasta va șterge org_id, org_unit_id și org_attributed_at pe TOATE tichetele și va restarta atribuirea de la zero. Sunteți sigur?',
    'system_reset_done'                 => 'Atribuire resetată. Toate tichetele vor fi re-atribuite la următoarea rulare backfill.',

    // System settings — attribution scheduler
    'system_attr_cron_enabled'          => 'Atribuiți automat tichete prin planificator',
    'system_attr_cron_enabled_hint'     => 'Când este activat, planificatorul rulează un backfill la fiecare 5 minute pentru a atribui tichete neprocesate. Dezactivați dacă preferați atribuirea manuală.',

    // System settings — attribution source
    'system_attr_source_heading'        => 'Sursă atribuire',
    'system_attr_source_desc'           => 'Controlează cum sunt legate tichetele la o organizație. Când modulul Etichete este inactiv, doar atribuirea bazată pe membri este disponibilă.',
    'system_attr_member'                => 'După membri',
    'system_attr_member_hint'           => 'Implicit. Tichetele sunt atribuite organizației din care face parte clientul la momentul creării.',
    'system_attr_tag'                   => 'După etichetă, reveniți la membri',
    'system_attr_tag_hint'              => 'Dacă un tichet are o etichetă legată la o organizație, acea legătură câștigă. În caz contrar se folosesc membri. Necesită modulul Etichete.',
    'system_attr_tag_only'              => 'Doar după etichetă',
    'system_attr_tag_only_hint'         => 'Tichetele sunt atribuite doar prin bindinguri de etichete. Tichetele fără etichetă potrivit nu primesc atribuire. Necesită modulul Etichete.',
    'system_attr_tags_inactive'         => 'Opțiunile bazate pe etichete sunt dezactivate deoarece modulul Etichete nu este activ.',

    // System settings — org tag bindings
    'org_tags_heading'                  => 'Bindinguri etichete',
    'org_tags_hint'                     => 'Selectați care etichete identifică tichetele acestei organizații. Opțional atribuiți o unitate structurală per etichetă.',
    'org_tags_search_placeholder'       => 'Căutați etichetă…',
];
