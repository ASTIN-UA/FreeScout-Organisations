# OrgPortal REST API

[← Tillbaka till README](../README.sv.md)

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
| `PUT` | `/api/organizations/{id}` | Uppdatera organisation |
| `DELETE` | `/api/organizations/{id}` | Ta bort organisation |
| `GET` | `/api/organizations/{id}/units` | Lista strukturella enheter |
| `POST` | `/api/organizations/{id}/units` | Skapa en strukturell enhet |
| `PUT` | `/api/units/{unitId}` | Byt namn på en enhet |
| `DELETE` | `/api/units/{unitId}` | Ta bort en enhet (medlemmar borttagna från enhet, enhetschefer nedgraderade) |
| `GET` | `/api/customers/{id}/organization` | Kundens organisationsmedlemskap |
| `PUT` | `/api/customers/{id}/organization` | Ange/uppdatera kundmedlemskap |
| `DELETE` | `/api/customers/{id}/organization` | Ta bort kund från organisation |

## Svarskoder

| Kod | Betydelse |
|------|---------|
| `200` | Framgång eller ingen-op (ingenting ändrat) |
| `201` | Resurs skapad; `Resource-ID`-rubrik innehåller ID:t |
| `400` | Valideringsfel — detaljer i `_embedded.errors` |
| `401` | Ogiltig eller saknad API-nyckel |
| `404` | Resursen hittades inte |
| `409` | Konflikt — kunden tillhör redan en aktiv medlemskap i en annan organisation |

---

## Organisationer

### GET /api/organizations

**Frågeparametrar**

| Parameter | Typ | Standard | Beskrivning |
|-----------|------|:-------:|-------------|
| `page` | heltal | `1` | Sidnummer |
| `pageSize` | heltal | `25` | Poster per sida (max 100) |
| `mailboxId` | heltal | — | Brevlådefilter: returnerar globala organisationer + de bundna till denna brevlåda |

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

**Begärandekropp**

| Fält | Typ | Obligatorisk | Beskrivning |
|-------|------|:--------:|-------------|
| `name` | sträng | ✅ | Organisationsnamn (max 255 tecken, unikt) |
| `mailboxId` | heltal\|null | — | Brevlåde-ID eller `null` / utelämna för global organisation |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(rubrik `Resource-ID: 1`)*
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

Returnerar organisationen med inbäddade **medlemmar** och **enheter**.

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

**Medlemsfält**

| Fält | Typ | Beskrivning |
|-------|------|-------------|
| `unitId` | heltal\|null | Strukturell enhet som medlemmen tillhör, eller `null` för hela organisationen |
| `role` | sträng | `member` eller `manager` |
| `canManageOrg` | booleskt | Huruvida denna chef kan befordra andra till global chef från portalen |
| `isActive` | booleskt | Aktivt medlemskap; inaktiva medlemmar får ingen billettilldelning eller meddelanden |
| `notifyOnNewTicket` | booleskt | Äldre per-medlem flagga för ny-biljett-meddelande |

---

### PUT /api/organizations/{id}

**Begärandekropp**

| Fält | Typ | Obligatorisk | Beskrivning |
|-------|------|:--------:|-------------|
| `name` | sträng | ✅ | Nytt organisationsnamn (max 255 tecken, unikt) |
| `mailboxId` | heltal\|null | — | Ny brevlåda; `null` — gör global; utelämna — lämna oförändrad |

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

När ingenting ändras är svarsmeddelandet `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(alla medlemmar kaskadbortagna)*
```json
{"success": true, "message": "Organization deleted."}
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

**Begärandekropp**

| Fält | Typ | Obligatorisk | Beskrivning |
|-------|------|:--------:|-------------|
| `name` | sträng | ✅ | Enhetens namn (unikt inom organisationen) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(rubrik `Resource-ID: 2`)*
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

**Begärandekropp**

| Fält | Typ | Obligatorisk | Beskrivning |
|-------|------|:--------:|-------------|
| `name` | sträng | ✅ | Nytt enhetens namn (unikt inom organisationen) |

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

Tar bort enheten. Chefer begränsade till denna enhet nedgraderas till `member`; alla medlemmar av enheten blir tilldelade (deras `unitId` blir `null`).

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

Tilldelar en kund till en organisation eller uppdaterar deras medlemskap. **Ett aktivt medlemskap per kund**: om kunden redan har ett *aktivt* medlemskap i *en annan* organisation avvisas begäran med `409 Konflikt`. För att överföra — ta först bort det nuvarande medlemskapet via `DELETE`.

**Begärandekropp**

| Fält | Typ | Obligatorisk | Beskrivning |
|-------|------|:--------:|-------------|
| `organizationId` | heltal | ✅ | Organisation-ID |
| `role` | sträng | — | `"member"` (standard) eller `"manager"` |
| `unitId` | heltal\|null | — | Strukturell enhet (måste tillhöra målorganisationen), eller `null` för hela organisationen |
| `canManageOrg` | booleskt | — | Ge denna chef rättigheter att befordra andra till global chef (standard `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(nytt medlemskap)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(medlemskap uppdaterat)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(kund redan aktiv i en annan organisation)*
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
