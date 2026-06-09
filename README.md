# OrgPortal — Organization Portal for FreeScout

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Модуль для FreeScout, що додає поняття **Організації** (компанії/команди) до клієнтів, розширює End-User Portal для менеджерів і відображає плашку організації на тікетах та картках Kanban.

**Мінімальна версія FreeScout:** 1.8.147  
**Залежності:** немає обов'язкових  
**Опціональні:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

## Можливості

### Управління організаціями (адмін)
- **Manage → Organizations** — повний CRUD: створення, редагування, видалення організацій
- **Прив'язка до поштової скриньки** — організація може бути **глобальною** (видима в усіх скриньках) або **прив'язаною до конкретної скриньки**; у списку організацій відображається відповідна мітка
- Прив'язка клієнтів до організацій з вибором ролі: `member` або `manager`
- **Зміна ролі** учасника прямо в таблиці (без видалення та повторного додавання)
- Автодоповнення пошуку клієнтів за іменем або email; вже доданих учасників (з будь-якої організації) виключено з результатів
- Email учасника відображається під іменем у таблиці учасників
- Один клієнт — одна організація (обмеження на рівні БД і API)
- **Колір плашки** — візуальна палітра з 12 кольорів у формі редагування організації; за замовчуванням сірий

### Права доступу користувача
- Нове право **"Дозволено керувати організаціями"** — не-адміни з цим правом отримують доступ до списку, створення та редагування організацій
- Видалення організацій залишається виключно для адмінів

### Картка клієнта
- Поле **Organization** у формі редагування клієнта — вибір організації та ролі
- Кнопка **Заявки організації** — відкриває пошук по всіх тікетах організації

### Плашка організації на тікетах
- Відображається під темою на сторінці тікета та перед назвою у списку розмов
- Клікабельна — відкриває пошук по всіх тікетах цієї організації
- Колір плашки визначається налаштуванням організації (за замовчуванням сірий)
- Увімкнення/вимкнення **окремо для кожної поштової скриньки** через **Mailbox Settings → OrgPortal**; глобальне значення використовується як fallback

### Плашка організації на картках Kanban
- Відображається після лічильника повідомлень на кожній картці
- Клікабельна — веде до пошуку по організації
- Колір відповідає налаштуванню організації
- Фільтр **Організація** вбудований у стандартний dropdown фільтрів Kanban: модальне вікно з чекбоксами, аналогічне фільтру по тегах; стан зберігається між переходами
- Увімкнення/вимкнення **окремо для кожної поштової скриньки** через **Mailbox Settings → OrgPortal**

### Фільтр пошуку по організації
- Розширює стандартний пошук FreeScout фільтром **Organization**
- Показує всі тікети клієнтів вибраної організації

### End-User Portal — доступ менеджерів *(опціонально)*

Менеджер організації отримує розширений доступ через EUP:

- Пункт **Тікети компанії** у навігації порталу
- Таблиця тікетів компанії з колонками:
  - **#** і **Тема** з ellipsis-скороченням і tooltip при наведенні
  - **Відповідальний** — призначений агент
  - **Автор** — клієнт, що відкрив тікет; клік фільтрує тікети за автором у межах організації
  - **Статус** — Active / Pending / Closed / Spam з іконками
  - **Стан** — назва колонки Kanban (з кастомною міткою, якщо налаштована); відображається лише якщо модуль Kanban активний
  - **Оновлено** — дата та час останньої відповіді
- Пошук за темою тікета
- Фільтри по Kanban-статусах (налаштовуються через **Mailbox Settings → OrgPortal**)
- Відповідь на тікет з підтримкою **вкладень** (drag & drop, multi-file)
- **Закриття тікета** — менеджер може закрити заявку; нова відповідь автоматично її відкриває
- Зміна автора тікета — переприсвоєння заявки іншому учаснику організації
- Сторінка **Org Settings** для налаштування email-сповіщень
- Доступ до тікетів **строго обмежений поточною скринькою** (організація скопована до іншої скриньки — portal 403)

