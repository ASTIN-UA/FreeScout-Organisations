# OrgPortal REST API

[← Tilbake til README](../../README.md)

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

*Valgfritt — krever [API og Webhooks](https://freescout.net/module/api-webhooks/)-modulen.*

Autentisering — `X-FreeScout-API-Key`-header eller `api_key`-spørringsparameter.

> **Interaktiv dokumentasjon** (ReDoc) er tilgjengelig på siden **Administrer → API & Webhooks** (lenke "OrgPortal API-dokumentasjon") eller direkte på `/orgportal/admin/api-docs`.

## Endepunkter

| Metode | Endepunkt | Beskrivelse |
|--------|----------|-------------|
| `GET` | `/api/organizations` | List organisasjoner (paginering, postkassefilter) |
| `POST` | `/api/organizations` | Opprett en organisasjon |
| `GET` | `/api/organizations/{id}` | Hent organisasjon med medlemmer og enheter |
| `PUT` | `/api/organizations/{id}` | Oppdater organisasjon (navn, farge, postkasse, isActive) |
| `DELETE` | `/api/organizations/{id}` | Slett organisasjon |
| `GET` | `/api/organizations/{id}/members` | List organisasjonsmedlemmer |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Hent ett medlem |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Oppdater medlem (rolle, enhet, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Fjern medlem |
| `GET` | `/api/organizations/{id}/tags` | List taggbindinger (krever Tags-modul) |
| `PUT` | `/api/organizations/{id}/tags` | Erstatt alle taggbindinger (krever Tags-modul) |
| `GET` | `/api/organizations/{id}/units` | List strukturelle enheter |
| `POST` | `/api/organizations/{id}/units` | Opprett en strukturell enhet |
| `PUT` | `/api/units/{unitId}` | Gi nytt navn til en enhet |
| `DELETE` | `/api/units/{unitId}` | Slett en enhet (medlemmer fjernes fra enheten, ledere degraderes) |
| `GET` | `/api/customers/{id}/organization` | Kundens organisasjonsmedlemskap |
| `PUT` | `/api/customers/{id}/organization` | Sett/oppdater kundemedlemskap |
| `DELETE` | `/api/customers/{id}/organization` | Fjern kunde fra organisasjon |

## Svarskoder

| Kode | Betydning |
|------|---------|
| `200` | Suksess |
| `201` | Ressurs opprettet; `Resource-ID`-header inneholder ID-en |
| `400` | Valideringsfeil — detaljer i `_embedded.errors` |
| `401` | Ugyldig eller manglende API-nøkkel |
| `404` | Ressurs ikke funnet |
| `409` | Konflikt — kunde har allerede aktivt medlemskap i en annen organisasjon |
| `422` | Brudd på forretningsregel — f.eks. sletting av en organisasjon som fortsatt har medlemmer eller billetter |
| `503` | Påkrevd modul (f.eks. Tags) er ikke aktiv |

---

## Organisasjoner

### GET /api/organizations

**Spørringsparametrer**

| Parameter | Type | Standard | Beskrivelse |
|-----------|------|:-------:|-------------|
| `page` | integer | `1` | Sidenummer |
| `pageSize` | integer | `25` | Poster per side (maks 100) |
| `mailboxId` | integer | — | Postkassefilter: returnerer globale organisasjoner + de bundet til denne postkassen |

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

**Forespørselstekst**

| Felt | Type | Påkrevd | Beskrivelse |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Organisasjonsnavn (maks 255 tegn, unikt) |
| `mailboxId` | integer\|null | — | Postkasse-ID eller `null` / utelat for global organisasjon |

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

Returnerer organisasjonen med innebygde **medlemmer** og **enheter**.

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

**Medlemsfelt**

| Felt | Type | Beskrivelse |
|------|------|-------------|
| `unitId` | integer\|null | Strukturell enhet medlemmet tilhører, eller `null` for hele organisasjonen |
| `role` | string | `"member"` eller `"manager"`. En **enhetsleder** er `role: "manager"` med ikke-null `unitId`; en **global leder** er `role: "manager"` med `unitId: null`. Strengen `"unit_manager"` finnes ikke i API-en — å sende den returnerer 400. |
| `canManageOrg` | boolean | Om denne lederen kan promotere andre til global leder fra portalen |
| `isActive` | boolean | Aktivt medlemskap; inaktive medlemmer mottar ingen billettildelinger eller varsler |
| `notifyOnNewTicket` | boolean | Per-medlem ny-billett varselsflagg |

---

### PUT /api/organizations/{id}

**Forespørselstekst**

| Felt | Type | Påkrevd | Beskrivelse |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Nytt organisasjonsnavn (maks 255 tegn, unikt) |
| `color` | string\|null | — | Badgefarge som heks (`"#ff0000"`), `null` for å tilbakestille til standard grå; utelat for å beholde nåværende |
| `mailboxId` | integer\|null | — | Ny postkasse; `null` — gjør global; utelat — la være uendret |
| `isActive` | boolean | — | `false` for å deaktivere organisasjonen; utelat for å beholde nåværende |

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

Blokkert når organisasjonen har aktive medlemmer eller billetter. Fjern først alle medlemmer og tildel/slett alle billetter på nytt.

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

## Organisasjonsmedlemmer

### GET /api/organizations/{id}/members

Returnerer en liste over alle medlemsposter for organisasjonen.

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

Returnerer en enkel medlemspost.

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

Oppdater medlemmets rolle, enhettilordning, canManageOrg-flagg eller aktiv status. Bare felt som finnes i teksten oppdateres (delvis oppdatering).

**Forespørselstekst**

| Felt | Type | Påkrevd | Beskrivelse |
|------|------|:-------:|-------------|
| `role` | string | — | `"member"` eller `"manager"`. For å opprette en **enhetsleder**: `role: "manager"` + `unitId: <id>`. For å opprette en **global leder**: `role: "manager"` + `unitId: null`. |
| `unitId` | integer\|null | — | Strukturell enhet (må tilhøre denne organisasjonen), eller `null` for å fjerne tilordning |
| `canManageOrg` | boolean | — | Gi global ledelsesrettigheter i portalen |
| `isActive` | boolean | — | `false` for å deaktivere uten å fjerne |

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

Fjern et medlem fra organisasjonen. Blokkert hvis medlemmet har saker i denne organisasjonen — bruk `PUT` med `isActive: false` i stedet for å deaktivere og bevare sakshistorikken.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

**422 Unprocessable Entity** *(member has tickets)*
```json
{"message": "Cannot remove this member: they have tickets in this organization. Deactivate them instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```

---

## Organisasjonstagger

> Krever at [Tags](https://freescout.net/module/tags/)-modulen er aktiv. Returnerer `503` hvis modulen ikke er installert.

### GET /api/organizations/{id}/tags

Returnerer alle taggbindinger for organisasjonen. Hver binding begrenser valgfritt en tagg til en spesifikk enhet.

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

**Fullstendig erstatning** — erstatter alle eksisterende taggbindinger for denne organisasjonen med den angitte listen. Send en tom array `[]` for å fjerne alle bindinger.

**Forespørselstekst** — en JSON-array av taggbindingsobjekter:

| Felt | Type | Påkrevd | Beskrivelse |
|------|------|:-------:|-------------|
| `tagId` | integer | ✅ | FreeScout tagg-ID |
| `unitId` | integer\|null | — | Begrens taggen til en spesifikk enhet, eller utelat/`null` for organisasjonsomfang |

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

## Strukturelle enheter

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

**Forespørselstekst**

| Felt | Type | Påkrevd | Beskrivelse |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Enhetsnavn (unikt innen organisasjonen) |

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

**Forespørselstekst**

| Felt | Type | Påkrevd | Beskrivelse |
|------|------|:-------:|-------------|
| `name` | string | ✅ | Nytt enhetsnavn (unikt innen organisasjonen) |

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

Sletter enheten. Ledere som er begrenset til denne enheten blir degradert til `member`; alle medlemmer av enheten blir fjernet fra enheten (deres `unitId` blir `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Kundemedlemskap

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

Tilordner en kunde til en organisasjon eller oppdaterer deres medlemskap. **Ett aktivt medlemskap per kunde**: hvis kunden allerede har et *aktivt* medlemskap i *en annen* organisasjon, blir forespørselen avvist med `409 Conflict`. For å overføre — deaktiver eller fjern først gjeldende medlemskap via `DELETE`.

**Forespørselstekst**

| Felt | Type | Påkrevd | Beskrivelse |
|------|------|:-------:|-------------|
| `organizationId` | integer | ✅ | Organisasjons-ID |
| `role` | string | — | `"member"` (standard) eller `"manager"` |
| `unitId` | integer\|null | — | Strukturell enhet (må tilhøre målorganisasjonen), eller `null` for hele organisasjonen |
| `canManageOrg` | boolean | — | Gi denne lederen rettigheten til å promotere andre til global leder (standard `false`) |
| `isActive` | boolean | — | `false` for å opprette/oppdatere som inaktiv (standard `true`) |

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

**200 OK** *(medlemskap oppdatert)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(kunde allerede aktiv i en annen organisasjon)*
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

Fjerner kun kundens **aktive** medlemskap. Historiske (deaktiverte) medlemskap i andre organisasjoner bevares uendret. Blokkert hvis kunden har saker i denne organisasjonen — bruk `PUT` med `isActive: false` i stedet for å deaktivere og bevare sakshistorikken.

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```

**422 Unprocessable Entity** *(customer has tickets)*
```json
{"message": "Cannot remove this membership: the customer has tickets in this organization. Deactivate instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```
