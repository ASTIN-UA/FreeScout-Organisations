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
| `PUT` | `/api/organizations/{id}` | Update organization (name, color, mailbox, isActive) |
| `DELETE` | `/api/organizations/{id}` | Delete organization |
| `GET` | `/api/organizations/{id}/members` | List organization members |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Get a single member |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Update member (role, unit, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Remove a member |
| `GET` | `/api/organizations/{id}/tags` | List tag bindings (requires Tags module) |
| `PUT` | `/api/organizations/{id}/tags` | Replace all tag bindings (requires Tags module) |
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
| `200` | Success |
| `201` | Resource created; `Resource-ID` header contains the ID |
| `400` | Validation error — details in `_embedded.errors` |
| `401` | Invalid or missing API key |
| `404` | Resource not found |
| `409` | Conflict — customer already has an active membership in another organization |
| `422` | Business rule violation — e.g. deleting an organization or removing a member that still has tickets |
| `503` | Required module (e.g. Tags) is not active |

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
  "color": null,
  "isActive": true,
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

**Member fields**

| Field | Type | Description |
|-------|------|-------------|
| `unitId` | integer\|null | Structural unit the member belongs to, or `null` for a global (org-wide) role |
| `role` | string | `"member"` or `"manager"`. A **unit manager** is `role: "manager"` with a non-null `unitId`; a **global manager** is `role: "manager"` with `unitId: null`. The string `"unit_manager"` does not exist in the API — passing it returns 400. |
| `canManageOrg` | boolean | Whether this manager may promote others to global manager from the portal |
| `isActive` | boolean | Active membership; inactive members receive no ticket assignments or notifications |
| `notifyOnNewTicket` | boolean | Per-member new-ticket notification flag |

---

### PUT /api/organizations/{id}

**Request body**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | New organization name (max 255 chars, unique) |
| `color` | string\|null | — | Badge color as hex (`"#ff0000"`), `null` to reset to default gray; omit to keep current |
| `mailboxId` | integer\|null | — | New mailbox; `null` — make global; omit — leave unchanged |
| `isActive` | boolean | — | `false` to deactivate the organization; omit to keep current |

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

Blocked when the organization has active members or tickets. Remove all members and reassign/delete all tickets first.

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

## Organization members

### GET /api/organizations/{id}/members

Returns a list of all member records for the organization.

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

Returns a single member record.

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

Update a member's role, unit assignment, canManageOrg flag, or active status. Only fields present in the body are updated (partial update).

**Request body**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `role` | string | — | `"member"` or `"manager"`. To create a **unit manager**: `role: "manager"` + `unitId: <id>`. To create a **global manager**: `role: "manager"` + `unitId: null`. |
| `unitId` | integer\|null | — | Structural unit (must belong to this organization), or `null` to unassign |
| `canManageOrg` | boolean | — | Grant global manager rights in the portal |
| `isActive` | boolean | — | `false` to deactivate without removing |

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

Hard-deletes the membership record. Blocked when the member has tickets in this organization — use `PUT` with `isActive: false` instead to deactivate ("fire") them and keep their ticket history intact.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

**422 Unprocessable Entity** *(member has tickets)*
```json
{"message": "Cannot remove this member: they have tickets in this organization. Deactivate them instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```

---

## Organization tags

> Requires the [Tags](https://freescout.net/module/tags/) module to be active. Returns `503` if the module is not installed.

### GET /api/organizations/{id}/tags

Returns all tag bindings for the organization. Each binding optionally scopes a tag to a specific unit.

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

**Full replace** — replaces all existing tag bindings for this organization with the supplied list. Send an empty array `[]` to remove all bindings.

**Request body** — a JSON array of tag binding objects:

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `tagId` | integer | ✅ | FreeScout tag ID |
| `unitId` | integer\|null | — | Scope the tag to a specific unit, or omit/`null` for org-wide |

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
| `isActive` | boolean | — | `false` to create/update as inactive (default `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
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

Removes the customer's **active** membership only. Historical (deactivated) memberships in other organizations are preserved and untouched. Blocked when the customer has tickets in this organization — use `PUT` with `isActive: false` instead to deactivate and keep their ticket history intact.

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```

**422 Unprocessable Entity** *(customer has tickets)*
```json
{"message": "Cannot remove this membership: the customer has tickets in this organization. Deactivate instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```
