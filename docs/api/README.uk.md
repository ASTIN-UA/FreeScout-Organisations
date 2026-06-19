# OrgPortal REST API

[← Повернутися до README](../../README.md)

🌐 **Language:**
[English](README.md) ·
[Українська](README.uk.md) ·
[Deutsch](README.de.md) ·
[Français](README.fr.md) ·
[Español](README.es.md) ·
[Italiano](README.it.md) ·
[Polski](README.pl.md) ·
[Čeština](README.cs.md) ·
[Slovenčina](README.sk.md) ·
[Nederlands](README.nl.md) ·
[Norsk](README.no.md) ·
[Dansk](README.da.md) ·
[Svenska](README.sv.md) ·
[Suomi](README.fi.md) ·
[Português (BR)](README.pt-BR.md) ·
[Português (PT)](README.pt-PT.md) ·
[Română](README.ro.md) ·
[中文 (简体)](README.zh-CN.md)

---

*Опціонально — потребує модуля [API and Webhooks](https://freescout.net/module/api-webhooks/).*

Аутентифікація — заголовок `X-FreeScout-API-Key` або параметр запиту `api_key`.

> **Інтерактивна документація** (ReDoc) доступна на сторінці **Керування → API & Webhooks** (посилання "OrgPortal API Docs") або безпосередньо за адресою `/orgportal/admin/api-docs`.

## Кінцеві точки

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Список організацій (пагінація, фільтр поштової скриньки) |
| `POST` | `/api/organizations` | Створення організації |
| `GET` | `/api/organizations/{id}` | Отримання організації з членами та одиницями |
| `PUT` | `/api/organizations/{id}` | Оновлення організації (назва, колір, поштова скринька, isActive) |
| `DELETE` | `/api/organizations/{id}` | Видалення організації |
| `GET` | `/api/organizations/{id}/members` | Список членів організації |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Отримання одного члена |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Оновлення члена (роль, одиниця, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Видалення члена |
| `GET` | `/api/organizations/{id}/tags` | Список прив'язок міток (потребує модуля Tags) |
| `PUT` | `/api/organizations/{id}/tags` | Заміна всіх прив'язок міток (потребує модуля Tags) |
| `GET` | `/api/organizations/{id}/units` | Список структурних одиниць |
| `POST` | `/api/organizations/{id}/units` | Створення структурної одиниці |
| `PUT` | `/api/units/{unitId}` | Перейменування одиниці |
| `DELETE` | `/api/units/{unitId}` | Видалення одиниці (члени не призначені, менеджери одиниці понижені) |
| `GET` | `/api/customers/{id}/organization` | Членство клієнта в організації |
| `PUT` | `/api/customers/{id}/organization` | Встановлення/оновлення членства клієнта |
| `DELETE` | `/api/customers/{id}/organization` | Видалення клієнта з організації |

## Коди відповідей

| Code | Meaning |
|------|---------|
| `200` | Успіх |
| `201` | Ресурс створено; заголовок `Resource-ID` містить ID |
| `400` | Помилка валідації — деталі в `_embedded.errors` |
| `401` | Недійсний або відсутній ключ API |
| `404` | Ресурс не знайдено |
| `409` | Конфлікт — клієнт уже має активне членство в іншій організації |
| `503` | Необхідний модуль (наприклад Tags) не активний |

---

## Організації

### GET /api/organizations

**Параметри запиту**

| Parameter | Type | Default | Description |
|-----------|------|:-------:|-------------|
| `page` | integer | `1` | Номер сторінки |
| `pageSize` | integer | `25` | Записів на сторінку (макс 100) |
| `mailboxId` | integer | — | Фільтр поштової скриньки: повертає глобальні організації + ті, що прив'язані до цієї скриньки |

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
        "color": "#4a90d9",
        "isActive": true,
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

### POST /api/organizations

**Тіло запиту**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Назва організації (макс 255 символів, унікальна) |
| `mailboxId` | integer\|null | — | ID поштової скриньки або `null` / опустити для глобальної організації |

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
  "color": null,
  "isActive": true,
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Повертає організацію з вбудованими **членами** та **одиницями**.

**200 OK**
```json
{
  "id": 1,
  "name": "Acme Corp",
  "color": "#4a90d9",
  "isActive": true,
  "mailboxId": null,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00",
  "_embedded": {
    "members": [
      {
        "id": 5,
        "organizationId": 1,
        "unitId": 2,
        "customerId": 42,
        "role": "manager",
        "canManageOrg": false,
        "isActive": true,
        "notifyOnNewTicket": true,
        "createdAt": "2026-06-01T10:05:00+00:00",
        "updatedAt": "2026-06-01T10:05:00+00:00"
      }
    ],
    "units": [
      {
        "id": 2,
        "organizationId": 1,
        "name": "Sales department",
        "createdAt": "2026-06-01T10:02:00+00:00",
        "updatedAt": "2026-06-01T10:02:00+00:00"
      }
    ]
  }
}
```

**Поля члена**

| Field | Type | Description |
|-------|------|-------------|
| `unitId` | integer\|null | Структурна одиниця, до якої належить член, або `null` для всієї організації |
| `role` | string | `member` або `manager` |
| `canManageOrg` | boolean | Чи може цей менеджер просувати інших до глобального менеджера через портал |
| `isActive` | boolean | Активне членство; неактивні члени не отримують призначення або сповіщення про квитки |
| `notifyOnNewTicket` | boolean | Прапорець сповіщення про новий квиток для окремого члена |

---

### PUT /api/organizations/{id}

**Тіло запиту**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Нова назва організації (макс 255 символів, унікальна) |
| `color` | string\|null | — | Колір значка як hex (`"#ff0000"`), `null` для скидання на сірий за замовчуванням; опустити, щоб залишити поточне |
| `mailboxId` | integer\|null | — | Нова поштова скринька; `null` — зробити глобальною; опустити — залишити без змін |
| `isActive` | boolean | — | `false` щоб деактивувати організацію; опустити, щоб залишити поточне |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corporation", "color": "#4a90d9", "isActive": true}'
```

**200 OK**
```json
{"success": true, "message": "Organization updated."}
```

---

### DELETE /api/organizations/{id}

**200 OK** *(усі члени видалені каскадно)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Члени організації

### GET /api/organizations/{id}/members

Повертає список усіх записів членів організації.

**200 OK**
```json
{
  "_embedded": {
    "members": [
      {
        "id": 5,
        "organizationId": 1,
        "unitId": 2,
        "customerId": 42,
        "role": "manager",
        "canManageOrg": false,
        "isActive": true,
        "notifyOnNewTicket": true,
        "createdAt": "2026-06-01T10:05:00+00:00",
        "updatedAt": "2026-06-01T10:05:00+00:00"
      }
    ]
  }
}
```

---

### GET /api/organizations/{id}/members/{memberId}

Повертає один запис члена.

**200 OK**
```json
{
  "id": 5,
  "organizationId": 1,
  "unitId": 2,
  "customerId": 42,
  "role": "manager",
  "canManageOrg": false,
  "isActive": true,
  "notifyOnNewTicket": true,
  "createdAt": "2026-06-01T10:05:00+00:00",
  "updatedAt": "2026-06-01T10:05:00+00:00"
}
```

---

### PUT /api/organizations/{id}/members/{memberId}

Оновіть роль члена, призначення одиниці, прапорець canManageOrg або статус активності. Оновлюються лише поля, присутні в тілі (часткове оновлення).

**Тіло запиту**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `role` | string | — | `"member"` або `"manager"` |
| `unitId` | integer\|null | — | Структурна одиниця (повинна належати цій організації), або `null` для скасування призначення |
| `canManageOrg` | boolean | — | Надайте права глобального менеджера в портальному |
| `isActive` | boolean | — | `false` щоб деактивувати без видалення |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1/members/5" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"role": "manager", "unitId": 2, "canManageOrg": true, "isActive": true}'
```

**200 OK**
```json
{"success": true, "message": "Member updated."}
```

---

### DELETE /api/organizations/{id}/members/{memberId}

Видалення члена з організації.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## Мітки організації

> Потребує активного модуля [Tags](https://freescout.net/module/tags/). Повертає `503` якщо модуль не встановлено.

### GET /api/organizations/{id}/tags

Повертає всі прив'язки міток для організації. Кожна прив'язка опціонально обмежує мітку конкретною одиницею.

**200 OK**
```json
{
  "_embedded": {
    "tags": [
      { "id": 1, "organizationId": 1, "tagId": 5, "unitId": null },
      { "id": 2, "organizationId": 1, "tagId": 8, "unitId": 2 }
    ]
  }
}
```

---

### PUT /api/organizations/{id}/tags

**Повна заміна** — замінює всі існуючі прив'язки міток для цієї організації поданим списком. Надішліть порожній масив `[]` щоб видалити всі прив'язки.

**Тіло запиту** — масив JSON об'єктів прив'язок міток:

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `tagId` | integer | ✅ | ID мітки FreeScout |
| `unitId` | integer\|null | — | Обмежте мітку конкретною одиницею, або опустіть/`null` для всієї організації |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1/tags" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '[{"tagId": 5}, {"tagId": 8, "unitId": 2}]'
```

**200 OK**
```json
{"success": true, "message": "Tags updated."}
```

---

## Структурні одиниці

### GET /api/organizations/{id}/units

**200 OK**
```json
{
  "_embedded": {
    "units": [
      {
        "id": 2,
        "organizationId": 1,
        "name": "Sales department",
        "createdAt": "2026-06-01T10:02:00+00:00",
        "updatedAt": "2026-06-01T10:02:00+00:00"
      }
    ]
  }
}
```

---

### POST /api/organizations/{id}/units

**Тіло запиту**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Назва одиниці (унікальна в межах організації) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(заголовок `Resource-ID: 2`)*
```json
{
  "id": 2,
  "organizationId": 1,
  "name": "Sales department",
  "createdAt": "2026-06-01T10:02:00+00:00",
  "updatedAt": "2026-06-01T10:02:00+00:00"
}
```

---

### PUT /api/units/{unitId}

**Тіло запиту**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Нова назва одиниці (унікальна в межах організації) |

```bash
curl -X PUT "https://your-freescout.com/api/units/2" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales & Marketing"}'
```

**200 OK**
```json
{"success": true, "message": "Unit updated."}
```

---

### DELETE /api/units/{unitId}

Видаляє одиницю. Менеджери обмежені цією одиницею понижуються до `member`; усі члени одиниці не призначуються (їх `unitId` стає `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Членство клієнта

### GET /api/customers/{id}/organization

**200 OK**
```json
{
  "customerId": 42,
  "organizationId": 1,
  "organizationName": "Acme Corp",
  "unitId": 2,
  "unitName": "Sales department",
  "role": "manager",
  "canManageOrg": false,
  "isActive": true,
  "notifyOnNewTicket": true
}
```

---

### PUT /api/customers/{id}/organization

Призначає клієнта до організації або оновлює його членство. **Одне активне членство на клієнта**: якщо клієнт вже має *активне* членство в *іншій* організації, запит відхиляється з `409 Conflict`. Для переведення — спочатку деактивуйте або видаліть поточне членство через `DELETE`.

**Тіло запиту**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | ID організації |
| `role` | string | — | `"member"` (за замовчуванням) або `"manager"` |
| `unitId` | integer\|null | — | Структурна одиниця (повинна належати цільовій організації), або `null` для всієї організації |
| `canManageOrg` | boolean | — | Надайте цьому менеджеру право просувати інших до глобального менеджера (за замовчуванням `false`) |
| `isActive` | boolean | — | `false` щоб створити/оновити як неактивне (за замовчуванням `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(нове членство)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(членство оновлено)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(клієнт вже активний в іншій організації)*
```json
{
  "message": "Customer already has an active membership in another organization.",
  "errorCode": "CUSTOMER_ALREADY_HAS_AN_ACTIVE_MEMBERSHIP_IN_ANOTHER_ORGANIZATION.",
  "_embedded": {
    "errors": [
      {
        "path": "organizationId",
        "message": "Customer is an active member of organization #3. Deactivate or remove it first via DELETE /api/customers/42/organization.",
        "source": "JSON"
      }
    ]
  }
}
```

---

### DELETE /api/customers/{id}/organization

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```
