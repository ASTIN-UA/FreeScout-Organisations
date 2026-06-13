# OrgPortal REST API

[← Zurück zur README](../README.de.md)

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

*Optional — erfordert das [API und Webhooks](https://freescout.net/module/api-webhooks/) Modul.*

Authentifizierung — `X-FreeScout-API-Key` Header oder `api_key` Query-Parameter.

> **Interaktive Dokumentation** (ReDoc) ist auf der Seite **Verwalten → API & Webhooks** verfügbar (Link "OrgPortal API Dokumentation") oder direkt unter `/orgportal/admin/api-docs`.

## Endpunkte

| Methode | Endpunkt | Beschreibung |
|---------|----------|-------------|
| `GET` | `/api/organizations` | Organisationen auflisten (Paginierung, Postfach-Filter) |
| `POST` | `/api/organizations` | Organisation erstellen |
| `GET` | `/api/organizations/{id}` | Organisation mit Mitgliedern und Struktureinheiten abrufen |
| `PUT` | `/api/organizations/{id}` | Organisation aktualisieren |
| `DELETE` | `/api/organizations/{id}` | Organisation löschen |
| `GET` | `/api/organizations/{id}/units` | Struktureinheiten auflisten |
| `POST` | `/api/organizations/{id}/units` | Struktureinheit erstellen |
| `PUT` | `/api/units/{unitId}` | Struktureinheit umbenennen |
| `DELETE` | `/api/units/{unitId}` | Struktureinheit löschen (Mitglieder deaktiviert, Manager degradiert) |
| `GET` | `/api/customers/{id}/organization` | Organisations-Mitgliedschaft des Kunden |
| `PUT` | `/api/customers/{id}/organization` | Kundenmitgliedschaft setzen/aktualisieren |
| `DELETE` | `/api/customers/{id}/organization` | Kunde aus Organisation entfernen |

## Antwortcodes

| Code | Bedeutung |
|------|-----------|
| `200` | Erfolg oder No-Op (nichts geändert) |
| `201` | Ressource erstellt; `Resource-ID` Header enthält die ID |
| `400` | Validierungsfehler — Details in `_embedded.errors` |
| `401` | Ungültiger oder fehlender API-Schlüssel |
| `404` | Ressource nicht gefunden |
| `409` | Konflikt — Kunde hat bereits eine aktive Mitgliedschaft in einer anderen Organisation |

---

## Organisationen

### GET /api/organizations

**Query-Parameter**

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|:-------:|-------------|
| `page` | integer | `1` | Seitennummer |
| `pageSize` | integer | `25` | Datensätze pro Seite (max 100) |
| `mailboxId` | integer | — | Postfach-Filter: gibt globale Organisationen + an dieses Postfach gebundene zurück |

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

**Request Body**

| Feld | Typ | Erforderlich | Beschreibung |
|------|-----|:----------:|-------------|
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
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Gibt die Organisation mit ihren eingebetteten **Mitgliedern** und **Struktureinheiten** zurück.

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

**Mitglieder-Felder**

| Feld | Typ | Beschreibung |
|------|-----|-------------|
| `unitId` | integer\|null | Struktureinheit, zu der das Mitglied gehört, oder `null` für die gesamte Organisation |
| `role` | string | `member` oder `manager` |
| `canManageOrg` | boolean | Ob dieser Manager das Recht hat, andere zu globalen Managern zu befördern |
| `isActive` | boolean | Aktive Mitgliedschaft; inaktive Mitglieder erhalten keine Ticket-Zuweisungen oder Benachrichtigungen |
| `notifyOnNewTicket` | boolean | Legacy pro-Mitglied Benachrichtigungsflag für neue Tickets |

---

### PUT /api/organizations/{id}

**Request Body**

| Feld | Typ | Erforderlich | Beschreibung |
|------|-----|:----------:|-------------|
| `name` | string | ✅ | Neuer Organisationsname (max 255 Zeichen, eindeutig) |
| `mailboxId` | integer\|null | — | Neues Postfach; `null` — global machen; weglassen — unverändert lassen |

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

Wenn sich nichts ändert, lautet die Antwortnachricht `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(alle Mitglieder werden kaskadierend gelöscht)*
```json
{"success": true, "message": "Organization deleted."}
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

**Request Body**

| Feld | Typ | Erforderlich | Beschreibung |
|------|-----|:----------:|-------------|
| `name` | string | ✅ | Name der Struktureinheit (eindeutig innerhalb der Organisation) |

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

**Request Body**

| Feld | Typ | Erforderlich | Beschreibung |
|------|-----|:----------:|-------------|
| `name` | string | ✅ | Neuer Name der Struktureinheit (eindeutig innerhalb der Organisation) |

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

Löscht die Struktureinheit. Manager mit Bereich auf diese Einheit sind werden zu `member` degradiert; alle Mitglieder der Einheit werden deaktiviert (ihr `unitId` wird `null`).

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

Weist einen Kunden einer Organisation zu oder aktualisiert seine Mitgliedschaft. **Eine aktive Mitgliedschaft pro Kunde**: Wenn der Kunde bereits eine *aktive* Mitgliedschaft in einer *anderen* Organisation hat, wird die Anfrage mit `409 Conflict` abgelehnt. Zum Transferieren — erst die aktuelle Mitgliedschaft via `DELETE` deaktivieren oder entfernen.

**Request Body**

| Feld | Typ | Erforderlich | Beschreibung |
|------|-----|:----------:|-------------|
| `organizationId` | integer | ✅ | Organisations-ID |
| `role` | string | — | `"member"` (Standard) oder `"manager"` |
| `unitId` | integer\|null | — | Struktureinheit (muss zu der Zielorganisation gehören), oder `null` für die gesamte Organisation |
| `canManageOrg` | boolean | — | Gewähren Sie diesem Manager das Recht, andere zu globalen Managern zu befördern (Standard `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(neue Mitgliedschaft)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(Mitgliedschaft aktualisiert)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(Kunde hat bereits eine aktive Mitgliedschaft in einer anderen Organisation)*
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
