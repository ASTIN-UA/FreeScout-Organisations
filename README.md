# OrgPortal — B2B Organization Portal for FreeScout

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

**OrgPortal** transforms FreeScout into a full-featured **B2B helpdesk platform** with organization management, hierarchical access control, and a dedicated self-service portal for your corporate clients. Built for companies that support other businesses — not just individual customers.

Whether you manage dozens of corporate accounts or thousands, OrgPortal gives your team and your clients the structure, visibility, and automation they need to work at scale.

**Minimum FreeScout version:** 1.8.147  
**Dependencies:** none required  
**Optional integrations:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

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

## Why OrgPortal?

- 🏢 **Built for B2B helpdesks** — group customers into organizations, assign roles, and give corporate managers a self-service portal with full visibility into their company's tickets
- 🗂️ **Hierarchical structure that scales** — divide organizations into departments, branches, or teams; global managers see everything, unit managers see only their scope
- 📸 **Permanent ticket attribution** — every ticket is snapshotted to its organization at creation time; historical data stays intact even when customers change organizations
- 🔔 **Granular notification control** — managers subscribe to exactly the events they care about, per unit, per event type, with per-member overrides in a visual matrix
- 🌍 **19 languages, zero setup** — fully localized UI and notification email templates out of the box; emails are sent in each manager's own language automatically
- ⚡ **Enterprise-ready REST API** — full API with interactive ReDoc docs for CRM integrations, automated onboarding, and custom workflows

---

## Features

### Organization Management

*Complete visibility into every corporate account, right inside FreeScout.*

- **Manage → Organizations** — full CRUD: create, edit, delete, activate/deactivate organizations
- **Mailbox binding** — organizations can be global (all mailboxes) or bound to a specific mailbox
- **Color-coded badges** — choose from 12 colors; badge appears on tickets and Kanban cards for instant visual identification; enable/disable per mailbox
- Clickable badge opens an instant search for all tickets from that organization
- **Organization filter** in standard FreeScout search — find every ticket from a corporate account in one click
- Organization info block in the admin ticket sidebar: organization name, structural unit, and member role on every ticket
- One customer — one organization, enforced at database and API level
- **Activate / deactivate organizations** — suspend an account without losing any history

### Structural Units — Department-Level Access Control

*Support large enterprises with complex internal hierarchies.*

Organizations can be divided into unlimited **structural units** (departments, branches, regional offices, project teams):

- Create, rename, and delete units directly in the organization edit form
- Assign members to units — each member belongs to one unit

**Three role levels:**

| Role | Access scope |
|------|-------------|
| `member` | Own tickets only |
| `unit_manager` | All tickets within their structural unit |
| `manager` (global) | All tickets across the entire organization |

- Unit managers have full portal capabilities — replies, attachments, author reassignment, close/reopen, notification management — scoped strictly to their unit
- Ticket access and notification delivery are enforced at unit boundaries

### Org Snapshot — Permanent Ticket Attribution

*Reliable historical reporting even as your client roster changes.*

When a ticket is created, OrgPortal automatically records the organization context as a permanent snapshot:

- `org_id`, `org_unit_id`, and `org_attributed_at` are written to the conversation at creation time
- **Immutable snapshot** — if a customer later leaves an organization, their historical tickets remain attributed to that organization; your reporting never breaks
- Attribution source is configurable: via organization membership or direct customer assignment
- **Backfill existing tickets** with `php artisan orgportal:backfill-attribution`
- Snapshot visibility and reset controls available in admin settings

### Access Control & Permissions

*Delegate organization management without granting admin access.*

- **"Allow managing organizations"** — support team leads can manage corporate accounts without admin rights
- **"Allow managing notification templates"** — separate granular permission for template editing
- Deleting organizations remains exclusively admin-only
- Portal access is strictly scoped per mailbox: a manager from Organization A cannot access Organization B

### Kanban Integration

*Keep your visual workflow aligned with your B2B accounts.*

- Organization badge on every Kanban card with the account's assigned color
- **Organization filter** in the Kanban filter panel — multi-select modal with checkboxes; filter state persists across navigation
- "State" column in the Company Tickets portal table shows the current Kanban column name (with custom label if configured)

---

## End-User Portal — Self-Service for Corporate Managers *(optional)*

