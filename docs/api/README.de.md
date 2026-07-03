# OrgPortal REST API

[← Zurück zur README](../../README.md)

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

*Optional — erfordert das Modul [API and Webhooks](https://freescout.net/module/api-webhooks/).*

Authentifizierung — Header `X-FreeScout-API-Key` oder Query-Parameter `api_key`.

> **Interaktive Dokumentation** (ReDoc) ist auf der Seite **Verwalten → API & Webhooks** verfügbar (Link "OrgPortal API Docs") oder direkt unter `/orgportal/admin/api-docs`.

## Endpunkte

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Organisationen auflisten (Paginierung, Postfachfilter) |
| `POST` | `/api/organizations` | Organisation erstellen |
| `GET` | `/api/organizations/{id}` | Organisation mit Mitgliedern und Struktureinheiten abrufen |
| `PUT` | `/api/organizations/{id}` | Organisation aktualisieren (Name, Farbe, Postfach, isActive) |
| `DELETE` | `/api/organizations/{id}` | Organisation löschen |
| `GET` | `/api/organizations/{id}/members` | Organisationsmitglieder auflisten |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Ein einzelnes Mitglied abrufen |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Mitglied aktualisieren (Rolle, Struktureinheit, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Mitglied entfernen |
| `GET` | `/api/organizations/{id}/tags` | Tag-Bindungen auflisten (erfordert Tags-Modul) |
| `PUT` | `/api/organizations/{id}/tags` | Alle Tag-Bindungen ersetzen (erfordert Tags-Modul) |
| `GET` | `/api/organizations/{id}/units` | Struktureinheiten auflisten |
| `POST` | `/api/organizations/{id}/units` | Struktureinheit erstellen |
| `PUT` | `/api/units/{unitId}` | Struktureinheit umbenennen |
| `DELETE` | `/api/units/{unitId}` | Struktureinheit löschen (Mitglieder nicht zugewiesen, Manager herabgestuft) |
| `GET` | `/api/customers/{id}/organization` | Organisations-Mitgliedschaft des Kunden |
| `PUT` | `/api/customers/{id}/organization` | Kundenmitgliedschaft festlegen/aktualisieren |
| `DELETE` | `/api/customers/{id}/organization` | Kunde aus Organisation entfernen |

## Antwortcodes

| Code | Meaning |
|------|---------|
| `200` | Erfolg |
| `201` | Ressource erstellt; Header `Resource-ID` enthält die ID |
| `400` | Validierungsfehler — Details in `_embedded.errors` |
| `401` | Ungültiger oder fehlender API-Schlüssel |
| `404` | Ressource nicht gefunden |
| `409` | Konflikt — Kunde hat bereits eine aktive Mitgliedschaft in einer anderen Organisation |
| `422` | Verstoß gegen Geschäftsregeln — z.B. Löschen einer Organisation, die noch Mitglieder oder Tickets hat |
| `503` | Erforderliches Modul (z.B. Tags) ist nicht aktiv |

---

## Organisationen

### GET /api/organizations

**Abfrageparameter**

| Parameter | Type | Default | Description |
|-----------|------|:-------:|-------------|
| `page` | integer | `1` | Seitenzahl |
| `pageSize` | integer | `25` | Datensätze pro Seite (max 100) |
| `mailboxId` | integer | — | Postfachfilter: gibt globale Organisationen + an dieses Postfach gebundene zurück |

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

**Anfragetext**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Organisationsname (max 255 Zeichen, eindeutig) |
| `mailboxId` | integer\|null | — | Postfach-ID oder `null` / weglassen für globale Organisation |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(Header `Resource-ID: 1`)*
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

Gibt die Organisation mit eingebetteten **Mitgliedern** und **Struktureinheiten** zurück.

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

**Mitgliederfelder**

| Field | Type | Description |
|-------|------|-------------|
| `unitId` | integer\|null | Struktureinheit, zu der das Mitglied gehört, oder `null` für die gesamte Organisation |
| `role` | string | `"member"` oder `"manager"`. Ein **Unit-Manager** ist `role: "manager"` mit nicht-null `unitId`; ein **globaler Manager** ist `role: "manager"` mit `unitId: null`. Der String `"unit_manager"` existiert nicht in der API — das Übergeben gibt 400 zurück. |
| `canManageOrg` | boolean | Ob dieser Manager andere im Portal zum globalen Manager befördern darf |
| `isActive` | boolean | Aktive Mitgliedschaft; inaktive Mitglieder erhalten keine Ticketzuweisungen oder Benachrichtigungen |
| `notifyOnNewTicket` | boolean | Flag für neue Ticket-Benachrichtigungen pro Mitglied |

---

### PUT /api/organizations/{id}

**Anfragetext**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Neuer Organisationsname (max 255 Zeichen, eindeutig) |
| `color` | string\|null | — | Abzeichenfarbe als hex (`"#ff0000"`), `null` zum Zurücksetzen auf Standardgrau; weglassen, um aktuell zu behalten |
| `mailboxId` | integer\|null | — | Neues Postfach; `null` — global machen; weglassen — unverändert lassen |
| `isActive` | boolean | — | `false` um Organisation zu deaktivieren; weglassen, um aktuell zu behalten |

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

Blockiert, wenn die Organisation aktive Mitglieder oder Tickets hat. Entfernen Sie zunächst alle Mitglieder und weisen Sie alle Tickets zu/löschen Sie sie.

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

## Organisationsmitglieder

### GET /api/organizations/{id}/members

Gibt eine Liste aller Mitgliedsdatensätze der Organisation zurück.

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

Gibt einen einzelnen Mitgliedsdatensatz zurück.

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

Aktualisiert die Rolle eines Mitglieds, Struktureinheitenzuweisung, canManageOrg-Flag oder Aktivitätsstatus. Nur im Text vorhandene Felder werden aktualisiert (Teilupdate).

**Anfragetext**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `role` | string | — | `"member"` oder `"manager"`. Zum Erstellen eines **Unit-Managers**: `role: "manager"` + `unitId: <id>`. Zum Erstellen eines **globalen Managers**: `role: "manager"` + `unitId: null`. |
| `unitId` | integer\|null | — | Struktureinheit (muss zu dieser Organisation gehören), oder `null` zum Aufheben der Zuweisung |
| `canManageOrg` | boolean | — | Gewähren Sie globale Manager-Rechte im Portal |
| `isActive` | boolean | — | `false` um zu deaktivieren, ohne zu entfernen |

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

Entfernt ein Mitglied aus der Organisation. Blockiert, wenn das Mitglied Tickets in dieser Organisation hat — verwenden Sie stattdessen `PUT` mit `isActive: false`, um es zu deaktivieren ("entlassen") und den Ticketverlauf zu erhalten.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

**422 Unprocessable Entity** *(member has tickets)*
```json
{"message": "Cannot remove this member: they have tickets in this organization. Deactivate them instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```

---

## Organisations-Tags

> Erfordert das aktive Modul [Tags](https://freescout.net/module/tags/). Gibt `503` zurück, wenn das Modul nicht installiert ist.

### GET /api/organizations/{id}/tags

Gibt alle Tag-Bindungen für die Organisation zurück. Jede Bindung beschränkt optional ein Tag auf eine bestimmte Struktureinheit.

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

**Vollständiger Austausch** — ersetzt alle vorhandenen Tag-Bindungen für diese Organisation mit der bereitgestellten Liste. Senden Sie ein leeres Array `[]`, um alle Bindungen zu entfernen.

**Anfragetext** — ein JSON-Array von Tag-Bindungsobjekten:

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `tagId` | integer | ✅ | FreeScout-Tag-ID |
| `unitId` | integer\|null | — | Begrenzen Sie das Tag auf eine bestimmte Einheit, oder weglassen/`null` für organisationsweit |

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

## Struktureinheiten

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

**Anfragetext**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Struktureinheitsname (eindeutig in der Organisation) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(Header `Resource-ID: 2`)*
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

**Anfragetext**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Neuer Struktureinheitsname (eindeutig in der Organisation) |

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

Löscht die Struktureinheit. Manager mit Geltungsbereich auf diese Einheit werden zu `member` herabgestuft; alle Mitglieder der Einheit werden nicht zugewiesen (ihre `unitId` wird `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Kundenmitgliedschaft

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

Weist einen Kunden einer Organisation zu oder aktualisiert seine Mitgliedschaft. **Eine aktive Mitgliedschaft pro Kunde**: Wenn der Kunde bereits eine *aktive* Mitgliedschaft in einer *anderen* Organisation hat, wird die Anfrage mit `409 Conflict` abgelehnt. Zum Übertragen — deaktivieren oder entfernen Sie zuerst die aktuelle Mitgliedschaft über `DELETE`.

**Anfragetext**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | Organisations-ID |
| `role` | string | — | `"member"` (Standard) oder `"manager"` |
| `unitId` | integer\|null | — | Struktureinheit (muss zur Zielorganisation gehören), oder `null` für die gesamte Organisation |
| `canManageOrg` | boolean | — | Gewähren Sie diesem Manager das Recht, andere zu globalem Manager zu befördern (Standard `false`) |
| `isActive` | boolean | — | `false` um als inaktiv zu erstellen/aktualisieren (Standard `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(neue Mitgliedschaft)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(Mitgliedschaft aktualisiert)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(Kunde ist bereits in einer anderen Organisation aktiv)*
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

Entfernt nur die **aktive** Mitgliedschaft des Kunden. Historische (deaktivierte) Mitgliedschaften in anderen Organisationen bleiben unverändert erhalten. Blockiert, wenn der Kunde Tickets in dieser Organisation hat — verwenden Sie stattdessen `PUT` mit `isActive: false`, um zu deaktivieren und den Ticketverlauf zu erhalten.

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```

**422 Unprocessable Entity** *(customer has tickets)*
```json
{"message": "Cannot remove this membership: the customer has tickets in this organization. Deactivate instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```
