# OrgPortal — Organisasjonsportal for FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

En FreeScout-modul som legger til konseptet **Organisasjoner** (bedrifter/team) til kunder, utvider End-User Portal for ledere og viser et organisasjonsbadge på billetter og Kanban-kort.

**Minimum FreeScout-versjon:** 1.8.147  
**Avhengigheter:** ingen obligatoriske  
**Valgfritt:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API og webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Språk:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Funksjoner

### Organisasjonshåndtering (admin)
- **Administrer → Organisasjoner** — fullstendig CRUD: opprett, rediger, slett organisasjoner
- **Postkassebinding** — en organisasjon kan være **global** (synlig i alle postkasser) eller **bundet til en spesifikk postkasse**; tilsvarende etikett vises i organisasjonslisten
- Tilordne kunder til organisasjoner med rollvalg: `medlem` eller `leder`
- **Endre medlemsrolle** direkte i tabellen (uten å fjerne og legge til igjen)
- Kundesøk med autofullføring etter navn eller e-post; kunder som allerede er i en organisasjon er utelukket fra resultater
- Medlems e-post vises under navn i medlemstabellen
- Én kunde — én organisasjon (håndhevet på database- og API-nivå)
- **Badgefarge** — visuell palett med 12 farger i organisasjonsredigeringsskjemaet; standard er grå

### Brukertillatelser
- Ny tillatelse **"Tillat organisasjonshåndtering"** — ikke-administratorer med denne tillatelsen får tilgang til lister-, opprett- og rediger-organisasjonssidene
- Sletting av organisasjoner forblir eksklusivt for administratorer

### Kundekort
- **Organisasjonsfelt** i kundenredigeringsskjemaet — velg organisasjon og rolle
- **Organisasjonsbilletter**-knapp — åpner et søk etter alle billetter for organisasjonen

### Organisasjonsbadge på billetter
- Vises under emnet på billetsiden og før navn i samtalelisten
- Kan klikkes — åpner et søk etter alle billetter for denne organisasjonen
- Badgefarge bestemmes av organisasjonsinnstillingen (standard grå)
- Aktivér/deaktivér **per postkasse** via **Postkasseinnstillinger → OrgPortal**; global verdi brukes som fallback

### Organisasjonsbadge på Kanban-kort
- Vises etter meldingsteller på hvert kort
- Kan klikkes — fører til organisasjonssøk
- Fargen samsvarer med organisasjonsinnstillingen
- **Organisasjons**filter innebygd i standard Kanban-filterdropdown: modal med avmerkingsbokser, tilsvarende taggfilteret; tilstand bevares mellom navigeringer
- Aktivér/deaktivér **per postkasse** via **Postkasseinnstillinger → OrgPortal**

### Organisasjonssøkfilter
- Utvider standard FreeScout-søk med et **Organisasjons**-filter
- Viser alle billetter fra kunder som tilhører den valgte organisasjonen

### End-User Portal — lederadgang *(valgfritt)*

En organisasjonsleder får utvidet tilgang gjennom EUP:

- **Bedriftsbilletter**-element i portalnavigasjonen
- Bedriftsbilletter-tabell med kolonner:
  - **#** og **Emne** med ellipseforkorting og verktøytips ved hovring
  - **Ansvarlig** — tildelt agent
  - **Forfatter** — kunden som åpnet billetten; klikk filtrerer billetter etter forfatter innenfor organisasjonen
  - **Status** — Aktiv / Ventende / Lukket / Spam med ikoner
  - **Tilstand** — Kanban-kolonnenavn (med tilpasset etikett hvis konfigurert); vises bare hvis Kanban-modulen er aktiv
  - **Oppdatert** — dato og tid for siste svar
- Søk etter billettmne
- Filter etter Kanban-status (konfigurerbar via **Postkasseinnstillinger → OrgPortal**)
- Svar på billett med **vedleggsstøtte** (dra og slip, multi-fil)
- **Lukk billett** — leder kan lukke en billett; et nytt svar åpner den igjen automatisk
- Endre billettskapelse — tilordne en billett til et annet organisasjonsmedlem på nytt
- **Organisasjonsinnstillinger**-side for konfigurering av e-postmeldinger
- Billettilgang er **strengt begrenset til gjeldende postkasse** (organisasjon kopiert til annen postkasse — portal 403)

### E-postmeldinger *(valgfritt)*
- Ledere med alternativet aktivert mottar en e-post når en ny billett opprettes av et medlem av organisasjonen
- Bruker poststyrer for den tilsvarende postkassen

### Postkasseinnstillinger