### Email-сповіщення *(опціонально)*
- Менеджери з увімкненою опцією отримують email при створенні нового тікета будь-яким членом організації
- Використовує поштовий драйвер відповідного mailbox

### Налаштування поштової скриньки

**Mailbox Settings → OrgPortal** (окремо для кожної скриньки):

| Опція | Опис |
|-------|------|
| Показувати плашку на сторінці тікета | Увімкнення/вимкнення badge у межах цієї скриньки |
| Показувати плашку на картках Kanban | Увімкнення/вимкнення badge у межах цієї скриньки |
| Фільтри статусів тікетів компанії | Вибір колонок Kanban, що відображаються як чекбокси на сторінці тікетів; кастомна мітка для кожного фільтра |

---

### REST API *(опціонально, потребує API and Webhooks)*

Автентифікація — заголовок `X-FreeScout-API-Key` або query-параметр `api_key`.

> **Інтерактивна документація** (ReDoc) доступна на сторінці **Manage → API & Webhooks** (посилання «OrgPortal API Docs») або напряму за адресою `/orgportal/admin/api-docs`.

| Метод | Endpoint | Опис |
|-------|----------|-------|
| `GET` | `/api/organizations` | Список організацій (пагінація, фільтр по скриньці) |
| `POST` | `/api/organizations` | Створити організацію |
| `GET` | `/api/organizations/{id}` | Отримати організацію з учасниками |
| `PUT` | `/api/organizations/{id}` | Оновити організацію |
| `DELETE` | `/api/organizations/{id}` | Видалити організацію |
| `GET` | `/api/customers/{id}/organization` | Організація клієнта |
| `PUT` | `/api/customers/{id}/organization` | Встановити/оновити членство клієнта |
| `DELETE` | `/api/customers/{id}/organization` | Видалити клієнта з організації |

#### Коди відповідей

| Код | Значення |
|-----|----------|
| `200` | Успіх або no-op (нічого не змінилось) |
| `201` | Ресурс створено; заголовок `Resource-ID` містить ID |
| `400` | Помилка валідації — деталі у `_embedded.errors` |
| `401` | Невірний або відсутній API ключ |
| `404` | Ресурс не знайдено |
| `409` | Конфлікт — клієнт вже є членом іншої організації |

---

#### GET /api/organizations

**Query parameters**

| Параметр | Тип | За замовч. | Опис |
|----------|-----|:---:|------|
| `page` | integer | `1` | Номер сторінки |
| `pageSize` | integer | `25` | Кількість записів (макс. 100) |
| `mailboxId` | integer | — | Фільтр по скриньці: повертає глобальні організації + прив'язані до цієї скриньки |

```bash
curl -X GET "https://your-freescout.com/api/organizations?mailboxId=3" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY"
```

**200 OK**
```json
{
  "_embedded": {
    "organizations": [
      {
        "id": 1,
        "name": "Acme Corp",
        "mailboxId": null,
        "createdAt": "2026-06-01T10:00:00+00:00",
        "updatedAt": "2026-06-01T10:00:00+00:00"
      }
    ]
  },
  "page": { "size": 25, "totalElements": 1, "totalPages": 1, "number": 1 }
}
```

---

#### POST /api/organizations

**Request body**

| Поле | Тип | Обов'язковий | Опис |
|------|-----|:---:|------|
| `name` | string | ✅ | Назва організації (макс. 255 символів, унікальна) |
| `mailboxId` | integer\|null | — | ID скриньки або `null` / не передавати для глобальної організації |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(заголовок `Resource-ID: 1`)*
```json
{
  "id": 1,
  "name": "Acme Corp",
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

#### GET /api/organizations/{id}

**200 OK**
```json
{
  "id": 1,
  "name": "Acme Corp",
  "mailboxId": null,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00",
  "_embedded": {
    "members": [
      {
        "id": 5,
        "organizationId": 1,
        "customerId": 42,
        "role": "manager",
        "notifyOnNewTicket": true,
        "createdAt": "2026-06-01T10:05:00+00:00",
        "updatedAt": "2026-06-01T10:05:00+00:00"
      }
    ]
  }
}
```

---

#### PUT /api/organizations/{id}

**Request body**

| Поле | Тип | Обов'язковий | Опис |
|------|-----|:---:|------|
| `name` | string | ✅ | Нова назва організації (макс. 255 символів, унікальна) |
| `mailboxId` | integer\|null | — | Нова скринька; `null` — зробити глобальною; не передавати — залишити без змін |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corporation", "mailboxId": null}'
```

