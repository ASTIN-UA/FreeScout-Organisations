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
    'org_deactivated'        => 'Організацію деактивовано.',
    'org_activated'          => 'Організацію активовано.',
    'org_delete_has_members' => 'Неможливо видалити організацію: у ній ще є :count учасник(ів). Спочатку видаліть усіх учасників.',
    'org_delete_has_tickets' => 'Неможливо видалити організацію: у ній ще є :count заявка(ок). Спочатку перепризначте або видаліть заявки.',

    // Admin — organizations list columns & actions
    'col_tickets'            => 'Заявки',
    'col_tags'               => 'Теги',
    'col_status'             => 'Статус',
    'org_status_active'      => 'Активна',
    'org_status_inactive'    => 'Неактивна',
    'filter_active'          => 'Активні',
    'filter_inactive'        => 'Неактивні',
    'filter_all'             => 'Всі',
    'btn_tickets'            => 'Заявки',
    'btn_deactivate'         => 'Деактивувати',
    'btn_activate'           => 'Активувати',
    'deactivate_no_snapshot' => 'Увімкніть режим snapshot у системних налаштуваннях, щоб деактивувати організації.',
    'confirm_deactivate_org' => 'Деактивувати цю організацію? Вона більше не буде доступна на порталі.',
    'confirm_activate_org'   => 'Активувати цю організацію?',
    'search_organizations'   => 'Пошук організацій…',

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
    'select_member'          => 'Виберіть учасника',
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
    'member_remove_has_tickets' => 'Неможливо видалити цього учасника: у нього є :count заявка(ок) в цій організації. Натомість деактивуйте його, щоб зберегти історію заявок.',
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
    'tab_units'              => 'Структурні підрозділи',

    // Portal — structural units
    'unit_name'              => 'Назва структурного підрозділу',
    'unit_name_placeholder'  => 'напр. Відділ продажів',
    'add_unit'               => 'Додати структурний підрозділ',
    'rename'                 => 'Перейменувати',
    'no_units'               => 'Ще немає підрозділів.',
    'unit_created'           => 'Підрозділ створено.',
    'unit_updated'           => 'Підрозділ оновлено.',
    'unit_deleted'           => 'Підрозділ видалено.',
    'unit_exists'            => 'Підрозділ з такою назвою вже існує.',
    'unit_not_found'         => 'Підрозділ не знайдено в цій організації.',
    'confirm_delete_unit'    => 'Видалити цей структурний підрозділ? Його учасники стануть без підрозділу, а менеджери підрозділу — звичайними учасниками.',

    // Portal — member management
    'member_unit'            => 'Структурний підрозділ',
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
    'show_deactivated'       => 'Показувати звільнених',
    'confirm_deactivate'     => 'Звільнити цього учасника? Нові заявки на нього не призначатимуться.',
    'cannot_deactivate_self' => 'Ви не можете звільнити самого себе.',

    // Notification subscriptions (portal)
    'notif_scope'                => 'Область',
    'notif_scope_org'            => 'Вся організація',
    'notif_scope_no_unit'        => 'Без підрозділу',
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
    'tpl_load_default'           => 'Завантажити стандартний шаблон',
    'tpl_locale_label'           => 'Мова шаблону',
    'tpl_locale_hint'            => 'Оберіть мову для редагування. Відображаються лише мови, увімкнені в перемикачі мов порталу.',

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
    'macro_reply_text'       => 'Текст відповіді',
    'macro_ticket_text'      => 'Текст заявки',

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
    'show_org_in_profile'   => 'Показувати блок організації у профілі клієнта в заявках',

    // Kanban filter
    'kanban_filter_org'           => 'Організація',
    'kanban_filter_org_search_placeholder' => 'Введіть назву організації...',
    'kanban_filter_org_no_results'         => 'Нічого не знайдено.',
    'kanban_filter_org_reset'              => 'Зняти фільтр',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Фільтри статусів тікетів компанії',
    'company_filters_hint'        => 'Оберіть колонки Kanban, які відображатимуться як фільтри на сторінці тікетів компанії. Перетягніть рядки для зміни порядку. Вкажіть назву для кожної мови порталу — користувачі бачать назву своєю мовою.',
    'filter_column_id'            => 'ID колонки',
    'filter_original_name'        => 'Колонка Kanban',
    'filter_label'                => 'Назва в порталі',
    'filter_label_language'       => 'Мова',
    'filter_add'                  => 'Додати фільтр',
    'filter_board'                => 'Дошка',
    'company_filters_no_boards'   => 'Дошок Kanban не знайдено. Спочатку створіть дошку.',

    // Custom Fields in portal
    'cf_fields_heading'           => 'Додаткові поля на сторінці заявки',
    'cf_fields_hint'              => 'Оберіть які додаткові поля відображати на сторінці заявки в порталі. Перетягніть для зміни порядку. Задайте назву для кожної мови порталу — вона показуватиметься замість оригінальної назви поля.',

    // User permission
    'perm_manage_organizations' => 'Дозволено керувати організаціями',
    'perm_manage_templates'     => 'Дозволено керувати шаблонами сповіщень організацій',

    // Admin — system tab (Phase 7 attribution)
    'system_tab_title'          => 'Система',
    'system_attribution_heading'=> 'Атрибуція тікетів',
    'system_attribution_more'   => 'докладніше',
    'system_attribution_desc'   => 'За замовчуванням портал визначає, які тікети бачить менеджер, за поточним списком учасників організації: якщо клієнт в орг — його тікети видимі. Це працює добре, поки клієнти не переміщуються між організаціями або не звільняються. Клієнт, переведений до іншої орг, миттєво «забирає» всі свої тікети з порталу, а той, кого деактивували, залишає прогалину в історії. Атрибуція тікетів вирішує цю проблему: організація та підрозділ фіксуються прямо в тікеті в момент його створення (snapshot). Тікет залишається у початковій організації навіть після переміщення клієнта. Приклад: Іван подав 10 тікетів, перебуваючи в «Альфа ТОВ». Пізніше його перевели до «Бета ТОВ». З увімкненою атрибуцією всі 10 тікетів залишаються видимими в порталі «Альфа ТОВ», а нові тікети Івана з\'являться у «Бета ТОВ». Без атрибуції — всі 10 тікетів зникають з «Альфа ТОВ» в момент переведення. Фонове завдання запускається кожні 5 хвилин і обробляє до 1 000 тікетів за раз, щоб атрибутувати наявну історію.',
    'system_tickets_attributed' => 'тікетів атрибутовано',
    'system_tickets_pending'    => 'ще :count тікетів очікують атрибуції',
    'system_backfill_complete'  => 'Усі тікети атрибутовано — можна вмикати snapshot-видимість.',
    'system_run_backfill'       => 'Запустити backfill зараз',
    'system_cron_hint'          => 'Обробить до 2 000 тікетів негайно (крон запускається автоматично кожні 5 хв).',
    'system_backfill_done'        => 'Backfill виконано: оброблено :count тікетів.',
    // Preflight stats
    'system_preflight_heading'          => 'Що відбудеться під час наступного запуску бекфілу:',
    'system_preflight_pending'          => 'Тікетів очікують атрибуції',
    'system_preflight_orgs_with_tags'   => ':n з :total організацій мають прив\'язані теги',
    'system_preflight_orgs_no_tags'     => ':n організацій без прив\'язаних тегів',
    'system_preflight_will_tag'         => 'буде атрибутовано за тегом',
    'system_preflight_will_member'      => 'використає членство або залишиться без атрибуції',

    // Backfill result summary
    'system_backfill_summary_heading'   => 'Бекфіл завершено:',
    'system_backfill_summary_processed' => 'Оброблено :n тікетів у цьому проході.',
    'system_backfill_summary_by_tag'    => 'атрибутовано за тегом',
    'system_backfill_summary_by_member' => 'атрибутовано за членством',
    'system_backfill_summary_unmatched' => 'збіг не знайдено (org_id не встановлено)',

    'system_save_settings'        => 'Зберегти налаштування',
    'system_reset_attribution'    => 'Скинути всю атрибуцію',
    'system_reset_confirm'        => 'Це очистить org_id, org_unit_id та org_attributed_at для ВСІХ тікетів і запустить атрибуцію з нуля. Ви впевнені?',
    'system_reset_done'           => 'Атрибуцію скинуто. Всі тікети буде переатрибутовано під час наступного запуску бекфілу.',
    'system_snapshot_warning'   => 'Є тікети без атрибуції. Рекомендуємо вмикати snapshot-видимість лише після того, як лічильник досягне 0 — інакше ці тікети можуть тимчасово зникнути з порталу.',
    'system_attr_cron_enabled'      => 'Автоматично атрибутувати тікети через планувальник',
    'system_attr_cron_enabled_hint' => 'Якщо увімкнено, планувальник запускає атрибуцію кожні 5 хвилин для необроблених тікетів. Вимкніть, якщо хочете запускати атрибуцію вручну.',
    'system_snapshot_label'     => 'Використовувати snapshot-видимість тікетів',
    'system_snapshot_hint'      => 'Після увімкнення портал показуватиме тікети за збереженим org_id замість поточного списку учасників. Безпечний fallback для неатрибутованих тікетів залишається активним завжди.',

    // Admin — system tab: attribution source
    'system_attr_source_heading'  => 'Джерело атрибуції',
    'system_attr_source_desc'     => 'Визначає, як заявки прив\'язуються до організації. Якщо модуль тегів неактивний, доступна лише атрибуція за членством.',
    'system_attr_member'          => 'За членством',
    'system_attr_member_hint'     => 'За замовчуванням. Заявка прив\'язується до організації, членом якої є автор на момент створення.',
    'system_attr_tag'             => 'За тегом, потім за членством',
    'system_attr_tag_hint'        => 'Якщо заявка має тег, прив\'язаний до організації — використовується він. Інакше — членство. Потрібен модуль тегів.',
    'system_attr_tag_only'        => 'Лише за тегом',
    'system_attr_tag_only_hint'   => 'Заявки атрибутуються лише через теги. Заявки без відповідного тегу не атрибутуються. Потрібен модуль тегів.',
    'system_attr_tags_inactive'   => 'Варіанти на основі тегів недоступні, оскільки модуль тегів неактивний.',

    // Admin — org edit: tag bindings
    'org_tags_heading'  => 'Прив\'язані теги',
    'org_tags_hint'     => 'Оберіть теги, за якими заявки належать цій організації. За потреби призначте підрозділ для кожного тегу.',
    'org_tags_search_placeholder' => 'Пошук тегу…',

    // Admin — system tab: language switcher
    'system_lang_heading'       => 'Перемикач мови порталу',
    'system_lang_desc'          => 'Додає випадаючий список вибору мови в шапку клієнтського порталу. Обрана мова зберігається в профілі клієнта та використовується при надсиланні email-сповіщень. Не діє, якщо встановлено модуль EupSwLang (використовуйте його налаштування).',
    'system_lang_enable'        => 'Увімкнути перемикач мови на порталі',
    'system_lang_enable_hint'   => 'Показує іконку глобуса в шапці порталу, де клієнти можуть обрати мову інтерфейсу.',
    'system_lang_locales'       => 'Доступні мови',
    'system_lang_locales_hint'  => 'У перемикачі відображатимуться лише обрані мови. Залиште всі вибраними, щоб показувати всі доступні мови.',
    'system_lang_requires_eup'  => 'Перемикач мови порталу потребує модуля <strong>EndUserPortal</strong>.',

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

    'author_not_read'        => 'Автор ще не прочитав цю відповідь',

    // Manager viewed (admin thread meta)
    'manager_org_label'    => 'Менеджер організації',
    'manager_viewed_when'  => 'переглянув(ла) :when',

    // Портальні сповіщення
    'notifications'          => 'Сповіщення',
    'no_notifications'       => 'Немає нових сповіщень',
    'notif_new_ticket'       => 'відкрив(ла) заявку',
    'notif_new_reply'        => 'відповів(ла) на розмову',
    'notif_customer_reply'   => 'відповів(ла) на розмову',
    'notif_mark_all_read'    => 'Позначити все як прочитане',
    'notif_today'            => 'Сьогодні',
    'notif_yesterday'        => 'Вчора',
];
