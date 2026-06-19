# OrgPortal REST API

[← Back to README](../../README.md)

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

*Volitelné — vyžaduje modul [API and Webhooks](https://freescout.net/module/api-webhooks/).*

Ověřování — hlavička `X-FreeScout-API-Key` nebo parametr dotazu `api_key`.

> **Interaktivní dokumentace** (ReDoc) je k dispozici na stránce **Spravovat → API a Webhooks** (odkaz "OrgPortal API Docs") nebo přímo na `/orgportal/admin/api-docs`.

## Koncové body

| Metoda | Koncový bod | Popis |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Vypsat organizace (stránkování, filtr poštovní schránky) |
| `POST` | `/api/organizations` | Vytvořit organizaci |
| `GET` | `/api/organizations/{id}` | Získat organizaci s členy a jednotkami |
| `PUT` | `/api/organizations/{id}` | Aktualizovat organizaci (název, barva, poštovní schránka, isActive) |
| `DELETE` | `/api/organizations/{id}` | Smazat organizaci |
| `GET` | `/api/organizations/{id}/members` | Vypsat členy organizace |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Získat jednoho člena |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Aktualizovat člena (role, jednotka, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Odebrat člena |
| `GET` | `/api/organizations/{id}/tags` | Vypsat vazby značek (vyžaduje modul Tags) |
| `PUT` | `/api/organizations/{id}/tags` | Nahradit všechny vazby značek (vyžaduje modul Tags) |
| `GET` | `/api/organizations/{id}/units` | Vypsat strukturální jednotky |
| `POST` | `/api/organizations/{id}/units` | Vytvořit strukturální jednotku |
| `PUT` | `/api/units/{unitId}` | Přejmenovat jednotku |
| `DELETE` | `/api/units/{unitId}` | Smazat jednotku (členové přiřazeni, správcové jednotky degradováni) |
| `GET` | `/api/customers/{id}/organization` | Členství zákazníka v organizaci |
| `PUT` | `/api/customers/{id}/organization` | Nastavit/aktualizovat členství zákazníka |
| `DELETE` | `/api/customers/{id}/organization` | Odebrat zákazníka z organizace |

## Kódy odpovědí

| Kód | Význam |
|------|---------|
| `200` | Úspěch |
| `201` | Prostředek vytvořen; hlavička `Resource-ID` obsahuje ID |
| `400` | Chyba ověření — podrobnosti v `_embedded.errors` |
| `401` | Neplatný nebo chybějící klíč API |
| `404` | Prostředek nenalezen |
| `409` | Konflikt — zákazník již má aktivní členství v jiné organizaci |
| `503` | Požadovaný modul (např. Tags) není aktivní |

---

## Organizace

### GET /api/organizations

**Parametry dotazu**

| Parametr | Typ | Výchozí | Popis |
|-----------|------|:-------:|-------------|
| `page` | celé číslo | `1` | Číslo stránky |
| `pageSize` | celé číslo | `25` | Záznamy na stránku (max 100) |
| `mailboxId` | celé číslo | — | Filtr poštovní schránky: vrátí globální organizace + ty vázané na tuto schránku |

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

**Tělo požadavku**

| Pole | Typ | Požadováno | Popis |
|-------|------|:--------:|-------------|
| `name` | řetězec | ✅ | Název organizace (max 255 znaků, unikátní) |
| `mailboxId` | celé číslo\|null | — | ID poštovní schránky nebo `null` / vynechat pro globální organizaci |

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
  "color": null,
  "isActive": true,
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Vrátí organizaci s vloženými **členy** a **jednotkami**.

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

**Pole člena**

| Pole | Typ | Popis |
|-------|------|-------------|
| `unitId` | celé číslo\|null | Strukturální jednotka, do které člen patří, nebo `null` pro celou organizaci |
| `role` | řetězec | `member` nebo `manager` |
| `canManageOrg` | logická hodnota | Zda může tento správce povyšovat ostatní na globálního správce z portálu |
| `isActive` | logická hodnota | Aktivní členství; neaktivní členové neobdrží přiřazení lístků ani oznámení |
| `notifyOnNewTicket` | logická hodnota | Příznak oznámení o novém lístku na člena |

---

### PUT /api/organizations/{id}

**Tělo požadavku**

| Pole | Typ | Požadováno | Popis |
|-------|------|:--------:|-------------|
| `name` | řetězec | ✅ | Nový název organizace (max 255 znaků, unikátní) |
| `color` | řetězec\|null | — | Barva odznáku jako šestnáctkový (`"#ff0000"`), `null` pro obnovení výchozí šedé; vynechat pro zachování aktuální |
| `mailboxId` | celé číslo\|null | — | Nová poštovní schránka; `null` — učinit globální; vynechat — ponechat beze změny |
| `isActive` | logická hodnota | — | `false` pro deaktivaci organizace; vynechat pro zachování aktuální |

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

**200 OK** *(všichni členové jsou odstraněni kaskádově)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Členové organizace

### GET /api/organizations/{id}/members

Vrátí seznam všech záznamů členů pro organizaci.

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

Vrátí jediný záznam člena.

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

Aktualizujte roli člena, přiřazení jednotky, příznak canManageOrg nebo aktivní status. Aktualizují se pouze pole přítomná v těle (částečná aktualizace).

**Tělo požadavku**

| Pole | Typ | Požadováno | Popis |
|-------|------|:--------:|-------------|
| `role` | řetězec | — | `"member"` nebo `"manager"` |
| `unitId` | celé číslo\|null | — | Strukturální jednotka (musí patřit do této organizace), nebo `null` pro nepřiřazení |
| `canManageOrg` | logická hodnota | — | Udělit práva globálního správce v portálu |
| `isActive` | logická hodnota | — | `false` pro deaktivaci bez odebrání |

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

Odebrat člena z organizace.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## Značky organizace

> Vyžaduje, aby byl modul [Tags](https://freescout.net/module/tags/) aktivní. Vrátí `503` pokud modul není nainstalován.

### GET /api/organizations/{id}/tags

Vrátí všechny vazby značek pro organizaci. Každá vazba volitelně rozsahuje značku na konkrétní jednotku.

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

**Úplná výměna** — nahradí všechny existující vazby značek pro tuto organizaci poskytnutým seznamem. Odešlete prázdné pole `[]` pro odebrání všech vazeb.

**Tělo požadavku** — pole JSON objektů vazeb značek:

| Pole | Typ | Požadováno | Popis |
|-------|------|:--------:|-------------|
| `tagId` | celé číslo | ✅ | ID značky FreeScout |
| `unitId` | celé číslo\|null | — | Rozsah značky na konkrétní jednotku, nebo vynechat/`null` pro celou organizaci |

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

| Pole | Typ | Požadováno | Popis |
|-------|------|:--------:|-------------|
| `name` | řetězec | ✅ | Název jednotky (unikátní v rámci organizace) |

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

| Pole | Typ | Požadováno | Popis |
|-------|------|:--------:|-------------|
| `name` | řetězec | ✅ | Nový název jednotky (unikátní v rámci organizace) |

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

Smazat jednotku. Správcové omezeni na tuto jednotku jsou degradováni na `member`; všichni členové jednotky jsou nepřiřazeni (jejich `unitId` se stane `null`).

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

Přiřadit zákazníka k organizaci nebo aktualizovat jeho členství. **Jedno aktivní členství na zákazníka**: pokud má zákazník již *aktivní* členství v *jiné* organizaci, požadavek je odmítnut s `409 Conflict`. Chcete-li přesunout — nejdříve deaktivujte nebo odeberte aktuální členství prostřednictvím `DELETE`.

**Tělo požadavku**

| Pole | Typ | Požadováno | Popis |
|-------|------|:--------:|-------------|
| `organizationId` | celé číslo | ✅ | ID organizace |
| `role` | řetězec | — | `"member"` (výchozí) nebo `"manager"` |
| `unitId` | celé číslo\|null | — | Strukturální jednotka (musí patřit do cílové organizace), nebo `null` pro celou organizaci |
| `canManageOrg` | logická hodnota | — | Udělit tomuto správci právo povyšovat ostatní na globálního správce (výchozí `false`) |
| `isActive` | logická hodnota | — | `false` pro vytvoření/aktualizaci jako neaktivní (výchozí `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(nové členství)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(členství aktualizováno)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(zákazník je již aktivní v jiné organizaci)*
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