**200 OK**
```json
{"success": true, "message": "Organization updated."}
```

---

#### DELETE /api/organizations/{id}

**200 OK** *(всі учасники каскадно видаляються)*
```json
{"success": true, "message": "Organization deleted."}
```

---

#### GET /api/customers/{id}/organization

**200 OK**
```json
{
  "customerId": 42,
  "organizationId": 1,
  "organizationName": "Acme Corp",
  "role": "manager",
  "notifyOnNewTicket": true
}
```

---

#### PUT /api/customers/{id}/organization

Призначає клієнта до організації або оновлює його роль. **Один клієнт — одна організація**: якщо клієнт вже є членом *іншої* організації, запит відхиляється з `409 Conflict`. Для переведення — спочатку видаліть поточне членство через `DELETE`.

**Request body**

| Поле | Тип | Обов'язковий | Опис |
|------|-----|:---:|------|
| `organizationId` | integer | ✅ | ID організації |
| `role` | string | — | `"member"` (за замовчуванням) або `"manager"` |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager"}'
```

**201 Created** *(нове членство)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(роль оновлено або no-op)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(клієнт уже в іншій організації)*
```json
{
  "message": "Customer already belongs to another organization.",
  "errorCode": "CUSTOMER_ALREADY_BELONGS_TO_ANOTHER_ORGANIZATION.",
  "_embedded": {
    "errors": [
      {
        "path": "organizationId",
        "message": "Customer is already a member of organization #3. Remove the existing membership first via DELETE /api/customers/42/organization.",
        "source": "JSON"
      }
    ]
  }
}
```

---

#### DELETE /api/customers/{id}/organization

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```

---

## Встановлення

1. Скопіюйте папку `OrgPortal` у `Modules/` вашого FreeScout
2. У адмін-панелі: **Manage → Modules → OrgPortal → Activate**
3. Запустіть міграції:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Очистіть кеш:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Сумісність з модулями

| Модуль | Статус |
|--------|--------|
| End-User Portal ≥ 1.0.85 | Опціональний — портальні функції для менеджерів |
| API and Webhooks ≥ 1.0.80 | Опціональний — REST API endpoints |
| Kanban ≥ 1.0.23 | Опціональний — плашка, фільтр, колонка «Стан» у тікетах компанії |
| Custom Fields | Сумісний |
| Workflows | Сумісний |
| Tags | Сумісний |

---

## Налаштування

### Глобальні (**Manage → OrgPortal Settings**)

| Опція | За замовчуванням |
|-------|-----------------|
| Показувати плашку на сторінці тікета | ✅ |
| Показувати плашку на картках Kanban | ✅ |

### Per-mailbox (**Mailbox Settings → OrgPortal**)

Перекривають глобальні значення для конкретної скриньки.

| Опція | Опис |
|-------|------|
| Показувати плашку на сторінці тікета | Badge у списку розмов та на сторінці тікета |
| Показувати плашку на картках Kanban | Badge на картках Kanban |
| Фільтри статусів тікетів компанії | Колонки Kanban як чекбокси на сторінці тікетів компанії; для кожного фільтра задається кастомна мітка, яку бачать користувачі порталу |

---

## Переклади

Підтримуються мови: **English** (`en`), **Ukrainian** (`uk`).  
Файли: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

---

## Ліцензія

Proprietary — ASTIN UA.
