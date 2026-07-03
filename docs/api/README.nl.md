# OrgPortal REST API

[← Terug naar README](../../README.md)

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

*Optioneel — vereist de module [API en Webhooks](https://freescout.net/module/api-webhooks/).*

Authenticatie — `X-FreeScout-API-Key` header of `api_key` queryparameter.

> **Interactieve documentatie** (ReDoc) is beschikbaar op de pagina **Beheren → API & Webhooks** (link "OrgPortal API Docs") of rechtstreeks op `/orgportal/admin/api-docs`.

## Eindpunten

| Methode | Eindpunt | Beschrijving |
|---------|----------|-------------|
| `GET` | `/api/organizations` | Organisaties weergeven (paginering, postvakfilter) |
| `POST` | `/api/organizations` | Organisatie aanmaken |
| `GET` | `/api/organizations/{id}` | Organisatie ophalen met leden en eenheden |
| `PUT` | `/api/organizations/{id}` | Organisatie bijwerken (naam, kleur, postvak, isActive) |
| `DELETE` | `/api/organizations/{id}` | Organisatie verwijderen |
| `GET` | `/api/organizations/{id}/members` | Leden van organisatie weergeven |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Één lid ophalen |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Lid bijwerken (rol, eenheid, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Lid verwijderen |
| `GET` | `/api/organizations/{id}/tags` | Taggenbindingen weergeven (vereist Tags-module) |
| `PUT` | `/api/organizations/{id}/tags` | Alle taggenbindingen vervangen (vereist Tags-module) |
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
| `200` | Succes |
| `201` | Bron aangemaakt; `Resource-ID` header bevat de ID |
| `400` | Validatiefout — details in `_embedded.errors` |
| `401` | Ongeldige of ontbrekende API-sleutel |
| `404` | Bron niet gevonden |
| `409` | Conflict — klant heeft al een actief lidmaatschap in een andere organisatie |
| `422` | Bedrijfsregelovertreding — bijv. het verwijderen van een organisatie die nog leden of kaarten heeft |
| `503` | Vereiste module (bijv. Tags) is niet actief |

---

## Organisaties

### GET /api/organizations

**Queryparameters**

| Parameter | Type | Standaard | Beschrijving |
|-----------|------|:-------:|-------------|
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

**Aanvraagtekst**

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
  "color": null,
  "isActive": true,
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Geeft de organisatie terug met ingebedde **leden** en **eenheden**.

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

**Lidesvelden**

| Veld | Type | Beschrijving |
|------|------|-------------|
| `unitId` | integer\|null | Structurele eenheid waartoe het lid behoort, of `null` voor de hele organisatie |
| `role` | string | `"member"` of `"manager"`. Een **eenheidsbeheerder** is `role: "manager"` met niet-null `unitId`; een **globale beheerder** is `role: "manager"` met `unitId: null`. De string `"unit_manager"` bestaat niet in de API — het doorgeven ervan retourneert 400. |
| `canManageOrg` | boolean | Of deze manager anderen tot globale manager van het portaal kan bevorderen |
| `isActive` | boolean | Actief lidmaatschap; inactieve leden ontvangen geen kaartoewijzingen of meldingen |
| `notifyOnNewTicket` | boolean | Per-lid nieuw-kaartmelding vlag |

---

### PUT /api/organizations/{id}

**Aanvraagtekst**

| Veld | Type | Vereist | Beschrijving |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Nieuwe organisatienaam (max 255 tekens, uniek) |
| `color` | string\|null | — | Badgekleur als hex (`"#ff0000"`), `null` om naar standaard grijs terug te stellen; weglaten om huidige te behouden |
| `mailboxId` | integer\|null | — | Nieuw postvak; `null` — maak globaal; weglaten — laat ongewijzigd |
| `isActive` | boolean | — | `false` om de organisatie te deactiveren; weglaten om huidige te behouden |

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

Geblokkeerd wanneer de organisatie actieve leden of kaarten heeft. Verwijder eerst alle leden en wijs alle kaarten opnieuw toe/verwijder deze.

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

## Organisatieleden

### GET /api/organizations/{id}/members

Geeft een lijst van alle ledenrecords voor de organisatie.

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

Geeft een enkel ledenrecord terug.

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

Werk de rol van een lid, eenheidsopdracht, canManageOrg-vlag of actieve status bij. Alleen velden aanwezig in de tekst worden bijgewerkt (gedeeltelijke update).

**Aanvraagtekst**

| Veld | Type | Vereist | Beschrijving |
|------|------|:-------:|-------------|
| `role` | string | — | `"member"` of `"manager"`. Om een **eenheidsbeheerder** te maken: `role: "manager"` + `unitId: <id>`. Om een **globale beheerder** te maken: `role: "manager"` + `unitId: null`. |
| `unitId` | integer\|null | — | Structurele eenheid (moet tot deze organisatie behoren), of `null` om toe te wijzen |
| `canManageOrg` | boolean | — | Globale managerrechten in het portaal verlenen |
| `isActive` | boolean | — | `false` om te deactiveren zonder te verwijderen |

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

Verwijder een lid uit de organisatie. Geblokkeerd als het lid tickets heeft in deze organisatie — gebruik in plaats daarvan `PUT` met `isActive: false` om te deactiveren en de tickethistorie te behouden.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

**422 Unprocessable Entity** *(member has tickets)*
```json
{"message": "Cannot remove this member: they have tickets in this organization. Deactivate them instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```

---

## Organisatietags

> Vereist dat de module [Tags](https://freescout.net/module/tags/) actief is. Geeft `503` terug als de module niet is geïnstalleerd.

### GET /api/organizations/{id}/tags

Geeft alle taggenbindingen voor de organisatie terug. Elke binding beperkt optioneel een tag tot een specifieke eenheid.

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

**Volledig vervangen** — vervangt alle bestaande taggenbindingen voor deze organisatie door de verstrekte lijst. Verstuur een lege array `[]` om alle bindingen te verwijderen.

**Aanvraagtekst** — een JSON-array van taggenbindingobjecten:

| Veld | Type | Vereist | Beschrijving |
|------|------|:-------:|-------------|
| `tagId` | integer | ✅ | FreeScout tag-ID |
| `unitId` | integer\|null | — | Beperk de tag tot een specifieke eenheid, of weglaten/`null` voor organisatiebreedte |

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

**Aanvraagtekst**

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

**Aanvraagtekst**

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

Verwijdert de eenheid. Managers beperkt tot deze eenheid worden gedegradeerd naar `member`; alle leden van de eenheid worden ontkoppeld (hun `unitId` wordt `null`).

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

Wijs een klant toe aan een organisatie of werk hun lidmaatschap bij. **Één actief lidmaatschap per klant**: als de klant al een *actief* lidmaatschap in *een andere* organisatie heeft, wordt het verzoek afgewezen met `409 Conflict`. Om over te dragen — eerst het huidige lidmaatschap deactiveren of verwijderen via `DELETE`.

**Aanvraagtekst**

| Veld | Type | Vereist | Beschrijving |
|------|------|:-------:|-------------|
| `organizationId` | integer | ✅ | Organisatie-ID |
| `role` | string | — | `"member"` (standaard) of `"manager"` |
| `unitId` | integer\|null | — | Structurele eenheid (moet tot de doelorganisatie behoren), of `null` voor de hele organisatie |
| `canManageOrg` | boolean | — | Geef deze manager het recht om anderen tot globale manager te bevorderen (standaard `false`) |
| `isActive` | boolean | — | `false` om als inactief te maken/bijwerken (standaard `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(nieuw lidmaatschap)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(lidmaatschap bijgewerkt)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(klant al actief in een andere organisatie)*
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

Verwijdert alleen het **actieve** lidmaatschap van de klant. Historische (gedeactiveerde) lidmaatschappen in andere organisaties blijven ongewijzigd bewaard. Geblokkeerd als de klant tickets heeft in deze organisatie — gebruik in plaats daarvan `PUT` met `isActive: false` om te deactiveren en de tickethistorie te behouden.

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```

**422 Unprocessable Entity** *(customer has tickets)*
```json
{"message": "Cannot remove this membership: the customer has tickets in this organization. Deactivate instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```
