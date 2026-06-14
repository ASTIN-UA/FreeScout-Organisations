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

## Дрібне

- [ ] `notify_on_new_ticket` колонка в `organization_members` — мертва після впровадження
      системи підписок. Прибрати (міграція + fillable/casts).
- [ ] `whereIn('role', ['manager', 'unit_manager', 'global_manager'])` в кількох місцях —
      ці рядки ніколи не зберігаються в БД (є лише `'manager'`/`'member'`).
      Замінити на `->where('role', 'manager')`.
