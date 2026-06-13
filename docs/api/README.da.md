# OrgPortal REST API

[← Tilbage til README](../README.da.md)

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
| `PUT` | `/api/organizations/{id}` | Opdater organisation |
| `DELETE` | `/api/organizations/{id}` | Slet organisation |
| `GET` | `/api/organizations/{id}/units` | Vis strukturelle enheder |
| `POST` | `/api/organizations/{id}/units` | Opret en strukturel enhed |
| `PUT` | `/api/units/{unitId}` | Omdøb en enhed |
| `DELETE` | `/api/units/{unitId}` | Slet en enhed (medlemmer fjernet fra tildeling, enhedsledere degraderet) |
| `GET` | `/api/customers/{id}/organization` | Kundens organisationsmedlemskab |
| `PUT` | `/api/customers/{id}/organization` | Indstil/opdater kundemedlemskab |
| `DELETE` | `/api/customers/{id}/organization` | Fjern kunde fra organisation |

## Svarkoder

| Kode | Betydning |
|------|-----------|
| `200` | Succes eller ingen-op (intet ændret) |
| `201` | Ressource oprettet; `Resource-ID`-header indeholder ID'et |
| `400` | Valideringsfejl — detaljer i `_embedded.errors` |
| `401` | Ugyldig eller manglende API-nøgle |
| `404` | Ressource ikke fundet |
| `409` | Konflikt — kunde har allerede et aktivt medlemskab i en anden organisation |

---

## Organisationer

### GET /api/organizations

**Forespørgselsparametre**

| Parameter | Type | Standard | Beskrivelse |
|-----------|------|:-------:|-------------|
| `page` | heltal | `1` | Sidenummer |
| `pageSize` | heltal | `25` | Poster pr. side (maks 100) |
| `mailboxId` | heltal | — | Postkassefilter: returnerer globale organisationer + dem bundet til denne postkasse |

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

**Requestkrop**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:--------:|-------------|
| `name` | streng | ✅ | Organisationsnavn (maks 255 tegn, unik) |
| `mailboxId` | heltal\|null | — | Postkasse-ID eller `null` / udelad for global organisation |

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

Returnerer organisationen med dens indlejrede **medlemmer** og **enheder**.

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

**Medlemsfelter**

| Felt | Type | Beskrivelse |
|------|------|-------------|
| `unitId` | heltal\|null | Strukturel enhed, medlemmet tilhører, eller `null` for hele organisationen |
| `role` | streng | `medlem` eller `leder` |
| `canManageOrg` | boolean | Om denne leder må forfremme andre til global leder fra portalen |
| `isActive` | boolean | Aktivt medlemskab; inaktive medlemmer modtager ingen billettildelinger eller notifikationer |
| `notifyOnNewTicket` | boolean | Arvet flag for notifikationer ved nye billetter |

---

### PUT /api/organizations/{id}

**Requestkrop**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:--------:|-------------|
| `name` | streng | ✅ | Nyt organisationsnavn (maks 255 tegn, unik) |
| `mailboxId` | heltal\|null | — | Ny postkasse; `null` — gør global; udelad — lad være uændret |

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

Når intet ændres, er responsmeddelelsen `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(alle medlemmer kaskadeslettet)*
```json
{"success": true, "message": "Organization deleted."}
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

**Requestkrop**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:--------:|-------------|
| `name` | streng | ✅ | Enhedsnavn (unik inden for organisationen) |

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

**Requestkrop**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:--------:|-------------|
| `name` | streng | ✅ | Nyt enhedsnavn (unik inden for organisationen) |

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

Sletter enheden. Ledere, der er begrænset til denne enhed, degraderes til `medlem`; alle medlemmer af enheden fjernes fra tildeling (deres `unitId` bliver `null`).

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

Tildeler en kunde til en organisation eller opdaterer deres medlemskab. **Et aktivt medlemskab pr. kunde**: hvis kunden allerede har et *aktivt* medlemskab i *anden* organisation, afvises anmodningen med `409 Konflikt`. For at overføre — først deaktivér eller fjern det aktuelle medlemskab via `DELETE`.

**Requestkrop**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:--------:|-------------|
| `organizationId` | heltal | ✅ | Organisation-ID |
| `role` | streng | — | `"medlem"` (standard) eller `"leder"` |
| `unitId` | heltal\|null | — | Strukturel enhed (skal tilhøre målorganisationen), eller `null` for hele organisationen |
| `canManageOrg` | boolean | — | Giv denne leder ret til at forfremme andre til global leder (standard `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
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

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```
