# OrgPortal — Organization Portal for FreeScout

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

A FreeScout module that adds the concept of **Organizations** (companies/teams) to customers, extends the End-User Portal for managers, and displays an organization badge on tickets and Kanban cards.

**Minimum FreeScout version:** 1.8.147  
**Dependencies:** none required  
**Optional:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Also available in:**
[Українська](docs/README.uk.md) ·
[Deutsch](docs/README.de.md) ·
[Français](docs/README.fr.md) ·
[Español](docs/README.es.md) ·
[Italiano](docs/README.it.md) ·
[Polski](docs/README.pl.md) ·
[Čeština](docs/README.cs.md) ·
[Slovenčina](docs/README.sk.md) ·
[Nederlands](docs/README.nl.md) ·
[Norsk](docs/README.no.md) ·
[Dansk](docs/README.da.md) ·
[Svenska](docs/README.sv.md) ·
[Suomi](docs/README.fi.md) ·
[Português (BR)](docs/README.pt-BR.md) ·
[Português (PT)](docs/README.pt-PT.md) ·
[Română](docs/README.ro.md) ·
[中文 (简体)](docs/README.zh-CN.md)

---

## Features

### Organization management (admin)
- **Manage → Organizations** — full CRUD: create, edit, delete organizations
- **Mailbox binding** — an organization can be **global** (visible in all mailboxes) or **bound to a specific mailbox**; the corresponding label is shown in the organization list
- Assign customers to organizations with role selection: `member` or `manager`
- **Change member role** directly in the table (without removing and re-adding)
- Customer search autocomplete by name or email; customers already in any organization are excluded from results
- Member email is displayed below the name in the members table
- One customer — one organization (enforced at DB and API level)
- **Badge color** — visual palette with 12 colors in the organization edit form; default is gray

### User permissions
- New permission **"Allow managing organizations"** — non-admins with this permission get access to the list, create, and edit organization pages
- Deleting organizations remains exclusive to admins

### Customer card
- **Organization** field in the customer edit form — select organization and role
- **Organization Tickets** button — opens a search for all tickets of the organization

### Organization badge on tickets
- Displayed below the subject on the ticket page and before the name in the conversation list
- Clickable — opens a search for all tickets of this organization
- Badge color is determined by the organization setting (default gray)
- Enable/disable **per mailbox** via **Mailbox Settings → OrgPortal**; global value is used as fallback

### Organization badge on Kanban cards
- Displayed after the message counter on each card
- Clickable — leads to organization search
- Color matches the organization setting
- **Organization** filter built into the standard Kanban filter dropdown: modal with checkboxes, similar to the tags filter; state is preserved between navigations
- Enable/disable **per mailbox** via **Mailbox Settings → OrgPortal**

### Organization search filter
- Extends the standard FreeScout search with an **Organization** filter
- Shows all tickets of customers belonging to the selected organization

### End-User Portal — manager access *(optional)*

An organization manager gets extended access through EUP:

- **Company Tickets** item in the portal navigation
- Company tickets table with columns:
  - **#** and **Subject** with ellipsis truncation and tooltip on hover
  - **Responsible** — assigned agent
  - **Author** — the customer who opened the ticket; click filters tickets by author within the organization
  - **Status** — Active / Pending / Closed / Spam with icons
  - **State** — Kanban column name (with custom label if configured); shown only if the Kanban module is active
  - **Updated** — date and time of the last reply
- Search by ticket subject
- Filters by Kanban statuses (configurable via **Mailbox Settings → OrgPortal**)
- Reply to ticket with **attachment** support (drag & drop, multi-file)
- **Close ticket** — manager can close a ticket; a new reply automatically reopens it
- Change ticket author — reassign a ticket to another organization member
- **Org Settings** page for configuring email notifications
- Ticket access is **strictly limited to the current mailbox** (organization copied to another mailbox — portal 403)

### Email notifications *(optional)*
- Managers with the option enabled receive an email when a new ticket is created by any member of the organization
- Uses the mail driver of the corresponding mailbox

