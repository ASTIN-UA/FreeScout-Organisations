# OrgPortal REST API

[← Tilbake til README](../README.no.md)

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
| `PUT` | `/api/organizations/{id}` | Oppdater organisasjon |
| `DELETE` | `/api/organizations/{id}` | Slett organisasjon |
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
| `200` | Suksess eller ingen-op (ingenting endret) |
| `201` | Ressurs opprettet; `Resource-ID`-header inneholder ID-en |
| `400` | Valideringsfeil — detaljer i `_embedded.errors` |
| `401` | Ugyldig eller manglende API-nøkkel |
| `404` | Ressurs ikke funnet |
| `409` | Konflikt — kunde har allerede aktivt medlemskap i en annen organisasjon |

---

## Organisasjoner

### GET /api/organizations

**Spørringsparametrer**

| Parameter | Type | Standard | Beskrivelse |
|-----------|------|:-------:|-------------|
| `page` | heltall | `1` | Sidenummer |
| `pageSize` | heltall | `25` | Poster per side (maks 100) |
| `mailboxId` | heltall | — | Postkassefilter: returnerer globale organisasjoner + de bundet til denne postkassen |

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

**Forespørselskropp**

| Felt | Type | Obligatorisk | Beskrivelse |
|-------|------|:--------:|-------------|
| `name` | streng | ✅ | Organisasjonsnavn (maks 255 tegn, unikt) |
| `mailboxId` | heltall\|null | — | Postkasse-ID eller `null` / utelat for global organisasjon |

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

Returnerer organisasjonen med innebygde **medlemmer** og **enheter**.

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

**Medlemsfelt**

| Felt | Type | Beskrivelse |
|-------|------|-------------|
| `unitId` | heltall\|null | Strukturell enhet medlemmet tilhører, eller `null` for hele organisasjonen |
| `role` | streng | `member` eller `manager` |
| `canManageOrg` | boolean | Hvorvidt denne lederen får lov til å promotere andre til global leder fra portalen |
| `isActive` | boolean | Aktivt medlemskap; inaktive medlemmer mottar ingen billettildelinger eller varsler |
| `notifyOnNewTicket` | boolean | Eldre per-medlem-flagg for varsling om nye billetter |

---

### PUT /api/organizations/{id}

**Forespørselskropp**

| Felt | Type | Obligatorisk | Beskrivelse |
|-------|------|:--------:|-------------|
| `name` | streng | ✅ | Nytt organisasjonsnavn (maks 255 tegn, unikt) |
| `mailboxId` | heltall\|null | — | Ny postkasse; `null` — gjør global; utelat — la være uendret |

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

Når ingenting endrer seg, er svarsmeldingen `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(alle medlemmer kaskadeslettes)*
```json
{"success": true, "message": "Organization deleted."}
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

**Forespørselskropp**

| Felt | Type | Obligatorisk | Beskrivelse |
|-------|------|:--------:|-------------|
| `name` | streng | ✅ | Enhetsnavn (unikt innenfor organisasjonen) |

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

**Forespørselskropp**

| Felt | Type | Obligatorisk | Beskrivelse |
|-------|------|:--------:|-------------|
| `name` | streng | ✅ | Nytt enhetsnavn (unikt innenfor organisasjonen) |

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

Sletter enheten. Ledere som er knyttet til denne enheten degraderes til `member`; alle medlemmer av enheten fjernes (deres `unitId` blir `null`).

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

Tildeler en kunde til en organisasjon eller oppdaterer deres medlemskap. **Én aktivt medlemskap per kunde**: hvis kunden allerede har et *aktivt* medlemskap i *en annen* organisasjon, avvises forespørselen med `409 Konflikt`. For å overføre — deaktivér eller fjern først gjeldende medlemskap via `DELETE`.

**Forespørselskropp**

| Felt | Type | Obligatorisk | Beskrivelse |
|-------|------|:--------:|-------------|
| `organizationId` | heltall | ✅ | Organisasjons-ID |
| `role` | streng | — | `"member"` (standard) eller `"manager"` |
| `unitId` | heltall\|null | — | Strukturell enhet (må tilhøre målorganisasjonen), eller `null` for hele organisasjonen |
| `canManageOrg` | boolean | — | Gi denne lederen rett til å promotere andre til global leder (standard `false`) |

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

**200 OK** *(medlemskap oppdatert)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(kunde har allerede aktivt medlemskap i en annen organisasjon)*
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
