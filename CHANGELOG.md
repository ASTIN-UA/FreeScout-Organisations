# Changelog

All notable changes to OrgPortal are documented here.

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