### Mailbox settings

**Mailbox Settings → OrgPortal** (per mailbox):

| Option | Description |
|--------|-------------|
| Show badge on ticket page | Enable/disable badge within this mailbox |
| Show badge on Kanban cards | Enable/disable badge within this mailbox |
| Company ticket status filters | Select Kanban columns displayed as checkboxes on the tickets page; custom label for each filter |

---

### REST API *(optional, requires API and Webhooks)*

Authentication — `X-FreeScout-API-Key` header or `api_key` query parameter.

> **Interactive documentation** (ReDoc) is available on the **Manage → API & Webhooks** page (link "OrgPortal API Docs") or directly at `/orgportal/admin/api-docs`.

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/organizations` | List organizations (pagination, mailbox filter) |
| `POST` | `/api/organizations` | Create an organization |
| `GET` | `/api/organizations/{id}` | Get organization with members |
| `PUT` | `/api/organizations/{id}` | Update organization |
| `DELETE` | `/api/organizations/{id}` | Delete organization |
| `GET` | `/api/customers/{id}/organization` | Customer's organization |
| `PUT` | `/api/customers/{id}/organization` | Set/update customer membership |
| `DELETE` | `/api/customers/{id}/organization` | Remove customer from organization |
| `GET` | `/api/mailboxes` | List mailboxes *(FreeScout base)* |
| `GET` | `/api/mailboxes/{id}` | Get mailbox by ID *(FreeScout base)* |
| `GET` | `/api/customers` | List customers *(FreeScout base)* |
| `GET` | `/api/customers/{id}` | Get customer by ID *(FreeScout base)* |

#### Response codes

| Code | Meaning |
|------|---------|
| `200` | Success or no-op (nothing changed) |
| `201` | Resource created; `Resource-ID` header contains the ID |
| `400` | Validation error — details in `_embedded.errors` |
| `401` | Invalid or missing API key |
| `404` | Resource not found |
| `409` | Conflict — customer already belongs to another organization |

---

#### GET /api/organizations

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

#### POST /api/organizations

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

#### GET /api/organizations/{id}

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
        "customerId": 42,
        "role": "manager",
        "notifyOnNewTicket": true,
        "createdAt": "2026-06-01T10:05:00+00:00",
        "updatedAt": "2026-06-01T10:05:00+00:00"
      }
    ]
  }
}
```

---

#### PUT /api/organizations/{id}

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

---

#### DELETE /api/organizations/{id}

**200 OK** *(all members are cascaded deleted)*
```json
{"success": true, "message": "Organization deleted."}
```

---

#### GET /api/customers/{id}/organization

**200 OK**
```json
{
  "customerId": 42,
  "organizationId": 1,
  "organizationName": "Acme Corp",
  "role": "manager",
  "notifyOnNewTicket": true
}
```

---

#### PUT /api/customers/{id}/organization

Assigns a customer to an organization or updates their role. **One customer — one organization**: if the customer is already a member of *another* organization, the request is rejected with `409 Conflict`. To transfer — first remove the current membership via `DELETE`.

**Request body**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | Organization ID |
| `role` | string | — | `"member"` (default) or `"manager"` |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager"}'
```

**201 Created** *(new membership)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(role updated or no-op)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(customer already in another organization)*
```json
{
  "message": "Customer already belongs to another organization.",
  "errorCode": "CUSTOMER_ALREADY_BELONGS_TO_ANOTHER_ORGANIZATION.",
  "_embedded": {
    "errors": [
      {
        "path": "organizationId",
        "message": "Customer is already a member of organization #3. Remove the existing membership first via DELETE /api/customers/42/organization.",
        "source": "JSON"
      }
    ]
  }
}
```

---

#### DELETE /api/customers/{id}/organization

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```

---

---

#### GET /api/mailboxes *(FreeScout base)*

Returns all mailboxes. Use the returned `id` as `mailboxId` when creating or filtering organizations.

