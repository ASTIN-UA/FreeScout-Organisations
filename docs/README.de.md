# OrgPortal — Organisationsportal für FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Ein FreeScout-Modul, das das Konzept von **Organisationen** (Unternehmen/Teams) zu Kunden hinzufügt, das End-User Portal für Manager erweitert und ein Organisations-Abzeichen auf Tickets und Kanban-Karten anzeigt.

**Mindest-FreeScout-Version:** 1.8.147  
**Abhängigkeiten:** keine erforderlich  
**Optional:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API und Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

🌐 **Sprache:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Funktionen

### Organisationsverwaltung (Admin)
- **Verwalten → Organisationen** — vollständiges CRUD: Erstellen, Bearbeiten, Löschen von Organisationen
- **Postfach-Bindung** — eine Organisation kann **global** (in allen Postfächern sichtbar) oder **an ein bestimmtes Postfach gebunden** sein; das entsprechende Label wird in der Organisationsliste angezeigt
- Kunden Organisationen mit Rollenauswahl zuweisen: `Mitglied` oder `Manager`
- **Mitglieder-Rolle ändern** direkt in der Tabelle (ohne Entfernen und erneutes Hinzufügen)
- Kundensuche mit Autovervollständigung nach Name oder E-Mail; Kunden, die bereits in einer Organisation sind, werden aus den Ergebnissen ausgeschlossen
- E-Mail des Mitglieds wird unter dem Namen in der Mitgliedertabelle angezeigt
- Ein Kunde — eine Organisation (erzwungen auf Datenbank- und API-Ebene)
- **Abzeichen-Farbe** — visuelle Palette mit 12 Farben im Organisations-Bearbeitungsformular; Standard ist grau

### Benutzerberechtigungen
- Neue Berechtigung **"Organisations-Management zulassen"** — Nicht-Admins mit dieser Berechtigung erhalten Zugriff auf die Listen-, Erstell- und Bearbeitungsseiten von Organisationen
- Das Löschen von Organisationen bleibt Admins vorbehalten

### Kundenkarte
- **Organisation** Feld im Kundenbearbeitungsformular — Organisation und Rolle auswählen
- **Organisationstickets** Schaltfläche — öffnet eine Suche nach allen Tickets der Organisation

### Organisations-Abzeichen auf Tickets
- Angezeigt unter dem Betreff auf der Ticket-Seite und vor dem Namen in der Gesprächsliste
- Anklickbar — öffnet eine Suche nach allen Tickets dieser Organisation
- Die Abzeichen-Farbe wird durch die Organisations-Einstellung bestimmt (Standard grau)
- Aktivieren/deaktivieren **pro Postfach** über **Postfach-Einstellungen → OrgPortal**; globaler Wert wird als Fallback verwendet

### Organisations-Abzeichen auf Kanban-Karten
- Angezeigt nach dem Nachrichtenzähler auf jeder Karte
- Anklickbar — führt zur Organisationssuche
- Farbe entspricht der Organisations-Einstellung
- **Organisation** Filter eingebaut in das Standard-Kanban-Filter-Dropdown: Modal mit Kontrollkästchen, ähnlich dem Tags-Filter; Status wird zwischen Navigationen beibehalten
- Aktivieren/deaktivieren **pro Postfach** über **Postfach-Einstellungen → OrgPortal**

### Organisationssuchfilter
- Erweitert die Standard-FreeScout-Suche mit einem **Organisation** Filter
- Zeigt alle Tickets von Kunden an, die zur gewählten Organisation gehören

### End-User Portal — Manager-Zugriff *(optional)*

Ein Organisations-Manager erhält erweiterten Zugriff über EUP:

- **Unternehmenstickets** Eintrag in der Portal-Navigation
- Unternehmenstickets-Tabelle mit Spalten:
  - **#** und **Betreff** mit Ellipsis-Kürzung und Tooltip beim Hover
  - **Verantwortlich** — beauftragter Agent
  - **Autor** — der Kunde, der das Ticket öffnete; Klick filtert Tickets nach Autor innerhalb der Organisation
  - **Status** — Aktiv / Ausstehend / Geschlossen / Spam mit Symbolen
  - **Status** — Name der Kanban-Spalte (mit benutzerdefiniertem Label, falls konfiguriert); nur angezeigt, wenn das Kanban-Modul aktiv ist
  - **Aktualisiert** — Datum und Uhrzeit der letzten Antwort
