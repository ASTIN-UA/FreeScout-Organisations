# OrgPortal REST API

[← Tillbaka till README](../../README.md)

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

*Valfritt — kräver modulen [API och webhooks](https://freescout.net/module/api-webhooks/).*

Autentisering — `X-FreeScout-API-Key`-rubrik eller `api_key`-frågeparameter.

> **Interaktiv dokumentation** (ReDoc) är tillgänglig på sidan **Hantera → API & webhooks** (länk "OrgPortal API-dokumentation") eller direkt på `/orgportal/admin/api-docs`.

## Slutpunkter

| Metod | Slutpunkt | Beskrivning |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Lista organisationer (sidindelning, brevlådefilter) |
| `POST` | `/api/organizations` | Skapa en organisation |
| `GET` | `/api/organizations/{id}` | Hämta organisation med medlemmar och enheter |
| `PUT` | `/api/organizations/{id}` | Uppdatera organisation (namn, färg, brevlåda, isActive) |
| `DELETE` | `/api/organizations/{id}` | Ta bort organisation |
| `GET` | `/api/organizations/{id}/members` | Lista organisationens medlemmar |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Hämta en medlem |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Uppdatera medlem (roll, enhet, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Ta bort medlem |
| `GET` | `/api/organizations/{id}/tags` | Lista taggbindningar (kräver Tags-modul) |
| `PUT` | `/api/organizations/{id}/tags` | Ersätt alla taggbindningar (kräver Tags-modul) |
| `GET` | `/api/organizations/{id}/units` | Lista strukturella enheter |
| `POST` | `/api/organizations/{id}/units` | Skapa en strukturell enhet |
| `PUT` | `/api/units/{unitId}` | Byt namn på en enhet |
| `DELETE` | `/api/units/{unitId}` | Ta bort en enhet (medlemmar borttagna från enhet, enhetschefer nedgraderade) |
| `GET` | `/api/customers/{id}/organization` | Kundens organisationsmedlemskap |
| `PUT` | `/api/customers/{id}/organization` | Ange/uppdatera kundmedlemskap |
| `DELETE` | `/api/customers/{id}/organization` | Ta bort kund från organisation |

## Svarskoder

| Kod | Betydelse |
|------|----------|
| `200` | Framgång |
| `201` | Resurs skapad; `Resource-ID` rubriken innehåller ID:t |
| `400` | Valideringsfel — detaljer i `_embedded.errors` |
| `401` | Ogiltig eller saknad API-nyckel |
| `404` | Resurs hittades inte |
| `409` | Konflikt — kund har redan aktivt medlemskap i en annan organisation |
| `422` | Brott mot affärsregel — t.ex. borttagning av en organisation som fortfarande har medlemmar eller biljetter |
| `503` | Obligatorisk modul (t.ex. Tags) är inte aktiv |

---

## Organisationer

### GET /api/organizations

**Frågeparametrar**

| Parameter | Typ | Standard | Beskrivning |
|-----------|------|:-------:|-------------|
| `page` | integer | `1` | Sidnummer |
| `pageSize` | integer | `25` | Poster per sida (max 100) |
| `mailboxId` | integer | — | Brevlådefilter: returnerar globala organisationer + de bundna till denna brevlåda |

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

**Förfråningstext**

| Fält | Typ | Obligatorisk | Beskrivning |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Organisationsnamn (max 255 tecken, unikt) |
| `mailboxId` | integer\|null | — | Brevlåde-ID eller `null` / utelämna för global organisation |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(header `Resource-ID: 1`)*
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

Returnerar organisationen med inbäddade **medlemmar** och **enheter**.

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

**Medlemsfält**

| Fält | Typ | Beskrivning |
|------|------|-------------|
| `unitId` | integer\|null | Strukturell enhet medlemmen tillhör, eller `null` för hela organisationen |
| `role` | string | `member` eller `manager` |
| `canManageOrg` | boolean | Om denna chef kan befordra andra till global chef från portalen |
| `isActive` | boolean | Aktivt medlemskap; inaktiva medlemmar får ingen biljettilldelning eller meddelanden |
| `notifyOnNewTicket` | boolean | Per-medlems flagga för ny-biljett notifiering |

---

### PUT /api/organizations/{id}

**Förfråningstext**

| Fält | Typ | Obligatorisk | Beskrivning |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Nytt organisationsnamn (max 255 tecken, unikt) |
| `color` | string\|null | — | Märkfärg som hex (`"#ff0000"`), `null` för att återställa till standard grå; utelämna för att behålla nuvarande |
| `mailboxId` | integer\|null | — | Ny brevlåda; `null` — gör global; utelämna — lämna oförändrad |
| `isActive` | boolean | — | `false` för att inaktivera organisationen; utelämna för att behålla nuvarande |

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

Blockerad när organisationen har aktiva medlemmar eller biljetter. Först ta bort alla medlemmar och tilldela om/ta bort alla biljetter.

**200 OK**
```json
{"success": true, "message": "Organization deleted."}
```

**422 Unprocessable Entity** *(organization has members)*
```json
{"message": "Cannot delete an organization that has members. Remove all members first.", "_embedded": {"errors": [{"members_count": 3}]}}
```

**422 Unprocessable Entity** *(organization has tickets)*
```json
{"message": "Cannot delete an organization that has tickets. Reassign or delete all tickets first.", "_embedded": {"errors": [{"conversations_count": 12}]}}
```

---

## Organisationsmedlemmar

### GET /api/organizations/{id}/members

Returnerar en lista över alla medlemsposter för organisationen.

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

Returnerar en enskild medlemspost.

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

Uppdatera medlemmens roll, enhetstilldelning, canManageOrg-flagga eller aktiv status. Endast fält som finns i texten uppdateras (delvis uppdatering).

**Förfråningstext**

| Fält | Typ | Obligatorisk | Beskrivning |
|------|------|:-------:|-------------|
| `role` | string | — | `"member"` eller `"manager"` |
| `unitId` | integer\|null | — | Strukturell enhet (måste tillhöra denna organisation), eller `null` för att avlägsna |
| `canManageOrg` | boolean | — | Bevilja global chefsbehörighet i portalen |
| `isActive` | boolean | — | `false` för att inaktivera utan att ta bort |

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

Ta bort en medlem från organisationen.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## Organisationstaggar

> Kräver att [Tags](https://freescout.net/module/tags/)-modulen är aktiv. Returnerar `503` om modulen inte är installerad.

### GET /api/organizations/{id}/tags

Returnerar alla taggbindningar för organisationen. Varje bindning begränsar eventuellt en tagg till en specifik enhet.

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

**Fullständig ersättning** — ersätter alla befintliga taggbindningar för denna organisation med den tillhandahållna listan. Skicka en tom array `[]` för att ta bort alla bindningar.

**Förfråningstext** — en JSON-array av taggbindningsobjekt:

| Fält | Typ | Obligatorisk | Beskrivning |
|------|------|:-------:|-------------|
| `tagId` | integer | ✅ | FreeScout tagg-ID |
| `unitId` | integer\|null | — | Begränsa taggen till en specifik enhet, eller utelämna/`null` för organisationsomfattning |

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

## Strukturella enheter

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

**Förfråningstext**

| Fält | Typ | Obligatorisk | Beskrivning |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Enhetsnamn (unikt inom organisationen) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(header `Resource-ID: 2`)*
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

**Förfråningstext**

| Fält | Typ | Obligatorisk | Beskrivning |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Nytt enhetsnamn (unikt inom organisationen) |

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

Tar bort enheten. Chefer begränsade till denna enhet nedgraderas till `member`; alla medlemmar av enheten tas bort från enheten (deras `unitId` blir `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Kundmedlemskap

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

Tilldelar en kund till en organisation eller uppdaterar deras medlemskap. **Ett aktivt medlemskap per kund**: om kunden redan har ett *aktivt* medlemskap i *en annan* organisation, avvisas begäran med `409 Conflict`. För att överföra — inaktivera eller ta bort först det befintliga medlemskapet via `DELETE`.

**Förfråningstext**

| Fält | Typ | Obligatorisk | Beskrivning |
|------|------|:-------:|-------------|
| `organizationId` | integer | ✅ | Organisations-ID |
| `role` | string | — | `"member"` (standard) eller `"manager"` |
| `unitId` | integer\|null | — | Strukturell enhet (måste tillhöra målorganisationen), eller `null` för hela organisationen |
| `canManageOrg` | boolean | — | Ge denna chef rätten att befordra andra till global chef (standard `false`) |
| `isActive` | boolean | — | `false` för att skapa/uppdatera som inaktiv (standard `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(nytt medlemskap)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(medlemskap uppdaterat)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(kund redan aktiv i annan organisation)*
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
