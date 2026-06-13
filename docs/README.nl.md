# OrgPortal — Organisatieportaal voor FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Een FreeScout-module die het concept van **Organisaties** (bedrijven/teams) aan klanten toevoegt, het Eind-gebruikersportaal voor managers uitbreidt en een organisatiebadge op tickets en Kanban-kaarten weergeeft.

**Minimale FreeScout-versie:** 1.8.147  
**Afhankelijkheden:** geen vereist  
**Optioneel:** [Eind-gebruikersportaal](https://freescout.net/module/end-user-portal/), [API en Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Taal:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Functies

### Organisatiebeheer (admin)
- **Beheren → Organisaties** — volledige CRUD: maak, bewerk, verwijder organisaties
- **Postvakkoppeling** — een organisatie kan **globaal** (zichtbaar in alle postvakken) of **gebonden aan een specifiek postvak** zijn; het overeenkomstige label wordt weergegeven in de organisatelijst
- Wijs klanten toe aan organisaties met rolselectie: `lid` of `manager`
- **Wijzig lidrol** rechtstreeks in de tabel (zonder verwijderen en opnieuw toevoegen)
- Klantzoekfunctie met autocomplete op naam of e-mail; klanten die al in een organisatie zitten, worden uitgesloten uit de resultaten
- E-mailadres van lid wordt onder de naam in de ledentabel weergegeven
- Één klant — één organisatie (afgedwongen op DB- en API-niveau)
- **Badgekleur** — visueel palet met 12 kleuren in het organisatiebewerk formulier; standaard is grijs

### Gebruikersmachtigingen
- Nieuwe machtiging **"Organisaties beheren toestaan"** — niet-admins met deze machtiging krijgen toegang tot de lijst-, maak- en bewerkingspagina's van organisaties
- Organisaties verwijderen blijft exclusief voor admins

### Klantkaart
- **Organisatie**-veld in het klantbewerkingsformulier — selecteer organisatie en rol
- **Organisatietickets** knop — opent een zoeking naar alle tickets van de organisatie

### Organisatiebadge op tickets
- Weergegeven onder het onderwerp op de ticketpagina en vóór de naam in de conversatielijst
- Klikbaar — opent een zoeking naar alle tickets van deze organisatie
- Badgekleur wordt bepaald door de organisatie-instelling (standaard grijs)
- Activeer/deactiveer **per postvak** via **Postvaksinstellingen → OrgPortal**; globale waarde wordt als fallback gebruikt

### Organisatiebadge op Kanban-kaarten
- Weergegeven na de berichtenteller op elke kaart
- Klikbaar — leidt naar organisatiezoeking
- Kleur komt overeen met de organisatie-instelling
- **Organisatie**-filter ingebouwd in het standaard Kanban-filtermenu: modale dialoog met selectievakjes, vergelijkbaar met het tagfilter; status blijft behouden tussen navigaties
- Activeer/deactiveer **per postvak** via **Postvaksinstellingen → OrgPortal**

### Organisatiezoekfilter
- Breidt FreeScout-zoeking uit met een **Organisatie**-filter
- Toont alle tickets van klanten die tot de geselecteerde organisatie behoren

### Eind-gebruikersportaal — managertoegang *(optioneel)*

Een organisatiemanager krijgt uitgebreide toegang via EUP:

- **Bedrijfstickets** item in de portalnavigatie
- Tabel met bedrijfstickets met kolommen:
  - **#** en **Onderwerp** met ellipsisafkapping en tooltip bij mouseover
  - **Verantwoordelijke** — toegewezen agent
  - **Auteur** — de klant die het ticket opende; klik filtert tickets op auteur binnen de organisatie
  - **Status** — Actief / In afwachting / Gesloten / Spam met pictogrammen
  - **Status** — naam van de Kanban-kolom (met aangepast label indien geconfigureerd); alleen weergegeven als de Kanban-module actief is
  - **Bijgewerkt** — datum en tijd van het laatste antwoord
- Zoeken op ticketonderwerp
- Filters op Kanban-statussen (configureerbaar via **Postvaksinstellingen → OrgPortal**)
- Antwoord op ticket met **bijlagen**-ondersteuning (slepen en neerzetten, meerdere bestanden)
- **Ticket sluiten** — manager kan ticket sluiten; nieuw antwoord opent het automatisch opnieuw
- Wijzig ticketauteur — wijs ticket opnieuw toe aan ander organisatielid
- **Org-instellingen** pagina voor het configureren van e-mailmeldingen
- Tickettoegang is **strikt beperkt tot huidig postvak** (organisatie gekopieerd naar ander postvak — portaal 403)

### Meldingsabonnementen *(optioneel)*

Portalbeheerders kunnen aanpassen welke gebeurtenissen en bereiken e-mailmeldingen activeren:

- **Abonnementmatrix** op het tabblad "Meldingen" in portalorganisatie-instellingen
- **Gebeurtenissen:** Nieuw ticket, Antwoord agent, Antwoord klant
- **Bereiken:** Hele organisatie (alleen globale managers) of specifieke structurele eenheden
- **Abonnementen per lid:** elke eenheidsrij is uitvouwbaar — klik erop om alle leden van die eenheid weer te geven en hun individuele abonnementen inline in- en uit te schakelen. Een globale manager beheert leden van alle eenheden; een eenheidsmanager alleen die van hun eigen eenheid.
- **Volledig transitieve cascade:** "Hele organisatie" bepaalt elke eenheid en elk lid; een selectievakje van een eenheid bepaalt al haar leden; het uitschakelen van een lid stelt zijn eenheid (en de organisatie) automatisch bij — in beide richtingen, per gebeurteniskolom.
- Meldingen gebruiken de mail-driver van het overeenkomstige postvak

### E-mailmeldingen *(optioneel)*
- Managers met de optie ingeschakeld ontvangen een e-mail wanneer een nieuw ticket wordt gemaakt door een organisatielid
- Gebruikt de mail-driver van het overeenkomstige postvak

### Postvaksinstellingen

**Postvaksinstellingen → OrgPortal** (per postvak):

| Optie | Beschrijving |
|-------|-------------|
| Badge op ticketpagina weergeven | Activeer/deactiveer badge in dit postvak |
| Badge op Kanban-kaarten weergeven | Activeer/deactiveer badge in dit postvak |
| Statusfilters bedrijfstickets | Selecteer Kanban-kolommen weergegeven als selectievakjes op de ticketpagina; aangepast label voor elk filter |

---

### REST API *(optioneel, vereist API en Webhooks)*

OrgPortal biedt een volledige REST API voor het beheren van organisaties, structurele eenheden en klantlidmaatschappen — authenticatie via de header `X-FreeScout-API-Key` of de queryparameter `api_key`.

📖 **Volledige API-referentie → [docs/api/README.nl.md](api/README.nl.md)** (alle eindpunten, voorbeelden van aanvragen/antwoorden, foutcodes)

Interactieve ReDoc-documentatie is ook beschikbaar via **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

---

## Installatie

1. Kopieer `OrgPortal` map naar `Modules/` van uw FreeScout
2. In het admin panel: **Beheren → Modules → OrgPortal → Activeren**
3. Voer migraties uit:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Wis cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Updates

OrgPortal ondersteunt **automatische updates** via FreeScout's ingebouwde module-update-mechanisme.

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

Wanneer een nieuwe versie beschikbaar is, verschijnt een banner op de pagina **Beheren → Modules**. Klik op **Nu bijwerken** — FreeScout downloadt en installeert automatisch de nieuwste versie.

Geen handmatige bestandskopie nodig.

---

## Moduleverenigbaarheid

| Module | Status |
|--------|--------|
| Eind-gebruikersportaal ≥ 1.0.85 | Optioneel — portalfuncties voor managers |
| API en Webhooks ≥ 1.0.80 | Optioneel — REST API eindpunten |
| Kanban ≥ 1.0.23 | Optioneel — badge, filter, "Status" kolom in bedrijfstickets |
| Aangepaste velden | Compatibel |
| Workflows | Compatibel |
| Tags | Compatibel |

---

## Configuratie

### Globaal (**Beheren → OrgPortal-instellingen**)

| Optie | Standaard |
|-------|----------|
| Badge op ticketpagina weergeven | ✅ |
| Badge op Kanban-kaarten weergeven | ✅ |

### Per postvak (**Postvaksinstellingen → OrgPortal**)

Overschrijft globale waarden voor het specifieke postvak.

| Optie | Beschrijving |
|-------|-------------|
| Badge op ticketpagina weergeven | Badge in conversatielijst en op ticketpagina |
| Badge op Kanban-kaarten weergeven | Badge op Kanban-kaarten |
| Statusfilters bedrijfstickets | Kanban-kolommen als selectievakjes op de bedrijfsticketspagina; aangepast label zichtbaar voor portalgebruikers |

---

## Vertalingen

Ondersteunde talen: **English** (`en`), **Українська** (`uk`), **Română** (`ro`), **Georgian** (`ka`), **Deutsch** (`de`), **Français** (`fr`), **Español** (`es`), **Italiano** (`it`), **Čeština** (`cs`), **Slovenčina** (`sk`), **Polski** (`pl`), **Nederlands** (`nl`), **Norsk** (`no`), **Dansk** (`da`), **Svenska** (`sv`), **Suomi** (`fi`), **Português BR** (`pt-BR`), **Português PT** (`pt-PT`), **中文 (简体)** (`zh-CN`).

Bestanden: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### EUPSWLANG-integratie

De module werkt correct met [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): de taal die in het portaal is geselecteerd, geldt ook voor OrgPortal-strings.

Opdat een taal in de EUPSWLANG-lijst verschijnt, moet het overeenkomstige bestand `Modules/EndUserPortal/Resources/lang/{locale}.json` bestaan. Bestanden voor **Română** (`ro`) zijn in het pakket opgenomen; **Georgian** (`ka`) wordt alleen ondersteund in de admin-sectie (geen systeemondersteuning in FreeScout core).

> **Technische detail:** middleware `ReapplyEupLocale` (laatst geregistreerd in de portalroute-groep) herstelt de locale na FreeScout's `Localize` middleware, die anders de portaaltaal zou resetten naar de systeemstandaard.

---

## Licentie

[MIT](../LICENSE) — © 2026 ASTIN-UA
