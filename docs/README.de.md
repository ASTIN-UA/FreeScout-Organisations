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

OrgPortal bietet eine vollständige REST-API zur Verwaltung von Organisationen, Struktureinheiten und Kundenmitgliedschaften — Authentifizierung über den Header `X-FreeScout-API-Key` oder den Query-Parameter `api_key`.

📖 **Vollständige API-Referenz → [docs/api/README.de.md](api/README.de.md)** (alle Endpunkte, Anfrage-/Antwortbeispiele, Fehlercodes)

Eine interaktive ReDoc-Dokumentation ist ebenfalls verfügbar unter **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

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

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

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
