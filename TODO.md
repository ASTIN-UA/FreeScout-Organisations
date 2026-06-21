# OrgPortal — TODO

---

## Phase 7 Stage 3: рефакторинг читання на snapshot-visibility

Чекбокс в адмін-панелі реалізовано. Залишилось: рефакторинг читання у `OrgPortalFrontController` — 5 методів:

- `companyTickets()` — `whereIn('customer_id', $filteredMemberIds)`
- `viewTicket()` — `whereIn('customer_id', $orgMemberIds)`
- `replyTicket()` — `whereIn('customer_id', $orgMemberIds)`
- `changeAuthor()` — `whereIn('customer_id', $orgMemberIds)`
- `closeTicket()` — `whereIn('customer_id', $orgMemberIds)`

Замінити на COALESCE-логіку:
```php
// Тікет видимий якщо:
// (a) org_id = орга менеджера (snapshot — авторитетний шлях)
// АБО
// (b) org_attributed_at IS NULL І customer_id IN $orgMemberIds (fallback поки backfill не завершений)
```
Гілку (b) прибрати після того як backfill = 0.

---

## Атрибуція через теги

Теги використовуються **лише на етапі backfill** як додатковий сигнал атрибуції.
Після того як `org_id` встановлено — теги більше не беруться до уваги при показі порталу.

**Flow атрибуції:**
1. Є тег що прив'язаний до організації → `org_id` за тегом *(пріоритет)*
2. Немає тегу → автор є членом організації → `org_id` за членством
3. Нічого не збіглось → unattributed

**Що реалізувати:**
- Таблиця `organization_tag` (org_id, tag_id) для маппінгу тег → організація
- UI в адмін edit.blade.php: блок "Прив'язані теги" з мультіселектом — показувати лише якщо модуль Tags активний (`\Module::isActive('tags')`)
- Врахування тегів у backfill-логіці (`OrgPortalAdminController::runBackfill()`)

---

## Опція "Тільки портал організації"

**Проблема:** стандартний EUP "Мої заявки" показує клієнту ВСІ його заявки без урахування `org_id`. Людина що перейшла з Компанії А в Компанію Б бачить старі заявки А.

**Поведінка залежно від опції:**

| | Опція увімкнена | Опція вимкнена |
|---|---|---|
| Звичайний учасник | редирект з EUP → наш портал (лише свої заявки, без фільтру підрозділу, без зміни автора) | стандартний EUP "Мої заявки" (всі заявки без фільтрації по org_id) |
| Менеджер | наш портал повністю (всі заявки компанії, всі фільтри, зміна автора) | наш портал повністю (без змін) |

**Що реалізувати:**
- Чекбокс в адмін System tab: "Тільки портал організації"
- ServiceProvider: хук на EUP tickets route → якщо опція увімкнена і клієнт є активним членом організації → redirect на `orgportal.portal.company-tickets`
- Розширити Company Tickets: доступ для звичайних учасників (лише свої заявки, без фільтру підрозділу, без кнопки зміни автора)

**Залежить від:** модуля Custom Fields (реалізовувати разом)

---

## Інтеграція Custom Fields у вигляді тікету на порталі

Якщо встановлений модуль Custom Fields (User Fields) — виводити значення кастомних полів всередині тікету на порталі EUP.

**Що реалізувати:**
- Перевірка `\Module::isActive('customfields')` у `viewTicket()`
- Завантаження custom fields для conversation
- Відображення у `portal/ticket.blade.php` між заголовком і тредами

---

## Деактивація організацій

Реалізувати **після** Stage 3 (snapshot є передумовою безпечної деактивації).

- Додати `is_active` / `deactivated_at` до `organizations`
- `Organization::scopeActive()` за зразком `OrganizationMember::scopeActive()`
- При деактивації: bulk-деактивація members, `conversations.org_id` не чіпати
- Реактивація → орга повертається, тікети вже на місці

---

## Мова клієнта при відправці email-сповіщень

Поле `locale` в `organization_members` вже є. Але `SendOrgNotification` його не використовує.

**Що залишилось:**
- Перемикач мови на порталі → POST → зберігати в `member->locale` (зараз лише cookie)
- У `SendOrgNotification::handle()` — завантажити `$manager->member->locale`, викликати `App::setLocale()` перед рендером шаблону
- Fallback: `config('app.locale')`

---

## Дрібне

- [ ] `notify_on_new_ticket` колонка в `organization_members` — мертва після впровадження
      системи підписок. Прибрати (міграція + fillable/casts).
- [ ] i18n: ключ `notif_scope_no_unit` додати до 16 локалей (є лише в `en` + `uk`):
      `cs, da, de, es, fi, fr, it, ka, nl, no, pl, pt-BR, pt-PT, ro, sk, sv, zh-CN`
- [ ] Рефакторинг `OrgPortalServiceProvider::getLocaleName()` — видалити хардкод список мов.
      `getAvailablePortalLocales()` вже динамічно тягне мови з EUP; `getLocaleName()` мав би
      покладатися тільки на `Helper::getLocaleData()`, а не мати хардкод для синхронізації.
