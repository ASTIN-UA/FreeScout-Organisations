# OrgPortal — Organization Portal for FreeScout

Модуль додає концепцію **Організація** до FreeScout та розширює End-User Portal для зовнішніх клієнтів.

## Можливості

### Адмін-панель (для агентів підтримки)
- Новий пункт меню **Manage → Organizations**
- Повний CRUD для організацій
- Додавання клієнтів до організації з вибором ролі (**member** / **manager**)
- AJAX-пошук клієнтів при додаванні
- Організації глобальні — не прив'язані до конкретного mailbox

### End-User Portal — звичайний учасник (role=member)
- Поведінка без змін — бачить тільки свої тікети

### End-User Portal — менеджер (role=manager)
- Додатковий таб **"Тікети компанії"** на сторінці My Tickets
- Перегляд усіх тікетів усіх учасників організації з усіх mailboxes
- Відповідь на будь-який тікет члена від свого імені
- Налаштування email-сповіщень (окрема сторінка або секція в EUP Settings)

### Email-нотифікація
- Коли учасник організації створює новий тікет (через EUP або email)
- Менеджерам з увімкненим `notify_on_new_ticket` надсилається email
- Тема: "Новий тікет від [ім'я]", тіло: посилання на тікет у порталі

## База даних

```
organizations
  id, name, created_at, updated_at

organization_members
  id, organization_id (FK), customer_id (FK),
  role ENUM('member','manager'),
  notify_on_new_ticket BOOLEAN DEFAULT false,
  created_at, updated_at
```

## Мінімальні вимоги

| | Версія |
|---|---|
| FreeScout | ≥ 1.8.147 |
| End-User Portal | ≥ 1.0.0 |
| PHP | ≥ 7.4 |

## Встановлення

1. Скопіювати папку `OrgPortal` до `Modules/` вашого FreeScout
2. Перейти до **Manage → Modules** і активувати **OrgPortal**
3. Міграції запустяться автоматично при активації

## Структура файлів

```
Modules/OrgPortal/
├── module.json
├── Config/config.php
├── Database/Migrations/
│   ├── ..._create_organizations_table.php
│   └── ..._create_organization_members_table.php
├── Http/
│   ├── routes.php
│   └── Controllers/
│       ├── OrgPortalAdminController.php
│       └── OrgPortalFrontController.php
├── Mail/
│   └── OrgNewTicketMail.php
├── Models/
│   ├── Organization.php
│   └── OrganizationMember.php
├── Providers/
│   └── OrgPortalServiceProvider.php
└── Resources/
    ├── lang/
    │   ├── en/messages.php
    │   └── uk/messages.php
    └── views/
        ├── admin/
        │   ├── index.blade.php
        │   ├── create.blade.php
        │   └── edit.blade.php
        ├── emails/
        │   └── new_ticket.blade.php
        └── portal/
            ├── company_tickets.blade.php
            ├── ticket.blade.php
            ├── settings.blade.php
            └── settings_inline.blade.php
```

## Маршрути

### Адмін (middleware: auth, admin)
| Метод | URL | Дія |
|---|---|---|
| GET | `/orgportal/admin/organizations` | Список організацій |
| GET | `/orgportal/admin/organizations/create` | Форма створення |
| POST | `/orgportal/admin/organizations` | Створити |
| GET | `/orgportal/admin/organizations/{id}/edit` | Форма редагування + учасники |
| PUT | `/orgportal/admin/organizations/{id}` | Оновити |
| DELETE | `/orgportal/admin/organizations/{id}` | Видалити |
| POST | `/orgportal/admin/organizations/{id}/members` | Додати учасника |
| DELETE | `/orgportal/admin/organizations/{id}/members/{mid}` | Видалити учасника |
| GET | `/orgportal/admin/customers/search?q=` | AJAX пошук клієнтів |

### Портал (EUP session)
| Метод | URL | Дія |
|---|---|---|
| GET | `/portal/org/company-tickets` | Тікети організації |
| GET | `/portal/org/tickets/{id}` | Перегляд тікету |
| POST | `/portal/org/tickets/{id}/reply` | Відповідь на тікет |
| GET | `/portal/org/settings` | Налаштування менеджера |
| POST | `/portal/org/settings` | Зберегти налаштування |

## Eventy хуки

| Хук | Тип | Призначення |
|---|---|---|
| `menu.manage.append` | action | Додає пункт "Organizations" до меню Manage |
| `conversation.created_by_customer` | action | Надсилає email-сповіщення менеджерам |
| `eup.tickets.tabs` | filter | Додає таб "Company Tickets" (тільки для менеджерів) |
| `eup.settings.after` | action | Додає секцію налаштувань сповіщень |

## Локалізація

Підтримувані мови: **English** (`en`), **Українська** (`uk`).

Файли перекладу: `Resources/lang/{locale}/messages.php`.  
Для додавання нової мови — скопіювати `en/messages.php` і перекласти значення.

## Ліцензія

MIT