- Suche nach Ticket-Betreff
- Filter nach Kanban-Status (konfigurierbar über **Postfach-Einstellungen → OrgPortal**)
- Antwort auf Ticket mit **Anhang** Unterstützung (Drag & Drop, mehrere Dateien)
- **Ticket schließen** — Manager kann ein Ticket schließen; eine neue Antwort öffnet es automatisch wieder
- Ticket-Autor ändern — Ticket einem anderen Organisations-Mitglied zuweisen
- **Org-Einstellungen** Seite für die Konfiguration von E-Mail-Benachrichtigungen
- Ticket-Zugriff ist **streng auf das aktuelle Postfach beschränkt** (Organisation in ein anderes Postfach kopiert — Portal 403)

### E-Mail-Benachrichtigungen *(optional)*
- Manager mit aktivierter Option erhalten eine E-Mail, wenn ein neues Ticket von einem Organisations-Mitglied erstellt wird
- Verwendet den Mail-Treiber des entsprechenden Postfachs

### Postfach-Einstellungen

**Postfach-Einstellungen → OrgPortal** (pro Postfach):

| Option | Beschreibung |
|--------|------------|
| Abzeichen auf Ticket-Seite anzeigen | Abzeichen in diesem Postfach aktivieren/deaktivieren |
| Abzeichen auf Kanban-Karten anzeigen | Abzeichen in diesem Postfach aktivieren/deaktivieren |
| Unternehmensticket-Status Filter | Wählen Sie Kanban-Spalten aus, die als Kontrollkästchen auf der Ticket-Seite angezeigt werden; benutzerdefiniertes Label für jeden Filter |

---

### REST API *(optional, erfordert API und Webhooks)*

Authentifizierung — `X-FreeScout-API-Key` Header oder `api_key` Query-Parameter.

> **Interaktive Dokumentation** (ReDoc) ist auf der Seite **Verwalten → API & Webhooks** (Link "OrgPortal API Dokumentation") oder direkt unter `/orgportal/admin/api-docs` verfügbar.

| Methode | Endpunkt | Beschreibung |
|---------|----------|------------|
| `GET` | `/api/organizations` | Organisationen auflisten (Paginierung, Postfach-Filter) |
| `POST` | `/api/organizations` | Organisation erstellen |
| `GET` | `/api/organizations/{id}` | Organisation mit Mitgliedern abrufen |
| `PUT` | `/api/organizations/{id}` | Organisation aktualisieren |
| `DELETE` | `/api/organizations/{id}` | Organisation löschen |
| `GET` | `/api/customers/{id}/organization` | Organisation des Kunden |
| `PUT` | `/api/customers/{id}/organization` | Kundenmitgliedschaft setzen/aktualisieren |
| `DELETE` | `/api/customers/{id}/organization` | Kunde aus Organisation entfernen |

#### Antwortcodes

| Code | Bedeutung |
|------|-----------|
| `200` | Erfolg oder No-Op (nichts geändert) |
| `201` | Ressource erstellt; `Resource-ID` Header enthält die ID |
| `400` | Validierungsfehler — Details in `_embedded.errors` |
| `401` | Ungültiger oder fehlender API-Schlüssel |
| `404` | Ressource nicht gefunden |
| `409` | Konflikt — Kunde gehört bereits zu einer anderen Organisation |

---

#### GET /api/organizations

**Query-Parameter**

| Parameter | Typ | Standard | Beschreibung |
|-----------|-----|:-------:|------------|
| `page` | integer | `1` | Seitennummer |
| `pageSize` | integer | `25` | Datensätze pro Seite (max 100) |
| `mailboxId` | integer | — | Postfach-Filter: gibt globale Organisationen + an dieses Postfach gebundene zurück |

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

**Request Body**

| Feld | Typ | Erforderlich | Beschreibung |
|------|-----|:----------:|------------|
| `name` | string | ✅ | Organisationsname (max 255 Zeichen, eindeutig) |
| `mailboxId` | integer\|null | — | Postfach-ID oder `null` / weglassen für globale Organisation |

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

#### PUT /api/organizations/{id}

**Request Body**

| Feld | Typ | Erforderlich | Beschreibung |
|------|-----|:----------:|------------|
| `name` | string | ✅ | Neuer Organisationsname (max 255 Zeichen, eindeutig) |
| `mailboxId` | integer\|null | — | Neues Postfach; `null` — global machen; weglassen — unverändert lassen |

**200 OK**
```json
{"success": true, "message": "Organization updated."}
```

---

#### DELETE /api/organizations/{id}

**200 OK** *(alle Mitglieder werden kaskadierend gelöscht)*
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

