# OrgPortal REST API

[← Zpět na README](../README.cs.md)

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

*Volitelné — vyžaduje modul [API a Webhooks](https://freescout.net/module/api-webhooks/).*

Ověření — hlavička `X-FreeScout-API-Key` nebo parametr dotazu `api_key`.

> **Interaktivní dokumentace** (ReDoc) je dostupná na stránce **Správa → API a Webhooks** (odkaz "Dokumentace OrgPortal API") nebo přímo na `/orgportal/admin/api-docs`.

## Koncové body

| Metoda | Koncový bod | Popis |
|--------|-------------|-------|
| `GET` | `/api/organizations` | Seznam organizací (stránkování, filtr poštovní schránky) |
| `POST` | `/api/organizations` | Vytvoří organizaci |
| `GET` | `/api/organizations/{id}` | Získá organizaci se členy a jednotkami |
| `PUT` | `/api/organizations/{id}` | Aktualizuje organizaci |
| `DELETE` | `/api/organizations/{id}` | Smaže organizaci |
| `GET` | `/api/organizations/{id}/units` | Seznam strukturálních jednotek |
| `POST` | `/api/organizations/{id}/units` | Vytvoří strukturální jednotku |
| `PUT` | `/api/units/{unitId}` | Přejmenuje jednotku |
| `DELETE` | `/api/units/{unitId}` | Smaže jednotku (členové zrušeni, manažeři degradováni) |
| `GET` | `/api/customers/{id}/organization` | Organizace zákazníka |
| `PUT` | `/api/customers/{id}/organization` | Nastaví/aktualizuje členství zákazníka |
| `DELETE` | `/api/customers/{id}/organization` | Odebere zákazníka z organizace |

## Kódy odpovědí

| Kód | Význam |
|-----|--------|
| `200` | Úspěch nebo no-op (nic se nezměnilo) |
| `201` | Zdroj vytvořen; hlavička `Resource-ID` obsahuje ID |
| `400` | Chyba ověření — detaily v `_embedded.errors` |
| `401` | Neplatný nebo chybějící klíč API |
| `404` | Zdroj nenalezen |
| `409` | Konflikt — zákazník již patří do jiné organizace |

---

## Organizace

### GET /api/organizations

**Parametry dotazu**

| Parametr | Typ | Výchozí | Popis |
|----------|-----|:-------:|-------|
| `page` | integer | `1` | Číslo stránky |
| `pageSize` | integer | `25` | Záznamů na stránku (max 100) |
| `mailboxId` | integer | — | Filtr poštovní schránky: vrací globální organizace + vázané na tuto schránku |

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

**Tělo požadavku**

| Pole | Typ | Povinné | Popis |
|------|-----|:--------:|-------|
| `name` | string | ✅ | Název organizace (max 255 znaků, jedinečný) |
| `mailboxId` | integer\|null | — | ID poštovní schránky nebo `null` / vynechte pro globální organizaci |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(hlavička `Resource-ID: 1`)*
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

Vrátí organizaci s integrovanými **členy** a **jednotkami**.

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

**Pole člena**

| Pole | Typ | Popis |
|------|-----|-------|
| `unitId` | integer\|null | Strukturální jednotka, do které člen patří, nebo `null` pro celou organizaci |
| `role` | string | `member` nebo `manager` |
| `canManageOrg` | boolean | Zda smí být tento manažer povýšit ostatní na globálního manažera z portálu |
| `isActive` | boolean | Aktivní členství; neaktivní členové se nepřiřazují lístkům ani nedostávají oznámení |
| `notifyOnNewTicket` | boolean | Starší příznak oznámení o novém lístku na člena |

---

### PUT /api/organizations/{id}

**Tělo požadavku**

| Pole | Typ | Povinné | Popis |
|------|-----|:--------:|-------|
| `name` | string | ✅ | Nový název organizace (max 255 znaků, jedinečný) |
| `mailboxId` | integer\|null | — | Nová schránka; `null` — učinit globální; vynechte — ponechat nezměněno |

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

Pokud se nic nezmění, zpráva odpovědi je `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(všichni členové budou vymazáni kaskádově)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Strukturální jednotky

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

**Tělo požadavku**

| Pole | Typ | Povinné | Popis |
|------|-----|:--------:|-------|
| `name` | string | ✅ | Název jednotky (jedinečný v rámci organizace) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(hlavička `Resource-ID: 2`)*
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

**Tělo požadavku**

| Pole | Typ | Povinné | Popis |
|------|-----|:--------:|-------|
| `name` | string | ✅ | Nový název jednotky (jedinečný v rámci organizace) |

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

Smaže jednotku. Manažeři omezeni na tuto jednotku jsou degradováni na `member`; všichni členové jednotky jsou zrušeni (jejich `unitId` se stane `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Členství zákazníka

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

Přiřadí zákazníka organizaci nebo aktualizuje jejich členství. **Jedno aktivní členství na zákazníka**: pokud má zákazník již *aktivní* členství v *jiné* organizaci, požadavek je odmítnut s `409 Conflict`. Pro přenos — nejdříve deaktivujte nebo odeberte aktuální členství přes `DELETE`.

**Tělo požadavku**

| Pole | Typ | Povinné | Popis |
|------|-----|:--------:|-------|
| `organizationId` | integer | ✅ | ID organizace |
| `role` | string | — | `"member"` (výchozí) nebo `"manager"` |
| `unitId` | integer\|null | — | Strukturální jednotka (musí patřit cílové organizaci), nebo `null` pro celou organizaci |
| `canManageOrg` | boolean | — | Udělit tomuto manažerovi právo povýšit ostatní na globálního manažera (výchozí `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(nové členství)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(členství aktualizováno)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(zákazník již aktivní v jiné organizaci)*
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
