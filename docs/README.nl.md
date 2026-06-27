# OrgPortal — B2B Organisatiebeheer Module voor FreeScout

[← Terug naar README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B module" width="140" align="right">

**OrgPortal** is een FreeScout module die volledig **B2B organisatiebeheer** toevoegt aan uw helpdesk: groepeer klanten in bedrijven, definieer afdelingshiërarchieën, geef zakelijke managers een selfserviceportaal en automatiseer notificaties — allemaal binnen FreeScout, zonder externe tools.

> Op zoek naar een manier om bedrijfsaccounts te beheren in FreeScout? Om zakelijke klanten hun eigen supportportaal te geven? Om te bepalen welke tickets elke B2B-contactpersoon kan zien op basis van hun rol en afdeling? OrgPortal lost dat allemaal op.

**Werkt met:** FreeScout 1.8.147+  
**Optionele integraties:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

> [!IMPORTANT]
> **Installeer altijd vanuit de [nieuwste release](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest), niet vanuit de broncode van het repository.**
> Download `OrgPortal.zip` van de Releases-pagina — deze bevat de juiste mapstructuur die FreeScout vereist.
> Het downloaden van de broncode (via "Code → Download ZIP" of `git clone`) **werkt niet** en verbreekt de modulestructuur.
> Automatische updates vereisen ook dat het release-ZIP voor de eerste installatie is gebruikt.

---

🌐 **Ook beschikbaar in:**
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

## Inhoudsopgave

- [Wat OrgPortal toevoegt aan FreeScout](#wat-orgportal-toevoegt-aan-freescout)
- [Organisaties](#organisaties)
- [Structurele Eenheden — Toegangsbeheer op Afdelingsniveau](#structurele-eenheden--toegangsbeheer-op-afdelingsniveau)
- [Org Snapshot — Permanente Ticketkoppeling](#org-snapshot--permanente-ticketkoppeling)
- [Kanban-integratie](#kanban-integratie)
- [Toegangsbeheer & Machtigingen](#toegangsbeheer--machtigingen)
- [Systeeminstellingen](#systeeminstellingen--beheer--organisaties--systeemtabblad)
- [End-User Portal — Selfservice voor Zakelijke Managers](#end-user-portal--selfservice-voor-zakelijke-managers-optioneel)
- [Realtime Notificatiebel](#realtime-notificatiebel-optioneel)
- [Notificatieabonnementen](#notificatieabonnementen-optioneel)
- [Portaal Organisatie-instellingen](#portaal-organisatie-instellingen)
- [Meertalige Notificatie E-mailsjablonen](#meertalige-notificatie-e-mailsjablonen-optioneel)
- [REST API](#rest-api-optioneel)
- [Installatie](#installatie)
- [Automatische Updates](#automatische-updates)
- [Modulecompatibiliteit](#modulecompatibiliteit)
- [Configuratie](#configuratie)
- [Vertalingen](#vertalingen)
- [Schermafbeeldingen](#schermafbeeldingen)
- [Licentie](#licentie)

---

## Wat OrgPortal toevoegt aan FreeScout

FreeScout is gebouwd rondom individuele klanten — elke e-mail is van een persoon, en er is geen ingebouwd concept van een bedrijf waarvoor die persoon werkt. Dit werkt prima voor B2C-helpdesks. Voor B2B schiet het tekort.

OrgPortal vult die leemte:

- **Bedrijfsaccounts** — groepeer klanten in organisaties met een naam, kleurenbadge, postvakmatch en actieve/inactieve status
- **Afdelingshiërarchieën** — verdeel organisaties in structurele eenheden (afdelingen, vestigingen, teams); elk lid heeft toegang tot zijn eigen eenheid
- **Rolgebaseerde toegang** — `member` ziet alleen eigen tickets; `unit_manager` ziet de volledige eenheid; `manager` ziet de volledige organisatie
- **Zakelijk selfserviceportaal** — managers bekijken alle bedrijfstickets, beantwoorden, sluiten, herbestemmen auteurs en beheren notificatievoorkeuren zonder uw team te contacteren
- **Permanente ticketkoppeling** — elk ticket wordt bij aanmaak gekoppeld aan de organisatie; historische rapporten blijven intact bij klantwijzigingen
- **Meertalige notificaties** — geautomatiseerde e-mailwaarschuwingen in de eigen taal van elke manager, met per-taal sjablonen en een ingebouwde WYSIWYG-editor
- **REST API** — synchroniseer lidmaatschappen vanuit uw CRM, automatiseer onboarding, beheer tags programmatisch

---

## Organisaties

*Één plek voor alles over een zakelijk account.*

**Beheer → Organisaties** opent een tabbladinterface met drie secties: Organisaties, Sjablonen en Systeem.

### Organisatielijst

- **Aanmaken, bewerken, verwijderen, activeren/deactiveren** van organisaties
- **Statusfilter** — schakel tussen Actief / Inactief / Alle met een radioknopgroep; filtert de tabel direct aan clientzijde
- **Live zoeken** — begint met filteren bij 2+ tekens, geen herladen van pagina
- **Kleurgecodeerde badges** — interactieve kleurenkiezer met 12 tinten en een live badgevoorbeeld naast de kiezer; badge verschijnt op elk ticket en elke Kanban-kaart
- Klikken op de badge of het ticketaantal opent een FreeScout-zoekopdracht gefilterd op die organisatie
- **Postvaakkoppeling** — organisaties kunnen globaal zijn (alle postvakken) of beperkt tot een specifiek postvak
- **Tagskolom** — toont ✓/✗ of er FreeScout-tags gekoppeld zijn aan de organisatie (Tags-module vereist); tags worden in het bewerkingsformulier toegewezen met een chip-widget en autocomplete-zoekopdracht
- **Ticketaantalkolom** — totaal aantal gesprekken per organisatie; klikbare link naar volledige zoekresultaten
- **Ledentelkolom**
- **Activeren / deactiveren** — account opschorten zonder geschiedenis te verliezen; vereist dat Org Snapshot is ingeschakeld (knop is uitgeschakeld met een tooltip als dat niet het geval is)
- **Verwijderen** — alleen beschikbaar als de organisatie 0 leden en 0 tickets heeft (beveiliging)
- Alle verwijder- en deactiveeringsacties vereisen bevestiging

![Organisatielijst — statusfilter, live zoeken, kleurenbadges, tags, ticketaantallen](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png)

### Organisatiebewerkingsformulier

- **Naam** en **postvaakkoppeling**
- **Kleurenkiezer** — 12 tinten met live badgevoorbeeld
- **Tags** — chip-widget: typ om bestaande FreeScout-tags te zoeken, klik om toe te voegen, × om te verwijderen
- **Ledentabel** — per lid: naam, rol, structurele eenheid, `can_manage_org`-selectievakje (verleent beheerderstoegang tot organisaties zonder volledige beheerdersrechten), actief/inactief schakelaar
- **Structurele eenhedenpaneel** — maak eenheden aan en hernoem ze direct in het bewerkingsformulier; leden worden in dezelfde weergave aan eenheden toegewezen
- **Lid toevoegen** — vult automatisch bestaande niet-gekoppelde gesprekken voor die klant aan

![Organisatiebewerking — kleurenkiezer, tag-chips, ledentabel met rollen en eenheden](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

### Integratie klantprofiel

- **Organisatieveld in het FreeScout-klantbewerkingsformulier** — live autocomplete-zoekopdracht voor organisaties; roldropdown verschijnt na het selecteren van een organisatie; × knop om te verwijderen
- **"Bekijk org-tickets"** snelkoppeling in het klantformulier
- **Org-infoblok in de beheerderticket-zijbalk** — organisatienaam (klikbare link naar de organisatiebewerkingspagina), structurele eenheid en lidrol; zichtbaarheid per postvak in instellingen schakelen
- **Één actief lidmaatschap per klant** — een klant kan niet worden toegevoegd aan een tweede organisatie terwijl hij een actief lidmaatschap heeft; inactieve/gearchiveerde lidmaatschappen zijn toegestaan

![Klantbewerking — organisatieveld met autocomplete en rolkiezer](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png)

---

## Structurele Eenheden — Toegangsbeheer op Afdelingsniveau

*Ondersteuning voor grote ondernemingen met complexe interne hiërarchieën.*

Organisaties kunnen worden verdeeld in onbeperkte **structurele eenheden** (afdelingen, vestigingen, regionale kantoren, projectteams):

- Maak eenheden aan, hernoem en verwijder ze in het beheerdersformulier of direct vanuit het portaal (alleen globale managers)
- Wijs leden toe aan eenheden — elk lid behoort tot één eenheid
- **Een eenheid verwijderen** degradeert automatisch de `unit_manager`-leden naar `member`

**Drie rolniveaus:**

| Rol | Toegangsbereik |
|-----|----------------|
| `member` | Alleen eigen tickets |
| `unit_manager` | Alle tickets binnen hun structurele eenheid |
| `manager` (globaal) | Alle tickets in de hele organisatie |

- Eenheidsmanagers hebben volledige portaalmogelijkheden — antwoorden, bijlagen, herbestemming van auteurs, sluiten/heropenen, notificatiebeheer — strikt beperkt tot hun eenheid
- Tickettoegang en notificatielevering worden gehandhaafd op eenheidsgrenzen

![Organisatiebewerking — leden met rollen en eenheden, eenhedenbeheerpaneel](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

---

## Org Snapshot — Permanente Ticketkoppeling

*Betrouwbare historische rapportage zelfs als uw klantenbestand verandert.*

Wanneer een ticket wordt aangemaakt, registreert OrgPortal de organisatiecontext als een permanente momentopname:

- `org_id`, `org_unit_id` en `org_attributed_at` worden bij aanmaak naar het gesprek geschreven
- **Onveranderlijk** — als een klant later een organisatie verlaat, blijven hun historische tickets gekoppeld aan die organisatie; rapportage breekt nooit
- **Een lid toevoegen** activeert automatische backfill van de bestaande niet-gekoppelde gesprekken van die klant

### Koppelingsbron — drie modi

Geconfigureerd in **Beheer → Organisaties → Systeemtabblad**:

| Modus | Gedrag |
|-------|--------|
| `member` | Ken ticket toe aan de organisatie waarvan de ticketauteur lid is |
| `tag` | Ken eerst toe op basis van FreeScout-tag gekoppeld aan een organisatie; val terug op lidmaatschap als geen tag overeenkomt |
| `tag_only` | Ken uitsluitend toe op basis van tag; lidmaatschap wordt niet gebruikt |

`tag` en `tag_only` modi zijn uitgeschakeld als de Tags-module inactief is.

### Backfill-tools

- **Voortgangsbalk** — toont X / Y tickets gekoppeld (%) met een "voltooid"-indicator wanneer klaar
- **Preflight-statistieken** — vóór het uitvoeren van backfill toont een overzicht hoeveel tickets worden gekoppeld via tag vs. via lidmaatschap vs. niet-overeenkomend
- **Backfill uitvoeren** knop — verwerkt tot 2000 tickets per klik; resultaatoverzicht (by_tag / by_member / unmatched) wordt daarna getoond
- **Auto-cron** (`attribution_cron_enabled`) — plant backfill elke 5 minuten, 1000 tickets per uitvoering, zonder overlap
- **Koppeling resetten** — wist alle org-momentopnamen (gevaarlijke actie, vereist bevestiging)
- Opdrachtregel: `php artisan orgportal:backfill-attribution`

![Systeemtabblad — koppelingsbron, voortgangsbalk, preflight-statistieken, backfill-besturingselementen](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png)

---

## Kanban-integratie

*Houd uw visuele workflow in lijn met uw B2B-accounts.*

- Organisatiebadge op elke Kanban-kaart met de toegewezen kleur van het account
- **Organisatiefilter** in het Kanban-filterpaneel — multi-select modaal met selectievakjes; filterstatus blijft behouden tijdens navigatie
- **Meertalige Kanban-statusfilterlabels** — geef elke Kanban-kolom een aangepaste naam per portaaltaal; schakel talen met de taalkiezer in per-postvak instellingen; sleep om filters te herordenen
- Vertaalde labels verschijnen zowel in de portaalfilterbar als in de **Status**-kolom van de bedrijfstickets tabel; terugvalvolgorde: opgeslagen taal → opgeslagen Engels → originele kolomnaam

![Kanban — organisatiebadges op kaarten en organisatiefiltermodaal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png)

---

## Toegangsbeheer & Machtigingen

*Delegeer organisatiebeheer zonder beheerderstoegang te verlenen.*

- **"Organisaties beheren toestaan"** (`can_manage_org`) — twee niveaus:
  - Als **gebruikersmachtiging** in agentinstellingen — laat een ondersteuningsteamleider alle organisaties beheren zonder beheerdersrechten
  - Als **per-lid vlag** in het organisatiebewerkingsformulier — laat een specifiek organisatielid die ene organisatie beheren vanuit het beheerderspaneel
- **"Notificatiesjablonen beheren toestaan"** — afzonderlijke gedetailleerde machtiging voor sjabloonbewerking
- Organisaties verwijderen blijft uitsluitend voorbehouden aan beheerders
- Portaaltoegang is strikt beperkt per postvak: een manager van Organisatie A heeft geen toegang tot Organisatie B

![Gedetailleerde machtigingen — organisaties en notificatiesjablonen beheren toestaan](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png)

---

## Systeeminstellingen — Beheer → Organisaties → Systeemtabblad

*Alleen-beheerder-besturingselementen voor koppeling, backfill en de portaaltaalkiezer.*

Het **Systeem**-tabblad is alleen zichtbaar voor FreeScout-beheerders.

### Paneel 1: Ticketkoppeling

Zie [Org Snapshot](#org-snapshot--permanente-ticketkoppeling) hierboven voor de volledige beschrijving van koppelingmodi, backfill-tools en auto-cron.

### Paneel 2: Portaaltaalkiezer

- **Inschakelen/uitschakelen** van de taalkiezer in de End-User Portal navigatiebalk
- **Kies welke van de 19 talen** u aanbiedt (selectievakjesraster); alle zijn standaard ingeschakeld
- Wanneer ingeschakeld kunnen managers de portaaltaal wisselen; hun keuze wordt opgeslagen en gebruikt voor notificatie-e-mails
- Dit is de ingebouwde taalkiezer van OrgPortal — hij werkt onafhankelijk van elke externe taalschakelmodule; beide kunnen naast elkaar bestaan

![Systeemtabblad — portaaltaalkiezerpaneel met taalvakjes](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png)

---

## End-User Portal — Selfservice voor Zakelijke Managers *(optioneel)*

*Geef uw B2B-klanten een portaal waar ze de supportrelatie van hun bedrijf beheren — zonder uw team te contacteren voor elke statusupdate.*

Vereist de [End-User Portal](https://freescout.net/module/end-user-portal/) module.

### Bedrijfstickets Dashboard

Een speciale **Bedrijfstickets**-sectie in de portaalnavigatie met een volledig uitgeruste tickettabel:

| Kolom | Beschrijving |
|-------|-------------|
| **#** | Ticket-ID |
| **Onderwerp** | Afgekapt met tooltip bij hover |
| **Verantwoordelijke** | Toegewezen ondersteuningsagent |
| **Auteur** | Klant die het ticket heeft geopend; klik om op deze auteur te filteren |
| **Status** | Actief / In behandeling / Gesloten / Spam met pictogrammen |
| **Staat** | Kanban-kolomnaam in de huidige portaaltaal (alleen als Kanban-module actief is) |
| **Bijgewerkt** | Datum en tijd van laatste antwoord |

**Twee onafhankelijke leesstatus-indicatoren per rij** — deze volgen twee verschillende personen en worden tegelijkertijd weergegeven:

| Indicator | Wiens leesstatus | Wat het betekent |
|-----------|------------------|------------------|
| **Vetgedrukte rij** | De manager die het portaal bekijkt | Manager heeft ongelezen notificaties voor dit gesprek — er is iets gebeurd dat hij nog niet heeft gezien |
| **👁 Oogpictogram** | De ticketauteur (de klant die het heeft ingediend) | De auteur heeft het laatste agentantwoord nog niet geopend — handig om te weten of een klant het antwoord daadwerkelijk heeft gezien |

Deze twee statussen zijn volledig onafhankelijk: een rij kan vetgedrukt zijn (manager heeft niet gelezen) terwijl het oog afwezig is (auteur heeft al gelezen), of omgekeerd. De manager ziet beide tegelijkertijd, wat een volledig beeld geeft van wat er aan beide kanten van het ticket gebeurt zonder het te openen.

**Auteurfilter** — klikken op een auteursnaam activeert een filter; een banner verschijnt bovenaan de tabel met de naam van de actieve auteur en een × link om het filter te wissen.

Zowel de desktoptabel als een responsieve **mobiele kaartindeling** zijn inbegrepen; ze schakelen automatisch op basis van schermbreedte.

De filterbalk sjabloon ondersteunt **overschrijving** via `enduserportal::partials.tickets_filters` — plaats een aangepaste weergave op dat pad om de standaard filterbalk van OrgPortal te vervangen terwijl alle andere functionaliteit behouden blijft.

![Bedrijfstickets — volledige tabel met leesindicatoren, auteursfilterbanner, statusfilters](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png)

### Ticketacties in het Portaal

Managers kunnen direct actie ondernemen — geen contact met support nodig:

- **Antwoorden met bijlagen** — drag & drop, meerdere bestanden per antwoord; bijlagenamen en bestandsgroottes worden bij elk gesprek getoond
- **Ticket sluiten** — een nieuw antwoord opent het automatisch opnieuw; een banner informeert de manager hierover als het ticket gesloten is
- **Ticketauteur wijzigen** — herbestem een ticket aan een ander organisatielid
- **Filteren op eenheid** — globale managers filteren de ticketlijst op structurele eenheid
- **Filteren op Kanban-status** — configureerbaar per postvak, labels getoond in de huidige portaaltaal

![Portaalticketweergave — antwoordformulier met drag & drop bijlagen en gesloten-ticket banner](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png)

### Manager Bekeken Bijhouden

- Een **"bekeken"** notitie verschijnt onder agentantwoorden in de beheerdersticketweergave wanneer een manager het ticket opent in het portaal
- Toont naam van de manager, rol (Organisatiemanager / Eenheidsmanager) en verstreken tijd
- Globale manager en eenheidsmanager weergaven worden onafhankelijk bijgehouden en weergegeven — zelfde UX als FreeScout's native "Klant bekeken"

![Manager bekeken bijhouden — 'bekeken' notitie verschijnt onder agentantwoord in beheerdersticketweergave](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png)

---

## Realtime Notificatiebel *(optioneel)*

*Houd managers geïnformeerd op het moment dat er iets gebeurt met de tickets van hun bedrijf.*

Vereist de [End-User Portal](https://freescout.net/module/end-user-portal/) module.

- 🔔 Belpictogram met live ongelezen-telling badge in de EUP-navigatiebalk — herpositioneert automatisch op mobiel (naast het hamburgermenu)
- Notificaties voor: **nieuw ticket**, **agentantwoord**, **klantantwoord** — voor alle managerrollen
- Dropdownpaneel met notificaties gegroepeerd op datum: actornaam, gebeurtenistype, ticketnummer, berichtvoorbeeld, tijdstempel
- **Automatisch als gelezen markeren** wanneer de manager het ticket opent
- Individuele notificaties als gelezen markeren via ×; **Alles als gelezen markeren** in paneelkoptekst
- Pollt elke 15 seconden; ververst bij navigatie voor/achteruit in browser (bfcache-bewust)

![Realtime notificatiebel — dropdown met gegroepeerde ongelezen notificaties](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png)

---

## Notificatieabonnementen *(optioneel)*

*Laat managers beslissen waarover ze geïnformeerd worden — niet meer, niet minder.*

- **Visuele abonnementsmatrix** op het tabblad "Notificaties" in portaal Organisatie-instellingen
- **Drie gebeurtenistypes:** Nieuw ticket · Agentantwoord · Klantantwoord
- **Twee bereikniveaus:** Gehele organisatie (globale managers) · Individuele structurele eenheden
- Leden zonder een eenheid worden gegroepeerd in een aparte **"Geen eenheid"** uitklapbare rij
- **Per-lid overschrijvingen** — klapbaar elke eenheidsrij uit om individuele leden te zien en hun abonnementen inline te schakelen; eenheidsmanagers met beperkte rol worden dienovereenkomstig gelabeld
- **Gecascadeerde logica in beide richtingen:**
  - "Gehele organisatie" inschakelen → schakelt alle eenheden en alle leden in
  - Een eenheid inschakelen → schakelt alle leden in
  - Een lid uitschakelen → reconcilieert automatisch de eenheids- en organisatievakjes
- Globale managers beheren alle leden; eenheidsmanagers beheren alleen hun eigen eenheid
- Notificaties gebruiken de maildriver van het betreffende postvak

![Notificatieabonnementsmatrix — per-eenheid en per-lid schakelaars](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png)

---

## Portaal Organisatie-instellingen

*Managers configureren hun organisatiestructuur zonder beheerderstoegang.*

**Organisatie-instellingen** in de portaalnavigatie heeft drie tabbladen:

### Notificatiestabblad

De hierboven beschreven abonnementsmatrix.

### Eenhedentabblad *(alleen globale managers)*

- **Eenheid aanmaken** — inline formulier met naamveld
- **Eenheid hernoemen** — inline bewerking direct in de tabelrij
- **Eenheid verwijderen** — knop met bevestiging; eenheidsmanagers worden automatisch gedegradeerd naar lid
- Ledentelling weergegeven per eenheid

### Ledentabblad

- Tabel van alle organisatieleden: naam, structurele eenheid, rol, actief/inactief statusbadge
- **"Globale manager"** label getoond naast de naam van het lid waar van toepassing
- **Gedeactiveerden tonen** selectievakje — verschijnt alleen wanneer inactieve leden bestaan; standaard verborgen
- **Globale managers** kunnen de eenheid en rol van elk lid bijwerken met een inline formulier (eenheid selecteren + rol selecteren + Toepassen)
- **Globale managers kunnen een lid niet promoveren tot globale manager** vanuit het portaal — dit vereist beheerderstoegang
- **Activeren / deactiveren** knop per lid met bevestiging voor deactivering

![Portaal Organisatie-instellingen — Eenheden- en Ledentabbladen](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png)

---

## Meertalige Notificatie E-mailsjablonen *(optioneel)*

*Uw zakelijke klanten ontvangen ondersteunings-e-mails in hun eigen taal — automatisch, zonder handmatige inspanning.*

Geconfigureerd in **Beheer → Organisaties → Sjabloontabblad** (zichtbaar voor gebruikers met de machtiging "sjablonen beheren").

- **Per-taal sjablonen** — apart onderwerp en inhoud voor elke portaaltaal; schakel ertussen met de taaldropdown; waarden worden in het geheugen omgewisseld zonder herladen van de pagina
- **Inklapbare panelen** per gebeurtenistype (Nieuw ticket / Agentantwoord / Klantantwoord) — Summernote-editor initialiseert lazily wanneer een paneel wordt geopend
- **Standaard laden** knop in elk paneel — herstelt het ingebouwde sjabloon voor de momenteel geselecteerde taal (valt terug op het ingebouwde Engels als er geen taalspecifiek standaard bestaat)
- **Summernote WYSIWYG-editor** voor rijke HTML e-mailsamenstelling
- **Macrovariabelenkiezer** — voeg tijdelijke aanduidingen in het onderwerp of de inhoud in met één klik; cursorpositie wordt bewaard in het onderwerpveld
- **19 ingebouwde standaardsjablonen** — direct klaar voor gebruik; geen configuratie nodig

**Beschikbare macrovariabelen:**

| Variabele | Beschrijving |
|-----------|-------------|
| `{manager_name}` | Naam van de manager die de notificatie ontvangt |
| `{author_name}` | Klant die het ticket heeft aangemaakt of beantwoord |
| `{org_name}` | Organisatienaam |
| `{unit_name}` | Naam van de structurele eenheid |
| `{subject}` | Ticketonderwerp |
| `{ticket_number}` | Ticket-ID |
| `{ticket_url}` | Directe link naar het ticket in het portaal |
| `{ticket_text}` | Volledige tekst van het initiële bericht (HTML) |
| `{reply_text}` | Volledige tekst van het laatste antwoord (HTML) |
| `{created_date}` | Datum van aanmaak ticket |
| `{created_time}` | Tijdstip van aanmaak ticket |
| `{created_datetime}` | Datum en tijd van aanmaak ticket |
| `{reply_date}` | Antwoorddatum |
| `{reply_time}` | Antwoordtijdstip |
| `{reply_datetime}` | Datum en tijd van antwoord |

**Terugvalvolgorde:** opgeslagen taalsjabloon → ingebouwd taalsjabloon → opgeslagen Engels sjabloon → ingebouwd Engels sjabloon

De notificatietaal wordt bepaald door de portaaltaalkeuze van elke manager, automatisch opgeslagen wanneer ze de taalkiezer gebruiken.

![E-mailsjablonen — per-taal inklapbare panelen, Standaard laden knop, Summernote-editor](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png)

---

## REST API *(optioneel)*

*Integreer OrgPortal in uw CRM, ERP of klant-onboardingworkflow.*

Vereist de [API and Webhooks](https://freescout.net/module/api-webhooks/) module.

- Volledige CRUD voor organisaties, structurele eenheden, klantlidmaatschappen en tags
- **Organisatievelden:** `name`, `color`, `mailboxId`, `isActive` — allemaal leesbaar en bijwerkbaar via API
- **Leden sub-resource** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — werk rol, eenheid, `canManageOrg` en per-lid `isActive` vlag onafhankelijk bij zonder de rest van het lidmaatschap aan te raken
- **Tags sub-resource** — `GET/PUT /api/organizations/{id}/tags` — toon of vervang volledig tagkoppelingen (vereist Tags-module; geeft `503` terug als inactief)
- Authenticatie via `X-FreeScout-API-Key` header of `api_key` queryparameter
- Interactieve **ReDoc documentatie** op **Beheer → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Volledige API-referentie → [docs/api/README.md](docs/api/README.md)**

![Interactieve API-documentatie — ReDoc met alle OrgPortal-eindpunten](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png)

---

## Installatie

> [!IMPORTANT]
> Download `OrgPortal.zip` van de **[Releases-pagina](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)** — gebruik **niet** "Code → Download ZIP" en kloon het repository niet. Alleen het release-ZIP heeft de juiste structuur voor FreeScout en ondersteunt automatische updates.

1. Download `OrgPortal.zip` van de [nieuwste release](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)
2. Pak uit en kopieer de map `OrgPortal` naar `Modules/` van uw FreeScout-installatie
2. Ga naar **Beheer → Modules → OrgPortal → Activeren**
3. Voer migraties uit:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Cache wissen:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Georgische taalondersteuning** wordt automatisch bij de eerste start geïmplementeerd — geen handmatig kopiëren van bestanden vereist.

---

## Automatische Updates

OrgPortal ondersteunt **één-klik updates** via het ingebouwde module-updatemechanisme van FreeScout.

> **Vereist FreeScout 1.8.170 of later.** Op oudere versies handmatig bijwerken door de map `OrgPortal` te vervangen door de nieuwste release-ZIP.

Wanneer een nieuwe versie beschikbaar is, verschijnt een banner op **Beheer → Modules**. Klik op **Nu bijwerken** — FreeScout downloadt en installeert de nieuwste versie automatisch.

---

## Modulecompatibiliteit

| Module | Status | Opmerkingen |
|--------|--------|-------------|
| End-User Portal ≥ 1.0.85 | Optioneel | Managersportaal, notificatiebel, abonnementen |
| API and Webhooks ≥ 1.0.80 | Optioneel | REST API-eindpunten |
| Kanban ≥ 1.0.23 | Optioneel | Badge op kaarten, org-filter, meertalige Statuskolom-labels |
| Custom Fields | ✅ Compatibel | — |
| Workflows | ✅ Compatibel | — |
| Tags | ✅ Compatibel | Tag-chips op organisatiebewerkingsformulier; tagkoppelingen via API (`/organizations/{id}/tags`); op tag gebaseerde ticketkoppeling |

---

## Configuratie

### Algemene instellingen — **Beheer → Organisaties → Systeemtabblad**

| Optie | Beschrijving |
|-------|-------------|
| Badge tonen op ticketpagina | Org-badge in gesprekslijst en ticketweergave |
| Badge tonen op Kanban-kaarten | Org-badge op Kanban-bord kaarten |
| Koppelingsbron | `member` / `tag` / `tag_only` — hoe tickets worden gekoppeld aan organisaties |
| Auto-cron backfill | Backfill elke 5 minuten automatisch uitvoeren |
| Momentopname zichtbaarheid | Koppelingsdata tonen/verbergen in ticket-zijbalk |
| Portaaltaalkiezer | Taalkiezer inschakelen in EUP-navigatiebalk; kies welke van de 19 talen u aanbiedt |

### Per-postvak instellingen — **Postvak Instellingen → OrgPortal**

Overschrijft algemene waarden voor het specifieke postvak.

| Optie | Beschrijving |
|-------|-------------|
| Badge tonen op ticketpagina | Badge in-/uitschakelen voor dit postvak |
| Badge tonen op Kanban-kaarten | Badge in-/uitschakelen voor dit postvak |
| Organisatieblok tonen in klantprofiel | Org-infoblok in ticket-zijbalk schakelen |
| Bedrijfsticket statusfilters | Wijs Kanban-kolommen toe aan benoemde filters in het portaal; per-taal labels met taalkiezer; sleep om te herordenen |

![Per-postvak instellingen — badge zichtbaarheid en Kanban-statusfilters met meertalige labels](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png)

---

## Vertalingen

OrgPortal is volledig gelokaliseerd in **19 talen**:

| Taal | Code | Taal | Code |
|------|------|------|------|
| Engels | `en` | Nederlands | `nl` |
| Oekraïens | `uk` | Noors | `no` |
| Duits | `de` | Deens | `da` |
| Frans | `fr` | Zweeds | `sv` |
| Spaans | `es` | Fins | `fi` |
| Italiaans | `it` | Portugees (BR) | `pt-BR` |
| Tsjechisch | `cs` | Portugees (PT) | `pt-PT` |
| Slowaaks | `sk` | Roemeens | `ro` |
| Pools | `pl` | Vereenvoudigd Chinees | `zh-CN` |
| Georgisch | `ka` | | |

Vertaalbestanden: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Notificatie e-mailsjablonen hebben ingebouwde standaarden voor alle 19 talen.

### Taalkiezer Integratie

OrgPortal bevat een ingebouwde portaaltaalkiezer (inschakelen in **Systeemtabblad → Portaaltaalkiezer**). Het integreert ook met [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — beide kunnen gelijktijdig actief zijn.

De taal die een manager selecteert is van toepassing op alle OrgPortal UI-teksten en wordt opgeslagen als hun notificatietaal — e-mails worden automatisch in hun gekozen taal verzonden.

> **Technische opmerking:** `OrgPortalSetLocale` middleware past de portaaltaal opnieuw toe na FreeScout's `Localize` middleware om te voorkomen dat deze bij elk verzoek wordt teruggezet naar de systeemstandaard.

---

## Schermafbeeldingen

| | |
|---|---|
| ![Organisatielijst](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png) | ![Organisatiebewerking](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png) |
| *Organisatielijst — statusfilter, live zoeken, kleurenbadges* | *Organisatiebewerking — kleurenkiezer, tag-chips, ledentabel* |
| ![Systeemtabblad](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png) | ![Klantbewerking](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png) |
| *Systeemtabblad — koppelingmodi, backfill, taalkiezer* | *Klantbewerking — org-veld met autocomplete* |
| ![Bedrijfstickets portaal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png) | ![Portaalantwoord](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png) |
| *Bedrijfstickets — tabel, auteurfilter, leesindicatoren* | *Portaalticket — antwoord met bijlagen, gesloten banner* |
| ![Portaal Organisatie-instellingen](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png) | ![Notificatiebel](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png) |
| *Portaal Org-instellingen — Eenheden- en Ledentabbladen* | *Realtime notificatiebel met dropdown* |
| ![Abonnementsmatrix](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png) | ![E-mailsjablonen](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png) |
| *Notificatieabonnementsmatrix — per-eenheid, per-lid* | *E-mailsjablonen — taalkiezer, Standaard laden, Summernote* |
| ![Kanban-integratie](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png) | ![Postvak instellingen](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png) |
| *Kanban — org-badges en org-filtermodaal* | *Per-postvak instellingen — Kanban-filters met meertalige labels* |
| ![API-documentatie](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png) | |
| *Interactieve API-documentatie — ReDoc* | |

---

## Licentie

[MIT](LICENSE) — © 2026 ASTIN-UA
