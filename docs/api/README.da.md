# OrgPortal REST API

[← Tilbage til README](../../README.md)

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

*Valgfrit — kræver modulet [API og webhooks](https://freescout.net/module/api-webhooks/).*

Godkendelse — `X-FreeScout-API-Key`-header eller `api_key`-forespørgselsparameter.

> **Interaktiv dokumentation** (ReDoc) er tilgængelig på siden **Administrer → API & webhooks** (link "OrgPortal API-dokumentation") eller direkte på `/orgportal/admin/api-docs`.

## Endpoints

| Metode | Endpoint | Beskrivelse |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Vis organisationer (pagination, postkassefilter) |
| `POST` | `/api/organizations` | Opret en organisation |
| `GET` | `/api/organizations/{id}` | Hent organisation med medlemmer og enheder |
| `PUT` | `/api/organizations/{id}` | Opdater organisation (navn, farve, postkasse, isActive) |
| `DELETE` | `/api/organizations/{id}` | Slet organisation |
| `GET` | `/api/organizations/{id}/members` | Vis organisationens medlemmer |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Hent et medlem |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Opdater medlem (rolle, enhed, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Fjern medlem |
| `GET` | `/api/organizations/{id}/tags` | Vis taggbindinger (kræver Tags-modul) |
| `PUT` | `/api/organizations/{id}/tags` | Erstat alle taggbindinger (kræver Tags-modul) |
| `GET` | `/api/organizations/{id}/units` | Vis strukturelle enheder |
| `POST` | `/api/organizations/{id}/units` | Opret en strukturel enhed |
| `PUT` | `/api/units/{unitId}` | Omdøb en enhed |
| `DELETE` | `/api/units/{unitId}` | Slet en enhed (medlemmer fjernet fra tildeling, enhedsledere degraderet) |
| `GET` | `/api/customers/{id}/organization` | Kundens organisationsmedlemskab |
| `PUT` | `/api/customers/{id}/organization` | Indstil/opdater kundemedlemskab |
| `DELETE` | `/api/customers/{id}/organization` | Fjern kunde fra organisation |

## Svarkoder

| Kode | Betydning |
|------|----------|
| `200` | Succes |
| `201` | Ressource oprettet; `Resource-ID` header indeholder ID'et |
| `400` | Valideringsfejl — detaljer i `_embedded.errors` |
| `401` | Ugyldig eller manglende API-nøgle |
| `404` | Ressource ikke fundet |
| `409` | Konflikt — kunde har allerede aktivt medlemskab i en anden organisation |
| `422` | Forretningsregelkrænkelse — f.eks. sletning af en organisation der stadig har medlemmer eller billetter |
| `503` | Påkrævet modul (f.eks. Tags) er ikke aktiv |

---

## Organisationer

### GET /api/organizations

**Forespørgselsparametre**

| Parameter | Type | Standard | Beskrivelse |
|-----------|------|:-------:|-------------|
| `page` | integer | `1` | Sidenummer |
| `pageSize` | integer | `25` | Poster pr. side (maks 100) |
| `mailboxId` | integer | — | Postkassefilter: returnerer globale organisationer + de bundet til denne postkasse |

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

**Anmodningsorgan**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Organisationsnavn (maks 255 tegn, unikt) |
| `mailboxId` | integer\|null | — | Postkasse-ID eller `null` / udelad for global organisation |

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

Returnerer organisationen med indlejrede **medlemmer** og **enheder**.

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

**Medlemsfelter**

| Felt | Type | Beskrivelse |
|------|------|-------------|
| `unitId` | integer\|null | Strukturel enhed medlemmet tilhører, eller `null` for hele organisationen |
| `role` | string | `"member"` eller `"manager"`. En **enhedsleder** er `role: "manager"` med ikke-null `unitId`; en **global leder** er `role: "manager"` med `unitId: null`. Strengen `"unit_manager"` findes ikke i API'en — at sende den returnerer 400. |
| `canManageOrg` | boolean | Om denne leder kan promovere andre til global leder fra portalen |
| `isActive` | boolean | Aktivt medlemskab; inaktive medlemmer modtager ingen billetopgaver eller meddelelser |
| `notifyOnNewTicket` | boolean | Flag for pr. medlem ny-billet notifikation |

---

### PUT /api/organizations/{id}

**Anmodningsorgan**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Nyt organisationsnavn (maks 255 tegn, unikt) |
| `color` | string\|null | — | Badge-farve som hex (`"#ff0000"`), `null` for at nulstille til standard grå; udelad for at bevare nuværende |
| `mailboxId` | integer\|null | — | Ny postkasse; `null` — gør global; udelad — lad være uændret |
| `isActive` | boolean | — | `false` for at deaktivere organisationen; udelad for at bevare nuværende |

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

Blokeret når organisationen har aktive medlemmer eller billetter. Fjern alle medlemmer og omtildel/slet alle billetter først.

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

## Organisationsmedlemmer

### GET /api/organizations/{id}/members

Returnerer en liste over alle medlemsposter for organisationen.

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

Returnerer en enkelt medlemspost.

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

Opdater medlemmets rolle, enhedstildeling, canManageOrg-flag eller aktiv status. Kun felter til stede i kroppen opdateres (delvis opdatering).

**Anmodningsorgan**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:-------:|-------------|
| `role` | string | — | `"member"` eller `"manager"`. For at oprette en **enhedsleder**: `role: "manager"` + `unitId: <id>`. For at oprette en **global leder**: `role: "manager"` + `unitId: null`. |
| `unitId` | integer\|null | — | Strukturel enhed (skal tilhøre denne organisation), eller `null` for at fjerne tildeling |
| `canManageOrg` | boolean | — | Giv global ledelsesrettigheder i portalen |
| `isActive` | boolean | — | `false` for at deaktivere uden at fjerne |

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

Fjern et medlem fra organisationen. Blokeret, hvis medlemmet har billetter i denne organisation — brug i stedet `PUT` med `isActive: false` for at deaktivere ("fyre") og bevare billethistorikken.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

**422 Unprocessable Entity** *(member has tickets)*
```json
{"message": "Cannot remove this member: they have tickets in this organization. Deactivate them instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```

---

## Organisationsmærker

> Kræver at [Tags](https://freescout.net/module/tags/)-modulet er aktivt. Returnerer `503` hvis modulet ikke er installeret.

### GET /api/organizations/{id}/tags

Returnerer alle taggbindinger for organisationen. Hver binding begrænser eventuelt et tag til en specifik enhed.

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

**Fuldstændig udskiftning** — erstatter alle eksisterende taggbindinger for denne organisation med den leverede liste. Send en tom array `[]` for at fjerne alle bindinger.

**Anmodningsorgan** — en JSON-array af taggbindingsobjekter:

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:-------:|-------------|
| `tagId` | integer | ✅ | FreeScout tag-ID |
| `unitId` | integer\|null | — | Begrænse taget til en specifik enhed, eller udelad/`null` for organisations-omfang |

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

## Strukturelle enheder

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

**Anmodningsorgan**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Enhedsnavn (unikt inden for organisationen) |

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

**Anmodningsorgan**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Nyt enhedsavn (unikt inden for organisationen) |

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

Sletter enheden. Ledere begrænset til denne enhed bliver degraderet til `member`; alle medlemmer af enheden fjernes fra enheden (deres `unitId` bliver `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Kundemedlemskab

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

Tildelinger en kunde til en organisation eller opdaterer deres medlemskab. **Et aktivt medlemskab pr. kunde**: hvis kunden allerede har et *aktivt* medlemskab i *en anden* organisation, afvises anmodningen med `409 Conflict`. For at overføre — deaktiver eller fjern først det aktuelle medlemskab via `DELETE`.

**Anmodningsorgan**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:-------:|-------------|
| `organizationId` | integer | ✅ | Organisations-ID |
| `role` | string | — | `"member"` (standard) eller `"manager"` |
| `unitId` | integer\|null | — | Strukturel enhed (skal tilhøre målorganisationen), eller `null` for hele organisationen |
| `canManageOrg` | boolean | — | Giv denne leder ret til at promovere andre til global leder (standard `false`) |
| `isActive` | boolean | — | `false` for at opret/opdater som inaktiv (standard `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(nyt medlemskab)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(medlemskab opdateret)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(kunde allerede aktiv i anden organisation)*
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

Fjerner kun kundens **aktive** medlemskab. Historiske (deaktiverede) medlemskaber i andre organisationer bevares uændret. Blokeret, hvis kunden har billetter i denne organisation — brug i stedet `PUT` med `isActive: false` for at deaktivere og bevare billethistorikken.

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```

**422 Unprocessable Entity** *(customer has tickets)*
```json
{"message": "Cannot remove this membership: the customer has tickets in this organization. Deactivate instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```
