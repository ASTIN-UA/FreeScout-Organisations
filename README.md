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
- New permission **"Allow managing notification templates"** — separate from organization management, allows editing email notification templates
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

### Notification Subscriptions *(optional)*

Portal managers can customize which events and scopes trigger email notifications:

- **Subscription matrix** on the "Notifications" tab in portal Organization Settings
- **Events:** New ticket, Agent reply, Customer reply
- **Scopes:** Entire organization (global managers only) or specific structural units
- **Cascading behavior:** Checking "Entire organization" automatically checks all unit rows; unchecking any unit unchecks "Entire organization"
- Notifications use the mail driver of the corresponding mailbox

### Email notifications *(optional)*
- Uses the mail driver of the corresponding mailbox
- Notification email templates can be customized by admins (see section below)

### Notification Email Templates *(optional, requires "Allow managing notification templates" permission)*

Admins can customize email templates sent to managers on the **Notification Templates** tab of the **Manage → Organizations** page.

**Template types:**
- New ticket notification
- Agent reply notification
- Customer reply notification

**Features:**
- Summernote WYSIWYG editor for each template
- Macro variable picker for inserting available placeholders
- Leave template empty to fall back to the built-in default

**Available macro variables:**
| Variable | Description |
|----------|-------------|
| `{manager_name}` | Name of the manager receiving the notification |
| `{author_name}` | Name of the ticket author (customer who created/replied) |
| `{org_name}` | Organization name |
| `{unit_name}` | Structural unit name (if applicable) |
| `{subject}` | Ticket subject |
| `{ticket_number}` | Ticket ID |
| `{ticket_url}` | Link to the ticket in the portal |
| `{created_date}` | Ticket creation date (format: YYYY-MM-DD) |
| `{created_time}` | Ticket creation time (format: HH:MM:SS) |
| `{created_datetime}` | Ticket creation date and time (ISO 8601) |
| `{reply_date}` | Reply date (format: YYYY-MM-DD) |
| `{reply_time}` | Reply time (format: HH:MM:SS) |
| `{reply_datetime}` | Reply date and time (ISO 8601) |

### Mailbox settings

**Mailbox Settings → OrgPortal** (per mailbox):

| Option | Description |
|--------|-------------|
| Show badge on ticket page | Enable/disable badge within this mailbox |
| Show badge on Kanban cards | Enable/disable badge within this mailbox |
| Company ticket status filters | Select Kanban columns displayed as checkboxes on the tickets page; custom label for each filter |

---

### REST API *(optional, requires API and Webhooks)*

OrgPortal exposes a full REST API for managing organizations, structural units, and customer memberships — authenticated via the `X-FreeScout-API-Key` header or `api_key` query parameter.

📖 **Full API reference → [docs/api/README.md](docs/api/README.md)** (all endpoints, request/response examples, error codes)

Interactive ReDoc documentation is also available at **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

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

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

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

[MIT](LICENSE) — © 2026 ASTIN-UA
