# Changelog

All notable changes to OrgPortal are documented here.

---

## [2.0.3] — 2026-07-02

### New Features

- **Custom Fields integration on the portal ticket page**: when the [Custom Fields](https://freescout.net/module/custom-fields/) module is installed and active, admins can now pick which custom fields appear on the portal ticket page from a new panel in **Mailbox Settings → OrgPortal**. Fields support drag-to-reorder and a custom label per portal language (falling back to the saved English label, then the original field name). Enabled fields with a non-empty value render in a responsive two-column grid between the ticket subject and the thread. Fully optional — hidden automatically when the module is not installed. Documented in all 18 README languages.

### Bug Fixes

- **Drag-to-reorder was non-functional** in both the Kanban company filters panel and the new Custom Fields panel: the code checked for jQuery UI's `$.fn.sortable`, which is not part of this project. Switched to the `sortable()`/`sortupdate` API of the html5sortable library that FreeScout already loads globally via the Kanban module.
- **Portal ticket view layout**: the custom fields block now renders above the "From / Change author" row, separated by horizontal rules, for clearer visual grouping.

### Internal

- Deduplicated the company-filters and custom-fields label-editor JavaScript in `mailbox_settings.blade.php` into a single shared `initLabelEditor()` helper.

---

## [2.0.2] — 2026-07-02

### Bug Fixes

- **OrgPortal now works without EndUserPortal installed**: the module no longer crashes FreeScout on boot when EndUserPortal is absent. Previously, `deployGeorgianAssets()` attempted to copy `ka.json` into the EndUserPortal language directory unconditionally, causing a fatal PHP error that prevented FreeScout from loading entirely. The Georgian asset is now only deployed when EndUserPortal is active.
- **Removed dev-only impersonate endpoint**: the `/orgportal/admin/impersonate/{customer_id}/{mailbox_id}` route was a temporary testing helper that was never exposed in the UI. It has been removed to reduce attack surface.

### UI Improvements

- **Portal Language Switcher panel** in global settings now shows a clear notice ("EndUserPortal module is required") instead of the settings form when EndUserPortal is not installed — available in all 19 supported languages.
- **Kanban company filters** in per-mailbox settings are now hidden when EndUserPortal is not installed, since these filters configure portal display which requires EUP to function.

---

## [2.0.1] — 2026-06-26

### Bug Fixes

- **Org badge on ticket page and conversations list**: when Snapshot Visibility is enabled and a ticket has `org_id` set (attributed via tag or backfill), the organization badge now correctly resolves from `org_id` instead of the customer's membership record. This fixes missing badges for tag-attributed tickets where the customer is not a member of any organization. Falls back to membership lookup when snapshot mode is off or `org_id` is null.

---

## [2.0.0] — 2026-06-21

This release is a major expansion of OrgPortal. Nearly every subsystem was extended or rewritten. The highlights are structural units with role-scoped portal access, permanent ticket attribution (org snapshot), a full notification infrastructure, tag-based attribution, Georgian language, and dozens of improvements across the admin UI, portal, API, and localization.

### Structural Units — Department-Level Access Control

- Added structural subdivisions (units) to organizations — departments, branches, teams, regional offices
- Three role levels: `member` (own tickets only) → `unit_manager` (entire unit) → `manager` (entire organization)
- Create, rename, and delete units in the admin org edit form or directly from the portal (global managers only)
- Assign members to units with an inline unit select in the members table
- Deleting a unit automatically demotes its `unit_manager` members to `member`
- Unit managers have full portal capabilities (reply, attach, close, change author) scoped strictly to their unit
- Unit filter on the company tickets page for global managers
- Ticket access and notification delivery enforced at unit boundaries

### Org Snapshot — Permanent Ticket Attribution

- `org_id`, `org_unit_id`, and `org_attributed_at` written to `conversations` at ticket creation time
- Immutable — customer leaving an organization does not affect historical ticket attribution; reporting never breaks
- Adding a member triggers automatic backfill of that customer's existing un-attributed conversations
- **Three attribution modes** (configured in System tab):
  - `member` — attribute by membership
  - `tag` — tag first, fall back to membership
  - `tag_only` — tag only, ignore membership
- **Backfill tools** in the System tab:
  - Progress bar showing X / Y attributed (%)
  - Preflight stats: breakdown by tag / membership / unmatched before running
  - Run backfill button (up to 2000 tickets per click) with result summary
  - Auto-cron toggle — runs backfill every 5 minutes, 1000 tickets per run, overlap-safe
  - Reset attribution button (danger action, requires confirmation)
- CLI: `php artisan orgportal:backfill-attribution`
- Snapshot visibility toggle in the ticket sidebar (per-mailbox)

### Tag-Based Attribution

- Organizations can have FreeScout tags bound to them — chip widget with autocomplete search in the org edit form
- Tags column in the organizations list (✓/✗ whether any tags are bound)
- Tag sub-resource in the API: `GET/PUT /api/organizations/{id}/tags`
- `tag` and `tag_only` attribution modes use these bindings to attribute tickets by tag before falling back to membership

### Organization List Enhancements

- **Activate / deactivate organizations** — suspend a client without losing history; requires Org Snapshot to be enabled (button disabled with tooltip when it is not)
- **Status filter** — radio group: Active / Inactive / All; filters the table client-side instantly
- **Live search** — AJAX, debounced, starts at 2+ characters, no page reload; clear restores the full list
- **Tags column** — shows whether any tags are bound to the org
- **Ticket count column** — total conversations per org, clickable link to full search results
- Delete guard: delete is available only when the org has 0 members and 0 tickets
- All delete and deactivate actions require confirmation

### Real-Time Notification Bell *(requires End-User Portal)*

- Bell icon with live unread count badge in the EUP navbar; repositions automatically on mobile
- Events covered: new ticket, agent reply, customer reply — for all manager roles
- Dropdown panel with notifications grouped by date: actor name, event type, ticket #, message preview, timestamp
- Auto-mark as read when the manager opens the ticket
- Mark individual notifications read via ×; Mark all as read in panel header
- Polls every 15 seconds; bfcache-aware refresh on browser back/forward navigation

### Notification Subscriptions *(requires End-User Portal)*

- Visual subscription matrix on the Notifications tab in portal Organization Settings
- Three event types: New ticket · Agent reply · Customer reply
- Two scope levels: Entire organization (global managers) · Individual structural units
- Per-member overrides — expand any unit row to toggle individual member subscriptions inline
- Fully transitive cascade in both directions (org → unit → member; disabling a member reconciles upward)
- Members without a unit grouped in a collapsible "No unit" row
- Inactive members hidden by default; Show deactivated toggle
- Global managers manage all members; unit managers manage only their own unit

### Multilingual Notification Email Templates *(requires End-User Portal)*

- Per-locale templates — separate subject and body for each of 19 portal languages
- Switch locales with a dropdown; values swap in memory without a page reload
- Collapsible panels per event type (New ticket / Agent reply / Customer reply)
- Summernote WYSIWYG editor, initializes lazily when a panel is opened
- Load Default button — restores the built-in template for the selected locale
- Macro variable picker inserts placeholders into subject or body with one click
- 19 built-in default templates ready out of the box in all supported languages
- Fallback chain: saved locale → built-in locale → saved English → built-in English
- Notification language determined by each manager's saved portal language

### Portal Enhancements *(requires End-User Portal)*

- **Mobile card layout** for the company tickets table; switches automatically by screen width
- **Author filter** — click an author name to filter by them; banner with active author name and × to clear
- **Two independent read-status indicators per row**: bold row (manager has unread notifications) + eye icon (ticket author hasn't seen the latest agent reply)
- **Manager viewed tracking**: "viewed" note under agent replies in the admin ticket view showing manager name, role, and elapsed time; global and unit manager views tracked independently
- **Change ticket author** — reassign a ticket to another organization member from the portal
- **Filter by Kanban status** — configurable per mailbox with multilingual labels
- Closed-ticket banner informing the manager that a new reply will reopen the ticket
- Org info block in the admin ticket sidebar — org name (clickable link), unit, member role; toggle visibility per mailbox in settings
- Inactive members hidden from the subscription matrix

### Portal Organization Settings *(requires End-User Portal)*

- **Units tab** (global managers only): create, rename, delete units inline; member count per unit
- **Members tab**: all members with unit, role, and active/inactive status; inline unit and role edit for global managers; activate/deactivate per member with confirmation; "Show deactivated" toggle
- **Notifications tab**: subscription matrix described above

### Multilingual Kanban Status Filter Labels *(requires Kanban)*

- Per-mailbox, per-locale custom labels for each Kanban column; displayed in the portal filter bar and the State column of the company tickets table
- Locale switcher in per-mailbox settings to switch between languages when editing labels
- Drag to reorder filters
- Fallback chain: saved locale → saved English → original column name
- Org filter on the Kanban board now works regardless of whether badge display is enabled for that mailbox

### REST API Enhancements *(requires API and Webhooks)*

- **Units CRUD**: `GET/POST /api/organizations/{id}/units`, `PUT/DELETE /api/units/{unitId}`
- **Members sub-resource** extended: `PUT /api/organizations/{id}/members/{memberId}` — update role, unit, `canManageOrg`, and per-member `isActive` independently without touching the rest of the membership
- **Tags sub-resource**: `GET/PUT /api/organizations/{id}/tags` — list or fully replace tag bindings (returns 503 if Tags module is inactive)
- New organization fields via API: `isActive`, `color`
- `DELETE /api/organizations/{id}/members/{memberId}` — remove a member
- Interactive ReDoc documentation accessible from **Manage → API & Webhooks → OrgPortal API Docs**
- Full API reference extracted to `docs/api/README.md` with 18-language versions

### Georgian Language Support

- Added Georgian (`ka`) UI translations for all OrgPortal interface keys
- Built-in Georgian notification email templates (new ticket / agent reply / customer reply)
- Georgian assets bundled inside the module and auto-deployed on first boot — no manual file copying required
- Georgian locale registered in `Helper::$locales` for End-User Portal display
- Georgian locale name `ქართული` added to `getLocaleName` map

### System Settings Improvements

- System tab reorganized into two collapsible panels (Attribution + Portal Language Switcher) with a single Save form
- **Portal Language Switcher**: enable/disable the language switcher in the EUP navbar; choose which of the 19 locales to offer (checkbox grid); all enabled by default
- Attribution source radio hints compacted to tooltips to reduce page height
- Attribution cron scheduling toggle exposed in the UI

### Localization

- 49+ new translation keys added across all 17 supported languages — covers org deactivation, ticket attribution, status filters, unit management, notification bell, and subscription matrix
- Notification bell keys added to all 17 locales
- All multilingual README translations (`docs/README.*.md`) updated to reflect the full current feature set

### Security

- Sanitize reply body in `replyTicket()` to prevent stored XSS
- Escape LIKE wildcards in org search to prevent SQL wildcard injection
- Add `JSON_HEX_APOS` to JS data attributes containing user content to prevent JSON parse errors and XSS
- HTML sanitization on ticket thread body in portal view
- JSON encoding flags on tag list JS output
- Portal locale cookie `secure` flag aligned with `session.secure` app setting

### Bug Fixes

- Fixed live search always returning empty results — `limit()` combined with `withCount()` breaks MySQL subquery aggregation; removed
- Fixed Summernote initialization guard that silently blocked all JS on the admin org page
- Fixed `trim(null)` PHP 8 deprecation in search input handling
- Fixed `Cannot redeclare searchOrganizations()` naming conflict; AJAX endpoint renamed to `listOrganizationsJson`
- Fixed missing `hasTable` guards in notification and thread_views migrations
- Fixed XSS sanitizer, orphan cleanup on conversation delete, and bulk thread-view insert
- Fixed no-unit members missing from the notification subscription matrix
- Fixed form data persistence when switching between locales in the multilingual template editor
- Fixed Georgian language appearing in the locale fallback chain for other locales
- Fixed manager read/unread tracking and notification bell edge cases

### Migration Safety

- All new migrations include `hasTable` / `hasColumn` guards in both `up()` and `down()` methods
- `down()` methods check each column individually before dropping to survive partial upgrades
- Idempotency guards added retroactively to all previously unguarded migrations

---

## [1.0.4] — 2026-06-11

### Changed
- Cosmetic: licence corrected to MIT across all localised READMEs
- Cosmetic: author name standardised to ASTIN-UA throughout documentation

## [1.0.3] — 2026-06-11

### Changed
- Cosmetic: licence corrected to MIT across all localised READMEs
- Cosmetic: author name standardised to ASTIN-UA throughout documentation

## [1.0.2] — 2026-06-10

### Fixed
- Auto-update now installs correctly: release ZIP contains only the `OrgPortal` module folder (previously the full repository archive was used, causing the update to fail silently)
- `detailsUrl` now points directly to CHANGELOG.md

## [1.0.1] — 2026-06-10

### Added
- Automatic updates via FreeScout's built-in module update mechanism — a banner appears on **Manage → Modules** when a new version is available; click **Update now** to install

## [1.0.0] — 2026-06-01

### Added
- Organization management (CRUD) with mailbox binding (global or per-mailbox)
- Member roles: `member` and `manager`; role change directly in the members table
- Badge color selection (12 colors) per organization
- Organization badge on ticket pages and conversation list (per-mailbox toggle)
- Organization badge on Kanban cards with organization filter (per-mailbox toggle)
- Organization search filter extending standard FreeScout search
- End-User Portal manager access: Company Tickets page with subject search, Kanban status filters, reply with attachments, ticket closure, author reassignment
- Org Settings page for email notification preferences
- Email notifications for managers on new ticket creation
- REST API (requires API and Webhooks module): full CRUD for organizations, customer membership endpoints, mailbox lookup endpoints; interactive ReDoc documentation at `/orgportal/admin/api-docs`
- Permission: **Allow managing organizations** for non-admin users
- Translations: English, Ukrainian, Romanian, Georgian, German, French, Spanish, Italian, Czech, Slovak, Polish, Dutch, Norwegian, Danish, Swedish, Finnish, Portuguese (BR), Portuguese (PT), Chinese Simplified
- EUPSWLANG integration for portal language switching
