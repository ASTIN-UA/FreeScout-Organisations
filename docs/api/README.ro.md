# OrgPortal REST API

[← Înapoi la README](../README.ro.md)

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

*Opțional — necesită modulul [API și Webhooks](https://freescout.net/module/api-webhooks/).*

Autentificare — antet `X-FreeScout-API-Key` sau parametru de interogare `api_key`.

> **Documentația interactivă** (ReDoc) este disponibilă pe pagina **Gestionare → API și Webhooks** (link "OrgPortal API Docs") sau direct la `/orgportal/admin/api-docs`.

## Puncte finale

| Metodă | Punct final | Descriere |
|--------|----------|-----------|
| `GET` | `/api/organizations` | Listare organizații (paginare, filtru căsuță) |
| `POST` | `/api/organizations` | Creare organizație |
| `GET` | `/api/organizations/{id}` | Obținere organizație cu membri și unități |
| `PUT` | `/api/organizations/{id}` | Actualizare organizație |
| `DELETE` | `/api/organizations/{id}` | Ștergere organizație |
| `GET` | `/api/organizations/{id}/units` | Listare unități structurale |
| `POST` | `/api/organizations/{id}/units` | Creare unitate structurală |
| `PUT` | `/api/units/{unitId}` | Redenumire unitate |
| `DELETE` | `/api/units/{unitId}` | Ștergere unitate (membri neasignați, manageri demotați) |
| `GET` | `/api/customers/{id}/organization` | Apartenența organizației clientului |
| `PUT` | `/api/customers/{id}/organization` | Setare/actualizare apartenența clientului |
| `DELETE` | `/api/customers/{id}/organization` | Eliminare client din organizație |

## Coduri de răspuns

| Cod | Semnificație |
|-----|--------------|
| `200` | Succes sau nicio operație (nimic nu s-a schimbat) |
| `201` | Resursă creată; antetul `Resource-ID` conține ID-ul |
| `400` | Eroare de validare — detalii în `_embedded.errors` |
| `401` | Cheie API invalidă sau lipsă |
| `404` | Resursă negăsită |
| `409` | Conflict — client are deja o apartenență activă în altă organizație |

---

## Organizații

### GET /api/organizations

**Parametri de interogare**

| Parametru | Tip | Implicit | Descriere |
|-----------|-----|:--------:|-----------|
| `page` | integer | `1` | Numărul paginii |
| `pageSize` | integer | `25` | Înregistrări pe pagină (max 100) |
| `mailboxId` | integer | — | Filtru căsuță: returnează organizații globale + cele legate de această căsuță |

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

**Corp cerere**

| Câmp | Tip | Obligatoriu | Descriere |
|-------|-----|:--------:|-----------|
| `name` | string | ✅ | Nume organizație (max 255 caractere, unic) |
| `mailboxId` | integer\|null | — | ID căsuță sau `null` / omitere pentru organizație globală |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(antet `Resource-ID: 1`)*
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

Returnează organizația cu membrii și unitățile sale încorporate.

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

**Câmpuri ale membrilor**

| Câmp | Tip | Descriere |
|-------|-----|-----------|
| `unitId` | integer\|null | Unitatea structurală căreia îi aparține membrul, sau `null` pentru întreaga organizație |
| `role` | string | `member` sau `manager` |
| `canManageOrg` | boolean | Dacă acest manager poate promova alți membri la manager global din portal |
| `isActive` | boolean | Apartenență activă; membrii inactivi nu primesc asignări sau notificări |
| `notifyOnNewTicket` | boolean | Steag moștenit pentru notificări pe membru pentru noi tichete |

---

### PUT /api/organizations/{id}

**Corp cerere**

| Câmp | Tip | Obligatoriu | Descriere |
|-------|-----|:--------:|-----------|
| `name` | string | ✅ | Nume nou organizație (max 255 caractere, unic) |
| `mailboxId` | integer\|null | — | Căsuță nouă; `null` — faceți global; omitere — lăsați neschimbat |

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

Când nimic nu se schimbă, mesajul de răspuns este `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(toți membrii sunt șterși în cascadă)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Unități structurale

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

**Corp cerere**

| Câmp | Tip | Obligatoriu | Descriere |
|-------|-----|:--------:|-----------|
| `name` | string | ✅ | Nume unitate (unic în cadrul organizației) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(antet `Resource-ID: 2`)*
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

**Corp cerere**

| Câmp | Tip | Obligatoriu | Descriere |
|-------|-----|:--------:|-----------|
| `name` | string | ✅ | Nume nouă unitate (unic în cadrul organizației) |

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

Șterge unitatea. Managerii limitați la această unitate sunt demotați la `member`; toți membrii unității sunt neasignați (`unitId` devine `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Apartenență client

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

Atribuie un client unei organizații sau actualizează apartenența sa. **O membru activă pe client**: dacă clientul are deja o membru *activă* în *altă* organizație, cererea este respinsă cu `409 Conflict`. Pentru transfer — mai întâi dezactivați sau eliminați cea curentă prin `DELETE`.

**Corp cerere**

| Câmp | Tip | Obligatoriu | Descriere |
|-------|-----|:--------:|-----------|
| `organizationId` | integer | ✅ | ID organizație |
| `role` | string | — | `"member"` (implicit) sau `"manager"` |
| `unitId` | integer\|null | — | Unitate structurală (trebuie să aparțină organizației țintă), sau `null` pentru întreaga organizație |
| `canManageOrg` | boolean | — | Acordă acestui manager dreptul de a promova alți membri la manager global (implicit `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(membru nouă)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(membru actualizată)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(client deja activ în altă organizație)*
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