```bash
curl -X GET "https://your-freescout.com/api/mailboxes" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY"
```

**200 OK**
```json
{
  "_embedded": {
    "mailboxes": [
      { "id": 1, "name": "Support", "email": "support@example.com", "createdAt": "...", "updatedAt": "..." },
      { "id": 2, "name": "Sales",   "email": "sales@example.com",   "createdAt": "...", "updatedAt": "..." }
    ]
  }
}
```

---

#### GET /api/customers *(FreeScout base)*

**Query parameters**

| Parameter | Type | Default | Description |
|-----------|------|:-------:|-------------|
| `email` | string | — | Filter by email address |
| `page` | integer | `1` | Page number |
| `pageSize` | integer | `25` | Records per page (max 100) |

```bash
curl -X GET "https://your-freescout.com/api/customers?email=john@acme.com" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY"
```

**200 OK**
```json
{
  "_embedded": {
    "customers": [
      { "id": 42, "firstName": "John", "lastName": "Doe", "email": "john@acme.com", "createdAt": "...", "updatedAt": "..." }
    ]
  },
  "page": { "size": 25, "totalElements": 1, "totalPages": 1, "number": 1 }
}
```

---

## Installation

1. Copy the `OrgPortal` folder into `Modules/` of your FreeScout
2. In the admin panel: **Manage → Modules → OrgPortal → Activate**
3. Run migrations:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Clear cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

## Updates

OrgPortal supports **automatic updates** via FreeScout's built-in module update mechanism.

When a new version is available, a banner will appear on the **Manage → Modules** page. Click **Update now** — FreeScout will download and install the latest version automatically.

No manual file copying required.

---

## Module compatibility

| Module | Status |
|--------|--------|
| End-User Portal ≥ 1.0.85 | Optional — portal features for managers |
| API and Webhooks ≥ 1.0.80 | Optional — REST API endpoints |
| Kanban ≥ 1.0.23 | Optional — badge, filter, "State" column in company tickets |
| Custom Fields | Compatible |
| Workflows | Compatible |
| Tags | Compatible |

---

## Configuration

### Global (**Manage → OrgPortal Settings**)

| Option | Default |
|--------|---------|
| Show badge on ticket page | ✅ |
| Show badge on Kanban cards | ✅ |

### Per-mailbox (**Mailbox Settings → OrgPortal**)

Overrides global values for the specific mailbox.

| Option | Description |
|--------|-------------|
| Show badge on ticket page | Badge in conversation list and on ticket page |
| Show badge on Kanban cards | Badge on Kanban cards |
| Company ticket status filters | Kanban columns as checkboxes on the company tickets page; each filter has a custom label visible to portal users |

---

## Translations

Supported languages: **English** (`en`), **Ukrainian** (`uk`), **Romanian** (`ro`), **Georgian** (`ka`), **German** (`de`), **French** (`fr`), **Spanish** (`es`), **Italian** (`it`), **Czech** (`cs`), **Slovak** (`sk`), **Polish** (`pl`), **Dutch** (`nl`), **Norwegian** (`no`), **Danish** (`da`), **Swedish** (`sv`), **Finnish** (`fi`), **Portuguese BR** (`pt-BR`), **Portuguese PT** (`pt-PT`), **Chinese Simplified** (`zh-CN`).

Files: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### EUPSWLANG integration

The module works correctly with [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): the language selected in the portal also applies to OrgPortal strings.

For a language to appear in the EUPSWLANG list, the corresponding `Modules/EndUserPortal/Resources/lang/{locale}.json` file must exist. Files for **Romanian** (`ro`) are included in the package; **Georgian** (`ka`) is only supported in the admin section (no system support in FreeScout core).

> **Technical detail:** `ReapplyEupLocale` middleware (registered last in the portal route group) restores the locale after FreeScout's `Localize` middleware, which would otherwise reset the portal language selection to the system default.

---

## License

[MIT](LICENSE) — © 2026 ASTIN UA
