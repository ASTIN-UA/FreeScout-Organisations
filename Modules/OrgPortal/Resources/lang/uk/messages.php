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
    'name'                   => 'Назва',
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
    'organization'           => 'Організація',
    'notify_new_ticket_label'=> 'Отримувати email-сповіщення, коли учасник моєї організації відкриває новий тікет',
    'settings_saved'         => 'Налаштування збережено.',

    // EUP nav
    'org_settings_nav'       => 'Налаштування орг.',

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
