<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organizacje',
    'new_organization'       => 'Nowa organizacja',
    'no_organizations'       => 'Brak organizacji.',
    'create_one'             => 'Utwórz jedną',

    // Admin — mailbox scope
    'mailbox'                => 'Skrzynka pocztowa',
    'global_scope'           => 'Globalna (wszystkie skrzynki)',
    'mailbox_scope_hint'     => 'Pozostaw puste, aby ta organizacja była widoczna we wszystkich skrzynkach pocztowych.',

    // Admin — create / edit form
    'organization_name'      => 'Nazwa organizacji',
    'create_organization'    => 'Utwórz organizację',
    'org_details'            => 'Szczegóły organizacji',
    'cancel'                 => 'Anuluj',
    'save'                   => 'Zapisz',
    'back'                   => 'Wstecz',
    'edit'                   => 'Edytuj',
    'delete'                 => 'Usuń',
    'confirm_delete_org'     => 'Usunąć tę organizację?',

    // Admin — flash messages
    'org_created'            => 'Organizacja utworzona.',
    'org_updated'            => 'Organizacja zaktualizowana.',
    'org_deleted'            => 'Organizacja usunięta.',

    // Admin — badge color
    'badge_color'            => 'Kolor odznaki',
    'color_default'          => 'Domyślny (szary)',
    'preview'                => 'Podgląd',

    // Admin — members table
    'name'                   => 'Imię',
    'email'                  => 'Email',
    'members'                => 'Członkowie',
    'role'                   => 'Rola',
    'member'                 => 'Członek',
    'manager'                => 'Kierownik',
    'deleted_customer'       => 'Usunięty klient',
    'no_members'             => 'Brak członków.',
    'remove'                 => 'Usuń',
    'confirm_remove_member'  => 'Usunąć tego członka?',

    // Admin — add member form
    'add_member'             => 'Dodaj członka',
    'search_customer'        => 'Szukaj klienta',
    'type_name_or_email'     => 'Wpisz imię lub email…',

    // Admin — member flash messages
    'role_updated'           => 'Rola zaktualizowana.',
    'member_added'           => 'Członek dodany.',
    'member_removed'         => 'Członek usunięty.',
    'already_member'         => 'Ten klient jest już członkiem organizacji.',
    'already_in_org'         => 'Ten klient już należy do innej organizacji.',

    // Portal — company tickets
    'company_tickets'        => 'Zgłoszenia firmowe',
    'my_tickets'             => 'Moje zgłoszenia',
    'no_org_tickets'         => 'Nie znaleziono zgłoszeń dla twojej organizacji.',
    'unknown'                => 'Nieznane',
    'from'                   => 'Od',
    'subject'                => 'Temat',
    'ticket_hash'            => 'Zgłoszenie nr',
    'updated'                => 'Zaktualizowane',
    'no_subject'             => '(brak tematu)',
    'responsible'            => 'Odpowiedzialny',
    'author'                 => 'Autor',
    'conv_status'            => 'Stan',
    'kanban_state'           => 'Status',
    'search_ticket'          => 'Szukaj zgłoszenia…',
    'filter_by_author'       => 'Pokaż zgłoszenia tego autora',
    'status_active'          => 'Aktywny',
    'status_pending'         => 'Oczekujący',
    'status_closed'          => 'Zamknięty',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Zespół wsparcia',
    'customer'               => 'Klient',
    'reply'                  => 'Odpowiedź',
    'write_reply'            => 'Napisz swoją odpowiedź…',
    'send_reply'             => 'Wyślij odpowiedź',
    'reply_sent'             => 'Odpowiedź wysłana.',
    'change_author'          => 'Zmień autora',
    'author_changed'         => 'Autor zgłoszenia zaktualizowany.',

    // Portal — settings
    'org_notification_settings' => 'Ustawienia powiadomień organizacji',
    'organization'           => 'Organizacja',
    'notify_new_ticket_label'=> 'Otrzymuj powiadomienie e-mail, gdy członek mojej organizacji otworzy nowe zgłoszenie',
    'settings_saved'         => 'Ustawienia zapisane.',

    // EUP nav
    'org_settings_nav'       => 'Ustawienia org.',

    // Conversation badge & search
    'filter_by_org'          => 'Pokaż wszystkie zgłoszenia z tej organizacji',
    'all_organizations'      => 'Wszystkie organizacje',
    'remove_filter'          => 'Usuń filtr',

    // Customer edit form
    'customer_organization'  => 'Organizacja',
    'no_organization'        => '— Brak —',
    'customer_role'          => 'Rola w organizacji',
    'view_org_tickets'       => 'Zobacz zgłoszenia organizacji',

    // Module settings
    'settings'               => 'Ustawienia',
    'module_settings'        => 'Ustawienia OrgPortal',
    'display_settings'       => 'Ustawienia wyświetlania',
    'show_badge_conversation'=> 'Pokaż odzakę organizacji na stronie zgłoszenia (obok tagów)',
    'show_badge_kanban'      => 'Pokaż odzakę organizacji na kartach Kanban',

    // Kanban filter
    'kanban_filter_org'           => 'Organizacja',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Filtry stanu zgłoszeń firmowych',
    'company_filters_hint'        => 'Wybierz, które kolumny Kanban będą wyświetlane jako pola wyboru na stronie Zgłoszenia firmowe. Możesz dostosować etykietę wyświetlaną użytkownikom portalu.',
    'filter_column_id'            => 'ID kolumny',
    'filter_label'                => 'Etykieta',
    'filter_add'                  => 'Dodaj filtr',
    'filter_board'                => 'Tablica',
    'company_filters_no_boards'   => 'Nie znaleziono tablic Kanban. Najpierw utwórz tablicę.',

    // User permission
    'perm_manage_organizations' => 'Zezwól na zarządzanie organizacjami',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API Docs',

    // Ticket actions
    'close_ticket'                  => 'Zamknij zgłoszenie',
    'close_ticket_confirm'          => 'Czy na pewno chcesz zamknąć to zgłoszenie?',
    'ticket_closed'                 => 'Zgłoszenie zostało zamknięte.',
    'ticket_closed_label'           => 'Zamknięte',
    'ticket_closed_reply_reopens'   => 'To zgłoszenie jest zamknięte. Wysłanie odpowiedzi je ponownie otworzy.',
    'attach_files'                  => 'Załączniki',
    'attach_files_hint'             => 'Do :count plików, maksymalnie :max MB każdy',
    'attach_add_more'               => 'Dodaj kolejny plik',
    'status_open'                   => 'Otwarty',

    // Errors
    'access_denied'          => 'Dostęp zabroniony. Wymagana rola kierownika.',

    // Email
    'email_hello'            => 'Cześć',
    'email_new_ticket_intro' => 'Członek twojej organizacji przesłał nowe zgłoszenie pomocy:',
    'email_new_ticket_footer'=> 'Otrzymałeś ten e-mail, ponieważ masz włączone powiadomienia o nowych zgłoszeniach dla twojej organizacji w Portalu klienta.',
    'new_ticket_from'        => 'Nowe zgłoszenie od :name',
    'email_from'             => 'Od',
    'email_subject'          => 'Temat',
    'email_ticket_number'    => 'Zgłoszenie nr',
    'view_ticket'            => 'Pokaż zgłoszenie',

    // Org units & notifications
    'activate'               => 'Aktywuj',
    'add_unit'               => 'Dodaj jednostkę strukturalną',
    'apply'                  => 'Zastosuj',
    'can_manage_org'         => 'Zarządza całą organizacją',
    'can_manage_org_hint'    => 'Umożliwia temu globalnym kierownikowi promocję innych członków na stanowisko globalnego kierownika z portalu.',
    'cannot_deactivate_self' => 'Nie możesz dezaktywować siebie.',
    'cannot_grant_global'    => 'Nie masz uprawnień do przydzielania globalnych kierowników.',
    'confirm_deactivate'     => 'Dezaktywować tego członka? Nie będzie już otrzymywać przydzielonych zgłoszeń.',
    'confirm_delete_unit'    => 'Usunąć tę jednostkę? Jej członkowie zostaną nieprzydzieleni, a kierownicy jednostki zostaną obniżeni do członków.',
    'deactivate'             => 'Dezaktywuj',
    'email_reply_agent_intro'    => 'Nowa odpowiedź agenta została dodana do zgłoszenia w twojej organizacji:',
    'email_reply_customer_intro' => 'Klient odpowiedział na zgłoszenie w twojej organizacji:',
    'email_reply_subject'        => 'Odpowiedź: :number — :subject',
    'global_grant_hint'      => 'Ustaw jednostkę, aby zrobić kierownika jednostki. Promocja na globalnego kierownika wymaga uprawnień administratora.',
    'macro_author_name'      => 'Imię autora zgłoszenia',
    'macro_created_date'     => 'Data utworzenia',
    'macro_created_datetime' => 'Data i godzina utworzenia',
    'macro_created_time'     => 'Godzina utworzenia',
    'macro_manager_name'     => 'Imię odbiorcy',
    'macro_org_name'         => 'Nazwa organizacji',
    'macro_reply_date'       => 'Data odpowiedzi',
    'macro_reply_datetime'   => 'Data i godzina odpowiedzi',
    'macro_reply_time'       => 'Godzina odpowiedzi',
    'macro_subject'          => 'Temat zgłoszenia',
    'macro_ticket_number'    => 'Numer zgłoszenia',
    'macro_ticket_url'       => 'Adres URL zgłoszenia',
    'macro_unit_name'        => 'Nazwa jednostki',
    'member_activated'       => 'Członek ponownie aktywowany.',
    'member_deactivated'     => 'Członek dezaktywowany.',
    'member_status'          => 'Stan',
    'member_unit'            => 'Jednostka strukturalna',
    'member_updated'         => 'Członek zaktualizowany.',
    'no_unit'                => 'Cała organizacja',
    'no_units'               => 'Brak jeszcze jednostek.',
    'notif_event_new_ticket'     => 'Nowe zgłoszenie',
    'notif_event_reply_agent'    => 'Odpowiedź agenta',
    'notif_event_reply_customer' => 'Odpowiedź klienta',
    'notif_hint'                 => 'Zaznacz pole, aby otrzymywać powiadomienia e-mail dla zgłoszeń z wybranego zakresu.',
    'notif_reply_triggers'       => 'Wyzwalacze powiadomień o odpowiedziach',
    'notif_scope'                => 'Zakres',
    'notif_scope_org'            => 'Cała organizacja',
    'notif_trigger_agent'        => 'Powiadamiaj o odpowiedziach agenta',
    'notif_trigger_customer'     => 'Powiadamiaj o odpowiedziach klienta',
    'notif_trigger_hint'         => 'Te ustawienia dotyczą całej organizacji. Kierownicy subskrybują określone zakresy na stronie ustawień portalu.',
    'org_settings_title'     => 'Ustawienia organizacji',
    'perm_manage_templates'     => 'Zezwól na zarządzanie szablonami powiadomień',
    'rename'                 => 'Zmień nazwę',
    'role_global_manager'    => 'Globalny kierownik',
    'role_manager_scoped'    => 'Kierownik',
    'role_member'            => 'Członek',
    'role_unit_manager'      => 'Kierownik jednostki',
    'select_member'          => 'Wybierz członka',
    'status_member_active'   => 'Aktywny',
    'status_member_inactive' => 'Dezaktywowany',
    'tab_notifications'      => 'Powiadomienia',
    'tab_units'              => 'Jednostki strukturalne',
    'tpl_body'                   => 'Treść wiadomości',
    'tpl_fallback_hint'          => '(pozostaw puste, aby użyć wbudowanego szablonu)',
    'tpl_heading'                => 'Szablon wiadomości e-mail',
    'tpl_insert_macro'           => 'Wstaw zmienną…',
    'tpl_load_default'           => 'Załaduj szablon domyślny',
    'tpl_subject'                => 'Temat',
    'tpl_subject_placeholder'    => 'Pozostaw puste, aby użyć domyślnie',
    'tpl_tab_title'              => 'Szablony powiadomień',
    'unit_created'           => 'Jednostka utworzona.',
    'unit_deleted'           => 'Jednostka usunięta.',
    'unit_exists'            => 'Jednostka o tej nazwie już istnieje.',
    'unit_name'              => 'Nazwa jednostki strukturalnej',
    'unit_name_placeholder'  => 'np. Dział sprzedaży',
    'unit_updated'           => 'Jednostka zaktualizowana.',
];
