# OrgPortal — TODO

## Phase 7 Stage 3: перемкнути читання на snapshot-visibility

**Що зроблено (Stages 0-2):**
- Нові тікети отримують `org_id`/`org_unit_id` при створенні (write-path)
- Backfill крутиться автоматично через cron (кожні 5 хв, по 1000 тікетів)
- При додаванні клієнта в орг — його старі тікети одразу атрибутуються
- Статус: `SELECT COUNT(*) FROM conversations WHERE org_attributed_at IS NULL AND customer_id IS NOT NULL`

**Що залишилось (Stage 3):**
Рефакторинг читання у `OrgPortalFrontController` — 5 методів:
- `companyTickets()` рядок 153 — `whereIn('customer_id', $filteredMemberIds)`
- `viewTicket()` рядок 265 — `whereIn('customer_id', $orgMemberIds)`
- `replyTicket()` рядок 305 — `whereIn('customer_id', $orgMemberIds)`
- `changeAuthor()` рядок 373 — `whereIn('customer_id', $orgMemberIds)`
- `closeTicket()` рядок 425 — `whereIn('customer_id', $orgMemberIds)`

Замінити на `OrgAttribution::visibleConversationsQuery($member)` з COALESCE-логікою:
```php
// Тікет видимий якщо:
// (a) org_id = орга менеджера (snapshot — авторитетний шлях)
// АБО
// (b) org_attributed_at IS NULL І customer_id IN $orgMemberIds (fallback для хвоста)
```
Гілку (b) прибрати після того як backfill = 0.

---

**Ідея: чекбокс в адмін-налаштуваннях замість feature-flag у коді**

Плюси:
- Адмін сам вирішує коли перемикати, без деплою
- Можна показати поруч `pending_count` — "Залишилось X тікетів без атрибуції"
- Зрозуміло для будь-кого хто буде підтримувати систему

Мінуси / застереження:
- **Важливо:** перемикати можна лише коли backfill = 0 (або близько до 0)
  Якщо увімкнути раніше — тікети без `org_attributed_at` тимчасово "зникнуть" з порталу
  (поки fallback-гілка (b) ще активна це безпечно, але без неї — ні)
- Тому або: блокувати чекбокс поки `pending_count > 0`
- Або: завжди тримати COALESCE-логіку (безпечно але трохи повільніше)

**Рекомендований підхід:**
Зробити в адмін-панелі (вкладка Settings або окремий розділ):
- Показати `pending_count` з кнопкою "Запустити backfill зараз"
- Чекбокс "Використовувати snapshot-видимість" — активний лише коли `pending_count = 0`
- Після увімкнення — COALESCE-fallback лишається назавжди (безпечно, гілка (b) просто ніколи не спрацьовує)

---

## Деактивація організацій

Реалізувати **після** Stage 3 (snapshot є передумовою безпечної деактивації).

- Додати `is_active` / `deactivated_at` до `organizations`
- `Organization::scopeActive()` за зразком `OrganizationMember::scopeActive()`
- При деактивації: bulk-деактивація members, `conversations.org_id` не чіпати
- Реактивація → орга повертається, тікети вже на місці

---

## i18n: переклад нових ключів на всі мови

Додані ключі `notif_scope_no_unit` лише в `en` + `uk`. Потрібно додати до решти 16 локалей:
`cs, da, de, es, fi, fr, it, ka, nl, no, pl, pt-BR, pt-PT, ro, sk, sv, zh-CN`

Значення: "No unit" (en) / "Без підрозділу" (uk) — переклад за аналогією з `notif_scope_org`.

---

## Інтеграція EupSwLang як частини модуля

Зараз EupSwLang — окремий модуль. Ідея: вбудувати вибір мови безпосередньо в OrgPortal.

**Scope:**
- Опція в адмін-налаштуваннях: увімкнути/вимкнути перемикач мов на порталі
- Вибір переліку доступних мов (мультіселект)
- Рендер перемикача в шапці EUP-порталу (вже є місце через `layout.body_bottom` або nav inject)
- Залежність від EupSwLang як опціональна (якщо встановлений — делегуємо, якщо ні — своя реалізація)

**Питання для рішення:** повна заміна EupSwLang чи доповнення?

---

## Збереження обраної мови клієнта

Клієнт обирає мову на порталі → зберігати в профіль → використовувати при:
- Відправці email-сповіщень (`SendOrgNotification` — рендерити шаблон мовою клієнта)
- Системних повідомленнях у bell-нотифікаціях

**Контекст:** EupSwLang зберігає мову лише в cookie браузера — серверний job (`SendOrgNotification`)
не має до неї доступу. Отже, потрібне власне поле в БД.

**Технічно:**
- Додати `locale` (varchar 8, nullable) до `organization_members`
- Перемикач мови на порталі → POST → зберігати в `member->locale` + дублювати в cookie (для UI)
- При `SendOrgNotification::handle()` — завантажити `$manager->member->locale`,
  викликати `App::setLocale($locale)` перед рендером шаблону
- Fallback: якщо locale null — використовувати `config('app.locale')`
- Вже є TODO-коментар у міграції seed: `// TODO: render through App::setLocale($managerLocale)`

**Залежить від:** інтеграції EupSwLang або власного перемикача (п. вище).

---

## Дрібне

- [ ] `notify_on_new_ticket` колонка в `organization_members` — мертва після впровадження
      системи підписок. Прибрати (міграція + fillable/casts).
- [ ] `whereIn('role', ['manager', 'unit_manager', 'global_manager'])` в кількох місцях —
      ці рядки ніколи не зберігаються в БД (є лише `'manager'`/`'member'`).
      Замінити на `->where('role', 'manager')`.
