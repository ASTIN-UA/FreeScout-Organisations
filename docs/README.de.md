# OrgPortal — B2B-Organisationsverwaltungsmodul für FreeScout

[← Zurück zu README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B-Modul" width="140" align="right">

**OrgPortal** ist ein FreeScout-Modul, das Ihrem Helpdesk ein vollständiges **B2B-Organisationsmanagement** hinzufügt: Kunden in Unternehmen gruppieren, Abteilungshierarchien definieren, Unternehmensmanagern ein Self-Service-Portal bereitstellen und Benachrichtigungen automatisieren — alles innerhalb von FreeScout, ohne externe Tools.

> Suchen Sie eine Möglichkeit, Unternehmenskonten in FreeScout zu verwalten? Ihren Firmenkunden ein eigenes Support-Portal zu geben? Zu steuern, welche Tickets jeder B2B-Kontakt je nach Rolle und Abteilung sehen kann? OrgPortal löst all das.

**Kompatibel mit:** FreeScout 1.8.147+  
**Optionale Integrationen:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Auch verfügbar in:**
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

## Was OrgPortal zu FreeScout hinzufügt

FreeScout ist auf einzelne Kunden ausgerichtet — jede E-Mail stammt von einer Person, und es gibt kein eingebautes Konzept für das Unternehmen, für das diese Person arbeitet. Für B2C-Helpdesks funktioniert das gut. Für B2B reicht es nicht aus.

OrgPortal schließt diese Lücke:

- **Unternehmenskonten** — Kunden in Organisationen gruppieren mit Name, Farb-Badge, Postfach-Bindung und Aktiv/Inaktiv-Status
- **Abteilungshierarchien** — Organisationen in strukturelle Einheiten aufteilen (Abteilungen, Niederlassungen, Teams); jedes Mitglied ist auf seine Einheit beschränkt
- **Rollenbasierter Zugriff** — `member` sieht nur eigene Tickets; `unit_manager` sieht die gesamte Einheit; `manager` sieht die gesamte Organisation
- **Unternehmens-Self-Service-Portal** — Manager sehen alle Unternehmenstickets, antworten, schließen, ordnen Autoren neu zu und verwalten Benachrichtigungseinstellungen, ohne Ihr Team zu kontaktieren
- **Permanente Ticket-Zuordnung** — jedes Ticket wird bei der Erstellung als Snapshot der Organisation zugeordnet; historische Berichte überleben Änderungen in der Kundenliste
- **Mehrsprachige Benachrichtigungen** — automatisierte E-Mail-Benachrichtigungen in der jeweiligen Sprache des Managers, mit pro-Locale-Vorlagen und einem integrierten WYSIWYG-Editor
- **REST API** — Mitgliedschaften aus Ihrem CRM synchronisieren, Onboarding automatisieren, Tags programmgesteuert verwalten

---

## Organisationen

*Ein Ort für alles rund um ein Unternehmenskonto.*

**Verwalten → Organisationen** öffnet eine Registerkartenoberfläche mit drei Bereichen: Organisationen, Vorlagen und System.

### Organisationsliste

- **Erstellen, bearbeiten, löschen, aktivieren/deaktivieren** von Organisationen
- **Statusfilter** — zwischen Aktiv / Inaktiv / Alle mit einer Radiogruppe umschalten; filtert die Tabelle sofort clientseitig
- **Live-Suche** — beginnt ab 2+ Zeichen zu filtern, kein Neuladen der Seite
- **Farbkodierte Badges** — interaktiver Farbwähler mit 12 Farbfeldern und einer Live-Badge-Vorschau neben dem Wähler; Badge erscheint auf jedem Ticket und jeder Kanban-Karte
- Klick auf den Badge oder die Ticket-Anzahl öffnet eine FreeScout-Suche gefiltert auf diese Organisation
- **Postfach-Bindung** — Organisationen können global (alle Postfächer) oder auf ein bestimmtes Postfach beschränkt sein
- **Tags-Spalte** — zeigt ✓/✗, ob FreeScout-Tags an die Organisation gebunden sind (Tags-Modul erforderlich); Tags werden im Bearbeitungsformular mit einem Chip-Widget und Autovervollständigungssuche zugewiesen
- **Ticket-Anzahl-Spalte** — Gesamtgespräche pro Organisation; anklickbarer Link zu vollständigen Suchergebnissen
- **Mitgliederanzahl**-Spalte
- **Aktivieren / Deaktivieren** — ein Konto aussetzen, ohne die History zu verlieren; erfordert, dass Org Snapshot aktiviert ist (Schaltfläche ist mit einem Tooltip deaktiviert, wenn nicht)
- **Löschen** — nur verfügbar, wenn die Organisation 0 Mitglieder und 0 Tickets hat (Sicherheitssperre)
- Alle Lösch- und Deaktivierungsaktionen erfordern eine Bestätigung

![Organisationsliste — Statusfilter, Live-Suche, Farb-Badges, Tags, Ticket-Anzahl](docs/screenshots/org-list.png)

### Organisationsbearbeitungsformular

- **Name** und **Postfach-Bindung**
- **Farbwähler** — 12 Farbfelder mit Live-Badge-Vorschau
- **Tags** — Chip-Widget: tippen zum Suchen vorhandener FreeScout-Tags, klicken zum Hinzufügen, × zum Entfernen
- **Mitgliedertabelle** — pro Mitglied: Name, Rolle, strukturelle Einheit, `can_manage_org`-Checkbox (gewährt Administratorzugriff auf Organisationen ohne vollständige Admin-Rechte), Aktiv/Inaktiv-Umschalter
- **Strukturelle Einheiten-Panel** — Einheiten direkt im Bearbeitungsformular erstellen und umbenennen; Mitglieder werden in der gleichen Ansicht Einheiten zugewiesen
- **Mitglied hinzufügen** — füllt automatisch vorhandene nicht zugeordnete Gespräche für diesen Kunden nach

![Organisationsbearbeitung — Farbwähler, Tag-Chips, Mitgliedertabelle mit Rollen und Einheiten](docs/screenshots/org-edit.png)

### Kundenprofilintegration

- **Organisationsfeld im FreeScout-Kundenbearbeitungsformular** — Live-Autovervollständigungssuche für Organisationen; Rollen-Dropdown erscheint nach Auswahl einer Organisation; × Schaltfläche zum Entfernen
- **"Org-Tickets anzeigen"** Schnelllink im Kundenformular
- **Org-Info-Block in der Admin-Ticket-Seitenleiste** — Organisationsname (anklickbarer Link zur Org-Bearbeitungsseite), strukturelle Einheit und Mitgliedsrolle; Sichtbarkeit pro Postfach in den Einstellungen umschalten
- **Eine aktive Mitgliedschaft pro Kunde** — ein Kunde kann keiner zweiten Organisation hinzugefügt werden, wenn er eine aktive Mitgliedschaft hat; inaktive/archivierte Mitgliedschaften sind erlaubt

![Kundenbearbeitung — Organisationsfeld mit Autovervollständigung und Rollenwähler](docs/screenshots/customer-org-field.png)

---

## Strukturelle Einheiten — Zugriffskontrolle auf Abteilungsebene

*Unterstützung großer Unternehmen mit komplexen internen Hierarchien.*

Organisationen können in unbegrenzt viele **strukturelle Einheiten** unterteilt werden (Abteilungen, Niederlassungen, Regionalbüros, Projektteams):

- Einheiten im Admin-Org-Bearbeitungsformular erstellen, umbenennen und löschen, oder direkt aus dem Portal (nur globale Manager)
- Mitglieder Einheiten zuweisen — jedes Mitglied gehört zu einer Einheit
- **Löschen einer Einheit** stuft automatisch die `unit_manager`-Mitglieder auf `member` herab

**Drei Rollenebenen:**

| Rolle | Zugriffsbereich |
|-------|----------------|
| `member` | Nur eigene Tickets |
| `unit_manager` | Alle Tickets innerhalb ihrer strukturellen Einheit |
| `manager` (global) | Alle Tickets in der gesamten Organisation |

- Einheitsmanager haben vollständige Portalfähigkeiten — Antworten, Anhänge, Autoren-Neuzuweisung, Schließen/Wiedereröffnen, Benachrichtigungsverwaltung — strikt auf ihre Einheit beschränkt
- Ticket-Zugriff und Benachrichtigungslieferung werden an Einheitsgrenzen durchgesetzt

![Organisationsbearbeitung — Mitglieder mit Rollen und Einheiten, Einheitsverwaltungs-Panel](docs/screenshots/org-edit.png)

---

## Org Snapshot — Permanente Ticket-Zuordnung

*Zuverlässige historische Berichterstattung auch wenn sich Ihre Kundenliste ändert.*

Wenn ein Ticket erstellt wird, zeichnet OrgPortal den Organisationskontext als permanenten Snapshot auf:

- `org_id`, `org_unit_id` und `org_attributed_at` werden beim Erstellen in das Gespräch geschrieben
- **Unveränderlich** — wenn ein Kunde später eine Organisation verlässt, bleiben seine historischen Tickets dieser Organisation zugeordnet; Berichte werden nie unterbrochen
- **Mitglied hinzufügen** löst automatisches Nachfüllen der vorhandenen nicht zugeordneten Gespräche dieses Kunden aus

### Zuordnungsquelle — drei Modi

Konfiguriert in **Verwalten → Organisationen → System-Tab**:

| Modus | Verhalten |
|-------|-----------|
| `member` | Ticket der Organisation zuordnen, deren Mitglied der Ticket-Autor ist |
| `tag` | Zuerst nach FreeScout-Tag zuordnen, der an eine Org gebunden ist; auf Mitgliedschaft zurückfallen, wenn kein Tag übereinstimmt |
| `tag_only` | Ausschließlich nach Tag zuordnen; Mitgliedschaft wird nicht verwendet |

`tag`- und `tag_only`-Modi sind deaktiviert, wenn das Tags-Modul inaktiv ist.

### Nachfüll-Tools

- **Fortschrittsbalken** — zeigt X / Y zugeordnete Tickets (%) mit einem "Abgeschlossen"-Indikator
- **Vorab-Statistiken** — vor dem Nachfüllen zeigt eine Aufschlüsselung, wie viele Tickets nach Tag vs. nach Mitgliedschaft vs. nicht zugeordnet werden
- **Nachfüllen ausführen**-Schaltfläche — verarbeitet bis zu 2000 Tickets pro Klick; Ergebnis-Zusammenfassung (by_tag / by_member / unmatched) wird danach angezeigt
- **Auto-Cron** (`attribution_cron_enabled`) — plant Nachfüllen alle 5 Minuten, 1000 Tickets pro Lauf, ohne Überschneidung
- **Zuordnung zurücksetzen** — löscht alle Org-Snapshots (Gefahrenaktion, erfordert Bestätigung)
- Befehlszeile: `php artisan orgportal:backfill-attribution`

![System-Tab — Zuordnungsquelle, Fortschrittsbalken, Vorab-Statistiken, Nachfüll-Steuerung](docs/screenshots/attribution-settings.png)

---

## Kanban-Integration

*Halten Sie Ihren visuellen Workflow mit Ihren B2B-Konten abgestimmt.*

- Organisations-Badge auf jeder Kanban-Karte mit der zugewiesenen Farbe des Kontos
- **Organisationsfilter** im Kanban-Filter-Panel — Multi-Select-Modal mit Checkboxen; Filterstatus bleibt bei der Navigation erhalten
- **Mehrsprachige Kanban-Statusfilter-Labels** — geben Sie jeder Kanban-Spalte einen benutzerdefinierten Namen pro Portalsprache; wechseln Sie Locales mit dem Sprachauswähler in den Postfach-Einstellungen; ziehen zum Neuordnen der Filter
- Übersetzte Labels erscheinen sowohl in der Portal-Filterleiste als auch in der **Status**-Spalte der Unternehmensticket-Tabelle; Fallback-Kette: gespeichertes Locale → gespeichertes Englisch → ursprünglicher Spaltenname

![Kanban — Organisations-Badges auf Karten und Org-Filter-Modal](docs/screenshots/kanban-org.png)

---

## Zugriffskontrolle & Berechtigungen

*Delegieren Sie die Organisationsverwaltung, ohne Admin-Zugang zu gewähren.*

- **"Organisationen verwalten erlauben"** (`can_manage_org`) — zwei Ebenen:
  - Als **Benutzerberechtigung** in den Agent-Einstellungen — ermöglicht einem Support-Teamleiter, alle Organisationen ohne Admin-Rechte zu verwalten
  - Als **pro-Mitglied-Flag** im Organisationsbearbeitungsformular — ermöglicht einem bestimmten Org-Mitglied, diese eine Organisation über das Admin-Panel zu verwalten
- **"Benachrichtigungsvorlagen verwalten erlauben"** — separate granulare Berechtigung für die Vorlagenbearbeitung
- Das Löschen von Organisationen bleibt ausschließlich Admins vorbehalten
- Der Portal-Zugang ist strikt pro Postfach begrenzt: ein Manager von Organisation A kann nicht auf Organisation B zugreifen

![Granulare Berechtigungen — Organisationen und Benachrichtigungsvorlagen verwalten erlauben](docs/screenshots/user-permissions.png)

---

## Systemeinstellungen — Verwalten → Organisationen → System-Tab

*Nur-Admin-Steuerung für Zuordnung, Nachfüllen und den Portal-Sprachumschalter.*

Der **System**-Tab ist nur für FreeScout-Administratoren sichtbar.

### Panel 1: Ticket-Zuordnung

Siehe [Org Snapshot](#org-snapshot--permanente-ticket-zuordnung) oben für die vollständige Beschreibung der Zuordnungsmodi, Nachfüll-Tools und Auto-Cron.

### Panel 2: Portal-Sprachumschalter

- **Aktivieren/Deaktivieren** des Sprachumschalters in der End-User Portal-Navigationsleiste
- **Wählen Sie aus den 19 Locales** (Checkbox-Raster); alle sind standardmäßig aktiviert
- Wenn aktiviert, können Manager die Portalsprache wechseln; ihre Wahl wird gespeichert und für Benachrichtigungs-E-Mails verwendet
- Dies ist OrgPortals eingebauter Sprachumschalter — er funktioniert unabhängig von jedem Drittanbieter-Sprachumschaltermodul; beide können koexistieren

![System-Tab — Portal-Sprachumschalter-Panel mit Locale-Checkboxen](docs/screenshots/system-settings.png)

---

## End-User Portal — Self-Service für Unternehmensmanager *(optional)*

*Geben Sie Ihren B2B-Kunden ein Portal, über das sie die Support-Beziehung ihres Unternehmens verwalten können — ohne Ihr Team für jedes Status-Update zu kontaktieren.*

Erfordert das [End-User Portal](https://freescout.net/module/end-user-portal/)-Modul.

### Unternehmenstickets-Dashboard

Ein dedizierter **Unternehmenstickets**-Bereich in der Portal-Navigation mit einer voll ausgestatteten Ticket-Tabelle:

| Spalte | Beschreibung |
|--------|-------------|
| **#** | Ticket-ID |
| **Betreff** | Abgeschnitten mit Tooltip beim Hover |
| **Verantwortlicher** | Zugewiesener Support-Agent |
| **Autor** | Kunde, der das Ticket geöffnet hat; klicken, um nach diesem Autor zu filtern |
| **Status** | Aktiv / Ausstehend / Geschlossen / Spam mit Symbolen |
| **Zustand** | Kanban-Spaltenname in der aktuellen Portalsprache (nur wenn das Kanban-Modul aktiv ist) |
| **Aktualisiert** | Datum und Uhrzeit der letzten Antwort |

**Zwei unabhängige Lesestatus-Indikatoren pro Zeile** — diese verfolgen zwei verschiedene Personen und werden gleichzeitig angezeigt:

| Indikator | Wessen Lesestatus | Was es bedeutet |
|-----------|-------------------|-----------------|
| **Fette Zeile** | Der Manager, der das Portal anzeigt | Manager hat ungelesene Benachrichtigungen für dieses Gespräch — etwas ist passiert, das er noch nicht gesehen hat |
| **👁 Auge-Symbol** | Der Ticket-Autor (der Kunde, der es eingereicht hat) | Der Autor hat die neueste Agenten-Antwort noch nicht geöffnet — nützlich, um zu wissen, ob ein Kunde die Antwort tatsächlich gesehen hat |

Diese zwei Zustände sind vollständig unabhängig: Eine Zeile kann fett sein (Manager hat nicht gelesen), während das Auge fehlt (Autor hat bereits gelesen), oder umgekehrt. Der Manager sieht beides gleichzeitig und erhält so ein vollständiges Bild davon, was auf beiden Seiten des Tickets passiert, ohne es zu öffnen.

**Autorenfilter** — Klicken auf einen Autorennamen aktiviert einen Filter; ein Banner erscheint oben in der Tabelle mit dem Namen des aktiven Autors und einem × Link zum Löschen des Filters.

Sowohl die Desktop-Tabelle als auch ein responsives **Mobil-Kartenlayout** sind enthalten; sie wechseln automatisch je nach Bildschirmbreite.

Die Filterleisten-Vorlage unterstützt **Override** über `enduserportal::partials.tickets_filters` — legen Sie eine benutzerdefinierte Ansicht unter diesem Pfad ab, um OrgPortals Standard-Filterleiste zu ersetzen, während alle anderen Funktionen erhalten bleiben.

![Unternehmenstickets — vollständige Tabelle mit Leseindikatoren, Autorenfilter-Banner, Statusfiltern](docs/screenshots/portal-tickets.png)

### Ticket-Aktionen im Portal

Manager können direkt aktiv werden — kein Kontakt zum Support nötig:

- **Antworten mit Anhängen** — Drag & Drop, mehrere Dateien pro Antwort; Anhangsnamen und Dateigrößen werden in jedem Thread angezeigt
- **Ticket schließen** — eine neue Antwort öffnet es automatisch wieder; ein Banner informiert den Manager darüber, wenn das Ticket geschlossen ist
- **Ticket-Autor ändern** — ein Ticket einem anderen Organisationsmitglied neu zuweisen
- **Nach Einheit filtern** — globale Manager filtern die Ticket-Liste nach struktureller Einheit
- **Nach Kanban-Status filtern** — pro Postfach konfigurierbar, Labels werden in der aktuellen Portalsprache angezeigt

![Portal-Ticket-Ansicht — Antwortformular mit Drag & Drop-Anhängen und geschlossenem Ticket-Banner](docs/screenshots/portal-reply.png)

### Manager-Ansicht-Tracking

- Eine **"angesehen"**-Notiz erscheint unter Agenten-Antworten in der Admin-Ticket-Ansicht, wenn ein Manager das Ticket im Portal öffnet
- Zeigt Managername, Rolle (Organisationsmanager / Einheitsmanager) und vergangene Zeit
- Globale Manager- und Einheitsmanager-Ansichten werden unabhängig verfolgt und angezeigt — gleiche UX wie FreeScout's natives "Kunde hat angesehen"

![Manager-Ansicht-Tracking — 'angesehen'-Notiz erscheint unter Agenten-Antwort in der Admin-Ticket-Ansicht](docs/screenshots/manager-viewed.png)

---

## Echtzeit-Benachrichtigungs-Glocke *(optional)*

*Halten Sie Manager sofort informiert, wenn etwas mit den Tickets ihres Unternehmens passiert.*

Erfordert das [End-User Portal](https://freescout.net/module/end-user-portal/)-Modul.

- 🔔 Glocken-Symbol mit Live-Ungelesen-Anzahl-Badge in der EUP-Navigationsleiste — positioniert sich auf Mobilgeräten automatisch neu (neben dem Hamburger-Menü)
- Benachrichtigungen für: **neues Ticket**, **Agenten-Antwort**, **Kunden-Antwort** — für alle Manager-Rollen
- Dropdown-Panel mit nach Datum gruppierten Benachrichtigungen: Akteurname, Ereignistyp, Ticket-Nummer, Nachrichtenvorschau, Zeitstempel
- **Automatisch als gelesen markieren** wenn der Manager das Ticket öffnet
- Einzelne Benachrichtigungen über × als gelesen markieren; **Alle als gelesen markieren** im Panel-Header
- Fragt alle 15 Sekunden ab; aktualisiert bei Browser-Vor/Zurück-Navigation (bfcache-bewusst)

![Echtzeit-Benachrichtigungs-Glocke — Dropdown mit gruppierten ungelesenen Benachrichtigungen](docs/screenshots/portal-bell.png)

---

## Benachrichtigungsabonnements *(optional)*

*Lassen Sie Manager entscheiden, worüber sie informiert werden — nicht mehr und nicht weniger.*

- **Visuelle Abonnementmatrix** auf der Registerkarte "Benachrichtigungen" in den Portal-Organisationseinstellungen
- **Drei Ereignistypen:** Neues Ticket · Agenten-Antwort · Kunden-Antwort
- **Zwei Bereichsebenen:** Gesamte Organisation (globale Manager) · Einzelne strukturelle Einheiten
- Mitglieder ohne Einheit werden in einer separaten **"Keine Einheit"**-erweiterbaren Zeile gruppiert
- **Pro-Mitglied-Überschreibungen** — erweitern Sie eine Einheitszeile, um einzelne Mitglieder anzuzeigen und ihre Abonnements inline umzuschalten; Einheitsmanager mit begrenzter Rolle werden entsprechend beschriftet
- **Kaskadenlogik in beide Richtungen:**
  - "Gesamte Organisation" aktivieren → aktiviert alle Einheiten und alle Mitglieder
  - Eine Einheit aktivieren → aktiviert alle ihre Mitglieder
  - Ein Mitglied deaktivieren → gleicht die Einheits- und Organisations-Checkboxen automatisch ab
- Globale Manager verwalten alle Mitglieder; Einheitsmanager verwalten nur ihre eigene Einheit
- Benachrichtigungen verwenden den Mail-Treiber des entsprechenden Postfachs

![Benachrichtigungsabonnementmatrix — pro-Einheit- und pro-Mitglied-Umschalter](docs/screenshots/portal-subscriptions.png)

---

## Portal-Organisationseinstellungen

*Manager konfigurieren ihre Organisationsstruktur ohne Admin-Zugang.*

**Organisationseinstellungen** in der Portal-Navigation hat drei Registerkarten:

### Benachrichtigungen-Tab

Die oben beschriebene Abonnementmatrix.

### Einheiten-Tab *(nur globale Manager)*

- **Einheit erstellen** — Inline-Formular mit Namensfeld
- **Einheit umbenennen** — Inline-Bearbeitung direkt in der Tabellenzeile
- **Einheit löschen** — Schaltfläche mit Bestätigung; Einheitsmanager werden automatisch auf Mitglied herabgestuft
- Mitgliederanzahl pro Einheit angezeigt

### Mitglieder-Tab

- Tabelle aller Organisationsmitglieder: Name, strukturelle Einheit, Rolle, Aktiv/Inaktiv-Status-Badge
- **"Globaler Manager"**-Label neben dem Mitgliedsnamen wo zutreffend
- **Deaktivierte anzeigen**-Checkbox — erscheint nur wenn inaktive Mitglieder vorhanden sind; standardmäßig ausgeblendet
- **Globale Manager** können die Einheit und Rolle eines Mitglieds mit einem Inline-Formular aktualisieren (Einheit wählen + Rolle wählen + Anwenden)
- **Globale Manager können ein Mitglied nicht zum globalen Manager befördern** über das Portal — dafür ist Admin-Zugang erforderlich
- **Aktivieren / Deaktivieren**-Schaltfläche pro Mitglied mit Bestätigung bei Deaktivierung

![Portal-Organisationseinstellungen — Einheiten- und Mitglieder-Tabs](docs/screenshots/portal-settings.png)

---

## Mehrsprachige Benachrichtigungs-E-Mail-Vorlagen *(optional)*

*Ihre Firmenkunden erhalten Support-E-Mails in ihrer eigenen Sprache — automatisch, ohne manuellen Aufwand.*

Konfiguriert in **Verwalten → Organisationen → Vorlagen-Tab** (sichtbar für Benutzer mit der Berechtigung "Vorlagen verwalten").

- **Pro-Locale-Vorlagen** — separate Betreff und Inhalt für jede Portalsprache; zwischen ihnen mit dem Locale-Dropdown wechseln; Werte werden im Speicher ausgetauscht ohne Neuladen der Seite
- **Einklappbare Panels** pro Ereignistyp (Neues Ticket / Agenten-Antwort / Kunden-Antwort) — Summernote-Editor initialisiert sich verzögert wenn ein Panel geöffnet wird
- **Standard laden**-Schaltfläche in jedem Panel — stellt die eingebaute Vorlage für das aktuell ausgewählte Locale wieder her (fällt auf den englischen eingebauten Standard zurück, wenn kein locale-spezifischer Standard vorhanden ist)
- **Summernote WYSIWYG-Editor** für die Erstellung von HTML-E-Mails
- **Makrovariablen-Auswähler** — Platzhalter mit einem Klick in Betreff oder Inhalt einfügen; die Cursorposition wird im Betrefffeld erhalten
- **19 eingebaute Standard-Vorlagen** — sofort einsatzbereit; keine Konfiguration erforderlich

**Verfügbare Makrovariablen:**

| Variable | Beschreibung |
|----------|-------------|
| `{manager_name}` | Name des Managers, der die Benachrichtigung erhält |
| `{author_name}` | Kunde, der das Ticket erstellt oder beantwortet hat |
| `{org_name}` | Organisationsname |
| `{unit_name}` | Name der strukturellen Einheit |
| `{subject}` | Ticket-Betreff |
| `{ticket_number}` | Ticket-ID |
| `{ticket_url}` | Direktlink zum Ticket im Portal |
| `{ticket_text}` | Vollständiger Text der Anfangsnachricht (HTML) |
| `{reply_text}` | Vollständiger Text der neuesten Antwort (HTML) |
| `{created_date}` | Erstellungsdatum des Tickets |
| `{created_time}` | Erstellungszeit des Tickets |
| `{created_datetime}` | Erstellungsdatum und -uhrzeit des Tickets |
| `{reply_date}` | Antwortdatum |
| `{reply_time}` | Antwortzeit |
| `{reply_datetime}` | Antwortdatum und -uhrzeit |

**Fallback-Kette:** gespeicherte Locale-Vorlage → eingebaute Locale-Vorlage → gespeicherte englische Vorlage → eingebaute englische Vorlage

Die Benachrichtigungssprache wird durch die Portal-Sprachauswahl jedes Managers bestimmt, die automatisch gespeichert wird, wenn er den Sprachumschalter verwendet.

![E-Mail-Vorlagen — pro-Locale einklappbare Panels, Standard-laden-Schaltfläche, Summernote-Editor](docs/screenshots/admin-templates.png)

---

## REST API *(optional)*

*Integrieren Sie OrgPortal in Ihr CRM, ERP oder Kunden-Onboarding-Workflow.*

Erfordert das [API and Webhooks](https://freescout.net/module/api-webhooks/)-Modul.

- Vollständiges CRUD für Organisationen, strukturelle Einheiten, Kundenmitgliedschaften und Tags
- **Organisationsfelder:** `name`, `color`, `mailboxId`, `isActive` — alle über API lesbar und aktualisierbar
- **Mitglieder-Sub-Ressource** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — Rolle, Einheit, `canManageOrg` und pro-Mitglied `isActive`-Flag unabhängig aktualisieren ohne den Rest der Mitgliedschaft zu berühren
- **Tags-Sub-Ressource** — `GET/PUT /api/organizations/{id}/tags` — Tag-Bindungen auflisten oder vollständig ersetzen (erfordert Tags-Modul; gibt `503` zurück wenn inaktiv)
- Authentifizierung über `X-FreeScout-API-Key`-Header oder `api_key`-Query-Parameter
- Interaktive **ReDoc-Dokumentation** unter **Verwalten → API & Webhooks → OrgPortal API-Dokumentation** (`/orgportal/admin/api-docs`)

📖 **Vollständige API-Referenz → [docs/api/README.md](docs/api/README.md)**

![Interaktive API-Dokumentation — ReDoc mit allen OrgPortal-Endpunkten](docs/screenshots/api-docs.png)

---

## Installation

1. Kopieren Sie den `OrgPortal`-Ordner in `Modules/` Ihrer FreeScout-Installation
2. Gehen Sie zu **Verwalten → Module → OrgPortal → Aktivieren**
3. Migrationen ausführen:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Cache leeren:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Georgische Sprachunterstützung** wird beim ersten Start automatisch bereitgestellt — kein manuelles Kopieren von Dateien erforderlich.

---

## Automatische Updates

OrgPortal unterstützt **Ein-Klick-Updates** über den eingebauten Modul-Update-Mechanismus von FreeScout.

> **Erfordert FreeScout 1.8.170 oder höher.** Bei älteren Versionen manuell aktualisieren, indem der `OrgPortal`-Ordner durch das neueste Release-ZIP ersetzt wird.

Wenn eine neue Version verfügbar ist, erscheint ein Banner unter **Verwalten → Module**. Klicken Sie auf **Jetzt aktualisieren** — FreeScout lädt die neueste Version automatisch herunter und installiert sie.

---

## Modulkompatibilität

| Modul | Status | Hinweise |
|-------|--------|----------|
| End-User Portal ≥ 1.0.85 | Optional | Manager-Portal, Benachrichtigungs-Glocke, Abonnements |
| API and Webhooks ≥ 1.0.80 | Optional | REST API-Endpunkte |
| Kanban ≥ 1.0.23 | Optional | Badge auf Karten, Org-Filter, mehrsprachige Status-Spalten-Labels |
| Custom Fields | ✅ Kompatibel | — |
| Workflows | ✅ Kompatibel | — |
| Tags | ✅ Kompatibel | Tag-Chips im Org-Bearbeitungsformular; Tag-Bindungen via API (`/organizations/{id}/tags`); tag-basierte Ticket-Zuordnung |

---

## Konfiguration

### Globale Einstellungen — **Verwalten → Organisationen → System-Tab**

| Option | Beschreibung |
|--------|-------------|
| Badge auf Ticket-Seite anzeigen | Org-Badge in der Gesprächsliste und Ticket-Ansicht |
| Badge auf Kanban-Karten anzeigen | Org-Badge auf Kanban-Board-Karten |
| Zuordnungsquelle | `member` / `tag` / `tag_only` — wie Tickets Organisationen zugeordnet werden |
| Auto-Cron-Nachfüllen | Nachfüllen alle 5 Minuten automatisch ausführen |
| Snapshot-Sichtbarkeit | Zuordnungsdaten in der Ticket-Seitenleiste anzeigen/ausblenden |
| Portal-Sprachumschalter | Sprachumschalter in der EUP-Navigationsleiste aktivieren; wählen Sie aus 19 Locales |

### Pro-Postfach-Einstellungen — **Postfach-Einstellungen → OrgPortal**

Überschreibt globale Werte für das spezifische Postfach.

| Option | Beschreibung |
|--------|-------------|
| Badge auf Ticket-Seite anzeigen | Badge für dieses Postfach aktivieren/deaktivieren |
| Badge auf Kanban-Karten anzeigen | Badge für dieses Postfach aktivieren/deaktivieren |
| Organisationsblock im Kundenprofil anzeigen | Org-Info-Block in der Ticket-Seitenleiste umschalten |
| Unternehmensticket-Statusfilter | Kanban-Spalten auf benannte Filter im Portal abbilden; pro-Sprach-Labels mit Locale-Umschalter; ziehen zum Neuordnen |

![Pro-Postfach-Einstellungen — Badge-Sichtbarkeit und Kanban-Statusfilter mit mehrsprachigen Labels](docs/screenshots/mailbox-settings.png)

---

## Übersetzungen

OrgPortal ist vollständig in **19 Sprachen** lokalisiert:

| Sprache | Code | Sprache | Code |
|---------|------|---------|------|
| Englisch | `en` | Niederländisch | `nl` |
| Ukrainisch | `uk` | Norwegisch | `no` |
| Deutsch | `de` | Dänisch | `da` |
| Französisch | `fr` | Schwedisch | `sv` |
| Spanisch | `es` | Finnisch | `fi` |
| Italienisch | `it` | Portugiesisch (BR) | `pt-BR` |
| Tschechisch | `cs` | Portugiesisch (PT) | `pt-PT` |
| Slowakisch | `sk` | Rumänisch | `ro` |
| Polnisch | `pl` | Chinesisch Vereinfacht | `zh-CN` |
| Georgisch | `ka` | | |

Übersetzungsdateien: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Benachrichtigungs-E-Mail-Vorlagen haben eingebaute Standards für alle 19 Sprachen.

### Sprachumschalter-Integration

OrgPortal enthält einen eingebauten Portal-Sprachumschalter (aktivieren in **System-Tab → Portal-Sprachumschalter**). Er integriert sich auch mit [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — beide können gleichzeitig aktiv sein.

Die Sprache, die ein Manager auswählt, gilt für alle OrgPortal-UI-Strings und wird als seine Benachrichtigungssprache gespeichert — E-Mails werden automatisch in seiner gewählten Sprache gesendet.

> **Technischer Hinweis:** `OrgPortalSetLocale`-Middleware wendet das Portal-Locale nach FreeScouts `Localize`-Middleware erneut an, um zu verhindern, dass es bei jeder Anfrage auf den Systemstandard zurückgesetzt wird.

---

## Screenshots

| | |
|---|---|
| ![Organisationsliste](docs/screenshots/org-list.png) | ![Organisationsbearbeitung](docs/screenshots/org-edit.png) |
| *Organisationsliste — Statusfilter, Live-Suche, Farb-Badges* | *Organisationsbearbeitung — Farbwähler, Tag-Chips, Mitgliedertabelle* |
| ![System-Tab](docs/screenshots/system-settings.png) | ![Kundenbearbeitung](docs/screenshots/customer-org-field.png) |
| *System-Tab — Zuordnungsmodi, Nachfüllen, Sprachumschalter* | *Kundenbearbeitung — Org-Feld mit Autovervollständigung* |
| ![Unternehmenstickets-Portal](docs/screenshots/portal-tickets.png) | ![Portal-Antwort](docs/screenshots/portal-reply.png) |
| *Unternehmenstickets — Tabelle, Autorenfilter, Leseindikatoren* | *Portal-Ticket — Antwort mit Anhängen, geschlossenes Banner* |
| ![Portal-Organisationseinstellungen](docs/screenshots/portal-settings.png) | ![Benachrichtigungs-Glocke](docs/screenshots/portal-bell.png) |
| *Portal-Org-Einstellungen — Einheiten- und Mitglieder-Tabs* | *Echtzeit-Benachrichtigungs-Glocke mit Dropdown* |
| ![Abonnementmatrix](docs/screenshots/portal-subscriptions.png) | ![E-Mail-Vorlagen](docs/screenshots/admin-templates.png) |
| *Benachrichtigungsabonnementmatrix — pro-Einheit, pro-Mitglied* | *E-Mail-Vorlagen — Locale-Umschalter, Standard laden, Summernote* |
| ![Kanban-Integration](docs/screenshots/kanban-org.png) | ![Postfach-Einstellungen](docs/screenshots/mailbox-settings.png) |
| *Kanban — Org-Badges und Org-Filter-Modal* | *Pro-Postfach-Einstellungen — Kanban-Filter mit mehrsprachigen Labels* |
| ![API-Dokumentation](docs/screenshots/api-docs.png) | |
| *Interaktive API-Dokumentation — ReDoc* | |

---

## Lizenz

[MIT](LICENSE) — © 2026 ASTIN-UA
