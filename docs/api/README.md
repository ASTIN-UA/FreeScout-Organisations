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

*Optional — requires the [API and Webhooks](https://freescout.net/module/api-webhooks/) module.*

Authentication — `X-FreeScout-API-Key` header or `api_key` query parameter.

> **Interactive documentation** (ReDoc) is available on the **Manage → API & Webhooks** page (link "OrgPortal API Docs") or directly at `/orgportal/admin/api-docs`.

## Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/organizations` | List organizations (pagination, mailbox filter) |
| `POST` | `/api/organizations` | Create an organization |
| `GET` | `/api/organizations/{id}` | Get organization with members and units |
| `PUT` | `/api/organizations/{id}` | Update organization |
| `DELETE` | `/api/organizations/{id}` | Delete organization |
| `GET` | `/api/organizations/{id}/units` | List structural units |
| `POST` | `/api/organizations/{id}/units` | Create a structural unit |
| `PUT` | `/api/units/{unitId}` | Rename a unit |
| `DELETE` | `/api/units/{unitId}` | Delete a unit (members unassigned, unit managers demoted) |
| `GET` | `/api/customers/{id}/organization` | Customer's organization membership |
| `PUT` | `/api/customers/{id}/organization` | Set/update customer membership |
| `DELETE` | `/api/customers/{id}/organization` | Remove customer from organization |

## Response codes

| Code | Meaning |
|------|---------|
| `200` | Success or no-op (nothing changed) |
| `201` | Resource created; `Resource-ID` header contains the ID |
| `400` | Validation error — details in `_embedded.errors` |
| `401` | Invalid or missing API key |
| `404` | Resource not found |
| `409` | Conflict — customer already has an active membership in another organization |

---

## Organizations

### GET /api/organizations

**Query parameters**

| Parameter | Type | Default | Description |
|-----------|------|:-------:|-------------|
| `page` | integer | `1` | Page number |
| `pageSize` | integer | `25` | Records per page (max 100) |
| `mailboxId` | integer | — | Mailbox filter: returns global organizations + those bound to this mailbox |

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

**Request body**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Organization name (max 255 chars, unique) |
| `mailboxId` | integer\|null | — | Mailbox ID or `null` / omit for global organization |

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

Returns the organization with its embedded **members** and **units**.

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

**Member fields**

| Field | Type | Description |
|-------|------|-------------|
| `unitId` | integer\|null | Structural unit the member belongs to, or `null` for the whole organization |
| `role` | string | `member` or `manager` |
| `canManageOrg` | boolean | Whether this manager may promote others to global manager from the portal |
| `isActive` | boolean | Active membership; inactive members receive no ticket assignments or notifications |
| `notifyOnNewTicket` | boolean | Legacy per-member new-ticket notification flag |

---

### PUT /api/organizations/{id}

**Request body**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | New organization name (max 255 chars, unique) |
| `mailboxId` | integer\|null | — | New mailbox; `null` — make global; omit — leave unchanged |

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

When nothing changes, the response message is `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(all members are cascaded deleted)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Structural units

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

**Request body**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Unit name (unique within the organization) |

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

**Request body**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | New unit name (unique within the organization) |

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

Deletes the unit. Managers scoped to this unit are demoted to `member`; all members of the unit are unassigned (their `unitId` becomes `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Customer membership

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

Assigns a customer to an organization or updates their membership. **One active membership per customer**: if the customer already has an *active* membership in *another* organization, the request is rejected with `409 Conflict`. To transfer — first deactivate or remove the current membership via `DELETE`.

**Request body**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | Organization ID |
| `role` | string | — | `"member"` (default) or `"manager"` |
| `unitId` | integer\|null | — | Structural unit (must belong to the target organization), or `null` for the whole organization |
| `canManageOrg` | boolean | — | Grant this manager the right to promote others to global manager (default `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(new membership)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(membership updated)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(customer already active in another organization)*
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
