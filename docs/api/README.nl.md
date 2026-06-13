# OrgPortal REST API

[← Terug naar README](../README.nl.md)

🌐 **Taal:**
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

*Optioneel — vereist de module [API en Webhooks](https://freescout.net/module/api-webhooks/).*

Authenticatie — `X-FreeScout-API-Key` header of `api_key` queryparameter.

> **Interactieve documentatie** (ReDoc) is beschikbaar op de pagina **Beheren → API & Webhooks** (link "OrgPortal API Docs") of rechtstreeks op `/orgportal/admin/api-docs`.

## Eindpunten

| Methode | Eindpunt | Beschrijving |
|---------|----------|-------------|
| `GET` | `/api/organizations` | Organisaties weergeven (paginering, postvakfilter) |
| `POST` | `/api/organizations` | Organisatie aanmaken |
| `GET` | `/api/organizations/{id}` | Organisatie met leden en eenheden ophalen |
| `PUT` | `/api/organizations/{id}` | Organisatie bijwerken |
| `DELETE` | `/api/organizations/{id}` | Organisatie verwijderen |
| `GET` | `/api/organizations/{id}/units` | Structurele eenheden weergeven |
| `POST` | `/api/organizations/{id}/units` | Structurele eenheid aanmaken |
| `PUT` | `/api/units/{unitId}` | Eenheid hernoemen |
| `DELETE` | `/api/units/{unitId}` | Eenheid verwijderen (leden ontkoppeld, eenheidsbeheerders gedegradeerd) |
| `GET` | `/api/customers/{id}/organization` | Lidmaatschap klantorganisatie |
| `PUT` | `/api/customers/{id}/organization` | Lidmaatschap instellen/bijwerken |
| `DELETE` | `/api/customers/{id}/organization` | Klant uit organisatie verwijderen |

## Antwoordcodes

| Code | Betekenis |
|------|-----------|
| `200` | Succes of geen-operatie (niets is gewijzigd) |
| `201` | Bron aangemaakt; `Resource-ID` header bevat de ID |
| `400` | Validatiefout — details in `_embedded.errors` |
| `401` | Ongeldige of ontbrekende API-sleutel |
| `404` | Bron niet gevonden |
| `409` | Conflict — klant behoort al tot een andere organisatie |

---

## Organisaties

### GET /api/organizations

**Queryparameters**

| Parameter | Type | Standaard | Beschrijving |
|-----------|------|:--------:|-------------|
| `page` | integer | `1` | Paginanummer |
| `pageSize` | integer | `25` | Records per pagina (max 100) |
| `mailboxId` | integer | — | Postvakfilter: retourneert globale organisaties + die gebonden zijn aan dit postvak |

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

**Verzoekbody**

| Veld | Type | Vereist | Beschrijving |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Organisatienaam (max 255 tekens, uniek) |
| `mailboxId` | integer\|null | — | Postvak-ID of `null` / weglaten voor globale organisatie |

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
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Retourneert de organisatie met ingesloten **leden** en **eenheden**.

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

**Lidvelden**

| Veld | Type | Beschrijving |
|------|------|-------------|
| `unitId` | integer\|null | Structurele eenheid waaraan het lid behoort, of `null` voor de gehele organisatie |
| `role` | string | `member` of `manager` |
| `canManageOrg` | boolean | Of deze beheerder anderen tot globale beheerder mag promoveren vanuit het portaal |
| `isActive` | boolean | Actief lidmaatschap; inactieve leden ontvangen geen tickettoewijzingen of meldingen |
| `notifyOnNewTicket` | boolean | Oude per-lid nieuwe-ticket-melding |

---

### PUT /api/organizations/{id}

**Verzoekbody**

| Veld | Type | Vereist | Beschrijving |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Nieuwe organisatienaam (max 255 tekens, uniek) |
| `mailboxId` | integer\|null | — | Nieuw postvak; `null` — maak globaal; weglaten — laat ongewijzigd |

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

Wanneer niets verandert, is het antwoordbericht `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(alle leden worden cascade verwijderd)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Structurele eenheden

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

**Verzoekbody**

| Veld | Type | Vereist | Beschrijving |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Eenheidsnaam (uniek binnen de organisatie) |

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

**Verzoekbody**

| Veld | Type | Vereist | Beschrijving |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Nieuwe eenheidsnaam (uniek binnen de organisatie) |

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

Verwijdert de eenheid. Beheerders beperkt tot deze eenheid worden gedegradeerd tot `member`; alle leden van de eenheid worden ontkoppeld (hun `unitId` wordt `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Klantlidmaatschap

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

Wijst een klant toe aan een organisatie of werkt hun lidmaatschap bij. **Één actief lidmaatschap per klant**: als de klant al een *actief* lidmaatschap in *een andere* organisatie heeft, wordt het verzoek afgewezen met `409 Conflict`. Om over te dragen — deactiveer of verwijder eerst het huidige lidmaatschap via `DELETE`.

**Verzoekbody**

| Veld | Type | Vereist | Beschrijving |
|------|------|:-------:|-------------|
| `organizationId` | integer | ✅ | Organisatie-ID |
| `role` | string | — | `"member"` (standaard) of `"manager"` |
| `unitId` | integer\|null | — | Structurele eenheid (moet behoren tot de doelorganisatie), of `null` voor de gehele organisatie |
| `canManageOrg` | boolean | — | Verleen deze beheerder het recht om anderen tot globale beheerder te promoveren (standaard `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(nieuw lidmaatschap)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(lidmaatschap bijgewerkt)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(klant al actief in andere organisatie)*
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
