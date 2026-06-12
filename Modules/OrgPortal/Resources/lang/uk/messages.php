<?php

return [

    // Admin — organizations list
    'organizations'          => 'Організації',
    'new_organization'       => 'Нова організація',
    'no_organizations'       => 'Ще немає організацій.',
    'create_one'             => 'Створити',

    // Admin — mailbox scope
    'mailbox'                => 'Скринька',
    'global_scope'           => 'Глобальна (всі скриньки)',
    'mailbox_scope_hint'     => 'Залиште порожнім, щоб організація була видима в усіх скриньках.',

    // Admin — create / edit form
    'organization_name'      => 'Назва організації',
    'create_organization'    => 'Створити організацію',
    'org_details'            => 'Деталі організації',
    'cancel'                 => 'Скасувати',
    'save'                   => 'Зберегти',
    'back'                   => 'Назад',
    'edit'                   => 'Редагувати',
    'delete'                 => 'Видалити',
    'confirm_delete_org'     => 'Видалити цю організацію?',

    // Admin — flash messages
    'org_created'            => 'Організацію створено.',
    'org_updated'            => 'Організацію оновлено.',
    'org_deleted'            => 'Організацію видалено.',

    // Admin — badge color
    'badge_color'            => 'Колір позначки',
    'color_default'          => 'За замовчуванням (сірий)',
    'preview'                => 'Перегляд',

    // Admin — members table
    'name'                   => 'Ім\'я',
    'email'                  => 'Email',
    'members'                => 'Учасники',
    'role'                   => 'Роль',
    'member'                 => 'Учасник',
    'manager'                => 'Менеджер',
    'deleted_customer'       => 'Видалений клієнт',
    'no_members'             => 'Ще немає учасників.',
    'remove'                 => 'Видалити',
    'confirm_remove_member'  => 'Видалити цього учасника?',

    // Admin — add member form
    'add_member'             => 'Додати учасника',
    'search_customer'        => 'Пошук клієнта',
    'type_name_or_email'     => 'Введіть ім\'я або email…',

    // Admin — member flash messages
    'role_updated'           => 'Роль оновлено.',
    'member_added'           => 'Учасника додано.',
    'member_removed'         => 'Учасника видалено.',
    'already_member'         => 'Цей клієнт вже є учасником організації.',
    'already_in_org'         => 'Цей клієнт вже належить до іншої організації.',

    // Portal — company tickets
    'company_tickets'        => 'Тікети компанії',
    'my_tickets'             => 'Мої тікети',
    'no_org_tickets'         => 'Тікетів для вашої організації не знайдено.',
    'unknown'                => 'Невідомо',
    'from'                   => 'Від',
    'subject'                => 'Тема',
    'ticket_hash'            => 'Тікет №',
    'updated'                => 'Оновлено',
    'no_subject'             => '(без теми)',
    'responsible'            => 'Відповідальний',
    'author'                 => 'Автор',
    'conv_status'            => 'Статус',
    'kanban_state'           => 'Стан',
    'search_ticket'          => 'Шукати заявку…',
    'filter_by_author'       => 'Показати заявки цього автора',
    'status_active'          => 'Активний',
    'status_pending'         => 'Очікує',
    'status_closed'          => 'Закрито',
    'status_spam'            => 'Спам',

    // Portal — ticket view
    'support_team'           => 'Команда підтримки',
    'customer'               => 'Клієнт',
    'reply'                  => 'Відповісти',
    'write_reply'            => 'Напишіть відповідь…',
    'send_reply'             => 'Надіслати відповідь',
    'reply_sent'             => 'Відповідь надіслано.',
    'change_author'          => 'Змінити автора',
    'author_changed'         => 'Автора тікета оновлено.',

    // Portal — settings
    'org_notification_settings' => 'Налаштування сповіщень організації',
    'org_settings_title'     => 'Налаштування організації',
    'organization'           => 'Організація',
    'notify_new_ticket_label'=> 'Отримувати email-сповіщення, коли учасник моєї організації відкриває новий тікет',
    'settings_saved'         => 'Налаштування збережено.',

    // Portal — settings tabs
    'tab_notifications'      => 'Сповіщення',
    'tab_units'              => 'Підрозділи',

    // Portal — structural units
    'unit_name'              => 'Назва підрозділу',
    'unit_name_placeholder'  => 'напр. Відділ продажів',
    'add_unit'               => 'Додати підрозділ',
    'rename'                 => 'Перейменувати',
    'no_units'               => 'Ще немає підрозділів.',
    'unit_created'           => 'Підрозділ створено.',
    'unit_updated'           => 'Підрозділ оновлено.',
    'unit_deleted'           => 'Підрозділ видалено.',
    'unit_exists'            => 'Підрозділ з такою назвою вже існує.',
    'confirm_delete_unit'    => 'Видалити цей підрозділ? Його учасники стануть без підрозділу, а менеджери підрозділу — звичайними учасниками.',

    // Portal — member management
    'member_unit'            => 'Підрозділ',
    'no_unit'                => 'Вся організація',
    'apply'                  => 'Застосувати',
    'role_member'            => 'Учасник',
    'role_manager_scoped'    => 'Менеджер',
    'role_unit_manager'      => 'Менеджер підрозділу',
    'role_global_manager'    => 'Глобальний менеджер',
    'global_grant_hint'      => 'Оберіть підрозділ, щоб призначити менеджера підрозділу. Підвищення до глобального менеджера потребує дозволу адміністратора.',
    'member_updated'         => 'Учасника оновлено.',
    'cannot_grant_global'    => 'Вам не дозволено призначати глобальних менеджерів.',
    'can_manage_org'         => 'Керує всією організацією',
    'can_manage_org_hint'    => 'Дозволяє цьому глобальному менеджеру підвищувати інших учасників до глобальних менеджерів через портал.',
    'member_status'          => 'Статус',
    'status_member_active'   => 'Активний',
    'status_member_inactive' => 'Звільнений',
    'deactivate'             => 'Звільнити',
    'activate'               => 'Відновити',
    'member_deactivated'     => 'Учасника звільнено.',
    'member_activated'       => 'Учасника відновлено.',
    'confirm_deactivate'     => 'Звільнити цього учасника? Нові заявки на нього не призначатимуться.',
    'cannot_deactivate_self' => 'Ви не можете звільнити самого себе.',

    // Notification subscriptions (portal)
    'notif_scope'                => 'Область',
    'notif_scope_org'            => 'Вся організація',
    'notif_event_new_ticket'     => 'Нова заявка',
    'notif_event_reply_agent'    => 'Відповідь агента',
    'notif_event_reply_customer' => 'Відповідь клієнта',
    'notif_hint'                 => 'Встановіть прапорець, щоб отримувати email-сповіщення про заявки з вибраної області.',

    // Notification template settings (admin)
    'notif_reply_triggers'       => 'Тригери сповіщень про відповіді',
    'notif_trigger_agent'        => 'Сповіщати про відповіді агентів',
    'notif_trigger_customer'     => 'Сповіщати про відповіді клієнтів',
    'notif_trigger_hint'         => 'Ці налаштування діють глобально. Менеджери підписуються на конкретні області на сторінці налаштувань порталу.',
    'tpl_tab_title'              => 'Шаблони сповіщень',
    'tpl_heading'                => 'Шаблон листа',
    'tpl_fallback_hint'          => '(залиште порожнім для використання вбудованого шаблону)',
    'tpl_subject'                => 'Тема',
    'tpl_subject_placeholder'    => 'Залиште порожнім для значення за замовчуванням',
    'tpl_body'                   => 'Тіло повідомлення',
    'tpl_insert_macro'           => 'Вставити змінну…',

    // Macros
    'macro_manager_name'     => 'Ім\'я отримувача',
    'macro_author_name'      => 'Ім\'я автора заявки',
    'macro_org_name'         => 'Назва організації',
    'macro_unit_name'        => 'Назва підрозділу',
    'macro_subject'          => 'Тема заявки',
    'macro_ticket_number'    => 'Номер заявки',
    'macro_ticket_url'       => 'Посилання на заявку',
    'macro_created_date'     => 'Дата створення',
    'macro_created_time'     => 'Час створення',
    'macro_created_datetime' => 'Дата та час створення',
    'macro_reply_date'       => 'Дата відповіді',
    'macro_reply_time'       => 'Час відповіді',
    'macro_reply_datetime'   => 'Дата та час відповіді',

    // Notification email fallback strings
    'email_reply_agent_intro'    => 'Агент додав нову відповідь до заявки вашої організації:',
    'email_reply_customer_intro' => 'Клієнт відповів на заявку вашої організації:',
    'email_reply_subject'        => 'Re: :number — :subject',

    // EUP nav
    'org_settings_nav'       => 'Налаштування організації',

    // Conversation badge & search
    'filter_by_org'          => 'Показати всі тікети цієї організації',
    'all_organizations'      => 'Всі організації',
    'remove_filter'          => 'Зняти фільтр',

    // Customer edit form
    'customer_organization'  => 'Організація',
    'no_organization'        => '— Без організації —',
    'customer_role'          => 'Роль в організації',
    'view_org_tickets'       => 'Заявки організації',

    // Module settings
    'settings'               => 'Налаштування',
    'module_settings'        => 'Налаштування OrgPortal',
    'display_settings'       => 'Налаштування відображення',
    'show_badge_conversation'=> 'Показувати плашку організації на сторінці тікета (поруч з тегами)',
    'show_badge_kanban'      => 'Показувати плашку організації на картках канбану',

    // Kanban filter
    'kanban_filter_org'           => 'Організація',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Фільтри статусів тікетів компанії',
    'company_filters_hint'        => 'Оберіть колонки Kanban, які відображатимуться як чекбокси-фільтри на сторінці тікетів компанії. Можна налаштувати назву, що бачитимуть користувачі порталу.',
    'filter_column_id'            => 'ID колонки',
    'filter_label'                => 'Назва',
    'filter_add'                  => 'Додати фільтр',
    'filter_board'                => 'Дошка',
    'company_filters_no_boards'   => 'Дошок Kanban не знайдено. Спочатку створіть дошку.',

    // User permission
    'perm_manage_organizations' => 'Дозволено керувати організаціями',
    'perm_manage_templates'     => 'Дозволено керувати шаблонами сповіщень організацій',

    // ApiWebhooks settings page
    'api_docs_link'          => 'Документація API OrgPortal',

    // Ticket actions
    'close_ticket'                  => 'Закрити заявку',
    'close_ticket_confirm'          => 'Ви впевнені, що хочете закрити цю заявку?',
    'ticket_closed'                 => 'Заявку закрито.',
    'ticket_closed_label'           => 'Закрито',
    'ticket_closed_reply_reopens'   => 'Ця заявка закрита. Відправка відповіді повторно відкриє її.',
    'attach_files'                  => 'Вкладення',
    'attach_files_hint'             => 'До :count файлів, максимум :max МБ кожен',
    'attach_add_more'               => 'Додати ще файл',
    'status_open'                   => 'Відкрита',

    // Errors
    'access_denied'          => 'Доступ заборонено. Потрібна роль менеджера.',

    // Email
    'email_hello'            => 'Привіт',
    'email_new_ticket_intro' => 'Учасник вашої організації відкрив новий тікет підтримки:',
    'email_new_ticket_footer'=> 'Ви отримали цей лист, оскільки увімкнули сповіщення про нові тікети для вашої організації в Клієнтському порталі.',
    'new_ticket_from'        => 'Новий тікет від :name',
    'email_from'             => 'Від',
    'email_subject'          => 'Тема',
    'email_ticket_number'    => 'Тікет №',
    'view_ticket'            => 'Переглянути тікет',
];
