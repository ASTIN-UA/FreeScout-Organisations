# OrgPortal REST API

[← Назад до README](../README.uk.md)

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

*Опціонально — потребує модуль [API and Webhooks](https://freescout.net/module/api-webhooks/).*

Автентифікація — заголовок `X-FreeScout-API-Key` або параметр запиту `api_key`.

> **Інтерактивна документація** (ReDoc) доступна на сторінці **Manage → API & Webhooks** (посилання "OrgPortal API Docs") або безпосередньо на `/orgportal/admin/api-docs`.

## Ендпоінти

| Метод | Ендпоінт | Опис |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Список організацій (пагінація, фільтр поштової скриньки) |
| `POST` | `/api/organizations` | Створити організацію |
| `GET` | `/api/organizations/{id}` | Отримати організацію з членами та підрозділами |
| `PUT` | `/api/organizations/{id}` | Оновити організацію |
| `DELETE` | `/api/organizations/{id}` | Видалити організацію |
| `GET` | `/api/organizations/{id}/units` | Список структурних підрозділів |
| `POST` | `/api/organizations/{id}/units` | Створити структурний підрозділ |
| `PUT` | `/api/units/{unitId}` | Перейменувати підрозділ |
| `DELETE` | `/api/units/{unitId}` | Видалити підрозділ (члени розпорядження, менеджери послаблені) |
| `GET` | `/api/customers/{id}/organization` | Членство клієнта в організації |
| `PUT` | `/api/customers/{id}/organization` | Встановити/оновити членство клієнта |
| `DELETE` | `/api/customers/{id}/organization` | Видалити клієнта з організації |

## Коди відповідей

| Код | Значення |
|------|---------|
| `200` | Успіх або без операції (ніщо не змінилося) |
| `201` | Ресурс створено; заголовок `Resource-ID` містить ID |
| `400` | Помилка валідації — деталі в `_embedded.errors` |
| `401` | Невалідний або відсутній API ключ |
| `404` | Ресурс не знайдено |
| `409` | Конфлікт — клієнт вже має активне членство в іншій організації |

---

## Організації

### GET /api/organizations

**Параметри запиту**

| Параметр | Тип | За замовчуванням | Опис |
|-----------|------|:-------:|-------------|
| `page` | integer | `1` | Номер сторінки |
| `pageSize` | integer | `25` | Записів на сторінку (макс 100) |
| `mailboxId` | integer | — | Фільтр поштової скриньки: повертає глобальні організації + пов'язані з цією поштовою скринькою |

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

### POST /api/organizations

**Тіло запиту**

| Поле | Тип | Обов'язкове | Опис |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Назва організації (макс 255 символів, унікальна) |
| `mailboxId` | integer\|null | — | ID поштової скриньки або `null` / пропустити для глобальної організації |

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

### GET /api/organizations/{id}

Повертає організацію з вкладеними **членами** та **підрозділами**.

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

| Поле | Тип | Опис |
|-------|------|-------------|
| `unitId` | integer\|null | Структурний підрозділ, до якого належить член, або `null` для цілої організації |
| `role` | string | `member` або `manager` |
| `canManageOrg` | boolean | Чи може цей менеджер просувати інших до глобального менеджера з портала |
| `isActive` | boolean | Активне членство; неактивні члени не отримують призначення квитків або сповіщень |
| `notifyOnNewTicket` | boolean | Застарілий прапор сповіщення про новий квиток для кожного члена |

---

### PUT /api/organizations/{id}

**Тіло запиту**

| Поле | Тип | Обов'язкове | Опис |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Нова назва організації (макс 255 символів, унікальна) |
| `mailboxId` | integer\|null | — | Нова пошткова скринька; `null` — зробити глобальною; пропустити — залишити без змін |

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

Коли нічого не змінюється, повідомлення відповіді буде `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(всі члени видаляються каскадно)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Структурні підрозділи

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

| Поле | Тип | Обов'язкове | Опис |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Назва підрозділу (унікальна в межах організації) |

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

| Поле | Тип | Обов'язкове | Опис |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Нова назва підрозділу (унікальна в межах організації) |

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

Видаляє підрозділ. Менеджери, обмежені цим підрозділом, понижуються до `member`; всі члени підрозділу розпорядження (їх `unitId` стає `null`).

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

Призначає клієнта організації або оновлює його членство. **Одне активне членство на клієнта**: якщо клієнт вже має *активне* членство в *іншій* організації, запит відхиляється з `409 Conflict`. Для передачі — спочатку деактивуйте або видаліть поточне членство через `DELETE`.

**Тіло запиту**

| Поле | Тип | Обов'язкове | Опис |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | ID організації |
| `role` | string | — | `"member"` (за замовчуванням) або `"manager"` |
| `unitId` | integer\|null | — | Структурний підрозділ (повинен належати цільовій організації), або `null` для цілої організації |
| `canManageOrg` | boolean | — | Надати цьому менеджеру право просувати інших до глобального менеджера (за замовчуванням `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(нове членство)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(членство оновлено)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(клієнт уже активний в іншій організації)*
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