**Postkasseinnstillinger → OrgPortal** (per postkasse):

| Alternativ | Beskrivelse |
|-----------|-------------|
| Vis badge på billetsiden | Aktivér/deaktivér badge innenfor denne postkassen |
| Vis badge på Kanban-kort | Aktivér/deaktivér badge innenfor denne postkassen |
| Filter for bedriftsbillettstatus | Velg Kanban-kolonner som vises som avmerkingsbokser på billettssiden; tilpasset etikett for hvert filter |

---

### REST API *(valgfritt, krever API og webhooks)*

OrgPortal tilbyr et komplett REST API for å administrere organisasjoner, strukturelle enheter og kundemedlemskap — autentisering via headeren `X-FreeScout-API-Key` eller spørringsparameteren `api_key`.

📖 **Fullstendig API-referanse → [docs/api/README.no.md](api/README.no.md)** (alle endepunkter, forespørsels-/svareksempler, feilkoder)

Interaktiv ReDoc-dokumentasjon er også tilgjengelig under **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

---

## Installasjon

1. Kopier `OrgPortal`-mappen til `Modules/` i din FreeScout
2. I administrasjonspanelet: **Administrer → Moduler → OrgPortal → Aktiver**
3. Kjør migrasjoner:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Tøm cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Oppdateringer

OrgPortal støtter **automatiske oppdateringer** via FreeScout's innebygde moduloppdateringsmekanisme.

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

Når en ny versjon er tilgjengelig, vil et banner vises på siden **Administrer → Moduler**. Klikk på **Oppdater nå** — FreeScout laster ned og installerer den nyeste versjonen automatisk.

Ingen manuell filkopiering påkrevd.

---

## Modulkompatibilitet

| Modul | Status |
|--------|--------|
| End-User Portal ≥ 1.0.85 | Valgfritt — portalfunksjoner for ledere |
| API og webhooks ≥ 1.0.80 | Valgfritt — REST API-endepunkter |
| Kanban ≥ 1.0.23 | Valgfritt — badge, filter, "Tilstand"-kolonne i bedriftsbilletter |
| Egendefinerte felt | Kompatibel |
| Arbeidsflyter | Kompatibel |
| Merkelapper | Kompatibel |

---

## Konfigurasjon

### Global (**Administrer → OrgPortal-innstillinger**)

| Alternativ | Standard |
|---------|---------|
| Vis badge på billetsiden | ✅ |
| Vis badge på Kanban-kort | ✅ |

### Per postkasse (**Postkasseinnstillinger → OrgPortal**)

Overstyrer globale verdier for den spesifikke postkassen.

| Alternativ | Beskrivelse |
|---------|-------------|
| Vis badge på billetsiden | Badge i samtaleliste og på billetsiden |
| Vis badge på Kanban-kort | Badge på Kanban-kort |
| Filter for bedriftsbillettstatus | Kanban-kolonner som avmerkingsbokser på bedriftsbillettssiden; hver filter har en tilpasset etikett synlig for portalbrukere |

---

## Oversettelser

Støttede språk: **Engelsk** (`en`), **Ukrainsk** (`uk`), **Rumensk** (`ro`), **Georgisk** (`ka`), **Tysk** (`de`), **Fransk** (`fr`), **Spansk** (`es`), **Italiensk** (`it`), **Tsjekkisk** (`cs`), **Slovakisk** (`sk`), **Polsk** (`pl`), **Russisk** (`ru`), **Nederlandsk** (`nl`), **Norsk** (`no`), **Dansk** (`da`), **Svensk** (`sv`), **Finsk** (`fi`), **Portugisisk BR** (`pt-BR`), **Portugisisk PT** (`pt-PT`), **Forenklet kinesisk** (`zh-CN`).

Filer: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### EUPSWLANG-integrasjon

Modulen fungerer korrekt med [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): språket som velges i portalen gjelder også for OrgPortal-strenger.

For at et språk skal vises i EUPSWLANG-listen, må den tilsvarende `Modules/EndUserPortal/Resources/lang/{locale}.json`-filen finnes. Filer for **Rumensk** (`ro`) er inkludert i pakken; **Georgisk** (`ka`) støttes bare i administrasjonsavsnittet (ingen systemstøtte i FreeScout core).

> **Teknisk detalj:** `ReapplyEupLocale`-middleware (registrert sist i portalruttegruppen) gjenoppretter lokalen etter FreeScouts `Localize`-middleware, som ellers ville nullstille portalspråkvalget til systemstandarden.

---

## Lisens

[MIT](../LICENSE) — © 2026 ASTIN-UA
