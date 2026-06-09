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
- Прив'язка клієнтів до організацій з вибором ролі: `member` або `manager`
- **Зміна ролі** учасника прямо в таблиці (без видалення та повторного додавання)
- Автодоповнення пошуку клієнтів за іменем або email; вже доданих учасників (з будь-якої організації) виключено з результатів
- Email учасника відображається під іменем у таблиці учасників
- Один клієнт — одна організація (обмеження на рівні БД)
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
- Увімкнення/вимкнення через **Manage → OrgPortal Settings**

### Плашка організації на картках Kanban
- Відображається після лічильника повідомлень на кожній картці
- Клікабельна — веде до пошуку по організації
- Колір відповідає налаштуванню організації
- Фільтр **Організація** вбудований у стандартний dropdown фільтрів Kanban: модальне вікно з чекбоксами, аналогічне фільтру по тегах; стан зберігається між переходами
- Увімкнення/вимкнення через **Manage → OrgPortal Settings**

### Фільтр пошуку по організації
- Розширює стандартний пошук FreeScout фільтром **Organization**
- Показує всі тікети клієнтів вибраної організації

### End-User Portal — доступ менеджерів *(опціонально)*
- Менеджери бачать пункт **Тікети компанії** у навігації порталу
- Сторінка заявок компанії має пошук, фільтри Kanban-статусів та колонку **Автор**
- Клік на ім'я автора фільтрує заявки конкретного учасника в межах організації
- Відповідь на тікети від імені організації
- Зміна автора тікета — менеджер може переприсвоїти заявку іншому учаснику організації
- Сторінка **Org Settings** для налаштування email-сповіщень

### Email-сповіщення *(опціонально)*
- Менеджери з увімкненою опцією отримують email при створенні нового тікета будь-яким членом організації
- Використовує поштовий драйвер відповідного mailbox

### REST API *(опціонально, потребує API and Webhooks)*

Автентифікація — заголовок `X-FreeScout-API-Key` або query-параметр `api_key`.

> **Інтерактивна документація** доступна на сторінці **Manage → API & Webhooks** (посилання «OrgPortal API Docs») або напряму за адресою `/orgportal/admin/api-docs`.

| Метод | Endpoint | Опис |
|-------|----------|-------|
| `GET` | `/api/organizations` | Список організацій (пагінація) |
| `POST` | `/api/organizations` | Створити організацію |
| `GET` | `/api/organizations/{id}` | Отримати організацію з учасниками |
| `PUT` | `/api/organizations/{id}` | Оновити організацію |
| `DELETE` | `/api/organizations/{id}` | Видалити організацію |
| `GET` | `/api/customers/{id}/organization` | Організація клієнта |
| `PUT` | `/api/customers/{id}/organization` | Встановити організацію клієнту |
| `DELETE` | `/api/customers/{id}/organization` | Видалити клієнта з організації |

#### GET /api/organizations

**Query parameters**

| Параметр | Тип | За замовч. | Опис |
|----------|-----|:---:|------|
| `page` | integer | `1` | Номер сторінки |
| `pageSize` | integer | `25` | Кількість записів (макс. 100) |

```bash
curl -X GET "https://your-freescout.com/api/organizations?page=1&pageSize=25" \
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
        "createdAt": "2026-06-01T10:00:00+00:00",
        "updatedAt": "2026-06-01T10:00:00+00:00"
      }
    ]
  },
  "page": {
    "size": 25,
    "totalElements": 1,
    "totalPages": 1,
    "number": 1
  }
}
```

---

#### POST /api/organizations

**Request body**

| Поле | Тип | Обов'язковий | Опис |
|------|-----|:---:|------|
| `name` | string | ✅ | Назва організації (макс. 255 символів, унікальна) |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp"}'
```

**201 Created** *(заголовок `Resource-ID: 1`)*
```json
{
  "id": 1,
  "name": "Acme Corp",
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

**400 Bad Request** *(порожня назва або дублікат)*
```json
{
  "message": "Validation failed",
  "errorCode": "VALIDATION_FAILED",
  "_embedded": {
    "errors": [
      {"path": "name", "message": "An organization with this name already exists.", "source": "JSON"}
    ]
  }
}
```

---

#### GET /api/organizations/{id}

```bash
curl -X GET "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY"
```

**200 OK**
```json
{
  "id": 1,
  "name": "Acme Corp",
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

**404 Not Found**
```json
{"message": "Organization not found.", "errorCode": "ORGANIZATION_NOT_FOUND."}
```

---

#### PUT /api/organizations/{id}

**Request body**

| Поле | Тип | Обов'язковий | Опис |
|------|-----|:---:|------|
| `name` | string | ✅ | Нова назва організації (макс. 255 символів, унікальна) |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corporation"}'
```

**200 OK**
```json
{"success": true, "message": "Organization updated."}
```

---

#### DELETE /api/organizations/{id}

```bash
curl -X DELETE "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY"
```

**200 OK** *(всі учасники каскадно видаляються)*
```json
{"success": true, "message": "Organization deleted."}
```

---

#### GET /api/customers/{id}/organization

```bash
curl -X GET "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY"
```

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

**404 Not Found** *(клієнт не є членом жодної організації)*
```json
{"message": "Customer is not a member of any organization.", "errorCode": "CUSTOMER_IS_NOT_A_MEMBER_OF_ANY_ORGANIZATION."}
```

---

#### PUT /api/customers/{id}/organization

**Request body**

| Поле | Тип | Обов'язковий | Опис |
|------|-----|:---:|------|
| `organizationId` | integer | ✅ | ID організації |
| `role` | string | — | `"member"` (за замовчуванням) або `"manager"` |

Якщо клієнт вже є членом іншої організації — запис оновлюється.

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "member"}'
```

**200 OK**
```json
{"success": true, "message": "Membership saved."}
```

**400 Bad Request**
```json
{
  "message": "Validation failed",
  "errorCode": "VALIDATION_FAILED",
  "_embedded": {
    "errors": [
      {"path": "role", "message": "role must be \"member\" or \"manager\".", "source": "JSON"}
    ]
  }
}
```

---

#### DELETE /api/customers/{id}/organization

```bash
curl -X DELETE "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY"
```

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
| Kanban ≥ 1.0.23 | Опціональний — плашка + фільтр |
| Custom Fields | Сумісний |
| Workflows | Сумісний |
| Tags | Сумісний |

---

## Налаштування

**Manage → OrgPortal Settings**

| Опція | За замовчуванням |
|-------|-----------------|
| Показувати плашку на сторінці тікета | ✅ |
| Показувати плашку на картках Kanban | ✅ |

---

## Переклади

Підтримуються мови: **English** (`en`), **Ukrainian** (`uk`).  
Файли: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

---

## Ліцензія

Proprietary — ASTIN UA.
