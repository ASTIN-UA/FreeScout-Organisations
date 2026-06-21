Відмінно! Тепер читаю основні моделі:
Тепер потрібні контролери та бізнес-логіка:
Тепер мне потрібна інформація про миграції та подальші деталі:
Тепер мне потрібні роути та бізнес логіка:
Тепер переходимо до більш глибокого аналізу. Потрібна логіка дозволів та обробки тікетів:
Тепер потрібні миграції та параметри бази даних:
Тепер потрібна інформація про сервіс провайдер та хуки:
Тепер знаю достатньо. Створю детальну技術 специфікацію:

---

# ТЕХНІЧНА СПЕЦИФІКАЦІЯ МОДУЛЯ FreeScout-Organisations (feature/org-units)

## 1. Domain Entities (Сутності)

### **Organization** 
- **Призначення**: Контейнер для групування користувачів та тікетів в межах організації
- **Таблиця**: `organizations`
- **Поля**:
  - `id` (int, primary key)
  - `name` (string) - назва організації, unique
  - `color` (string, nullable) - HEX колір для бейджа (#9eaab5 за замовченням)
  - `mailbox_id` (int, nullable, FK) - глобальна організація (NULL) або прив'язана до конкретного mailbox
  - `created_at`, `updated_at` (timestamps)
- **Зв'язки**:
  - `members()` - hasMany OrganizationMember
  - `units()` - hasMany OrganizationUnit
  - `mailbox()` - belongsTo Mailbox
  - `customers()` - belongsToMany Customer (через organization_members)
  - `managers()` - hasMany OrganizationMember (role='manager')
  - `globalManagers()` - hasMany OrganizationMember (role='manager' AND unit_id IS NULL)
- **Скоп**: `visibleInMailbox($mailboxId)` - для mailbox повертає глобальні (mailbox_id IS NULL) + прив'язані до цього mailbox
- **Життєвий цикл**:
  - **Створює**: Admin або користувач з `PERM_MANAGE_ORGANIZATIONS`
  - **Оновлює**: Admin або користувач з `PERM_MANAGE_ORGANIZATIONS`
  - **Видаляє**: Admin тільки (destructive action в AdminController)

### **OrganizationUnit**
- **Призначення**: Структурна підрозділу організації для розділення управління та прав доступу
- **Таблиця**: `organization_units`
- **Поля**:
  - `id` (int, primary key)
  - `organization_id` (int, FK) - посилання на Organization
  - `name` (string) - назва підрозділу, unique per organization
  - `created_at`, `updated_at` (timestamps)
- **Зв'язки**:
  - `organization()` - belongsTo Organization
  - `members()` - hasMany OrganizationMember (via unit_id)
  - `customers()` - belongsToMany Customer (через organization_members з unit_id)
  - `managers()` - hasMany OrganizationMember (role='manager' AND unit_id IS THIS)
- **Життєвий цикл**:
  - **Створює**: Global Manager або Admin
  - **Оновлює**: Global Manager або Admin (rename)
  - **Видаляє**: Global Manager або Admin (members' unit_id → NULL на CASCADE)

### **OrganizationMember**
- **Призначення**: Зв'язна таблиця між Customer, Organization та OrganizationUnit з ролями та станами
- **Таблиця**: `organization_members`
- **Поля**:
  - `id` (int, primary key)
  - `organization_id` (int, FK)
  - `unit_id` (int, nullable, FK) - NULL для global manager, конкретний unit_id для unit manager
  - `customer_id` (int, FK, unique per org) - посилання на Customer (User логін з порталу)
  - `role` (enum: 'member', 'manager') - тип ролі
  - `can_manage_org` (boolean, default false) - legacy, не використовується (замінена unit_id logic)
  - `notify_on_new_ticket` (boolean, default false) - legacy, мігрована в OrgNotificationSubscription
  - `is_active` (boolean, default true) - активний член (true) або деактивований (false)
  - `deactivated_at` (timestamp, nullable) - коли був деактивований
  - `created_at`, `updated_at` (timestamps)
- **Constraints**: 
  - Unique: `(organization_id, customer_id)`
- **Методи**:
  - `isManager()` - role === 'manager'
  - `isMember()` - role === 'member'
  - `isGlobalManager()` - role === 'manager' AND unit_id IS NULL
  - `isUnitManager()` - role === 'manager' AND unit_id IS NOT NULL
  - `scopeActive($query)` - is_active = true
- **Зв'язки**:
  - `organization()` - belongsTo Organization
  - `unit()` - belongsTo OrganizationUnit
  - `customer()` - belongsTo Customer
- **Життєвий цикл**:
  - **Створює**: Admin (в AdminController) або Auto (customer.updated hook)
  - **Оновлює**: Admin, Global Manager, або auto-sync з customer
  - **Видаляє**: Admin (физично), або soft-деактивація через is_active=false
  - **Перехід неактивне**: При деактивації користувача (is_active=false, deactivated_at=now)

### **OrgNotificationSubscription**
- **Призначення**: Управління повідомленнями для кожного члена організації (які события його сповіщають)
- **Таблиця**: `org_notification_subscriptions`
- **Поля**:
  - `id` (int, primary key)
  - `member_id` (int, FK) - посилання на OrganizationMember
  - `event` (enum: 'new_ticket', 'reply_agent', 'reply_customer')
  - `scope_type` (enum: 'org', 'unit') - на рівні якої сутності діє підписка
  - `scope_id` (int, nullable) - NULL для 'org' scope, unit_id для 'unit' scope
  - `created_at`, `updated_at` (timestamps)
- **Constraints**:
  - Unique: `(member_id, event, scope_type, scope_id)` (MySQL-compatible, трактує NULL як 0)
- **Методи**:
  - `scopeId isSubscribed($subscriberMemberId, $event, $authorUnitId)` - перевіряє чи вже підписаний
- **Зв'язки**:
  - `member()` - belongsTo OrganizationMember
- **Логіка**: Член сповіщується якщо має 'org' subscription АБО 'unit' subscription на ту ж unit, що й автор
- **Життєвий цикл**:
  - **Міграція з legacy**: `notify_on_new_ticket=true` → new_ticket, org scope
  - **Управління**: Portal Settings вкладка або Admin Panel

### **OrgPortalNotification**
- **Призначення**: Історія сповіщень членам порталу про нові тікети/відповіді в їхній організації
- **Таблиця**: `org_portal_notifications`
- **Поля**:
  - `id` (int, primary key)
  - `customer_id` (int, FK) - членові якого організації
  - `conversation_id` (int, FK) - посилання на Conversation
  - `thread_id` (int, nullable, FK) - посилання на конкретний Thread (може бути NULL)
  - `type` (string: 'new_ticket', 'new_reply', 'customer_reply')
  - `read_at` (timestamp, nullable) - коли був прочитаний (NULL = непрочитано)
  - `created_at` (timestamp, useCurrent) - коли було створено
- **Методи**:
  - `isRead()` - read_at !== null
  - `unreadFor($customerId)` - unpaginated list, newest 20, sorted DESC
  - `unreadCountFor($customerId)` - count непрочитаних
  - `markReadForConversation($customerId, $conversationId)` - set read_at=now
  - `markAllRead($customerId)` - set read_at=now для всіх
- **Індекси**: 
  - `(customer_id, read_at)` - для quick unread queries
  - `(conversation_id)` - для mark read on view
- **Зв'язки**:
  - `conversation()` - belongsTo Conversation
- **Життєвий цикл**:
  - **Створює**: Event listeners (CustomerReplied, MessageCreated тощо) в OrgPortalServiceProvider
  - **Оновлює**: View ticket, mark read actions
  - **Видаляє**: Cascade on conversation delete

### **OrgPortalThreadView** (Read State per Thread)
- **Призначення**: Відстеження якого члена організації бачив конкретний thread (для display "viewed" чи avatar stacks)
- **Таблиця**: `org_portal_thread_views`
- **Поля**:
  - `id` (int, primary key)
  - `thread_id` (int, FK) - посилання на Thread
  - `conversation_id` (int, FK) - дублиця для швидших запитів (де conversation_id=...)
  - `customer_id` (int, FK) - який член це бачив
  - `viewed_at` (timestamp) - коли це бачив (server-side time на момент view)
- **No timestamps**: `timestamps = false`
- **Методи**:
  - `forThread($threadId)` - all views for thread, eager-loaded з customer + member
  - `markConversationViewed($conversationId, $customerId)` - batch insert усіх thread_ids для conversation які ще не в таблиці
- **Зв'язки**:
  - `customer()` - belongsTo Customer
  - `member()` - hasOne OrganizationMember (customer_id match, active only)
- **Життєвий цикл**:
  - **Створює**: viewTicket action (batch insert via markConversationViewed)
  - **Немає delete/update**: append-only історія

---

## 2. Roles & Contexts (Ролі та контексти доступу)

### **FreeScout Admin**
- **Дані**: 
  - Бачить усі Organization, Unit, Member, Conversation безоблічно
  - Глобальний простір (всі mailboxes)
- **Операції**:
  - CRUD Organization
  - CRUD OrganizationUnit
  - CRUD OrganizationMember (add/remove/change role/toggle active)
  - CRUD Notification Templates (global settings)
  - Impersonate portal link
  - Delete Organization (destructive, admin-only)
- **Обмеження**: Немає (крім контролю самою FreeScout ролі Admin)
- **Тікет у контексті**: Видить якAdmin, + Organization бейдж (якщо включено)

### **FreeScout User з permission `PERM_MANAGE_ORGANIZATIONS` (Non-Admin Manager)**
- **Дані**:
  - Бачить усі Organization, Unit, Member
  - Обмежено mailbox scope (в Inter-mailbox рівні дотримується mailbox_id)
- **Операції**:
  - CRUD Organization (НЕ delete)
  - CRUD Unit
  - CRUD Member
  - View/edit Notification Templates (якщо має `PERM_MANAGE_TEMPLATES`)
  - НЕ может impersonate
  - НЕ можна видалити Organization
- **Обмеження**: 
  - Destructive actions (delete) забороні (authorizeAdmin())
  - Settings/templates — тільки якщо також має PERM_MANAGE_TEMPLATES
- **Тікет у контексті**: Як в Admin (бейдж, організація видна)

### **Global Manager** (в порталі)
- **Визначення**: OrganizationMember з role='manager' AND unit_id IS NULL
- **Дані**: 
  - Бачить усіх members, units, tickets в їхній Organization
  - У порталі: всіх customers цієї Organization
  - Conversations усіх членів Organization
- **Операції**:
  - View company tickets (всіх)
  - View single ticket
  - Reply as organization (customer_id від кого?) - зміна автора
  - Change ticket author (на будь-кого активного в Organization)
  - Create/rename/delete Units
  - Update members (role, notifications, toggle active)
  - View thread views (хто бачив thread)
- **Обмеження**:
  - Не може управляти Organization (тільки члени в Admin)
  - Не може видалити другого Global Manager (?)
- **Тікет у контексті**: 
  - Бачить усі tickets от усіх членів Organization
  - Видить двисигнали (manager_has_unread, author_has_unread)
  - За один тікет может керувати автором

### **Unit Manager** (в порталі)
- **Визначення**: OrganizationMember з role='manager' AND unit_id IS NOT NULL
- **Дані**:
  - Бачить тільки members + tickets цієї ОДНОЇ Unit
  - У порталі: customers тільки цієї Unit
  - Conversations тільки цих customers
- **Операції**:
  - View company tickets (тільки Unit's)
  - View single ticket (тільки Unit's)
  - Reply
  - Change ticket author (тільки на активних членів цієї Unit)
  - НЕ може create/delete Units
  - НЕ може управляти members з інших Units
  - Update members у своїй Unit (role, notifications, toggle active)
- **Обмеження**:
  - Строго Unit scope (Запит `WHERE unit_id = ?` або visibility check)
- **Тікет у контексті**: Видить теж два сигнали, але обмежено Unit

### **Regular Member** (в порталі)
- **Визначення**: OrganizationMember з role='member'
- **Дані**:
  - Бачить тільки свої tickets (customer_id = auth) або опціонально усі (залежить від конфіга)
- **Операції**:
  - View company tickets (себе?) - **ВІД КОДУ ЗАЛЕЖИТЬ**
  - Create/reply на tickets
  - View settings
- **Обмеження**: Не може видати члені, не може управляти
- **Тікет у контексті**: Видить свої tickets

### **End-User (Неавтентифікований / Звичайний Customer без організації)**
- **Дані**: Доступ до FreeScout End-User Portal для своїх tickets
- **Операції**: Create/reply tickets як звичайний користувач
- **Обмеження**: Не доступ до org portal (redirect на login)
- **Тікет у контексті**: Видить як звичайна EUP сторінка

---

## 3. Data Flow (Основні сценарії)

### **Сценарій 1: Створення тікета з FreeScout Admin**
1. Admin / Agent у FreeScout створює нову Conversation
2. Conversation.customer_id = Customer (або create нового)
3. Customer має активне OrganizationMember (role=member або manager)
4. **Синхронізація організації**: 
   - Якщо Organization має mailbox_id → ticket вже в mailbox (OK)
   - Якщо Organization mailbox_id IS NULL → global, видна в усіх mailboxes
5. **Notification**: Event CustomerReplied або MessageCreated
   - Слухається в OrgPortalServiceProvider.registerNotificationHooks()
   - Проходить через OrgNotificationSubscription filter
   - Для кожного manager Organization → create OrgPortalNotification (type='new_ticket')

### **Сценарій 2: Створення тікета з Portal (від Manager)**
1. Global Manager / Unit Manager логується в portal (auth check)
2. Вибирає author (customer) з assignableCustomerIds (активні члени scope)
3. POST `/help/{mailbox_id}/org/ticket/{conversation_id}/reply` 
4. **Валідація**: 
   - Manager vedere member customer IDs
   - conversation_id перевіряється against visibleCustomerIds
5. **Створення**: 
   - Новий Thread (type=CUSTOMER, customer_id=author, created_by_customer_id=manager_customer_id)
   - Conversation.status=ACTIVE, last_reply_at=now, last_reply_from=CUSTOMER
   - Fire `conversation.customer_replied` hook
6. **Notification**:
   - Поповнюються managers Organization (except author)
   - Слідкує OrgNotificationSubscription (event='reply_customer' for members who subscribe)
   - Не будить автора (це автор ж відповідав)

### **Сценарій 3: Відповідь Agent (FreeScout)**
1. Agent у FreeScout reply на ticket
2. Новий Thread (type=MESSAGE, user_id=agent, customer_id=author)
3. Event `CustomerReplied` (помилкова назва? Має бути AgentReplied?)
4. OrgPortalServiceProvider слухає цей event
5. Для кожного manager/member Organization (filter by subscription):
   - Create OrgPortalNotification (type='reply_agent')
   - Set thread_id, conversation_id
6. Thread.opened_at = NULL (не читав на порталі ще)

### **Сценарій 4: Відповідь Customer (від Portal)**
1. Manager reply на ticket (див. Сценарій 2)
2. Це вже customer reply, ходить через OrgPortalFrontController.replyTicket
3. Thread.source_via = CUSTOMER, source_type = WEB
4. Event CustomerReplied fired
5. **Локальна синхронізація**: Conversation.last_reply_from = CUSTOMER
6. Інші managers Organization отримують notification 'reply_customer'

### **Сценарій 5: Зміна статусу тікета**
1. Manager у порталі close ticket via POST `/ticket/{id}/close`
2. Conversation.status = CLOSED
3. Автор (customer) отримує email (стандартний FreeScout flow)
4. **NOT implemented в коді**: Не видим окремої логіки для org-level notifications при status change
5. **Assumption**: Рівня як звичайна FreeScout close

### **Сценарій 6: Зміна автора тікета (Reassignment)**
1. Global/Unit Manager у порталі POST `/ticket/{id}/change-author`
2. Валідація: new_customer_id має бути активний member в scope
3. Conversation.customer_id = new_customer_id
4. **Синхронізація**: Тікет тепер "належит" новому customer
5. **Notification**: ??? (коду не видим explicit hook)
6. **Очікування**: Лишній manager/portal бачить це в списку за новим customer_id

### **Сценарій 7: Прив'язка Customer до Organization (Admin)**
1. Admin редагує Customer (edit page)
2. Selector Organization + Role
3. POST customer.updated hook (OrgPortalServiceProvider.registerCustomerHooks)
4. Логіка:
   - orgId з `orgportal_organization_id` input
   - Ищется existing active membership (is_active=true)
   - Если нет orgId → удалить membership
   - Если orgId не существует → no-op
   - Если membership существует:
     - Update organization_id, role
     - Если меняется org → unit_id=NULL (cascade)
   - Если membership не существует → Create
5. **Результат**: Customer тепер member Organization, автоматично отримує доступ до portal

### **Сценарій 8: View Ticket у Portal (Manager)**
1. Manager POST → `/help/{mailbox_id}/org/ticket/{id}`
2. OrgPortalFrontController.viewTicket
3. Fetch conversation якщо у visibleCustomerIds (perms check)
4. Fetch threads (type=CUSTOMER або MESSAGE, published, хронологічний порядок)
5. **Read Marking**:
   - Call OrgPortalThreadView.markConversationViewed($conversation_id, $manager_customer_id)
   - Batch insert усіх thread_ids цієї conversation у thread_views table (не вже viewed)
   - OrgPortalNotification.markReadForConversation($manager_customer_id, $conversation_id)
   - Set read_at = now для всіх notifications цього conversation
6. **Display**:
   - Показати threads з avatar stacks хто бачив (за допомогою thread_views)
   - Показати коли автор остаточно бачив (thread.opened_at)
7. Return view з threads, manager, conversation

---

## 4. Read/Unread Model

### **Визначення "Прочитано"**
**Два незалежних сигнали:**

1. **manager_has_unread** (OrgPortalNotification):
   - Per-conversation signal
   - Є непрочитана OrgPortalNotification у цього manager для цього conversation
   - Марк як read при viewTicket (markReadForConversation)
   - Показується у company-tickets list як "new" indicator на карточці

2. **author_has_unread** (Thread.opened_at):
   - Per-ticket, потенційно per-thread signal
   - Thread.opened_at = NULL → не читав original author
   - Thread.opened_at = timestamp → час коли author це бачив (не реалізовано в коді)
   - Показується як "unread by customer" indicator

### **Де зберігається**
- **OrgPortalNotification** таблиця: `read_at` column (NULL = unread, timestamp = read)
- **OrgPortalThreadView** таблиця: `viewed_at` (історія хто бачив, не boolean)
- **Thread** таблиця: `opened_at` (FreeScout core, not OrgPortal-specific)

### **Per-Message чи Per-Ticket**
- **Per-ticket** для manager notifications (OrgPortalNotification scope = conversation)
- **Per-thread** для view history (OrgPortalThreadView scope = thread)
- **Per-ticket** для author unread (Thread.opened_at на поточний thread, але логіка в коді тільки показуєLatestAgentThread)

### **Залежить від ролі / організації / підрозділу**
- **ОРГ SCOPE**: 
  - Global Manager видить усіх tickets Organization → uno notification set per manager
  - Unit Manager видить тільки своєї Unit → same, але обмежено Unit members
- **ПІДРОЗДІЛ SCOPE**:
  - OrgPortalThreadView.member() eager-loads active membership → якщо manager переходить в іншу unit, попередня view історія залишається (але неактуальна)
- **РОЛЬ SCOPE**:
  - Regular member чи manager розрізнюються у subscriptions (OrgNotificationSubscription.event + scope_type)

### **Синхронізація Portal ↔ FreeScout**
- **З Portal**: 
  - markReadForConversation (set read_at при view)
  - markAllRead (set read_at для всіх unread)
  - Batch вставка у OrgPortalThreadView при view
- **Від FreeScout**: 
  - Event listeners (CustomerReplied etc) автоматично create OrgPortalNotification
  - Якщо agent reply → type='reply_agent', не read в portal (read_at=NULL)
  - Если customer reply від portal → thread автоматично marked viewed (OrgPortalFrontController.replyTicket → markConversationViewed ?)
  - **NOT FOUND в коді**: Automatic sync of opened_at when author views (вероятно не implemented)

---

## 5. Event / State Mechanism

### **События які існують**

#### **Laravel Events (App\Events namespace)**
- `CustomerReplied($conversation, $thread)` - FreeScout core event, fired у:
  - OrgPortalFrontController.replyTicket (manager reply)
  - FreeScout admin agent reply
  - Customer reply від EUP
- Trigger: Любий customer reply (тип Thread::TYPE_CUSTOMER)

#### **Eventy Hooks (OrgPortal module)**
- `conversation.customer_replied` - Eventy filter hook (not event), замість Laravel event
  - Дозволяє модулям модифікувати conversation перед save
  - OrgPortalServiceProvider може слухати тут

### **Що їх тригерить**

| События | Тригер | Код |
|--------|--------|-----|
| CustomerReplied | POST ticket reply from portal / agent | OrgPortalFrontController.replyTicket, FreeScout core |
| conversation.customer_replied | Після create Thread | OrgPortalFrontController.replyTicket |
| conversation.updated | Conversation.save() | FreeScout core |
| Attachment.created | File upload | FreeScout |
| Thread.created | New thread (any type) | FreeScout |

### **Як оновлюються залежні дані**

1. **Event CustomerReplied** → 
   - OrgPortalServiceProvider.registerNotificationHooks() слухає
   - Запускає job/logic для:
     - Fetch members Organization thread->customer_id
     - Filter by OrgNotificationSubscription (event, scope_type, scope_id)
     - Для кожного: Create OrgPortalNotification(type='reply_customer' або 'reply_agent')

2. **OrgPortalThreadView** →
   - viewTicket() сам call markConversationViewed()
   - Batch insert усіх threads conversation_id у table

3. **OrgPortalNotification** →
   - viewTicket() або API markRead()
   - Set read_at = now

### **Черги, Observers, Синхронні тригери**

- **Нема явних Job/Queue**: Коду не видим dispatch() або Job::dispatch()
- **Нема Observer**: Коду видимо Eloquent Events (created, updated), але нема явних Observer класів
- **Синхронні тригери**: 
  - OrgPortalFrontController прямо call markConversationViewed()
  - Notification creation закладається в OrgPortalServiceProvider хуки (які синхронні per default)
  - Може бути затримання якщо слухачів багато, але не optimize-но

**ASSUMPTION**: All notification creation is synchronous, no queued jobs

---

## 6. Permissions Model

### **Базові Permissions (FreeScout user-level)**

Два permission ID зареєстровані в OrgPortalServiceProvider:
- `PERM_MANAGE_ORGANIZATIONS = 100` - управління Organization, Unit, Member
- `PERM_MANAGE_TEMPLATES = 101` - управління Notification Templates

**Перевірка**: 
```php
OrgPortalServiceProvider::userCanManageOrganizations($user)
// → return $user->isAdmin() || $user->hasPermission(100)

OrgPortalServiceProvider::userCanManageTemplates($user)
// → return $user->isAdmin() || $user->hasPermission(101)
```

### **Organization-Level Permissions**

Визначаються через `organization.mailbox_id`:
- **NULL (Global Organization)** → Видна у ВСІХ mailboxes, усім користувачам (якщо мають base permission)
- **Specific mailbox_id** → Видна ТІЛЬКИ у тому mailbox

### **Unit-Level Permissions**

Визначаються через `organization_member.unit_id`:
- **NULL (Global Manager)** → Видить усіх members + tickets Organization
- **Specific unit_id (Unit Manager)** → Видить тільки цієї Unit

**Perms check в контролерах**:
```php
$member->isGlobalManager() // unit_id is null
$member->isUnitManager()   // unit_id is not null
```

### **Portal Permissions (Organization member role)**

- **role = 'member'** → 
  - Create ticket (за себе?)
  - Reply tickets
  - Вможливо view свої tickets (коду не ясно)
  - НЕ manage structure

- **role = 'manager'** (Global) →
  - View/manage ALL tickets Organization
  - Change author
  - Create/delete Units
  - Manage members
  - Delete members (?)

- **role = 'manager'** (Unit) →
  - View/manage Unit tickets
  - Change author (тільки Unit members)
  - НЕ create/delete Units
  - Manage Unit members

### **Ticket Permissions (who can see / edit)**

| Action | Global Manager | Unit Manager | Member | Notes |
|--------|--------|--------|--------|--------|
| View all tickets | ✅ | ✅ (Unit) | ❌ (own?) | Перевіряється через visibleCustomerIds |
| Reply | ✅ | ✅ (Unit) | ✅ | POST /ticket/{id}/reply |
| Change author | ✅ | ✅ (Unit) | ❌ | Нов customer_id мусить бути у assignableCustomerIds |
| View thread views (avatars) | ✅ | ✅ (Unit) | ? | OrgPortalThreadView.forThread() |
| Mark notification read | ✅ | ✅ (Unit) | ✅ | Automatic на view або API |

### **Стандартна перевірка в контролерах**

**OrgPortalFrontController**:
```php
protected function requireManager($customer, $mailbox): OrganizationMember {
    // 1. Fetch member (role MUST be manager, global_manager чи unit_manager)
    // 2. Check org mailbox scope
    // 3. Return member або abort(403)
}

protected function visibleCustomerIds($member) {
    // Global manager → усіх Organization members
    // Unit manager → тільки Unit members
}

protected function assignableCustomerIds($member) {
    // Same як visible, але ONLY is_active=true
    // Нельзя reassign тікет неактивного члена
}
```

**OrgPortalAdminController**:

```php
protected function authorizeManage() { // Per-action check
protected function authorizeAdmin() { // Destructive actions
protected function authorizeTemplates() { // Template management
```

### **API Permissions (если включен ApiWebhooks модуль)**

- Вимагає API authentication (middleware ApiAuth)
- Те саме CRUD для Organization, Unit, Member
- JSON responses
- Mailbox scope respect (можна create org без mailbox_id, але query обмежена)

---

## 7. System Diagram (текстовий)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                        FreeScout Core System                                │
│                                                                             │
│  ┌──────────────┐     ┌──────────────┐     ┌──────────────┐              │
│  │   Mailbox    │─────│  Customer    │─────│  Conversation            │
│  │              │     │ (User login) │     │  + Thread                │
│  └──────────────┘     └──────────────┘     └──────────────┘              │
│         │                    │                      │                     │
│         │ mailbox_id         │ PK:id                │ customer_id        │
│         │                    │ email, name          │ status, created_at │
│         └────────────────────┼──────────────────────┘                    │
│                              │                                            │
└──────────────────────────────┼────────────────────────────────────────────┘
                               │
                ┌──────────────┴──────────────┐
                │                             │
┌───────────────▼──────────────────┐    ┌────▼───────────────────────────┐
│   OrgPortal Module               │    │   End-User Portal Module       │
│                                  │    │                                │
│  ┌──────────────────────────┐   │    │  Portal login, ticket view     │
│  │    Organization          │   │    └────────────────────────────────┘
│  │ - id (PK)                │   │
│  │ - name                   │   │
│  │ - color                  │   │
│  │ - mailbox_id (nullable)  │   │
│  └───────────┬──────────────┘   │
│              │                   │
│              │ 1:N               │
│              │                   │
│  ┌───────────▼──────────────┐   │
│  │  OrganizationUnit        │   │
│  │ - id (PK)                │   │
│  │ - organization_id (FK)   │   │
│  │ - name                   │   │
│  └───────────┬──────────────┘   │
│              │                   │
│              │ 1:N               │
│              │                   │
│  ┌───────────▼──────────────────────────────┐
│  │  OrganizationMember                      │
│  │  (Join: Customer → Organization + Unit) │
│  │ - id (PK)                                │
│  │ - organization_id (FK)                   │
│  │ - customer_id (FK) → Customer.id         │
│  │ - unit_id (FK, nullable)                 │
│  │ - role (enum: member, manager)           │
│  │ - is_active (boolean)                    │
│  │ - deactivated_at (timestamp, nullable)   │
│  │ - notify_on_new_ticket (boolean)         │
│  └─────────┬──────────────────────────────┬─┘
│            │                              │
│            │ 1:N                          │ 1:N (N:N via pivot)
│            │                              │
│  ┌─────────▼──────────────┐   ┌──────────▼────────────────┐
│  │ OrgNotificationSub     │   │   Other Customer          │
│  │ - member_id (FK)       │   │   (via belongsToMany)     │
│  │ - event (enum)         │   │                            │
│  │ - scope_type (org|unit)│   └────────────────────────────┘
│  │ - scope_id (nullable)  │
│  └────────────────────────┘
│
│  ┌────────────────────────────────────────┐
│  │  OrgPortalNotification                 │
│  │  (Portal-level notification history)   │
│  │ - id (PK)                              │
│  │ - customer_id (FK → Customer)          │
│  │ - conversation_id (FK → Conversation)  │
│  │ - thread_id (FK → Thread, nullable)    │
│  │ - type (new_ticket|new_reply|...)      │
│  │ - read_at (timestamp, nullable)        │
│  │ - created_at (timestamp)               │
│  └────────────────────────────────────────┘
│
│  ┌────────────────────────────────────────┐
│  │  OrgPortalThreadView                   │
│  │  (Read history: who viewed thread)     │
│  │ - thread_id (FK → Thread)              │
│  │ - conversation_id (FK)                 │
│  │ - customer_id (FK)                     │
│  │ - viewed_at (timestamp)                │
│  └────────────────────────────────────────┘
│
└──────────────────────────────────────────────────────────────────────────────┘

                            PERMISSIONS LAYER
┌──────────────────────────────────────────────────────────────────────────────┐
│                                                                              │
│  FreeScout User                 Organization Member Portal Access           │
│  ┌──────────────────┐            ┌────────────────────────────────────┐     │
│  │ Admin            │───────────►│ Sees ALL org + all global mgmt    │     │
│  │ + all permission │            └────────────────────────────────────┘     │
│  └──────────────────┘                                                        │
│  ┌──────────────────┐            ┌────────────────────────────────────┐     │
│  │ Permission:100   │───────────►│ Can create/manage orgs             │     │
│  │ (PERM_MANAGE_ORG)│            │ (no destructive in UI, admin only) │     │
│  └──────────────────┘            └────────────────────────────────────┘     │
│  ┌──────────────────┐            ┌────────────────────────────────────┐     │
│  │ Permission:101   │───────────►│ Can edit notification templates    │     │
│  │ (PERM_MANAGE_TPL)│            └────────────────────────────────────┘     │
│  └──────────────────┘                                                        │
│                                                                              │
│  Role Hierarchy in Portal:                                                   │
│  ┌─────────────────────────────────────────────────────────────────────────┐│
│  │ Global Manager (unit_id IS NULL)  ──────────────────► FULL ACCESS       ││
│  │   ├─ View ALL tickets (Organization members)                            ││
│  │   ├─ Manage ALL units                                                   ││
│  │   ├─ Manage ALL members                                                 ││
│  │   └─ Reassign tickets (any active member)                               ││
│  │                                                                          ││
│  │ Unit Manager (unit_id IS NOT NULL)  ───────────────► UNIT SCOPE ACCESS ││
│  │   ├─ View unit tickets only                                             ││
│  │   ├─ Cannot manage units                                                ││
│  │   ├─ Manage unit members only                                           ││
│  │   └─ Reassign within unit                                               ││
│  │                                                                          ││
│  │ Regular Member (role='member')  ─────────────────► LIMITED ACCESS       ││
│  │   ├─ Create/reply tickets                                               ││
│  │   ├─ View own? (not clear from code)                                    ││
│  │   └─ No management capabilities                                         ││
│  └─────────────────────────────────────────────────────────────────────────┘│
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘


HTTP ROUTING & FLOW
┌──────────────────────────────────────────────────────────────────────────────┐
│                                                                              │
│ FreeScout Admin Interface:                                                  │
│ ┌─────────────────────────────────────────────────────────────────────────┐ │
│ │ /orgportal/admin/organizations              (list)                      │ │
│ │ /orgportal/admin/organizations/create       (form)                      │ │
│ │ POST /orgportal/admin/organizations         (store)                     │ │
│ │ /orgportal/admin/organizations/{id}/edit    (form)                      │ │
│ │ PUT /orgportal/admin/organizations/{id}     (update)                    │ │
│ │ DELETE /orgportal/admin/organizations/{id}  (destroy) [ADMIN-ONLY]      │ │
│ │ ... members, units CRUD ...                                             │ │
│ │ ... impersonate, settings ...                                           │ │
│ └─────────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
│ Portal End-User Interface:  /help/{mailbox_id}/org/                         │
│ ┌─────────────────────────────────────────────────────────────────────────┐ │
│ │ GET  /company-tickets               (list org tickets)                  │ │
│ │ GET  /ticket/{conversation_id}      (view single)                       │ │
│ │ POST /ticket/{conversation_id}/reply                                    │ │
│ │ POST /ticket/{conversation_id}/change-author                            │ │
│ │ POST /ticket/{conversation_id}/close                                    │ │
│ │ GET  /settings                                                          │ │
│ │ POST /settings                      (subscription preferences)          │ │
│ │ POST /units                         (create, global manager only)       │ │
│ │ PUT  /units/{unit_id}               (rename)                            │ │
│ │ DELETE /units/{unit_id}             (delete)                            │ │
│ │ POST /members/{member_id}           (update role/notifications)         │ │
│ │ POST /notifications                 (get unread)                        │ │
│ │ POST /notifications/read-all        (mark all read)                     │ │
│ │ POST /notifications/{conversation_id}/read                              │ │
│ └─────────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
│ API Endpoints (requires ApiWebhooks module & auth):                         │
│ ┌─────────────────────────────────────────────────────────────────────────┐ │
│ │ /api/organizations          (list, create, get, update, delete)         │ │
│ │ /api/organizations/{id}/units                                           │ │
│ │ /units/{unitId}             (get, update, delete)                       │ │
│ │ /api/customers/{id}/organization    (get, set, remove)                  │ │
│ └─────────────────────────────────────────────────────────────────────────┘ │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘

---

## Бізнес-процеси в діаграмі

┌─────────────────────────────────────────────────────────────────────────────┐
│  CUSTOMER REPLY FLOW                                                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  1. Manager в Portal: POST /ticket/{id}/reply                              │
│  2. Валідація: message, author check (visibleCustomerIds)                  │
│  3. Create Thread:                                                          │
│     - type=CUSTOMER, customer_id=author, created_by_customer_id=manager    │
│     - source_type=WEB, state=PUBLISHED                                    │
│  4. Conversation.status=ACTIVE, last_reply_at=now                          │
│  5. Fire event: CustomerReplied($conversation, $thread)                     │
│  6. HOOK: conversation.customer_replied (Eventy filter)                    │
│  7. LISTENER: OrgPortalServiceProvider (registerNotificationHooks)          │
│     ├─ Fetch all managers in Organization                                  │
│     ├─ For each manager:                                                   │
│     │   ├─ Check OrgNotificationSubscription (event='reply_customer')      │
│     │   ├─ Verify scope (org or matching unit)                             │
│     │   └─ Create OrgPortalNotification(type='reply_customer')             │
│     └─ Portal manager sees notification                                    │
│  8. Return: redirect to ticket view + flash "Reply sent"                    │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────┐
│  VIEW TICKET FLOW (read marking)                                            │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  1. Manager GET /ticket/{id}                                                │
│  2. Fetch conversation + threads                                            │
│  3. Mark read:                                                              │
│     ├─ OrgPortalThreadView.markConversationViewed($conv_id, $mgr_id)       │
│     │   └─ Batch INSERT thread_ids → org_portal_thread_views              │
│     └─ OrgPortalNotification.markReadForConversation($mgr_id, $conv_id)    │
│         └─ UPDATE read_at=now WHERE customer_id + conversation_id         │
│  4. Render threads + compute thread.avatar_stack from thread_views        │
│  5. Show author_has_unread via thread.opened_at check                      │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 8. Запроси / SQL Patterns (типові запити)

### **Fetching Organization Members (scope by unit if unit manager)**
```sql
SELECT * FROM organization_members
WHERE organization_id = ? 
  AND (unit_id IS NULL OR unit_id = ?)  -- if unit manager
  AND is_active = true
ORDER BY role DESC, customer_id;
```

### **Get Visible Conversations for Manager**
```sql
SELECT * FROM conversations
WHERE customer_id IN (
    SELECT customer_id FROM organization_members
    WHERE organization_id = ?
      AND (unit_id IS NULL OR unit_id = ?)
      AND is_active = true
)
AND mailbox_id = ?
ORDER BY last_reply_at DESC;
```

### **Check Notification Subscription**
```sql
SELECT 1 FROM org_notification_subscriptions
WHERE member_id = ?
  AND event = ?
  AND (
    scope_type = 'org'
    OR (scope_type = 'unit' AND scope_id = ?)
  )
LIMIT 1;
```

### **Unread Notifications for Manager**
```sql
SELECT COUNT(*) FROM org_portal_notifications
WHERE customer_id = ? AND read_at IS NULL;
```

### **Thread Views (who viewed)**
```sql
SELECT customer_id, viewed_at FROM org_portal_thread_views
WHERE thread_id = ?
ORDER BY viewed_at DESC;
```

---

## 9. Key Business Rules

1. **Global vs Unit Organization Scope**
   - Глобальний manager (unit_id IS NULL) бачить усе Organization
   - Unit manager (unit_id IS NOT NULL) бачить тільки свій Unit
   - Ці обидва типи мають role='manager'

2. **Active vs Inactive Members**
   - is_active=true → бачиться у dropdown author, може отримувати tickets
   - is_active=false → залишає історичні tickets видимими, але не можна reassign до неї

3. **Organization Scope (mailbox vs global)**
   - mailbox_id IS NULL → Organization видна у УСІХ mailboxes
   - mailbox_id = X → Organization видна ТІЛЬКИ у mailbox X
   - Enforcement у OrganizationFrontController.requireManager (abort 403 якщо не match)

4. **Ticket Author = Customer**
   - Conversation.customer_id визначає "от кого" ticket
   - Manager може change author via changeAuthor action
   - При change author переназначается ticket новому customer

5. **Notification Subscriptions**
   - Per-member configuration (OrgNotificationSubscription)
   - Три events: new_ticket, reply_agent, reply_customer
   - Scope: org-wide або unit-specific
   - Член NOT сповіщується якщо не має підписки

6. **Deactivation, не deletion**
   - Members ніколи не видаляються (историческое preserve)
   - Замість delete: is_active=false, deactivated_at=now
   - Деактивовані члени залишаються видимі у existing tickets

7. **Read State Independent Signals**
   - manager_has_unread (OrgPortalNotification.read_at) - per-conversation
   - author_has_unread (Thread.opened_at) - per-thread/ticket
   - Ці ДВА сигнали показуються у company-tickets list

8. **Unit Cascade Behavior**
   - Delete unit → organization_members.unit_id → NULL (set null cascade)
   - Change org → organization_members.unit_id → NULL (migration logic)
   - Move customer to different org → unit_id invalidated

---

## 10. Not Implemented / Assumptions

| Feature | Status | Note |
|---------|--------|------|
| Thread.opened_at автоматично зі side author | NOT IMPL | Code не contains logic до set opened_at; display uses статичний latestAgentThread check |
| Regular member visibility rule (свої tickets vs all) | UNCLEAR | queryBuilding не differentiates; assumpt is managers only in portal |
| Workflow integration | PARTIAL | Fires standard FreeScout events (CustomerReplied), інші moduli мають слухати |
| Email notifications z portal | NOT IMPL | Portal notifications via OrgPortalNotification bell icon, НЕ email |
| Soft-delete / restore members | NOT IMPL | is_active toggle, але нема restore UI |
| Bulk operations (add multiple members) | NOT IMPL | API supports, но admin UI додає поодинці |
| API rate limiting | NOT IMPL | Relies на ApiWebhooks module |
| Audit logging | NOT IMPL | НЕ трекується хто змінив memberships / roles |
| Translation / localization | PARTIAL | langua file orgportal::messages exists, але деяких рядків можуть бути хардкод |
| Dark mode support | UNKNOWN | CSS module.css не перевірений |

---

## Висновок

Модуль FreeScout-Organisations реалізує **multi-tenant organization management** з ієрархією (Organization → Unit → Member) та **portal-based ticket management** для org managers. Ключові особливості:

- ✅ Дві ролі manager (глобальна + unit-scoped)
- ✅ Три-рівневий пермішн (FreeScout user → Organization member → Unit membership)
- ✅ Event-driven notification system (OrgPortalNotification table)
- ✅ Dual read state tracking (per-conversation + per-thread)
- ✅ Mailbox-aware org scoping (global vs dedicated)
- ❌ Thread.opened_at не автоматизується (display-only logic)
- ⚠️ Member deletion вважається soft-deactivation (не full delete)

**Архітектура**: Шаруватість модуля (Models → Controllers → Views) слідує Laravel Modular структурі. PermissionModel базуються на FreeScout core (User permissions) + Eloquent roles у pivot таблиці.
