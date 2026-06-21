# OrgPortal — B2B-organisasjonsstyringsmodul for FreeScout

[← Tilbake til README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B-modul" width="140" align="right">

**OrgPortal** er en FreeScout-modul som legger til fullstendig **B2B-organisasjonsstyring** i din helpdesk: grupper kunder i bedrifter, definer avdelingshierarkier, gi bedriftsledere en selvbetjeningsportal, og automatiser varsler — alt inne i FreeScout, uten eksterne verktøy.

> Leter du etter en måte å administrere bedriftskontoer i FreeScout? Gi bedriftskunder deres egen støtteportal? Styre hvilke saker hvert B2B-kontakt kan se basert på rolle og avdeling? OrgPortal løser alt dette.

**Fungerer med:** FreeScout 1.8.147+  
**Valgfrie integrasjoner:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Også tilgjengelig på:**
[Українська](docs/README.uk.md) · [Deutsch](docs/README.de.md) · [Français](docs/README.fr.md) · [Español](docs/README.es.md) · [Italiano](docs/README.it.md) · [Polski](docs/README.pl.md) · [Čeština](docs/README.cs.md) · [Slovenčina](docs/README.sk.md) · [Nederlands](docs/README.nl.md) · [Norsk](docs/README.no.md) · [Dansk](docs/README.da.md) · [Svenska](docs/README.sv.md) · [Suomi](docs/README.fi.md) · [Português (BR)](docs/README.pt-BR.md) · [Português (PT)](docs/README.pt-PT.md) · [Română](docs/README.ro.md) · [中文 (简体)](docs/README.zh-CN.md)

---

## Hva OrgPortal tilføyer FreeScout

FreeScout er bygget rundt individuelle kunder — hver e-post er fra en person, og det finnes ingen innebygd konsept for bedriften denne personen jobber for. Dette fungerer fint for B2C-helpdesker. For B2B er det utilstrekkelig.

OrgPortal fyller dette gapet:

- **Bedriftskontoer** — grupper kunder i organisasjoner med navn, fargemerke, postboksomfang og aktiv/inaktiv status
- **Avdelingshierarkier** — del organisasjoner i strukturelle enheter (avdelinger, filialer, team); hvert medlem er knyttet til sin enhet
- **Rollebasert tilgang** — `member` ser kun egne saker; `unit_manager` ser hele enheten; `manager` ser hele organisasjonen
- **Bedriftens selvbetjeningsportal** — ledere ser alle bedriftssaker, svarer, lukker, omtildeler forfattere og administrerer varselpreferanser uten å kontakte teamet ditt
- **Permanent sakstilskriving** — hver sak lagres med organisasjonskontekst ved opprettelse; historisk rapportering overlever endringer i klientlisten
- **Flerspråklige varsler** — automatiserte e-postvarsler på hver leders eget språk, med per-lokalitetsmaler og innebygd WYSIWYG-editor
- **REST API** — synkroniser medlemskap fra CRM, automatiser onboarding, administrer tagger programmatisk

---

## Organisasjoner

*Ett sted for alt om en bedriftskonto.*

**Manage → Organizations** åpner et fanebasert grensesnitt med tre seksjoner: Organizations, Templates og System.

### Organisasjonsliste

- **Opprett, rediger, slett, aktiver/deaktiver** organisasjoner
- **Statusfilter** — veksle mellom Aktiv / Inaktiv / Alle med en radiogruppe; filtrerer tabellen på klientsiden umiddelbart
- **Livesøk** — begynner å filtrere ved 2+ tegn, ingen sideopplasting
- **Fargemerker** — interaktiv fargevelger med 12 farger og forhåndsvisning av merket ved siden av velgeren; merket vises på alle saker og Kanban-kort
- Å klikke på merket eller sakstallet åpner et FreeScout-søk filtrert til den organisasjonen
- **Postbokstilknytning** — organisasjoner kan være globale (alle postbokser) eller begrenset til en bestemt postboks
- **Tagg-kolonne** — viser ✓/✗ om noen FreeScout-tagger er knyttet til organisasjonen (Tags-modulen kreves); tagger tildeles i redigeringsskjemaet med en chip-basert widget og autofullføringssøk
- **Saksantall-kolonne** — totalt antall samtaler per organisasjon; klikkbar lenke til fullstendige søkeresultater
- **Antall medlemmer**-kolonne
- **Aktiver / deaktiver** — suspender en konto uten å miste historikk; krever at Org Snapshot er aktivert (knappen er deaktivert med et verktøytips når det ikke er det)
- **Slett** — tilgjengelig kun når organisasjonen har 0 medlemmer og 0 saker (sikkerhetsgrense)
- Alle slett- og deaktiveringshandlinger krever bekreftelse

![Organisasjonsliste — statusfilter, livesøk, fargemerker, tagger, saksantall](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png)

### Redigeringsskjema for organisasjon

- **Navn** og **postbokstilknytning**
- **Fargevelger** — 12 farger med forhåndsvisning av merket
- **Tagger** — chip-basert widget: skriv for å søke eksisterende FreeScout-tagger, klikk for å legge til, × for å fjerne
- **Medlemstabell** — per medlem: navn, rolle, strukturell enhet, `can_manage_org`-avkrysningsboks (gir adminadgang til organisasjoner uten fulle adminrettigheter), aktiv/inaktiv-veksler
- **Panel for strukturelle enheter** — opprett og gi nytt navn til enheter direkte i redigeringsskjemaet; medlemmer tildeles enheter i samme visning
- **Legge til et medlem** — fyller automatisk inn eksisterende saker uten tilskriving for denne kunden

![Organisasjonsredigering — fargevelger, tagg-chips, medlemstabell med roller og enheter](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

### Kundeprofil-integrasjon

- **Organisasjonsfelt i FreeScout-kundens redigeringsskjema** — live autofullføringssøk etter organisasjoner; rullegardinmeny for rolle vises etter valg av org; × for å fjerne
- **«Vis org-saker»**-snarvei i kundeskjemaet
- **Org-infoblokk i admin-saknets sidepanel** — organisasjonsnavn (klikkbar lenke til org-redigeringssiden), strukturell enhet og medlemsrolle; slå av/på synlighet per postboks i innstillinger
- **Ett aktivt medlemskap per kunde** — en kunde kan ikke legges til en ny organisasjon mens de har et aktivt medlemskap; inaktive/arkiverte medlemskap er tillatt

![Kunderedigering — organisasjonsfelt med autofullføring og rollevelger](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png)

---

## Strukturelle enheter — Tilgangskontroll på avdelingsnivå

*Støtt store bedrifter med komplekse interne hierarkier.*

Organisasjoner kan deles inn i ubegrensede **strukturelle enheter** (avdelinger, filialer, regionale kontorer, prosjektteam):

- Opprett, gi nytt navn til og slett enheter i admin-org-redigeringsskjemaet, eller direkte fra portalen (kun globale ledere)
- Tildel medlemmer til enheter — hvert medlem tilhører én enhet
- **Sletting av en enhet** nedgraderer automatisk `unit_manager`-medlemmer til `member`

**Tre rollenivåer:**

| Rolle | Tilgangsomfang |
|-------|---------------|
| `member` | Kun egne saker |
| `unit_manager` | Alle saker innenfor sin strukturelle enhet |
| `manager` (global) | Alle saker på tvers av hele organisasjonen |

- Enhetsledere har fulle portalfunksjoner — svar, vedlegg, omtildeling av forfatter, lukk/gjenåpne, varselstyring — begrenset strengt til sin enhet
- Saktilgang og varsellevering håndheves ved enhetsgrenser

![Organisasjonsredigering — medlemmer med roller og enheter, panel for enhetsadministrasjon](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

---

## Org Snapshot — Permanent sakstilskriving

*Pålitelig historisk rapportering selv når klientlisten endres.*

Når en sak opprettes, registrerer OrgPortal organisasjonskonteksten som et permanent øyeblikksbilde:

- `org_id`, `org_unit_id` og `org_attributed_at` skrives til samtalen ved opprettelsestidspunktet
- **Uforanderlig** — hvis en kunde senere forlater en organisasjon, forblir historiske saker tilskrevet den organisasjonen; rapportering brytes aldri
- **Legge til et medlem** utløser automatisk etterfylling av den kundens eksisterende saker uten tilskriving

### Tilskrivingskilde — tre moduser

Konfigurert i **Manage → Organizations → System tab**:

| Modus | Oppførsel |
|-------|----------|
| `member` | Tilskrive sak til organisasjonen saksforfatteren er medlem av |
| `tag` | Tilskrive etter FreeScout-tagg knyttet til en org først; fall tilbake til medlemskap hvis ingen tagg matcher |
| `tag_only` | Tilskrive utelukkende etter tagg; medlemskap brukes ikke |

`tag`- og `tag_only`-moduser er deaktivert når Tags-modulen er inaktiv.

### Etterfyllingsverktøy

- **Fremdriftslinje** — viser X / Y tilskrevne saker (%) med en «fullført»-indikator når ferdig
- **Forhåndsstatistikk** — før kjøring av etterfylling vises en oversikt over hvor mange saker som vil tilskrives etter tagg vs. etter medlemskap vs. umatchede
- **Kjør etterfylling**-knapp — behandler opptil 2000 saker per klikk; resultatsammendrag (by_tag / by_member / unmatched) vises etterpå
- **Auto-cron** (`attribution_cron_enabled`) — planlegger etterfylling hvert 5. minutt, 1000 saker per kjøring, uten overlapping
- **Tilbakestill tilskriving** — tømmer alle org-øyeblikksbilder (farlig handling, krever bekreftelse)
- Kommandolinje: `php artisan orgportal:backfill-attribution`

![System-fanen — tilskrivingskilde, fremdriftslinje, forhåndsstatistikk, etterfyllingskontroller](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png)

---

## Kanban-integrasjon

*Hold den visuelle arbeidsflyten tilpasset B2B-kontoene dine.*

- Organisasjonsmerke på hvert Kanban-kort med kontoens tildelte farge
- **Organisasjonsfilter** i Kanban-filterpanelet — flervalgsmodal med avkrysningsbokser; filterstatus vedvarer på tvers av navigasjon
- **Flerspråklige Kanban-statusfiltretiketter** — gi hver Kanban-kolonne et egendefinert navn per portalspråk; bytt lokaliteter med språkvelgeren i per-postboks-innstillinger; dra for å omordne filtre
- Oversatte etiketter vises i både portalfilterlisten og **State**-kolonnen i bedriftssaktabellen; reservekjede: lagret lokalitet → lagret engelsk → opprinnelig kolonnenavn

![Kanban — organisasjonsmerker på kort og org-filtermodal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png)

---

## Tilgangskontroll og tillatelser

*Deleger organisasjonsstyring uten å gi adminadgang.*

- **«Tillat administrasjon av organisasjoner»** (`can_manage_org`) — to nivåer:
  - Som en **brukertillatelse** i agentinnstillinger — lar en supportteamleder administrere alle organisasjoner uten adminrettigheter
  - Som et **per-medlem-flagg** i organisasjonens redigeringsskjema — lar et bestemt org-medlem administrere den ene organisasjonen fra adminpanelet
- **«Tillat administrasjon av varselsmaler»** — separat detaljert tillatelse for malredigering
- Sletting av organisasjoner er utelukkende forbeholdt admin
- Portaltilgang er strengt begrenset per postboks: en leder fra Organisasjon A kan ikke få tilgang til Organisasjon B

![Detaljerte tillatelser — tillat administrasjon av organisasjoner og varselsmaler](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png)

---

## Systeminnstillinger — Manage → Organizations → System tab

*Admin-kontroller for tilskriving, etterfylling og portalspråkvelgeren.*

**System**-fanen er kun synlig for FreeScout-administratorer.

### Panel 1: Sakstilskriving

Se [Org Snapshot](#org-snapshot--permanent-sakstilskriving) ovenfor for fullstendig beskrivelse av tilskrivingsmoduser, etterfyllingsverktøy og auto-cron.

### Panel 2: Portalspråkvelger

- **Aktiver/deaktiver** språkvelgeren i End-User Portal-navigasjonslinjen
- **Velg hvilke av de 19 lokalitetene** som skal tilbys (avkrysningsrutenett); alle er aktivert som standard
- Når aktivert kan ledere bytte portalspråk; valget lagres og brukes for varsel-e-poster
- Dette er OrgPortals innebygde språkvelger — den fungerer uavhengig av tredjeparts språkbyttemoduler; begge kan sameksistere

![System-fanen — portalspråkvelger-panel med lokalitetsavkrysningsbokser](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png)

---

## End-User Portal — Selvbetjening for bedriftsledere *(valgfritt)*

*Gi B2B-klientene en portal der de administrerer bedriftens støtteforhold — uten å kontakte teamet ditt for hver statusoppdatering.*

Krever [End-User Portal](https://freescout.net/module/end-user-portal/)-modulen.

### Bedriftssaks-dashbord

En dedikert **Company Tickets**-seksjon i portalnavigasjon med en fullstendig sakstabell:

| Kolonne | Beskrivelse |
|---------|------------|
| **#** | Saks-ID |
| **Emne** | Avkortet med verktøytips ved hovring |
| **Ansvarlig** | Tildelt supportagent |
| **Forfatter** | Kunden som åpnet saken; klikk for å filtrere etter denne forfatteren |
| **Status** | Aktiv / Venter / Lukket / Søppel med ikoner |
| **State** | Kanban-kolonnenavn på gjeldende portalspråk (kun når Kanban-modulen er aktiv) |
| **Oppdatert** | Dato og klokkeslett for siste svar |

**To uavhengige lesestatusindikatorer per rad** — disse sporer to forskjellige personer og vises samtidig:

| Indikator | Hvems lesestatus | Hva det betyr |
|-----------|-----------------|---------------|
| **Fet rad** | Lederen som ser portalen | Lederen har uleste varsler for denne samtalen — noe har skjedd som de ikke har sett ennå |
| **👁 Øye-ikon** | Saksforfatteren (kunden som sendte inn saken) | Forfatteren har ennå ikke åpnet det siste agentsvaret — nyttig for å vite om en klient faktisk så svaret |

Disse to tilstandene er helt uavhengige: en rad kan være fet (lederen har ikke lest) mens øyet er fraværende (forfatteren har allerede lest), eller omvendt. Lederen ser begge samtidig, og får et fullstendig bilde av hva som skjer på begge sider av saken uten å åpne den.

**Forfatterfilter** — å klikke på et forfatternavn aktiverer et filter; et banner vises øverst i tabellen med den aktive forfatterens navn og en ×-lenke for å tømme filteret.

Både skrivebordsvisningen og en responsiv **mobilkortvisning** er inkludert; de bytter automatisk basert på skjermbredde.

Filterliste-malen støtter **overstyring** via `enduserportal::partials.tickets_filters` — plasser en egendefinert visning på denne stien for å erstatte OrgPortals standardfilterliste mens all annen funksjonalitet beholdes.

![Bedriftssaker — fullstendig tabell med lesestatusindikatorer, forfatterfilterbanner, statusfiltre](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png)

### Sakshandlinger i portalen

Ledere kan handle direkte — ingen grunn til å kontakte support:

- **Svar med vedlegg** — dra og slipp, flere filer per svar; vedleggsnavn og filstørrelser vises på hver tråd
- **Lukk sak** — et nytt svar åpner den automatisk; et banner informerer lederen om dette når saken er lukket
- **Endre saksforfatter** — omtildel en sak til et annet organisasjonsmedlem
- **Filtrer etter enhet** — globale ledere filtrerer sakslisten etter strukturell enhet
- **Filtrer etter Kanban-status** — konfigurerbar per postboks, etiketter vises på gjeldende portalspråk

![Portalsakvisning — svarskjema med dra-og-slipp-vedlegg og lukket-sak-banner](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png)

### Sporing av ledervisning

- Et **«vist»**-notat vises under agentsvar i adminsakvisningen når en leder åpner saken i portalen
- Viser ledernavn, rolle (Organisasjonsleder / Enhetsleder) og tid som har gått
- Global leder- og enhetsledervisninger spores og vises uavhengig — samme UX som FreeScouts native «Kunde vist»

![Ledervisningssporing — «vist»-notat vises under agentsvar i adminsakvisningen](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png)

---

## Sanntidsvarselsklokke *(valgfritt)*

*Hold ledere informert i det øyeblikket noe skjer med bedriftens saker.*

Krever [End-User Portal](https://freescout.net/module/end-user-portal/)-modulen.

- 🔔 Klokkikon med live ulest-antall-merke i EUP-navigasjonslinjen — omposisjoneres automatisk på mobil (ved siden av hamburgermenyen)
- Varsler for: **ny sak**, **agentsvar**, **kundesvar** — for alle lederroller
- Rullegardinpanel med varsler gruppert etter dato: aktørnavn, hendelsestype, saksnummer, meldingsforhåndsvisning, tidsstempel
- **Auto-merk som lest** når lederen åpner saken
- Merk individuelle varsler som lest via ×; **Merk alle som lest** i panelhode
- Spør hvert 15. sekund; oppdateres ved frem/tilbake-navigasjon i nettleseren (bfcache-bevisst)

![Sanntidsvarselsklokke — rullegardinmeny med grupperte uleste varsler](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png)

---

## Varselsabonnementer *(valgfritt)*

*La ledere bestemme hva de hører om — ikke mer, ikke mindre.*

- **Visuell abonnementsmatrise** på «Notifications»-fanen i portalens organisasjonsinnstillinger
- **Tre hendelsestyper:** Ny sak · Agentsvar · Kundesvar
- **To omfangsnivåer:** Hele organisasjonen (globale ledere) · Individuelle strukturelle enheter
- Medlemmer uten enhet er gruppert i en separat **«Ingen enhet»**-utvidbar rad
- **Per-medlem-overstyringer** — utvid hvilken som helst enhetsrad for å vise individuelle medlemmer og slå av/på abonnementene deres inline; enhetsledere med begrenset rolle er merket tilsvarende
- **Kaskadert logikk i begge retninger:**
  - Aktivering av «Hele organisasjonen» → aktiverer alle enheter og alle medlemmer
  - Aktivering av en enhet → aktiverer alle dens medlemmer
  - Deaktivering av et medlem → automatisk avstemmer enhet- og organisasjonsavkrysningsboksene
- Globale ledere administrerer alle medlemmer; enhetsledere administrerer kun sin egen enhet
- Varsler bruker postdriveren til den tilsvarende postboksen

![Varselsabonnementsmatrise — per-enhet og per-medlem-veksler](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png)

---

## Portalens organisasjonsinnstillinger

*Ledere konfigurerer organisasjonsstrukturen uten adminadgang.*

**Organization Settings** i portalnavigasjonen har tre faner:

### Notifications-fanen

Abonnementsmatrisen beskrevet ovenfor.

### Units-fanen *(kun globale ledere)*

- **Opprett enhet** — inline skjema med navnefelt
- **Gi nytt navn til enhet** — inline redigering direkte i tabellraden
- **Slett enhet** — knapp med bekreftelse; enhetsledere nedgraderes automatisk til member
- Antall medlemmer vises per enhet

### Members-fanen

- Tabell over alle organisasjonsmedlemmer: navn, strukturell enhet, rolle, aktiv/inaktiv statusmerke
- **«Global manager»**-etikett vises ved siden av medlemsnavnet der det gjelder
- **Vis deaktiverte**-avkrysningsboks — vises kun når inaktive medlemmer finnes; skjult som standard
- **Globale ledere** kan oppdatere ethvert medlems enhet og rolle med et inline skjema (enhetsvelger + rollevelger + Bruk)
- **Globale ledere kan ikke forfremme et medlem til global leder** fra portalen — dette krever adminadgang
- **Aktiver / deaktiver**-knapp per medlem med bekreftelse for deaktivering

![Portalens organisasjonsinnstillinger — Units og Members-faner](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png)

---

## Flerspråklige varsels-e-postmaler *(valgfritt)*

*Bedriftsklientene dine mottar støtte-e-poster på sitt eget språk — automatisk, uten manuelt arbeid.*

Konfigurert i **Manage → Organizations → Templates tab** (synlig for brukere med «administrer maler»-tillatelse).

- **Per-lokalitetsmaler** — separat emne og kropp for hvert portalspråk; bytt mellom dem med lokalitetsrullegardinmenyen; verdier byttes i minnet uten sideopplasting
- **Sammenleggbare paneler** per hendelsestype (Ny sak / Agentsvar / Kundesvar) — Summernote-editoren initialiseres lat når et panel åpnes
- **Last standard**-knapp i hvert panel — gjenoppretter den innebygde malen for gjeldende valgte lokalitet (faller tilbake til innebygd engelsk hvis ingen lokalitetsspesifikk standard finnes)
- **Summernote WYSIWYG-editor** for rik HTML-e-postkomposisjon
- **Makrovariabelvelger** — sett inn plassholdere i emne eller kropp med ett klikk; markørposisjon bevares i emnefeltet
- **19 innebygde standardmaler** — klare til bruk rett ut av boksen; ingen konfigurasjon nødvendig

**Tilgjengelige makrovariabler:**

| Variabel | Beskrivelse |
|----------|------------|
| `{manager_name}` | Navn på lederen som mottar varselet |
| `{author_name}` | Kunden som opprettet eller svarte på saken |
| `{org_name}` | Organisasjonsnavn |
| `{unit_name}` | Strukturell enhetsnavn |
| `{subject}` | Saksemne |
| `{ticket_number}` | Saks-ID |
| `{ticket_url}` | Direkte lenke til saken i portalen |
| `{ticket_text}` | Fullstendig tekst i den opprinnelige meldingen (HTML) |
| `{reply_text}` | Fullstendig tekst i det siste svaret (HTML) |
| `{created_date}` | Saksopprettelsesdato |
| `{created_time}` | Saksopprettelsestid |
| `{created_datetime}` | Saksopprettelsesdato og -tid |
| `{reply_date}` | Svarsdato |
| `{reply_time}` | Svarsidspunkt |
| `{reply_datetime}` | Svarsdato og -tid |

**Reservekjede:** lagret lokalitetsmal → innebygd lokalitetsmal → lagret engelskmal → innebygd engelskmal

Varselspråket bestemmes av hver leders portalspråkvalg, lagret automatisk når de bruker språkvelgeren.

![E-postmaler — per-lokalitets sammenleggbare paneler, Last standard-knapp, Summernote-editor](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png)

---

## REST API *(valgfritt)*

*Integrer OrgPortal i CRM, ERP eller kundeonboarding-arbeidsflyten din.*

Krever [API and Webhooks](https://freescout.net/module/api-webhooks/)-modulen.

- Full CRUD for organisasjoner, strukturelle enheter, kundemedlemskap og tagger
- **Organisasjonsfelter:** `name`, `color`, `mailboxId`, `isActive` — alle lesbare og oppdaterbare via API
- **Members-underressurs** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — oppdater rolle, enhet, `canManageOrg` og per-medlem `isActive`-flagg uavhengig uten å røre resten av medlemskapet
- **Tags-underressurs** — `GET/PUT /api/organizations/{id}/tags` — list opp eller erstatt fullstendig tagg-tilknytninger (krever Tags-modulen; returnerer `503` hvis inaktiv)
- Autentisering via `X-FreeScout-API-Key`-header eller `api_key`-spørringsparameter
- Interaktiv **ReDoc-dokumentasjon** på **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Fullstendig API-referanse → [docs/api/README.md](docs/api/README.md)**

![Interaktiv API-dokumentasjon — ReDoc med alle OrgPortal-endepunkter](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png)

---

## Installasjon

1. Kopier `OrgPortal`-mappen inn i `Modules/` i FreeScout-installasjonen din
2. Gå til **Manage → Modules → OrgPortal → Activate**
3. Kjør migrasjoner:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Tøm buffer:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Georgisk språkstøtte** distribueres automatisk ved første oppstart — ingen manuell filkopiering nødvendig.

---

## Automatiske oppdateringer

OrgPortal støtter **ett-klikks oppdateringer** via FreeScouts innebygde moduloppdateringsmekanisme.

> **Krever FreeScout 1.8.170 eller nyere.** På eldre versjoner, oppdater manuelt ved å erstatte `OrgPortal`-mappen med den nyeste utgivelses-ZIP-filen.

Når en ny versjon er tilgjengelig, vises et banner på **Manage → Modules**. Klikk **Update now** — FreeScout laster ned og installerer den nyeste versjonen automatisk.

---

## Modulkompatibilitet

| Modul | Status | Merknader |
|-------|--------|----------|
| End-User Portal ≥ 1.0.85 | Valgfri | Lederportal, varselklokke, abonnementer |
| API and Webhooks ≥ 1.0.80 | Valgfri | REST API-endepunkter |
| Kanban ≥ 1.0.23 | Valgfri | Merke på kort, org-filter, flerspråklige State-kolonneetiketter |
| Custom Fields | ✅ Kompatibel | — |
| Workflows | ✅ Kompatibel | — |
| Tags | ✅ Kompatibel | Tagg-chips i org-redigeringsskjema; tagg-tilknytninger via API (`/organizations/{id}/tags`); taggbasert sakstilskriving |

---

## Konfigurasjon

### Globale innstillinger — **Manage → Organizations → System tab**

| Alternativ | Beskrivelse |
|-----------|------------|
| Vis merke på sakssiden | Org-merke i samtalelistene og saksvisning |
| Vis merke på Kanban-kort | Org-merke på Kanban-tavlekort |
| Tilskrivingskilde | `member` / `tag` / `tag_only` — hvordan saker tilskrives organisasjoner |
| Auto-cron etterfylling | Kjør etterfylling hvert 5. minutt automatisk |
| Snapshot-synlighet | Vis/skjul tilskrivingsdata i sakssidepanelet |
| Portalspråkvelger | Aktiver språkvelger i EUP-navigasjonslinjen; velg hvilke av 19 lokaliteter som skal tilbys |

### Per-postboks-innstillinger — **Mailbox Settings → OrgPortal**

| Alternativ | Beskrivelse |
|-----------|------------|
| Vis merke på sakssiden | Aktiver/deaktiver merke for denne postboksen |
| Vis merke på Kanban-kort | Aktiver/deaktiver merke for denne postboksen |
| Vis organisasjonsblokk i kundeprofil | Slå av/på org-infoblokk i sakssidepanelet |
| Bedriftssaks-statusfiltre | Kartlegg Kanban-kolonner til navngitte filtre i portalen; per-språketiketter med lokalitetsvelger; dra for å omordne |

![Per-postboks-innstillinger — merkesynlighet og Kanban-statusfiltre med flerspråklige etiketter](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png)

---

## Oversettelser

OrgPortal er fullt lokalisert på **19 språk**:

| Språk | Kode | Språk | Kode |
|-------|------|-------|------|
| Engelsk | `en` | Nederlandsk | `nl` |
| Ukrainsk | `uk` | Norsk | `no` |
| Tysk | `de` | Dansk | `da` |
| Fransk | `fr` | Svensk | `sv` |
| Spansk | `es` | Finsk | `fi` |
| Italiensk | `it` | Portugisisk (BR) | `pt-BR` |
| Tsjekkisk | `cs` | Portugisisk (PT) | `pt-PT` |
| Slovakisk | `sk` | Rumensk | `ro` |
| Polsk | `pl` | Kinesisk forenklet | `zh-CN` |
| Georgisk | `ka` | | |

Oversettelsesfiler: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Varsels-e-postmaler har innebygde standarder for alle 19 språk.

### Integrasjon av språkvelger

OrgPortal inkluderer en innebygd portalspråkvelger (aktiver i **System tab → Portal Language Switcher**). Den integreres også med [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — begge kan være aktive samtidig.

Språket en leder velger gjelder for alle OrgPortal UI-strenger og lagres som deres varselspråk — e-poster sendes automatisk på det valgte språket.

> **Teknisk merknad:** `OrgPortalSetLocale`-mellomvare re-anvender portallokaliteten etter FreeScouts `Localize`-mellomvare for å forhindre at den tilbakestilles til systemstandarden ved hver forespørsel.

---

## Skjermbilder

| | |
|---|---|
| ![Organisasjonsliste](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png) | ![Organisasjonsredigering](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png) |
| *Organisasjonsliste — statusfilter, livesøk, fargemerker* | *Organisasjonsredigering — fargevelger, tagg-chips, medlemstabell* |
| ![System-fanen](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png) | ![Kunderedigering](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png) |
| *System-fanen — tilskrivingsmoduser, etterfylling, språkvelger* | *Kunderedigering — org-felt med autofullføring* |
| ![Bedriftssaksportal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png) | ![Portalsvar](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png) |
| *Bedriftssaker — tabell, forfatterfilter, lesestatusindikatorer* | *Portalsak — svar med vedlegg, lukket-banner* |
| ![Portalens organisasjonsinnstillinger](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png) | ![Varselklokke](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png) |
| *Portalens org-innstillinger — Units og Members-faner* | *Sanntidsvarselklokke med rullegardinmeny* |
| ![Abonnementsmatrise](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png) | ![E-postmaler](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png) |
| *Varselsabonnementsmatrise — per-enhet, per-medlem* | *E-postmaler — lokalitetsvelger, Last standard, Summernote* |
| ![Kanban-integrasjon](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png) | ![Postboksinnstillinger](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png) |
| *Kanban — org-merker og org-filtermodal* | *Per-postboks-innstillinger — Kanban-filtre med flerspråklige etiketter* |
| ![API-dokumentasjon](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png) | |
| *Interaktiv API-dokumentasjon — ReDoc* | |

---

## Lisens

[MIT](LICENSE) — © 2026 ASTIN-UA
