# OrgPortal — B2B-organisationsstyringsmodul til FreeScout

[← Tilbage til README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B module" width="140" align="right">

**OrgPortal** er et FreeScout-modul, der tilføjer fuld **B2B-organisationsstyring** til dit helpdesk: gruppér kunder i virksomheder, definer afdelingshierarkier, giv virksomhedsledere en selvbetjeningsportal, og automatisér notifikationer — alt inden i FreeScout, uden eksterne værktøjer.

> Leder du efter en måde at administrere virksomhedskonti i FreeScout? At give erhvervskunder deres egen supportportal? At styre hvilke sager hver B2B-kontakt kan se baseret på deres rolle og afdeling? OrgPortal løser alt dette.

**Virker med:** FreeScout 1.8.147+  
**Valgfrie integrationer:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Også tilgængelig på:**
[Українська](docs/README.uk.md) · [Deutsch](docs/README.de.md) · [Français](docs/README.fr.md) · [Español](docs/README.es.md) · [Italiano](docs/README.it.md) · [Polski](docs/README.pl.md) · [Čeština](docs/README.cs.md) · [Slovenčina](docs/README.sk.md) · [Nederlands](docs/README.nl.md) · [Norsk](docs/README.no.md) · [Dansk](docs/README.da.md) · [Svenska](docs/README.sv.md) · [Suomi](docs/README.fi.md) · [Português (BR)](docs/README.pt-BR.md) · [Português (PT)](docs/README.pt-PT.md) · [Română](docs/README.ro.md) · [中文 (简体)](docs/README.zh-CN.md)

---

## Hvad OrgPortal tilføjer til FreeScout

FreeScout er bygget omkring individuelle kunder — hver e-mail er fra en person, og der er intet indbygget koncept om en virksomhed, denne person arbejder for. Det fungerer fint til B2C-helpdesks. For B2B er det utilstrækkeligt.

OrgPortal udfylder dette hul:

- **Virksomhedskonti** — gruppér kunder i organisationer med navn, farve-badge, postkassescope og aktiv/inaktiv-status
- **Afdelingshierarkier** — opdel organisationer i strukturelle enheder (afdelinger, filialer, teams); hvert medlem er begrænset til sin enhed
- **Rollebaseret adgang** — `member` ser kun egne sager; `unit_manager` ser hele enheden; `manager` ser hele organisationen
- **Virksomheders selvbetjeningsportal** — ledere ser alle virksomhedens sager, svarer, lukker, omtildeler forfattere og administrerer notifikationspræferencer uden at kontakte dit team
- **Permanent sagsattribuering** — hver sag får et snapshot til sin organisation ved oprettelse; historisk rapportering overlever ændringer i kundeoversigten
- **Flersprogede notifikationer** — automatiske e-mailadvarsler på hver leders eget sprog, med per-locale-skabeloner og en indbygget WYSIWYG-editor
- **REST API** — synkronisér medlemskaber fra dit CRM, automatisér onboarding, administrér tags programmatisk

---

## Organisationer

*Ét sted for alt om en virksomhedskonto.*

**Manage → Organizations** åbner en fanebaseret grænseflade med tre sektioner: Organizations, Templates og System.

### Organisationsliste

- **Opret, rediger, slet, aktivér/deaktivér** organisationer
- **Statusfilter** — skift mellem Aktiv / Inaktiv / Alle med en radiogruppe; filtrerer tabellen klientsidigt med det samme
- **Live-søgning** — begynder filtrering ved 2+ tegn, ingen sideopdatering
- **Farvekodede badges** — interaktiv farvevælger med 12 nuancer og en live badge-forhåndsvisning ved siden af vælgeren; badge vises på alle sager og Kanban-kort
- Klik på badge eller sagsantal åbner en FreeScout-søgning filtreret til den pågældende organisation
- **Postkassebinding** — organisationer kan være globale (alle postkasser) eller begrænset til en specifik postkasse
- **Tags-kolonne** — viser ✓/✗ om der er FreeScout-tags knyttet til organisationen (Tags-modul påkrævet); tags tildeles i redigeringsformularen med et chip-baseret widget og autosøgning
- **Sagsantal-kolonne** — samlede samtaler per organisation; klikbart link til fulde søgeresultater
- **Medlemsantal**-kolonne
- **Aktivér / deaktivér** — suspendér en konto uden at miste historik; kræver at Org Snapshot er aktiveret (knappen er deaktiveret med et tooltip når det ikke er)
- **Slet** — kun tilgængelig når organisationen har 0 medlemmer og 0 sager (sikkerhedsvagt)
- Alle slet- og deaktiveringshandlinger kræver bekræftelse

![Organisationsliste — statusfilter, live-søgning, farvebadges, tags, sagsantal](docs/screenshots/org-list.png)

### Organisationsredigeringsformular

- **Navn** og **postkassebinding**
- **Farvevælger** — 12 nuancer med live badge-forhåndsvisning
- **Tags** — chip-baseret widget: skriv for at søge eksisterende FreeScout-tags, klik for at tilføje, × for at fjerne
- **Medlemstabel** — per medlem: navn, rolle, strukturel enhed, `can_manage_org`-afkrydsningsfelt (giver administratoradgang til organisationer uden fulde administratorrettigheder), aktiv/inaktiv-toggle
- **Panel for strukturelle enheder** — opret og omdøb enheder direkte i redigeringsformularen; medlemmer tildeles enheder i samme visning
- **Tilføjelse af et medlem** — backfiller automatisk eksisterende ikke-tilskrevne samtaler for denne kunde

![Organisationsredigering — farvevælger, tag-chips, medlemstabel med roller og enheder](docs/screenshots/org-edit.png)

### Kundeprofil-integration

- **Organisationsfelt i FreeScout-kundeformularen** — live autosøgning efter organisationer; rolledropdown vises efter valg af en org; ×-knap til fjernelse
- **"View org tickets"**-genvejslink i kundeformularen
- **Org-infoblock i adminsagens sidepanel** — organisationsnavn (klikbart link til org-redigeringssiden), strukturel enhed og medlemsrolle; slå synlighed til/fra per postkasse i indstillinger
- **Ét aktivt medlemskab per kunde** — en kunde kan ikke tilføjes til en anden organisation, mens de har et aktivt medlemskab; inaktive/arkiverede medlemskaber er tilladt

![Kunderediger — organisationsfelt med autosøgning og rolevælger](docs/screenshots/customer-org-field.png)

---

## Strukturelle enheder — Adgangskontrol på afdelingsniveau

*Understøt store virksomheder med komplekse interne hierarkier.*

Organisationer kan opdeles i ubegrænsede **strukturelle enheder** (afdelinger, filialer, regionale kontorer, projektteams):

- Opret, omdøb og slet enheder i adminorganisationsredigeringsformularen, eller direkte fra portalen (kun globale ledere)
- Tildel medlemmer til enheder — hvert medlem tilhører én enhed
- **Sletning af en enhed** demoverer automatisk dens `unit_manager`-medlemmer til `member`

**Tre rolleniveauer:**

| Rolle | Adgangsomfang |
|-------|--------------|
| `member` | Kun egne sager |
| `unit_manager` | Alle sager inden for deres strukturelle enhed |
| `manager` (global) | Alle sager på tværs af hele organisationen |

- Enhedsledere har fuld portalfunktionalitet — svar, vedhæftninger, forfatteromtildeling, luk/genåbn, notifikationsstyring — strengt begrænset til deres enhed
- Sagsadgang og notifikationslevering håndhæves ved enhedsgrænser

![Organisationsredigering — medlemmer med roller og enheder, enhedsstyringspanel](docs/screenshots/org-edit.png)

---

## Org Snapshot — Permanent sagsattribuering

*Pålidelig historisk rapportering selv når din kundeliste ændrer sig.*

Når en sag oprettes, registrerer OrgPortal organisationskonteksten som et permanent snapshot:

- `org_id`, `org_unit_id` og `org_attributed_at` skrives til samtalen ved oprettelsestidspunktet
- **Uforanderlig** — hvis en kunde senere forlader en organisation, forbliver deres historiske sager tilskrevet den org; rapportering bryder aldrig
- **Tilføjelse af et medlem** udløser automatisk backfill af kundens eksisterende ikke-tilskrevne samtaler

### Attributeringskilder — tre tilstande

Konfigureres i **Manage → Organizations → System tab**:

| Tilstand | Adfærd |
|----------|--------|
| `member` | Tilskriver sagen til den organisation, som sagsforfatteren er medlem af |
| `tag` | Tilskriver efter FreeScout-tag knyttet til en org først; falder tilbage til medlemskab hvis intet tag matcher |
| `tag_only` | Tilskriver udelukkende efter tag; medlemskab bruges ikke |

`tag` og `tag_only`-tilstande er deaktiveret når Tags-modulet er inaktivt.

### Backfill-værktøjer

- **Statuslinje** — viser X / Y sager tilskrevet (%) med en "færdig"-indikator når det er gjort
- **Preflight-statistik** — inden backfill køres vises en oversigt over hvor mange sager der tilskrives via tag vs. via medlemskab vs. umatchede
- **Kør backfill**-knap — behandler op til 2000 sager per klik; resultatoversigt (by_tag / by_member / unmatched) vises bagefter
- **Auto-cron** (`attribution_cron_enabled`) — planlægger backfill hvert 5. minut, 1000 sager per kørsel, uden overlap
- **Nulstil attributering** — rydder alle org-snapshots (farlig handling, kræver bekræftelse)
- Kommandolinje: `php artisan orgportal:backfill-attribution`

![Systemfane — attributeringskilde, statuslinje, preflight-statistik, backfill-kontroller](docs/screenshots/attribution-settings.png)

---

## Kanban-integration

*Hold dit visuelle workflow i overensstemmelse med dine B2B-konti.*

- Organisationsbadge på hvert Kanban-kort med kontoens tildelte farve
- **Organisationsfilter** i Kanban-filterpanelet — flervalgsmodal med afkrydsningsfelter; filterstatus bevares på tværs af navigation
- **Flersprogede Kanban-statusfilterlabels** — giv hver Kanban-kolonne et tilpasset navn per portalsprog; skift locales med sprogvælgeren i per-postkasse-indstillinger; træk for at omarrangere filtre
- Oversatte labels vises både i portalfilterlinjen og i **Tilstand**-kolonnen i virksomhedssagstabellen; fallback-kæde: gemt locale → gemt engelsk → originalt kolonnenavn

![Kanban — organisationsbadges på kort og org-filtermodal](docs/screenshots/kanban-org.png)

---

## Adgangskontrol og tilladelser

*Delegér organisationsstyring uden at give administratoradgang.*

- **"Tillad styring af organisationer"** (`can_manage_org`) — to niveauer:
  - Som **brugertilladelse** i agentindstillinger — lader en supportteamleder administrere alle organisationer uden administratorrettigheder
  - Som **per-medlem-flag** i organisationsredigeringsformularen — lader et specifikt org-medlem administrere netop den organisation fra adminpanelet
- **"Tillad styring af notifikationsskabeloner"** — separat granulær tilladelse til skabelonredigering
- Sletning af organisationer forbliver udelukkende administratorekslusivt
- Portaladgang er strengt begrænset per postkasse: en leder fra Organisation A kan ikke tilgå Organisation B

![Granulære tilladelser — tillad styring af organisationer og notifikationsskabeloner](docs/screenshots/user-permissions.png)

---

## Systemindstillinger — Manage → Organizations → System tab

*Administratoreksklusive kontroller for attributering, backfill og portalsprogsskifter.*

**System**-fanen er kun synlig for FreeScout-administratorer.

### Panel 1: Sagsattribuering

Se [Org Snapshot](#org-snapshot--permanent-sagsattribuering) ovenfor for den fulde beskrivelse af attributeringstilstande, backfill-værktøjer og auto-cron.

### Panel 2: Portalsprogsskifter

- **Aktivér/deaktivér** sprogskifteren i End-User Portal-navigationslinjen
- **Vælg hvilke af de 19 locales** der tilbydes (afkrydsningsfeltgitter); alle er aktiveret som standard
- Når aktiveret kan ledere skifte portalsprog; deres valg gemmes og bruges til notifikationse-mails
- Dette er OrgPortals indbyggede sprogskifter — den fungerer uafhængigt af ethvert tredjepartssprogskiftemodul; begge kan sameksistere

![Systemfane — portalsprogsskifterpanel med locale-afkrydsningsfelter](docs/screenshots/system-settings.png)

---

## End-User Portal — Selvbetjening for virksomhedsledere *(valgfrit)*

*Giv dine B2B-kunder en portal, hvor de administrerer deres virksomheds supportrelation — uden at kontakte dit team for hver statusopdatering.*

Kræver [End-User Portal](https://freescout.net/module/end-user-portal/)-modulet.

### Virksomhedssager-dashboard

En dedikeret **Company Tickets**-sektion i portalnavigationen med en fuldt udstyret sagstabel:

| Kolonne | Beskrivelse |
|---------|-------------|
| **#** | Sags-ID |
| **Emne** | Afkortet med tooltip ved hover |
| **Ansvarlig** | Tildelt supportagent |
| **Forfatter** | Kunde der åbnede sagen; klik for at filtrere efter denne forfatter |
| **Status** | Aktiv / Afventer / Lukket / Spam med ikoner |
| **Tilstand** | Kanban-kolonnenavn i det aktuelle portalsprog (kun når Kanban-modulet er aktivt) |
| **Opdateret** | Dato og tid for seneste svar |

**To uafhængige læsestatusindikatorer per række** — disse sporer to forskellige personer og vises simultant:

| Indikator | Hvis læsestatus | Hvad det betyder |
|-----------|----------------|------------------|
| **Fed række** | Den leder der ser portalen | Lederen har ulæste notifikationer for denne samtale — noget skete som de ikke har set endnu |
| **👁 Øje-ikon** | Sagsforfatteren (kunden der indsendte den) | Forfatteren har endnu ikke åbnet det seneste agentsvar — nyttigt til at vide om en klient faktisk så svaret |

Disse to tilstande er helt uafhængige: en række kan være fed (leder har ikke læst) mens øjet er fraværende (forfatter har allerede læst), eller omvendt. Lederen ser begge på samme tid, og får et komplet billede af hvad der sker på begge sider af sagen uden at åbne den.

**Forfatterfilter** — klik på et forfatternavn aktiverer et filter; et banner vises øverst i tabellen med den aktive forfatters navn med et ×-link til at rydde filteret.

Både desktoptabellen og et responsivt **mobilkortlayout** er inkluderet; de skifter automatisk baseret på skærmbredde.

Filterlinjen understøtter **tilsidesættelse** via `enduserportal::partials.tickets_filters` — placer en tilpasset visning på den sti for at erstatte OrgPortals standardfilterlinje mens al anden funktionalitet bevares.

![Virksomhedssager — fuld tabel med læseindikatorer, forfatterfilter-banner, statusfiltre](docs/screenshots/portal-tickets.png)

### Sagshandlinger i portalen

Ledere kan handle direkte — ingen grund til at kontakte support:

- **Svar med vedhæftninger** — træk & slip, flere filer per svar; vedhæftningsnavne og filstørrelser vises på hver tråd
- **Luk sag** — et nyt svar genåbner den automatisk; et banner informerer lederen om dette når sagen er lukket
- **Skift sagsforfattere** — omtildel en sag til et andet organisationsmedlem
- **Filtrer efter enhed** — globale ledere filtrerer saglisten efter strukturel enhed
- **Filtrer efter Kanban-status** — konfigurerbar per postkasse, labels vises i det aktuelle portalsprog

![Portalsagsvisning — svarformular med træk & slip-vedhæftninger og lukket-sag-banner](docs/screenshots/portal-reply.png)

### Sporing af ledervisning

- En **"set"**-note vises under agentsvar i adminosagsvisningen når en leder åbner sagen i portalen
- Viser ledernavn, rolle (Organisationsleder / Enhedsleder) og forløbet tid
- Global leder- og enhedsledervisninger spores og vises uafhængigt — samme UX som FreeScouts native "Customer viewed"

![Sporing af ledervisning — 'set'-note vises under agentsvar i adminosagsvisning](docs/screenshots/manager-viewed.png)

---

## Realtids-notifikationsklokke *(valgfrit)*

*Hold ledere informeret i det øjeblik noget sker med deres virksomheds sager.*

Kræver [End-User Portal](https://freescout.net/module/end-user-portal/)-modulet.

- 🔔 Klokke-ikon med live ulæst-tæller-badge i EUP-navigationslinjen — placerer sig automatisk på mobil (ved siden af hamburgermenuen)
- Notifikationer for: **ny sag**, **agentsvar**, **kundesvar** — for alle lederroller
- Dropdown-panel med notifikationer grupperet efter dato: aktørnavn, hændelsestype, sagsnummer, beskedforhåndsvisning, tidsstempel
- **Auto-markér som læst** når lederen åbner sagen
- Markér individuelle notifikationer som læst via ×; **Markér alle som læst** i panel-header
- Poller hvert 15. sekund; opdaterer ved browser frem/tilbage-navigation (bfcache-bevidst)

![Realtids-notifikationsklokke — dropdown med grupperede ulæste notifikationer](docs/screenshots/portal-bell.png)

---

## Notifikationsabonnementer *(valgfrit)*

*Lad ledere bestemme hvad de hører om — hverken mere eller mindre.*

- **Visuelt abonnementsmatrix** på "Notifications"-fanen i portalorganisationsindstillinger
- **Tre hændelsestyper:** Ny sag · Agentsvar · Kundesvar
- **To omfangsniveauer:** Hele organisationen (globale ledere) · Individuelle strukturelle enheder
- Medlemmer uden en enhed grupperes i en separat **"Ingen enhed"**-udvidelig række
- **Per-medlem-tilsidesættelser** — udvid enhver enhedsrække for at afsløre individuelle medlemmer og slå deres abonnementer til/fra inline; enhedsledere med scoped rolle er markeret tilsvarende
- **Kaskadelogik i begge retninger:**
  - Aktivering af "Hele organisationen" → aktiverer alle enheder og alle medlemmer
  - Aktivering af en enhed → aktiverer alle dens medlemmer
  - Deaktivering af et medlem → auto-afstemmer enheds- og organisationsafkrydsningsfelterne
- Globale ledere administrerer alle medlemmer; enhedsledere administrerer kun deres egen enhed
- Notifikationer bruger mail-driveren for den tilsvarende postkasse

![Notifikationsabonnementsmatrix — per-enhed og per-medlem-toggles](docs/screenshots/portal-subscriptions.png)

---

## Portalorganisationsindstillinger

*Ledere konfigurerer deres organisationsstruktur uden administratoradgang.*

**Organization Settings** i portalnavigationen har tre faner:

### Notifikationsfane

Abonnementsmatrixet beskrevet ovenfor.

### Enhedsfane *(kun globale ledere)*

- **Opret enhed** — inline formular med navnefelt
- **Omdøb enhed** — inline redigering direkte i tabelrækken
- **Slet enhed** — knap med bekræftelse; enhedsledere demoveres automatisk til member
- Medlemsantal vises per enhed

### Medlemsfane

- Tabel over alle organisationsmedlemmer: navn, strukturel enhed, rolle, aktiv/inaktiv-statusbadge
- **"Global leder"**-label vises ved siden af medlemsnavnet hvor det er relevant
- **Vis deaktiverede**-afkrydsningsfelt — vises kun når inaktive medlemmer eksisterer; skjult som standard
- **Globale ledere** kan opdatere ethvert medlems enhed og rolle med en inline formular (enhedsvælger + rolevælger + Anvend)
- **Globale ledere kan ikke forfremme et medlem til global leder** fra portalen — dette kræver administratoradgang
- **Aktivér / deaktivér**-knap per medlem med bekræftelse for deaktivering

![Portalorganisationsindstillinger — Enheder og Medlemmer-faner](docs/screenshots/portal-settings.png)

---

## Flersprogede notifikations-e-mailskabeloner *(valgfrit)*

*Dine erhvervskunder modtager supporte-mails på deres eget sprog — automatisk, uden manuel indsats.*

Konfigureres i **Manage → Organizations → Templates tab** (synlig for brugere med "manage templates"-tilladelse).

- **Per-locale-skabeloner** — separat emne og brødtekst for hvert portalsprog; skift mellem dem med locale-dropdown; værdier byttes i hukommelsen uden sideopdatering
- **Sammenfoldelige paneler** per hændelsestype (Ny sag / Agentsvar / Kundesvar) — Summernote-editor initialiseres dovent når et panel åbnes
- **Indlæs standard**-knap i hvert panel — gendanner den indbyggede skabelon for det aktuelt valgte locale (falder tilbage til engelsk inbygget hvis der ikke eksisterer en locale-specifik standard)
- **Summernote WYSIWYG-editor** til rig HTML-e-mailsammensætning
- **Makrovariabelvælger** — indsæt pladsholdere i emne eller brødtekst med ét klik; markørposition bevares i emnefeltet
- **19 indbyggede standardskabeloner** — klar til brug ud af boksen; ingen konfiguration nødvendig

**Tilgængelige makrovariabler:**

| Variabel | Beskrivelse |
|----------|-------------|
| `{manager_name}` | Navn på den leder der modtager notifikationen |
| `{author_name}` | Kunde der oprettede eller svarede på sagen |
| `{org_name}` | Organisationsnavn |
| `{unit_name}` | Navn på strukturel enhed |
| `{subject}` | Sagsemne |
| `{ticket_number}` | Sags-ID |
| `{ticket_url}` | Direkte link til sagen i portalen |
| `{ticket_text}` | Fuld tekst af den første besked (HTML) |
| `{reply_text}` | Fuld tekst af det seneste svar (HTML) |
| `{created_date}` | Sagsoprettelsesdato |
| `{created_time}` | Sagsoprettelsestidspunkt |
| `{created_datetime}` | Sagsoprettelsesdato og -tidspunkt |
| `{reply_date}` | Svarsdato |
| `{reply_time}` | Svarstidspunkt |
| `{reply_datetime}` | Svarsdato og -tidspunkt |

**Fallback-kæde:** gemt locale-skabelon → indbygget locale-skabelon → gemt engelsk skabelon → indbygget engelsk skabelon

Notifikationssproget bestemmes af hver leders portalsprogvalg, gemt automatisk når de bruger sprogskifteren.

![E-mailskabeloner — per-locale sammenfoldelige paneler, Indlæs standard-knap, Summernote-editor](docs/screenshots/admin-templates.png)

---

## REST API *(valgfrit)*

*Integrer OrgPortal i dit CRM, ERP eller kundens onboarding-workflow.*

Kræver [API and Webhooks](https://freescout.net/module/api-webhooks/)-modulet.

- Fuld CRUD for organisationer, strukturelle enheder, kundemedlemskaber og tags
- **Organisationsfelter:** `name`, `color`, `mailboxId`, `isActive` — alle læsbare og opdaterbare via API
- **Medlemmer-underressource** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — opdatér rolle, enhed, `canManageOrg` og per-medlem `isActive`-flag uafhængigt uden at røre resten af medlemskabet
- **Tags-underressource** — `GET/PUT /api/organizations/{id}/tags` — list eller erstat fuldt tag-bindinger (kræver Tags-modul; returnerer `503` hvis inaktivt)
- Autentificering via `X-FreeScout-API-Key`-header eller `api_key`-forespørgselsparameter
- Interaktiv **ReDoc-dokumentation** ved **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Fuld API-reference → [docs/api/README.md](docs/api/README.md)**

![Interaktiv API-dokumentation — ReDoc med alle OrgPortal-endpoints](docs/screenshots/api-docs.png)

---

## Installation

1. Kopiér `OrgPortal`-mappen til `Modules/` i din FreeScout-installation
2. Gå til **Manage → Modules → OrgPortal → Activate**
3. Kør migreringer:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Ryd cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Georgisk sprogunderstøttelse** installeres automatisk ved første opstart — ingen manuel filkopiering nødvendig.

---

## Automatiske opdateringer

OrgPortal understøtter **ét-klik-opdateringer** via FreeScouts indbyggede modulopdateringsmekanisme.

> **Kræver FreeScout 1.8.170 eller nyere.** På ældre versioner opdateres manuelt ved at erstatte `OrgPortal`-mappen med den seneste udgivelses-ZIP.

Når en ny version er tilgængelig, vises et banner på **Manage → Modules**. Klik **Update now** — FreeScout downloader og installerer automatisk den seneste version.

---

## Modulkompatibilitet

| Modul | Status | Noter |
|-------|--------|-------|
| End-User Portal ≥ 1.0.85 | Valgfrit | Lederportal, notifikationsklokke, abonnementer |
| API and Webhooks ≥ 1.0.80 | Valgfrit | REST API-endpoints |
| Kanban ≥ 1.0.23 | Valgfrit | Badge på kort, org-filter, flersprogede Tilstand-kolonner-labels |
| Custom Fields | ✅ Kompatibel | — |
| Workflows | ✅ Kompatibel | — |
| Tags | ✅ Kompatibel | Tag-chips på org-redigeringsformular; tag-bindinger via API (`/organizations/{id}/tags`); tag-baseret sagsattribuering |

---

## Konfiguration

### Globale indstillinger — **Manage → Organizations → System tab**

| Indstilling | Beskrivelse |
|-------------|-------------|
| Vis badge på sagsside | Org-badge i samtaleliste og sagsvisning |
| Vis badge på Kanban-kort | Org-badge på Kanban-boardkort |
| Attributeringskilde | `member` / `tag` / `tag_only` — hvordan sager tilskrives organisationer |
| Auto-cron backfill | Kør backfill hvert 5. minut automatisk |
| Snapshot-synlighed | Vis/skjul attributeringsdata i sagens sidepanel |
| Portalsprogsskifter | Aktivér sprogskifter i EUP-navigationslinjen; vælg hvilke af 19 locales der tilbydes |

### Per-postkasse-indstillinger — **Mailbox Settings → OrgPortal**

Tilsidesætter globale værdier for den specifikke postkasse.

| Indstilling | Beskrivelse |
|-------------|-------------|
| Vis badge på sagsside | Aktivér/deaktivér badge for denne postkasse |
| Vis badge på Kanban-kort | Aktivér/deaktivér badge for denne postkasse |
| Vis organisationsblok i kundeprofil | Slå org-infoblock til/fra i sagens sidepanel |
| Virksomhedssagsstatusfiltre | Kortlæg Kanban-kolonner til navngivne filtre i portalen; per-sprogslabels med locale-skifter; træk for at omarrangere |

![Per-postkasse-indstillinger — badge-synlighed og Kanban-statusfiltre med flersprogede labels](docs/screenshots/mailbox-settings.png)

---

## Oversættelser

OrgPortal er fuldt lokaliseret på **19 sprog**:

| Sprog | Kode | Sprog | Kode |
|-------|------|-------|------|
| Engelsk | `en` | Nederlandsk | `nl` |
| Ukrainsk | `uk` | Norsk | `no` |
| Tysk | `de` | Dansk | `da` |
| Fransk | `fr` | Svensk | `sv` |
| Spansk | `es` | Finsk | `fi` |
| Italiensk | `it` | Portugisisk (BR) | `pt-BR` |
| Tjekkisk | `cs` | Portugisisk (PT) | `pt-PT` |
| Slovakisk | `sk` | Rumænsk | `ro` |
| Polsk | `pl` | Forenklet kinesisk | `zh-CN` |
| Georgisk | `ka` | | |

Overættelsesfiler: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Notifikations-e-mailskabeloner har indbyggede standarder for alle 19 sprog.

### Integration af sprogskifter

OrgPortal inkluderer en indbygget portalsprogsskifter (aktivér i **System tab → Portal Language Switcher**). Den integrerer også med [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — begge kan være aktive samtidig.

Det sprog en leder vælger gælder for alle OrgPortal-UI-strenge og gemmes som deres notifikationssprog — e-mails sendes på deres valgte sprog automatisk.

> **Teknisk note:** `OrgPortalSetLocale`-middleware genanvender portal-locale efter FreeScouts `Localize`-middleware for at forhindre at det nulstilles til systemstandarden ved hver anmodning.

---

## Skærmbilleder

| | |
|---|---|
| ![Organisationsliste](docs/screenshots/org-list.png) | ![Organisationsredigering](docs/screenshots/org-edit.png) |
| *Organisationsliste — statusfilter, live-søgning, farvebadges* | *Organisationsredigering — farvevælger, tag-chips, medlemstabel* |
| ![Systemfane](docs/screenshots/system-settings.png) | ![Kunderediger](docs/screenshots/customer-org-field.png) |
| *Systemfane — attributeringstilstande, backfill, sprogskifter* | *Kunderediger — org-felt med autosøgning* |
| ![Virksomhedssager-portal](docs/screenshots/portal-tickets.png) | ![Portalsvar](docs/screenshots/portal-reply.png) |
| *Virksomhedssager — tabel, forfatterfilter, læseindikatorer* | *Portalsag — svar med vedhæftninger, lukket-banner* |
| ![Portalorganisationsindstillinger](docs/screenshots/portal-settings.png) | ![Notifikationsklokke](docs/screenshots/portal-bell.png) |
| *Portal org-indstillinger — Enheder og Medlemmer-faner* | *Realtids-notifikationsklokke med dropdown* |
| ![Abonnementsmatrix](docs/screenshots/portal-subscriptions.png) | ![E-mailskabeloner](docs/screenshots/admin-templates.png) |
| *Notifikationsabonnementsmatrix — per-enhed, per-medlem* | *E-mailskabeloner — locale-skifter, Indlæs standard, Summernote* |
| ![Kanban-integration](docs/screenshots/kanban-org.png) | ![Postkasseindstillinger](docs/screenshots/mailbox-settings.png) |
| *Kanban — org-badges og org-filtermodal* | *Per-postkasse-indstillinger — Kanban-filtre med flersprogede labels* |
| ![API-dokumentation](docs/screenshots/api-docs.png) | |
| *Interaktiv API-dokumentation — ReDoc* | |

---

## Licens

[MIT](LICENSE) — © 2026 ASTIN-UA