*Give your B2B clients a portal where they manage their company's support relationship — without contacting your team for every status update.*

Requires the [End-User Portal](https://freescout.net/module/end-user-portal/) module.

### Company Tickets Dashboard

A dedicated **Company Tickets** section in portal navigation with a full-featured ticket table:

| Column | Description |
|--------|-------------|
| **#** | Ticket ID |
| **Subject** | Truncated with tooltip on hover |
| **Responsible** | Assigned support agent |
| **Author** | Customer who opened the ticket; click to filter by this author |
| **Status** | Active / Pending / Closed / Spam with icons |
| **State** | Kanban column name (only when Kanban module is active) |
| **Updated** | Date and time of last reply |

**Two independent read indicators per row:**
- **Bold row** — manager has unread notifications for this conversation
- **👁 Eye icon** — the ticket author has not yet opened the latest agent reply

### Ticket Actions in the Portal

Managers can take action directly — no need to contact support:

- **Reply with attachments** — drag & drop, multiple files per reply
- **Close ticket** — a new reply automatically reopens it
- **Change ticket author** — reassign a ticket to another organization member
- **Filter by unit** — global managers filter the ticket list by structural unit
- **Filter by Kanban status** — configurable per mailbox

### Manager Viewed Tracking

- A **"viewed"** note appears under agent replies in the admin ticket view when a manager opens the ticket in the portal
- Shows manager name, role (Organization manager / Unit manager), and time elapsed
- Global manager and unit manager views tracked and displayed independently — same UX as FreeScout's native "Customer viewed"

---

## Real-Time Notification Bell *(optional)*

*Keep managers informed the moment something happens with their company's tickets.*

Requires the [End-User Portal](https://freescout.net/module/end-user-portal/) module.

- 🔔 Bell icon with live unread count badge in the EUP navbar
- Notifications for: **new ticket**, **agent reply**, **customer reply** — for all manager roles
- Dropdown panel with notifications grouped by date: actor name, event type, ticket number, message preview, timestamp
- **Auto-mark as read** when the manager opens the ticket
- Mark individual notifications read via ×; **Mark all as read** in panel header
- Polls every 15 seconds; refreshes on browser back/forward navigation (bfcache-aware)

---

## Notification Subscriptions *(optional)*

*Let managers decide what they hear about — nothing more, nothing less.*

- **Visual subscription matrix** on the "Notifications" tab in portal Organization Settings
- **Three event types:** New ticket · Agent reply · Customer reply
- **Two scope levels:** Entire organization (global managers) · Individual structural units
- **Per-member overrides** — expand any unit row to reveal individual members and toggle their subscriptions inline
- **Cascaded logic in both directions:**
  - Enabling "Entire organization" → enables all units and all members
  - Enabling a unit → enables all its members
  - Disabling a member → auto-reconciles the unit and organization checkboxes
- Global managers manage all members; unit managers manage only their own unit
- Notifications use the mail driver of the corresponding mailbox

---

## Multilingual Notification Email Templates *(optional)*

*Your corporate clients receive support emails in their own language — automatically, with no manual effort.*

- **Per-locale templates** — separate subject and body for each portal language; switch between them with the locale dropdown in the admin template editor
- **Summernote WYSIWYG editor** for rich HTML email composition
- **Macro variable picker** — insert placeholders with one click
- **19 built-in default templates** — ready to use out of the box; no configuration needed

**Available macro variables:**

| Variable | Description |
|----------|-------------|
| `{manager_name}` | Name of the manager receiving the notification |
| `{author_name}` | Customer who created or replied to the ticket |
| `{org_name}` | Organization name |
| `{unit_name}` | Structural unit name |
| `{subject}` | Ticket subject |
| `{ticket_number}` | Ticket ID |
| `{ticket_url}` | Direct link to the ticket in the portal |
| `{ticket_text}` | Full text of the initial message (HTML) |
| `{reply_text}` | Full text of the latest reply (HTML) |
| `{created_datetime}` | Ticket creation date and time |
| `{reply_datetime}` | Reply date and time |

**Fallback chain:** saved locale template → built-in locale template → saved English template → built-in English template

Notification language is determined by each manager's portal language selection, saved automatically when they use the language switcher.

---

## REST API *(optional)*

*Integrate OrgPortal into your CRM, ERP, or customer onboarding workflow.*

Requires the [API and Webhooks](https://freescout.net/module/api-webhooks/) module.

- Full CRUD API for organizations, structural units, and customer memberships
- Authentication via `X-FreeScout-API-Key` header or `api_key` query parameter
- Interactive **ReDoc documentation** available at **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Full API reference → [docs/api/README.md](docs/api/README.md)**

---

## Installation

1. Copy the `OrgPortal` folder into `Modules/` of your FreeScout installation
2. Go to **Manage → Modules → OrgPortal → Activate**
3. Run migrations:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Clear cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Georgian language support** is deployed automatically on first boot — no manual file copying required.

---

## Automatic Updates

OrgPortal supports **one-click updates** via FreeScout's built-in module update mechanism.

> **Requires FreeScout 1.8.170 or later.** On older versions, update manually by replacing the `OrgPortal` folder with the latest release ZIP.

When a new version is available, a banner appears on **Manage → Modules**. Click **Update now** — FreeScout downloads and installs the latest version automatically.

---

## Module Compatibility

| Module | Status | Notes |
|--------|--------|-------|
| End-User Portal ≥ 1.0.85 | Optional | Manager portal, notification bell, subscriptions |
| API and Webhooks ≥ 1.0.80 | Optional | REST API endpoints |
| Kanban ≥ 1.0.23 | Optional | Badge on cards, org filter, State column |
| Custom Fields | ✅ Compatible | — |
| Workflows | ✅ Compatible | — |
| Tags | ✅ Compatible | — |

---

## Configuration

### Global Settings — **Manage → OrgPortal Settings**

| Option | Default | Description |
|--------|---------|-------------|
| Show badge on ticket page | ✅ Enabled | Org badge in conversation list and ticket view |
| Show badge on Kanban cards | ✅ Enabled | Org badge on Kanban board cards |
| Org snapshot visibility | Enabled | Show/hide attribution data in ticket sidebar |
| Attribution source | Member | How tickets are attributed to organizations |

### Per-Mailbox Settings — **Mailbox Settings → OrgPortal**

Overrides global values for the specific mailbox.

| Option | Description |
|--------|-------------|
| Show badge on ticket page | Enable/disable badge for this mailbox |
| Show badge on Kanban cards | Enable/disable badge for this mailbox |
| Show organization block in customer profile | Toggle org info in the ticket sidebar |
| Company ticket status filters | Map Kanban columns to named filters visible in the portal |

---

## Translations

OrgPortal is fully localized in **19 languages**:

| Language | Code | Language | Code |
|----------|------|----------|------|
| English | `en` | Dutch | `nl` |
| Ukrainian | `uk` | Norwegian | `no` |
| German | `de` | Danish | `da` |
| French | `fr` | Swedish | `sv` |
| Spanish | `es` | Finnish | `fi` |
| Italian | `it` | Portuguese (BR) | `pt-BR` |
| Czech | `cs` | Portuguese (PT) | `pt-PT` |
| Slovak | `sk` | Romanian | `ro` |
| Polish | `pl` | Chinese Simplified | `zh-CN` |
| Georgian | `ka` | | |

Translation files: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Notification email templates also have built-in defaults for all 19 languages.

### EUP Switch Language Integration

OrgPortal works seamlessly with [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): the language a manager selects in the portal applies to all OrgPortal UI strings and is saved as their notification language — emails are sent in their chosen language automatically.

> **Technical note:** `OrgPortalSetLocale` middleware re-applies the portal locale after FreeScout's `Localize` middleware to prevent it from being reset to the system default on every request.

---

## License

[MIT](LICENSE) — © 2026 ASTIN-UA

<!-- SCREENSHOT PLACEHOLDERS
Add screenshots to docs/screenshots/ and replace each placeholder below with the actual image tag.

1. After section "Organization Management":
   File: docs/screenshots/org-list.png
   Caption: "Organizations list — color badges, mailbox binding, active/inactive status"
   What to capture: Organizations table with colored badges, Mailbox/Tags/Tickets/Status columns, Edit/Deactivate/Delete action buttons

2. After section "Structural Units (Departments & Branches)":
   File: docs/screenshots/org-edit.png
   Caption: "Organization edit — members with roles and units, unit management panel"
   What to capture: Organization edit form — members table with Role/Unit/Can Manage/Active columns, unit management block, Add Member button

3. After section "User Permissions":
   File: docs/screenshots/user-permissions.png
   Caption: "Granular permissions — allow managing organizations and notification templates"
   What to capture: Agent permissions edit page with two new checkboxes: "Allow managing organizations" and "Allow managing notification templates"

4. After section "Customer Profile Integration":
   File: docs/screenshots/customer-org-field.png
   Caption: "Customer profile — organization field with role selector and org info block in ticket sidebar"
   What to capture: Left — customer edit form with Organization + Role fields. Right — admin ticket sidebar with org block (name, unit, role)

5. After section "Organization Badge on Tickets":
   File: docs/screenshots/ticket-badge.png
   Caption: "Organization badge on ticket page and conversation list"
   What to capture: Ticket with colored org badge below subject in conversation list + same badge on ticket detail page

6. After section "Kanban Integration":
   File: docs/screenshots/kanban-org.png
   Caption: "Kanban — organization badges on cards and org filter modal"
   What to capture: Kanban board with visible org badges on cards + open organization filter modal with checkboxes

7. After section "Manager Viewed Tracking":
   File: docs/screenshots/manager-viewed.png
   Caption: "Manager viewed tracking — 'viewed' note appears under agent reply in admin ticket view"
   What to capture: Admin ticket with note under agent reply: "Viewed by [Manager Name] (Organization manager) · X minutes ago"

8. After section "Org Snapshot & Ticket Attribution":
   File: docs/screenshots/attribution-settings.png
   Caption: "Org Snapshot settings — attribution source and backfill controls"
   What to capture: Attribution section in Manage → OrgPortal Settings: attribution source selector, Backfill button, Reset button with warning

9. After "Company Tickets" table description in End-User Portal section:
   File: docs/screenshots/portal-tickets.png
   Caption: "Company Tickets — full table with read indicators, status filters, and unit filter"
   What to capture: Company Tickets table with all columns (#, Subject, Responsible, Author, Status, State, Updated), bold rows (unread), eye icon, status filter checkboxes, unit dropdown

10. After portal reply/attachment description in End-User Portal section:
    File: docs/screenshots/portal-reply.png
    Caption: "Portal ticket view — reply form with drag & drop attachments"
    What to capture: Portal ticket page with open reply form, drag-and-drop attachment zone, Send/Close buttons

11. After section "Portal Organization Settings":
    File: docs/screenshots/portal-settings.png
    Caption: "Portal Organization Settings — members tab with locale and unit info"
    What to capture: Org Settings page in portal with tabs (Notifications, Members), members table with locale and unit columns

12. After section "Real-Time Notification Bell":
    File: docs/screenshots/portal-bell.png
    Caption: "Real-time notification bell — dropdown with grouped unread notifications"
    What to capture: EUP navbar with bell icon + unread badge, open dropdown with notifications grouped by date, Mark all read link

13. After section "Granular Notification Subscriptions":
    File: docs/screenshots/portal-subscriptions.png
    Caption: "Notification subscription matrix — per-unit and per-member toggles"
    What to capture: Subscription matrix (rows = org + units + expanded unit with members, columns = New ticket / Agent reply / Customer reply), checkboxes at intersections

14. After section "Multilingual Email Notification Templates":
    File: docs/screenshots/admin-templates.png
    Caption: "Email templates — per-locale editor with Summernote and macro variable picker"
    What to capture: Templates tab with locale dropdown at top, Summernote WYSIWYG editor, "Macro Variables" picker button, "Load Default" button

15. After section "REST API":
    File: docs/screenshots/api-docs.png
    Caption: "Interactive API documentation — ReDoc with all OrgPortal endpoints"
    What to capture: ReDoc page with endpoint list on left (Organizations, Units, Members) and expanded request/response example on right

16. After "Per-Mailbox Settings" in Configuration section:
    File: docs/screenshots/mailbox-settings.png
    Caption: "Per-mailbox settings — badge visibility and Kanban status filters configuration"
    What to capture: OrgPortal tab in Mailbox Settings with Show badge checkboxes + Kanban status filters table with custom labels
-->
