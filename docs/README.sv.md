# OrgPortal — B2B-organisationshanteringsmodul för FreeScout

[← Tillbaka till README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B-modul" width="140" align="right">

**OrgPortal** är en FreeScout-modul som lägger till fullständig **B2B-organisationshantering** i din helpdesk: gruppera kunder i företag, definiera avdelningshierarkier, ge företagschefer en självbetjäningsportal och automatisera aviseringar — allt inuti FreeScout, utan externa verktyg.

> Letar du efter ett sätt att hantera företagskonton i FreeScout? Att ge företagskunder en egen supportportal? Att kontrollera vilka ärenden varje B2B-kontakt kan se baserat på sin roll och avdelning? OrgPortal löser allt detta.

**Fungerar med:** FreeScout 1.8.147+  
**Valfria integrationer:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/), [Custom Fields](https://freescout.net/module/custom-fields/)

> [!IMPORTANT]
> **Installera alltid från den [senaste versionen](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest), inte från källkoden i repositoriet.**
> Ladda ner `OrgPortal.zip` från Releases-sidan — den innehåller rätt mappstruktur som FreeScout kräver.
> Att ladda ner källkoden (via "Code → Download ZIP" eller `git clone`) **fungerar inte** och förstör modulstrukturen.
> Automatiska uppdateringar kräver också att release-ZIP-en användes för den initiala installationen.

---

🌐 **Även tillgänglig på:**
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

## Innehållsförteckning

- [Vad OrgPortal lägger till i FreeScout](#vad-orgportal-lägger-till-i-freescout)
- [Organisationer](#organisationer)
- [Strukturella enheter — Åtkomstkontroll på avdelningsnivå](#strukturella-enheter--åtkomstkontroll-på-avdelningsnivå)
- [Emaildomäner — Automatisk medlemskap](#emaildomäner--automatisk-medlemskap)
- [Org Snapshot — Permanent ärendetillskrivning](#org-snapshot--permanent-ärendetillskrivning)
- [Kanban-integration](#kanban-integration)
- [Integration med anpassade fält](#integration-med-anpassade-fält)
- [Åtkomstkontroll och behörigheter](#åtkomstkontroll-och-behörigheter)
- [Systeminställningar](#systeminställningar--manage--organizations--system-tab)
- [End-User Portal — Självbetjäning för företagschefer](#end-user-portal--självbetjäning-för-företagschefer-valfritt)
- [Aviseringsklocka i realtid](#aviseringsklocka-i-realtid-valfritt)
- [Aviseringsprenumerationer](#aviseringsprenumerationer-valfritt)
- [Portalorganisationsinställningar](#portalorganisationsinställningar)
- [Flerspråkiga aviseringsmeddelande-mallar](#flerspråkiga-aviseringsmeddelande-mallar-valfritt)
- [REST API](#rest-api-valfritt)
- [Installation](#installation)
- [Automatiska uppdateringar](#automatiska-uppdateringar)
- [Modulkompatibilitet](#modulkompatibilitet)
- [Konfiguration](#konfiguration)
- [Översättningar](#översättningar)
- [Skärmdumpar](#skärmdumpar)
- [Licens](#licens)

---

## Vad OrgPortal lägger till i FreeScout

FreeScout är byggt kring enskilda kunder — varje e-post är från en person och det finns inget inbyggt koncept för det företag personen arbetar på. Detta fungerar bra för B2C-helpdeskar. För B2B räcker det inte.

OrgPortal fyller den luckan:

- **Företagskonton** — gruppera kunder i organisationer med namn, färgmärkning, postlådeomfattning och aktiv/inaktiv status
- **Automatisk medlemskap genom e-postdomän** — binda `company.com` till en organisation och varje kund som skriver från den domänen registreras och tilldelas automatiskt
- **Avdelningshierarkier** — dela upp organisationer i strukturella enheter (avdelningar, filialer, team); varje medlem är begränsad till sin enhet
- **Rollbaserad åtkomst** — `member` ser bara egna ärenden; `unit_manager` ser hela enheten; `manager` ser hela organisationen
- **Företagsselfservice-portal** — chefer visar alla företagsärenden, svarar, stänger, omtilldelar författare och hanterar aviseringsinställningar utan att kontakta ditt team
- **Permanent ärendetillskrivning** — varje ärende ögonblicksbildas till sin organisation vid skapandet; historisk rapportering överlever förändringar i kundlistan
- **Flerspråkiga aviseringar** — automatiserade e-postaviseringar på varje chefs eget språk, med per-locale-mallar och en inbyggd WYSIWYG-redigerare
- **REST API** — synkronisera medlemskap från ditt CRM, automatisera onboarding, hantera taggar programmatiskt

---

## Organisationer

*Ett ställe för allt om ett företagskonto.*

**Manage → Organizations** öppnar ett flikgränssnitt med tre avsnitt: Organizations, Templates och System.

### Organisationslista

- **Skapa, redigera, ta bort, aktivera/inaktivera** organisationer
- **Statusfilter** — växla mellan Active / Inactive / All med en radiogrupp; filtrerar tabellen direkt på klientsidan
- **Livesökning** — börjar filtrera vid 2+ tecken, ingen sidomladdning
- **Färgkodade märken** — interaktiv färgväljare med 12 nyanser och en liveförhandsgranskning av märket bredvid väljaren; märket visas på varje ärende och Kanban-kort
- Att klicka på märket eller ärendeantalet öppnar en FreeScout-sökning filtrerad till den organisationen
- **Postlådebindning** — organisationer kan vara globala (alla postlådor) eller begränsade till en specifik postlåda
- **Taggkolumn** — visar ✓/✗ om några FreeScout-taggar är bundna till organisationen (Tags-modulen krävs); taggar tilldelas i redigeringsformuläret med en chip-baserad widget och autokompletteringssökning
- **Ärendeantalskolumn** — totala konversationer per organisation; klickbar länk till fullständiga sökresultat
- **Kolumn för medlemsantal**
- **Aktivera / inaktivera** — avbryt ett konto utan att förlora historik; kräver att Org Snapshot är aktiverat (knappen är inaktiverad med ett verktygstips om det inte är det)
- **Ta bort** — tillgängligt endast när organisationen har 0 medlemmar och 0 ärenden (säkerhetsskydd)
- Alla borttagnings- och inaktiveringsåtgärder kräver bekräftelse

![Organisationslista — statusfilter, livesökning, färgmärken, taggar, ärendeantal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png)

### Organisationsredigeringsformulär

- **Namn** och **postlådebindning**
- **Färgväljare** — 12 nyanser med liveförhandsgranskning av märke
- **Taggar** — chip-baserad widget: skriv för att söka befintliga FreeScout-taggar, klicka för att lägga till, × för att ta bort
- **Medlemstabell** — per medlem: namn, roll, strukturell enhet, `can_manage_org`-kryssruta (ger administratörsåtkomst till organisationer utan fullständiga administratörsrättigheter), aktiv/inaktiv-växel
- **Panel för strukturella enheter** — skapa och byt namn på enheter direkt i redigeringsformuläret; medlemmar tilldelas enheter i samma vy
- **Att lägga till en medlem** — fyller automatiskt i befintliga otillskrivna konversationer för den kunden

![Organisationsredigering — färgväljare, tagg-chips, medlemstabell med roller och enheter](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

### Integration med kundprofil

- **Organisationsfält i FreeScout-kundredigeringsformuläret** — livesökning med autokomplettering för organisationer; rollrullgardin visas efter att ha valt en org; ×-knapp för att ta bort
- **"View org tickets"**-genväglänk i kundformuläret
- **Org-infoblocket i administratörsärendets sidopanel** — organisationsnamn (klickbar länk till org-redigeringssidan), strukturell enhet och medlemsroll; växla synlighet per postlåda i inställningar
- **Ett aktivt medlemskap per kund** — en kund kan inte läggas till i en andra organisation medan de har ett aktivt medlemskap; inaktiva/arkiverade medlemskap är tillåtna

![Kundredigering — organisationsfält med autokomplettering och rollväljare](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png)

---

## Strukturella enheter — Åtkomstkontroll på avdelningsnivå

*Stöd stora företag med komplexa interna hierarkier.*

Organisationer kan delas in i obegränsat antal **strukturella enheter** (avdelningar, filialer, regionkontor, projektteam):

- Skapa, byt namn och ta bort enheter i administratörsorganisationsredigeringsformuläret, eller direkt från portalen (endast globala chefer)
- Tilldela medlemmar till enheter — varje medlem tillhör en enhet
- **Att ta bort en enhet** degraderar automatiskt dess `unit_manager`-medlemmar till `member`

**Tre rollnivåer:**

| Roll | Åtkomstomfång |
|------|--------------|
| `member` | Bara egna ärenden |
| `unit_manager` | Alla ärenden inom deras strukturella enhet |
| `manager` (global) | Alla ärenden i hela organisationen |

- Enhetschefer har fullständiga portalfunktioner — svar, bilagor, omtilldelning av författare, stäng/öppna igen, avisieringshantering — strikt begränsade till deras enhet
- Åtkomst till ärenden och leverans av aviseringar upprätthålls vid enhetsgränser

![Organisationsredigering — medlemmar med roller och enheter, enhetshanterings-panel](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

---

## Emaildomäner — Automatisk medlemskap

*Sluta lägga till samma företags personal en efter en.*

Binda en e-postdomän till en organisation och varje kund som skriver från den domänen tilldelas den och registreras som medlem automatiskt — ingen manuell åtgärd, inget att glömma när en ny person skickar ett e-postmeddelande för första gången.

Konfigureras per organisation i **Manage → Organizations → edit → Email domains**.

### Hur matchning fungerar

| Regel | Beteende |
|-------|----------|
| **Endast exakt matchning** | `company.com` matchar `jane@company.com`. Det matchar **inte** `jane@mail.company.com` eller `jane@www.company.com` — lägg till dem som separata poster om du vill |
| **Normalisering** | `@Company.COM`, `https://www.company.com/` och `company.com.` lagras alla som `company.com` |
| **Manuell tilldelning vinner alltid** | En kund som redan tillhör en annan organisation flyttas aldrig. Konsulter och medvetna adminåtgärder är säkra |
| **Återkallelse kvarstår** | Inaktivering av en medlem är permanent tills en människa reverserar det. Kunden kan fortsätta skicka e-post; automatisering återställer inte sin åtkomst |
| **Postlådeomfattning** | En domän på en postlåda-specifik organisation gäller endast i den postlådan. En postlåda-specifik bindning tar företräde framför en global för samma domän |
| **Flera domäner** | En organisation kan innehålla så många domäner den behöver (`company.com`, `company.co.uk`, ett förvärvat varumärke…) |

### Offentliga leverantörer är blockerade

`gmail.com`, `outlook.com`, `ukr.net`, `icloud.com`, engångsmajltjänster och liknande är **avvisade vid sparning**. Att binda en skulle dra hundratals orelaterade kunder in i en enda organisation och — genom End-User Portal — ge dem åtkomst till varandras ärenden.

Listan levereras med modulen och kan **utökas** (aldrig reduceras) via alternativet `orgportal.public_domains_extra` för regionala leverantörer. En hårdkodad reserv garanterar att huvudleverantörerna förblir blockerade även om config-filen saknas eller är skadad.

Inaktiverade organisationer slutar registrera kunder helt och hållet.

### Lägga till befintliga kunder

En ny bindning påverkar endast framtida e-post. För att registrera kunder som redan finns påverkar bindningen endast framtida e-post: befintliga kunder registreras inte retroaktivt. De plockas upp när de skriver in igen.

### Ta bort en bindning

Att ta bort en domän stoppar framtida automatisk tilldelning. Medlemmar som den redan skapade **behålls som standard** — de kanske redan använder portalen. Du uppmanas separat avgöra om du vill inaktivera dem; denna rollback påverkar endast medlemmar som registrerats av den specifika domänen, aldrig de tillagda för hand.

Medlemmar som skapades automatiskt är markerade med ett **@** märke i medlemslistan.

---

## Org Snapshot — Permanent ärendetillskrivning

*Tillförlitlig historisk rapportering även när din kundlista förändras.*

När ett ärende skapas registrerar OrgPortal organisationskontexten som en permanent ögonblicksbild:

- `org_id`, `org_unit_id` och `org_attributed_at` skrivs till konversationen vid skapandetidpunkten
- **Oföränderlig** — om en kund senare lämnar en organisation förblir deras historiska ärenden tillskrivna den organisationen; rapportering bryts aldrig
- **Att lägga till en medlem** utlöser automatisk backfill av den kundens befintliga otillskrivna konversationer

### Tillskrivningskälla — tre lägen

Konfigureras i **Manage → Organizations → System tab**:

| Läge | Beteende |
|------|----------|
| `member` | Tillskriva ärende till organisationen som ärendets författare är medlem i |
| `tag` | Tillskriva via FreeScout-tagg bunden till en org först; fall back till medlemskap om ingen tagg matchar |
| `tag_only` | Tillskriva uteslutande via tagg; medlemskap används inte |

`tag`- och `tag_only`-lägen är inaktiverade när Tags-modulen är inaktiv.

**Emaildomäner fungerar som sista utväg** i lägen `member` och `tag`: när varken en tagbindning eller ett befintligt medlemskap löser ärendet, kontrolleras författarens e-postdomän. Det åsidosätter aldrig någondera, så en taggregel eller en admins manuella tilldelning har alltid företräde. I läge `tag_only` används inte domänmatchning.

### Backfill-verktyg

- **Förloppsindikator** — visar X / Y ärenden tillskrivna (%) med en "complete"-indikator när klart
- **Preflight-statistik** — innan backfill körs visar en sammanfattning hur många ärenden som kommer att tillskrivas via tagg vs. via medlemskap vs. omatchade
- **Kör backfill**-knapp — bearbetar upp till 2000 ärenden per klick; resultatsammanfattning (by_tag / by_member / unmatched) visas efteråt
- **Auto-cron** (`attribution_cron_enabled`) — schemalägger backfill var 5:e minut, 1000 ärenden per körning, utan överlapp
- **Återställ tillskrivning** — rensar alla org-ögonblicksbilder (farlig åtgärd, kräver bekräftelse)
- Kommandorad: `php artisan orgportal:backfill-attribution`

![Systemflik — tillskrivningskälla, förloppsindikator, preflight-statistik, backfill-kontroller](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png)

---

## Kanban-integration

*Håll ditt visuella arbetsflöde i linje med dina B2B-konton.*

- Organisationsmärke på varje Kanban-kort med kontots tilldelade färg
- **Organisationsfilter** i Kanban-filterpanelen — multivals-modal med kryssrutor; filterstatus kvarstår vid navigering
- **Flerspråkiga Kanban-statusfilteretikett** — ge varje Kanban-kolumn ett anpassat namn per portalspråk; växla lokaler med språkväljaren i per-postlåde-inställningar; dra för att ordna om filter
- Översatta etiketter visas både i portalens filterfält och i **State**-kolumnen i företagsärendetabellen; fallback-kedja: sparad lokal → sparad engelska → ursprungligt kolumnnamn

![Kanban — organisationsmärken på kort och org-filtermodal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png)

---

## Integration med anpassade fält

*Visa data från modulen Anpassade fält direkt på ärendesidan i portalen.*

Kräver att modulen [Custom Fields](https://freescout.net/module/custom-fields/) är installerad och aktiv.

- En panel per brevlåda i Postlådeinställningar → OrgPortal låter dig välja vilka anpassade fält som visas på ärendesidan i portalen
- Dra fält för att ändra ordning; varje fält kan ha en anpassad etikett per portalspråk, med reservfall till den sparade engelska etiketten och sedan det ursprungliga fältnamnet
- På ärendesidan i portalen visas aktiverade fält i ett responsivt rutnät med två kolumner mellan ärendets ämne och tråden — endast fält med ett icke-tomt värde visas
- Helt valfritt — panelen och blocket på ärendesidan döljs automatiskt när modulen Anpassade fält inte är installerad eller aktiv

---

## Åtkomstkontroll och behörigheter

*Delegera organisationshantering utan att ge administratörsåtkomst.*

- **"Allow managing organizations"** (`can_manage_org`) — två nivåer:
  - Som en **användarbehörighet** i agentinställningar — låter en supportteamledare hantera alla organisationer utan administratörsrättigheter
  - Som en **per-medlemsflagga** i organisationsredigeringsformuläret — låter en specifik org-medlem hantera den enda organisationen från administratörspanelen
- **"Allow managing notification templates"** — separat detaljerad behörighet för mallredigering
- Borttagning av organisationer förblir uteslutande administratörsexklusivt
- Portalåtkomst är strikt begränsad per postlåda: en chef från Organisation A kan inte komma åt Organisation B

![Detaljerade behörigheter — tillåt hantering av organisationer och aviseringsmallar](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png)

---

## Systeminställningar — Manage → Organizations → System tab

*Administratörsexklusiva kontroller för tillskrivning, backfill och portalspråksväxlaren.*

Fliken **System** är bara synlig för FreeScout-administratörer.

### Panel 1: Ärendetillskrivning

Se [Org Snapshot](#org-snapshot--permanent-ärendetillskrivning) ovan för den fullständiga beskrivningen av tillskrivningslägen, backfill-verktyg och auto-cron.

### Panel 2: Portalspråksväxlare

- **Aktivera/inaktivera** språkväxlaren i End-User Portal-navigeringsfältet
- **Välj vilka av de 19 lokalerna** att erbjuda (kryssrutekugg); alla är aktiverade som standard
- När aktiverat kan chefer byta portalspråk; deras val sparas och används för aviseringsmeddelanden
- Detta är OrgPortals inbyggda språkväxlare — den fungerar oberoende av alla tredjepartsspråkbytesmoduler; båda kan samexistera

![Systemflik — portalspråksväxlarpanel med lokalkryssrutor](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png)

---

## End-User Portal — Självbetjäning för företagschefer *(valfritt)*

*Ge dina B2B-kunder en portal där de hanterar sitt företags supportrelation — utan att kontakta ditt team för varje statusuppdatering.*

Kräver modulen [End-User Portal](https://freescout.net/module/end-user-portal/).

### Instrumentpanel för företagsärenden

Ett dedikerat **Company Tickets**-avsnitt i portalnavigering med en fullfjädrad ärendetabell:

| Kolumn | Beskrivning |
|--------|-------------|
| **#** | Ärende-ID |
| **Ämne** | Avkortat med verktygstips vid hovring |
| **Ansvarig** | Tilldelad supportagent |
| **Författare** | Kund som öppnade ärendet; klicka för att filtrera efter den här författaren |
| **Status** | Active / Pending / Closed / Spam med ikoner |
| **State** | Kanban-kolumnnamn på det aktuella portalspråket (endast när Kanban-modulen är aktiv) |
| **Uppdaterad** | Datum och tid för senaste svar |

**Två oberoende lässtatusindikatorer per rad** — dessa spårar två olika personer och visas samtidigt:

| Indikator | Vems lässtatus | Vad det innebär |
|-----------|----------------|-----------------|
| **Fetstilsrad** | Chefen som visar portalen | Chefen har olästa aviseringar för denna konversation — något hände som de inte har sett ännu |
| **👁 Ögonikon** | Ärendets författare (kunden som skickade in det) | Författaren har ännu inte öppnat det senaste agentsvaret — användbart för att veta om en klient faktiskt såg svaret |

Dessa två tillstånd är helt oberoende: en rad kan vara fetstilad (chefen har inte läst) medan ögat saknas (författaren har redan läst), eller vice versa. Chefen ser båda samtidigt och får en fullständig bild av vad som händer på båda sidor av ärendet utan att öppna det.

**Författarfilter** — att klicka på ett författarnamn aktiverar ett filter; en banner visas överst i tabellen med den aktiva författarens namn och en ×-länk för att rensa filtret.

Både skrivbordstabellen och en responsiv **kortlayout för mobil** ingår; de växlar automatiskt baserat på skärmbredd.

Filterbarsmallen stödjer **åsidosättning** via `enduserportal::partials.tickets_filters` — placera en anpassad vy på den sökvägen för att ersätta OrgPortals standardfilterfält och behålla all annan funktionalitet.

![Företagsärenden — fullständig tabell med läsindikatorer, författarfilterbanner, statusfilter](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png)

### Ärendeåtgärder i portalen

Chefer kan agera direkt — inget behov av att kontakta support:

- **Svara med bilagor** — dra och släpp, flera filer per svar; bilagsnamn och filstorlekar visas på varje tråd
- **Stäng ärende** — ett nytt svar öppnar det automatiskt igen; en banner informerar chefen om detta när ärendet är stängt
- **Ändra ärendeförfattare** — tilldela om ett ärende till en annan organisationsmedlem
- **Filtrera efter enhet** — globala chefer filtrerar ärendelistan efter strukturell enhet
- **Filtrera efter Kanban-status** — konfigurerbar per postlåda, etiketter visas på det aktuella portalspråket

![Portalärendevy — svarsformulär med dra-och-släpp-bilagor och stängd-ärendebanner](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png)

### Spårning av chefens visning

- En **"viewed"**-anteckning visas under agentsvar i administratörsärendevyn när en chef öppnar ärendet i portalen
- Visar chefens namn, roll (Organisation manager / Unit manager) och förfluten tid
- Globala chefs- och enhetschefsvyer spåras och visas oberoende — samma UX som FreeScouts inbyggda "Customer viewed"

![Spårning av chefens visning — 'viewed'-anteckning visas under agentsvar i admin-ärendevy](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png)

---

## Aviseringsklocka i realtid *(valfritt)*

*Håll chefer informerade i det ögonblick något händer med deras företags ärenden.*

Kräver modulen [End-User Portal](https://freescout.net/module/end-user-portal/).

- 🔔 Klockikon med levande oläst antal-märke i EUP-navigeringsfältet — ompositioneras automatiskt på mobil (bredvid hamburgmenyn)
- Aviseringar för: **nytt ärende**, **agentsvar**, **kundsvar** — för alla chefsroller
- Rullgardinspanel med aviseringar grupperade efter datum: aktörens namn, händelsetyp, ärendenummer, meddelandeförhandsgranskning, tidsstämpel
- **Auto-markera som läst** när chefen öppnar ärendet
- Markera enskilda aviseringar som lästa via ×; **Markera alla som lästa** i panelhuvudet
- Hämtar var 15:e sekund; uppdateras vid webbläsarens fram/tillbaka-navigering (bfcache-medveten)

![Aviseringsklocka i realtid — rullgardin med grupperade olästa aviseringar](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png)

---

## Aviseringsprenumerationer *(valfritt)*

*Låt chefer bestämma vad de hör om — inget mer, inget mindre.*

- **Visuell prenumerationsmatris** på fliken "Notifications" i portalens organisationsinställningar
- **Tre händelsetyper:** Nytt ärende · Agentsvar · Kundsvar
- **Två omfångsnivåer:** Hela organisationen (globala chefer) · Individuella strukturella enheter
- Medlemmar utan en enhet grupperas i en separat **"No unit"**-expanderbar rad
- **Per-medlemsåsidosättningar** — expandera valfri enhetsrad för att visa enskilda medlemmar och växla deras prenumerationer inline; enhetschefer med begränsad roll märks på lämpligt sätt
- **Kaskadlogik i båda riktningarna:**
  - Att aktivera "Entire organization" → aktiverar alla enheter och alla medlemmar
  - Att aktivera en enhet → aktiverar alla dess medlemmar
  - Att inaktivera en medlem → auto-reconcilierar enhetens och organisationens kryssrutor
- Globala chefer hanterar alla medlemmar; enhetschefer hanterar bara sin egen enhet
- Aviseringar använder postdrivrutinen för den motsvarande postlådan

![Aviseringsprenumerationsmatris — per-enhets- och per-medlemsväxlar](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png)

---

## Portalorganisationsinställningar

*Chefer konfigurerar sin organisationsstruktur utan administratörsåtkomst.*

**Organization Settings** i portalnavigering har tre flikar:

### Fliken Notifications

Prenumerationsmatrisen beskriven ovan.

### Fliken Units *(endast globala chefer)*

- **Skapa enhet** — inline-formulär med namnfält
- **Byt namn på enhet** — inline-redigering direkt i tabellraden
- **Ta bort enhet** — knapp med bekräftelse; enhetschefer degraderas automatiskt till member
- Antal medlemmar visas per enhet

### Fliken Members

- Tabell över alla organisationsmedlemmar: namn, strukturell enhet, roll, aktiv/inaktiv statusmärke
- Etiketten **"Global manager"** visas bredvid medlemmens namn där tillämpligt
- **Visa inaktiverade** kryssruta — visas bara när inaktiva medlemmar finns; dold som standard
- **Globala chefer** kan uppdatera vilken members enhet och roll som helst med ett inline-formulär (enhetsval + rollval + Tillämpa)
- **Globala chefer kan inte befordra en medlem till global chef** från portalen — detta kräver administratörsåtkomst
- **Aktivera / inaktivera**-knapp per medlem med bekräftelse för inaktivering

![Portalorganisationsinställningar — flikarna Units och Members](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png)

---

## Flerspråkiga aviseringsmeddelande-mallar *(valfritt)*

*Dina företagskunder får supportmeddelanden på sitt eget språk — automatiskt, utan manuell insats.*

Konfigureras i **Manage → Organizations → Templates tab** (synlig för användare med behörigheten "manage templates").

- **Per-locale-mallar** — separat ämne och brödtext för varje portalspråk; växla mellan dem med lokalets rullgardin; värden byts ut i minnet utan sidomladdning
- **Infällbara paneler** per händelsetyp (Nytt ärende / Agentsvar / Kundsvar) — Summernote-redigeraren initieras lättjefullt när en panel öppnas
- **Ladda standard**-knapp i varje panel — återställer den inbyggda mallen för den valda lokalen (faller tillbaka till inbyggd engelska om ingen lokal-specifik standard finns)
- **Summernote WYSIWYG-redigerare** för rik HTML-e-postsammansättning
- **Makrovariabelväljare** — infoga platshållare i ämne eller brödtext med ett klick; markörpositionen bevaras i ämnesfältet
- **19 inbyggda standardmallar** — redo att använda direkt; ingen konfiguration behövs

**Tillgängliga makrovariabler:**

| Variabel | Beskrivning |
|----------|-------------|
| `{manager_name}` | Namn på chefen som tar emot aviseringen |
| `{author_name}` | Kund som skapade eller svarade på ärendet |
| `{org_name}` | Organisationsnamn |
| `{unit_name}` | Strukturell enhets namn |
| `{subject}` | Ärendets ämne |
| `{ticket_number}` | Ärende-ID |
| `{ticket_url}` | Direktlänk till ärendet i portalen |
| `{ticket_text}` | Fullständig text i ursprungsmeddelandet (HTML) |
| `{reply_text}` | Fullständig text i det senaste svaret (HTML) |
| `{created_date}` | Ärendets skapandedatum |
| `{created_time}` | Ärendets skapandetid |
| `{created_datetime}` | Ärendets skapandedatum och -tid |
| `{reply_date}` | Svarsdatum |
| `{reply_time}` | Svarstid |
| `{reply_datetime}` | Svarsdatum och -tid |

**Fallback-kedja:** sparad lokal mall → inbyggd lokal mall → sparad engelsk mall → inbyggd engelsk mall

Aviseringsspråket bestäms av varje chefs portalspråksval, sparat automatiskt när de använder språkväxlaren.

![E-postmallar — per-locale infällbara paneler, Ladda standard-knapp, Summernote-redigerare](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png)

---

## REST API *(valfritt)*

*Integrera OrgPortal i ditt CRM, ERP eller kundregistreringsarbetsflöde.*

Kräver modulen [API and Webhooks](https://freescout.net/module/api-webhooks/).

- Fullständig CRUD för organisationer, strukturella enheter, kundmedlemskap och taggar
- **Organisationsfält:** `name`, `color`, `mailboxId`, `isActive` — alla läsbara och uppdateringsbara via API
- **Medlemmar-subresurs** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — uppdatera roll, enhet, `canManageOrg` och per-members `isActive`-flagga oberoende utan att röra resten av medlemskapet
- **Taggar-subresurs** — `GET/PUT /api/organizations/{id}/tags` — lista eller helt ersätta taggbindningar (kräver Tags-modulen; returnerar `503` om inaktiv)
- Autentisering via `X-FreeScout-API-Key`-huvud eller `api_key`-frågeparameter
- Interaktiv **ReDoc-dokumentation** vid **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Fullständig API-referens → [docs/api/README.md](docs/api/README.md)**

![Interaktiv API-dokumentation — ReDoc med alla OrgPortal-slutpunkter](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png)

---

## Installation

> [!IMPORTANT]
> Ladda ner `OrgPortal.zip` från **[Releases-sidan](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)** — använd **inte** "Code → Download ZIP" och klona inte repositoriet. Endast release-ZIP-en har rätt struktur för FreeScout och stöder automatiska uppdateringar.

1. Ladda ner `OrgPortal.zip` från [den senaste versionen](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)
2. Packa upp och kopiera mappen `OrgPortal` till `Modules/` i din FreeScout-installation
2. Gå till **Manage → Modules → OrgPortal → Activate**
3. Kör migrationer:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Rensa cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Georgiskt språkstöd** distribueras automatiskt vid första start — ingen manuell filkopiering krävs.

---

## Automatiska uppdateringar

OrgPortal stödjer **enklicksuppdateringar** via FreeScouts inbyggda moduluppdateringsmekanism.

> **Kräver FreeScout 1.8.170 eller senare.** På äldre versioner uppdaterar du manuellt genom att ersätta mappen `OrgPortal` med den senaste utgåvans ZIP.

När en ny version är tillgänglig visas en banner på **Manage → Modules**. Klicka på **Update now** — FreeScout laddar ned och installerar den senaste versionen automatiskt.

---

## Modulkompatibilitet

| Modul | Status | Anteckningar |
|-------|--------|--------------|
| End-User Portal ≥ 1.0.85 | Valfri | Chefsportal, aviseringsklocka, prenumerationer |
| API and Webhooks ≥ 1.0.80 | Valfri | REST API-slutpunkter |
| Kanban ≥ 1.0.23 | Valfri | Märke på kort, org-filter, flerspråkiga State-kolumnetiketter |
| Custom Fields | ✅ Kompatibel | — |
| Workflows | ✅ Kompatibel | — |
| Tags | ✅ Kompatibel | Tagg-chips i org-redigeringsformuläret; taggbindningar via API (`/organizations/{id}/tags`); taggbaserad ärendetillskrivning |

---

## Konfiguration

### Globala inställningar — **Manage → Organizations → System tab**

| Alternativ | Beskrivning |
|------------|-------------|
| Visa märke på ärendesida | Org-märke i konversationslista och ärendevy |
| Visa märke på Kanban-kort | Org-märke på Kanban-boardkort |
| Tillskrivningskälla | `member` / `tag` / `tag_only` — hur ärenden tillskrivs organisationer |
| Auto-cron backfill | Kör backfill var 5:e minut automatiskt |
| Ögonblicksbildsynlighet | Visa/dölj tillskrivningsdata i ärendesidopanelen |
| Portalspråksväxlare | Aktivera språkväxlare i EUP-navigeringsfältet; välj vilka av 19 lokaler att erbjuda |

### Per-postlåde-inställningar — **Mailbox Settings → OrgPortal**

Åsidosätter globala värden för den specifika postlådan.

| Alternativ | Beskrivning |
|------------|-------------|
| Visa märke på ärendesida | Aktivera/inaktivera märke för den här postlådan |
| Visa märke på Kanban-kort | Aktivera/inaktivera märke för den här postlådan |
| Visa organisationsblock i kundprofil | Växla org-infoblocket i ärendesidopanelen |
| Företagsärendessstatusfilter | Mappa Kanban-kolumner till namngivna filter i portalen; per-språketiketter med lokalväxlare; dra för att ordna om |

![Per-postlåde-inställningar — märkessynlighet och Kanban-statusfilter med flerspråkiga etiketter](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png)

---

## Översättningar

OrgPortal är fullständigt lokaliserat på **19 språk**:

| Språk | Kod | Språk | Kod |
|-------|-----|-------|-----|
| Engelska | `en` | Nederländska | `nl` |
| Ukrainska | `uk` | Norska | `no` |
| Tyska | `de` | Danska | `da` |
| Franska | `fr` | Svenska | `sv` |
| Spanska | `es` | Finska | `fi` |
| Italienska | `it` | Portugisiska (BR) | `pt-BR` |
| Tjeckiska | `cs` | Portugisiska (PT) | `pt-PT` |
| Slovakiska | `sk` | Rumänska | `ro` |
| Polska | `pl` | Förenklad kinesiska | `zh-CN` |
| Georgiska | `ka` | | |

Översättningsfiler: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Aviseringsmeddelande-mallar har inbyggda standardvärden för alla 19 språk.

### Integration av språkväxlare

OrgPortal inkluderar en inbyggd portalspråksväxlare (aktivera i **System tab → Portal Language Switcher**). Den integreras också med [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — båda kan vara aktiva samtidigt.

Det språk en chef väljer gäller för alla OrgPortal UI-strängar och sparas som deras aviseringsspråk — e-postmeddelanden skickas automatiskt på deras valda språk.

> **Teknisk anteckning:** `OrgPortalSetLocale`-mellanvaran återapplicerar portallokalen efter FreeScouts `Localize`-mellanvara för att förhindra att den återställs till systemstandarden vid varje begäran.

---

## Skärmdumpar

| | |
|---|---|
| ![Organisationslista](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png) | ![Organisationsredigering](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png) |
| *Organisationslista — statusfilter, livesökning, färgmärken* | *Organisationsredigering — färgväljare, tagg-chips, medlemstabell* |
| ![Systemflik](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png) | ![Kundredigering](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png) |
| *Systemflik — tillskrivningslägen, backfill, språkväxlare* | *Kundredigering — org-fält med autokomplettering* |
| ![Portalen Företagsärenden](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png) | ![Portalsvar](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png) |
| *Företagsärenden — tabell, författarfilter, läsindikatorer* | *Portalärende — svar med bilagor, stängd banner* |
| ![Portalorganisationsinställningar](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png) | ![Aviseringsklocka](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png) |
| *Portalorganisationsinst. — flikarna Units och Members* | *Aviseringsklocka i realtid med rullgardin* |
| ![Prenumerationsmatris](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png) | ![E-postmallar](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png) |
| *Aviseringsprenumerationsmatris — per-enhet, per-medlem* | *E-postmallar — lokalväxlare, Ladda standard, Summernote* |
| ![Kanban-integration](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png) | ![Postlådeinställningar](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png) |
| *Kanban — org-märken och org-filtermodal* | *Per-postlåde-inställningar — Kanban-filter med flerspråkiga etiketter* |
| ![API-dokumentation](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png) | |
| *Interaktiv API-dokumentation — ReDoc* | |

---

## Licens

[MIT](LICENSE) — © 2026 ASTIN-UA