Weist einen Kunden einer Organisation zu oder aktualisiert seine Rolle. **Ein Kunde — eine Organisation**: Wenn der Kunde bereits Mitglied einer *anderen* Organisation ist, wird die Anfrage mit `409 Conflict` abgelehnt. Zum Transferieren — erst die aktuelle Mitgliedschaft via `DELETE` entfernen.

**Request Body**

| Feld | Typ | Erforderlich | Beschreibung |
|------|-----|:----------:|------------|
| `organizationId` | integer | ✅ | Organisations-ID |
| `role` | string | — | `"member"` (Standard) oder `"manager"` |

**201 Created** *(neue Mitgliedschaft)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(Rolle aktualisiert oder No-Op)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(Kunde bereits in einer anderen Organisation)*
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

## Installation

1. Kopieren Sie den Ordner `OrgPortal` in `Modules/` Ihres FreeScout
2. In der Admin-Panel: **Verwalten → Module → OrgPortal → Aktivieren**
3. Führen Sie Migrationen aus:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Cache löschen:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Updates

OrgPortal unterstützt **automatische Updates** über den integrierten Modul-Update-Mechanismus von FreeScout.

Wenn eine neue Version verfügbar ist, erscheint ein Banner auf der Seite **Verwalten → Module**. Klicken Sie auf **Jetzt aktualisieren** — FreeScout lädt und installiert die neueste Version automatisch herunter.

Keine manuelle Dateikopie erforderlich.

---

## Modul-Kompatibilität

| Modul | Status |
|-------|--------|
| End-User Portal ≥ 1.0.85 | Optional — Portal-Funktionen für Manager |
| API und Webhooks ≥ 1.0.80 | Optional — REST API Endpunkte |
| Kanban ≥ 1.0.23 | Optional — Abzeichen, Filter, "Status" Spalte in Unternehmenstickets |
| Custom Fields | Kompatibel |
| Workflows | Kompatibel |
| Tags | Kompatibel |

---

## Konfiguration

### Global (**Verwalten → OrgPortal-Einstellungen**)

| Option | Standard |
|--------|----------|
| Abzeichen auf Ticket-Seite anzeigen | ✅ |
| Abzeichen auf Kanban-Karten anzeigen | ✅ |

### Pro Postfach (**Postfach-Einstellungen → OrgPortal**)

Überschreibt globale Werte für das spezifische Postfach.

| Option | Beschreibung |
|--------|------------|
| Abzeichen auf Ticket-Seite anzeigen | Abzeichen in Gesprächsliste und auf Ticket-Seite |
| Abzeichen auf Kanban-Karten anzeigen | Abzeichen auf Kanban-Karten |
| Unternehmensticket-Status Filter | Kanban-Spalten als Kontrollkästchen auf der Seite Unternehmenstickets; jeder Filter hat ein für Portal-Benutzer sichtbares benutzerdefiniertes Label |

---

## Übersetzungen

Unterstützte Sprachen: **English** (`en`), **Українська** (`uk`), **Română** (`ro`), **Georgian** (`ka`), **Deutsch** (`de`), **Français** (`fr`), **Español** (`es`), **Italiano** (`it`), **Čeština** (`cs`), **Slovenčina** (`sk`), **Polski** (`pl`), **Русский** (`ru`), **Nederlands** (`nl`), **Norsk** (`no`), **Dansk** (`da`), **Svenska** (`sv`), **Suomi** (`fi`), **Português BR** (`pt-BR`), **Português PT** (`pt-PT`), **中文 (简体)** (`zh-CN`).

Dateien: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### EUPSWLANG Integration

Das Modul funktioniert korrekt mit [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): die im Portal ausgewählte Sprache gilt auch für OrgPortal-Zeichenketten.

Damit eine Sprache in der EUPSWLANG-Liste angezeigt wird, muss die entsprechende Datei `Modules/EndUserPortal/Resources/lang/{locale}.json` existieren. Dateien für **Română** (`ro`) sind im Paket enthalten; **Georgian** (`ka`) wird nur im Admin-Bereich unterstützt (keine System-Unterstützung im FreeScout Core).

> **Technisches Detail:** `ReapplyEupLocale` Middleware (zuletzt in der Portal-Routengruppe registriert) stellt die Locale wieder her, nachdem FreeScouts `Localize` Middleware sie sonst auf die System-Standardsprache zurücksetzen würde.

---

## Lizenz

[MIT](../LICENSE) — © 2026 ASTIN-UA
