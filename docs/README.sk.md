# OrgPortal — B2B modul správy organizácií pre FreeScout

[← Späť na README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B modul" width="140" align="right">

**OrgPortal** je modul pre FreeScout, ktorý pridáva kompletnú **správu B2B organizácií** do vášho helpdesku: zoskupujte zákazníkov do spoločností, definujte hierarchie oddelení, poskytnite firemným manažérom samoobslužný portál a automatizujte notifikácie — všetko priamo vo FreeScout, bez potreby externých nástrojov.

> Hľadáte spôsob, ako spravovať firemné účty vo FreeScout? Poskytnúť firemným klientom vlastný zákaznícky portál? Kontrolovať, ktoré tikety môže každý B2B kontakt vidieť podľa svojej roly a oddelenia? OrgPortal to všetko rieši.

**Funguje s:** FreeScout 1.8.147+  
**Voliteľné integrácie:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

> [!IMPORTANT]
> **Vždy inštalujte z [najnovšej verzie](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest), nie zo zdrojového kódu repozitára.**
> Stiahnite `OrgPortal.zip` zo stránky Releases — obsahuje správnu adresárovú štruktúru vyžadovanú FreeScoutom.
> Stiahnutie zdrojového kódu (cez "Code → Download ZIP" alebo `git clone`) **nebude fungovať** a zničí štruktúru modulu.
> Automatické aktualizácie tiež vyžadujú, aby bol ZIP verzie použitý pri počiatočnej inštalácii.

---

🌐 **K dispozícii aj v:**
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

## Obsah

- [Čo OrgPortal pridáva do FreeScout](#čo-orgportal-pridáva-do-freescout)
- [Organizácie](#organizácie)
- [Štrukturálne jednotky — Kontrola prístupu na úrovni oddelení](#štrukturálne-jednotky--kontrola-prístupu-na-úrovni-oddelení)
- [Org Snapshot — Trvalé priradenie tiketu](#org-snapshot--trvalé-priradenie-tiketu)
- [Integrácia s Kanban](#integrácia-s-kanban)
- [Kontrola prístupu a oprávnení](#kontrola-prístupu-a-oprávnení)
- [Systémové nastavenia](#systémové-nastavenia--manage--organizations--záložka-system)
- [End-User Portal — Samoobsluha pre firemných manažérov](#end-user-portal--samoobsluha-pre-firemných-manažérov-voliteľné)
- [Notifikačný zvonček v reálnom čase](#notifikačný-zvonček-v-reálnom-čase-voliteľné)
- [Predplatné notifikácií](#predplatné-notifikácií-voliteľné)
- [Nastavenia organizácie v portáli](#nastavenia-organizácie-v-portáli)
- [Viacjazyčné šablóny e-mailov notifikácií](#viacjazyčné-šablóny-e-mailov-notifikácií-voliteľné)
- [REST API](#rest-api-voliteľné)
- [Inštalácia](#inštalácia)
- [Automatické aktualizácie](#automatické-aktualizácie)
- [Kompatibilita modulov](#kompatibilita-modulov)
- [Konfigurácia](#konfigurácia)
- [Preklady](#preklady)
- [Snímky obrazovky](#snímky-obrazovky)
- [Licencia](#licencia)

---

## Čo OrgPortal pridáva do FreeScout

FreeScout je postavený okolo jednotlivých zákazníkov — každý e-mail pochádza od osoby a neexistuje žiadny vstavaný koncept spoločnosti, pre ktorú táto osoba pracuje. Pre B2C helpdesky to funguje dobre. Pre B2B to nestačí.

OrgPortal vypĺňa túto medzeru:

- **Firemné účty** — zoskupujte zákazníkov do organizácií s názvom, farebným odznáčkom, väzbou na poštovú schránku a stavom aktívny/neaktívny
- **Hierarchia oddelení** — rozdeľte organizácie na štrukturálne jednotky (oddelenia, pobočky, tímy); každý člen je priradený k svojej jednotke
- **Prístup podľa roly** — `member` vidí iba vlastné tikety; `unit_manager` vidí celú jednotku; `manager` vidí celú organizáciu
- **Firemný samoobslužný portál** — manažéri prezerajú všetky tikety spoločnosti, odpovedajú, uzatvárajú, priraďujú autorov a spravujú predvoľby notifikácií bez toho, aby kontaktovali váš tím
- **Trvalé priradenie tiketu** — každý tiket je pri vytvorení zaznamenaný k organizácii; historické reporty prežijú zmeny zoznamu klientov
- **Viacjazyčné notifikácie** — automatické e-mailové upozornenia v jazyku každého manažéra s lokalizovanými šablónami a vstavaným WYSIWYG editorom
- **REST API** — synchronizujte členstvá z vášho CRM, automatizujte onboarding, spravujte štítky programovo

---

## Organizácie

*Jedno miesto pre všetko o firemnom účte.*

**Manage → Organizations** otvorí rozhranie so záložkami s tromi sekciami: Organizations, Templates a System.

### Zoznam organizácií

- **Vytváranie, úprava, mazanie, aktivácia/deaktivácia** organizácií
- **Filter stavu** — prepínanie medzi Active / Inactive / All pomocou skupiny prepínačov; okamžite filtruje tabuľku na strane klienta
- **Živé vyhľadávanie** — filtruje od 2+ znakov, bez obnovenia stránky
- **Farebne označené odznáčky** — interaktívny výber farby s 12 možnosťami a živým náhľadom odznáčka vedľa výberu; odznáčok sa zobrazuje na každom tikete a Kanban karte
- Kliknutím na odznáčok alebo počet tikietov sa otvorí vyhľadávanie vo FreeScout filtrované pre danú organizáciu
- **Väzba na poštovú schránku** — organizácie môžu byť globálne (všetky schránky) alebo priradené k špecifickej schránke
- **Stĺpec štítkov** — zobrazuje ✓/✗ či sú nejaké FreeScout štítky priradené k organizácii (vyžaduje modul Tags); štítky sa priraďujú vo formulári úprav pomocou miniaplikácie s čipmi a vyhľadávaním s automatickým dopĺňaním
- **Stĺpec počtu tikietov** — celkový počet konverzácií na organizáciu; klikateľný odkaz na úplné výsledky vyhľadávania
- **Stĺpec počtu členov**
- **Aktivovať / deaktivovať** — pozastavte účet bez straty histórie; vyžaduje aktivovaný Org Snapshot (tlačidlo je deaktivované s popisom, keď nie je)
- **Zmazať** — dostupné iba keď organizácia má 0 členov a 0 tikietov (ochranný mechanizmus)
- Všetky akcie mazania a deaktivácie vyžadujú potvrdenie

![Zoznam organizácií — filter stavu, živé vyhľadávanie, farebné odznáčky, štítky, počty tikietov](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png)

### Formulár úpravy organizácie

- **Názov** a **väzba na poštovú schránku**
- **Výber farby** — 12 možností s živým náhľadom odznáčka
- **Štítky** — miniaplikácia s čipmi: zadajte text pre vyhľadávanie existujúcich FreeScout štítkov, kliknite pre pridanie, × pre odstránenie
- **Tabuľka členov** — pre každého člena: meno, rola, štrukturálna jednotka, zaškrtávacie políčko `can_manage_org` (udeľuje prístup správcu k organizáciám bez úplných práv správcu), prepínač aktívny/neaktívny
- **Panel štrukturálnych jednotiek** — vytváranie a premenovávanie jednotiek priamo vo formulári úprav; členovia sú priraďovaní k jednotkám v rovnakom zobrazení
- **Pridanie člena** — automaticky doplní existujúce nepriradené konverzácie daného zákazníka

![Úprava organizácie — výber farby, čipy štítkov, tabuľka členov s rolami a jednotkami](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

### Integrácia s profilom zákazníka

- **Pole organizácie vo formulári úpravy zákazníka vo FreeScout** — živé vyhľadávanie s automatickým dopĺňaním pre organizácie; po výbere organizácie sa zobrazí rozbaľovací zoznam roly; tlačidlo × pre odstránenie
- Skratkový odkaz **„Zobraziť tikety organizácie"** vo formulári zákazníka
- **Blok informácií o organizácii v bočnom paneli tiketu správcu** — názov organizácie (klikateľný odkaz na stránku úpravy organizácie), štrukturálna jednotka a rola člena; viditeľnosť je možné prepínať pre každú schránku v nastaveniach
- **Jedno aktívne členstvo na zákazníka** — zákazníka nie je možné pridať do druhej organizácie, kým má aktívne členstvo; neaktívne/archivované členstvá sú povolené

![Úprava zákazníka — pole organizácie s automatickým dopĺňaním a výberom roly](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png)

---

## Štrukturálne jednotky — Kontrola prístupu na úrovni oddelení

*Podpora veľkých podnikov so zložitými internými hierarchiami.*

Organizácie je možné rozdeliť na neobmedzený počet **štrukturálnych jednotiek** (oddelenia, pobočky, regionálne kancelárie, projektové tímy):

- Vytváranie, premenovávanie a mazanie jednotiek vo formulári úpravy organizácie správcu alebo priamo z portálu (iba globálni manažéri)
- Priraďovanie členov k jednotkám — každý člen patrí do jednej jednotky
- **Zmazanie jednotky** automaticky znižuje `unit_manager` členov na `member`

**Tri úrovne rol:**

| Rola | Rozsah prístupu |
|------|-----------------|
| `member` | Iba vlastné tikety |
| `unit_manager` | Všetky tikety v rámci ich štrukturálnej jednotky |
| `manager` (globálny) | Všetky tikety v celej organizácii |

- Manažéri jednotiek majú plné možnosti portálu — odpovede, prílohy, priraďovanie autorov, uzatváranie/znovuotvorenie, správa notifikácií — obmedzené prísne na ich jednotku
- Prístup k tiketom a doručovanie notifikácií sú vynucované na hraniciach jednotiek

![Úprava organizácie — členovia s rolami a jednotkami, panel správy jednotiek](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

---

## Org Snapshot — Trvalé priradenie tiketu

*Spoľahlivé historické reporty aj pri zmenách vo vašom zozname klientov.*

Keď je tiket vytvorený, OrgPortal zaznamená kontext organizácie ako trvalý snapshot:

- `org_id`, `org_unit_id` a `org_attributed_at` sa zapisujú do konverzácie pri jej vytvorení
- **Nemenný** — ak zákazník neskôr opustí organizáciu, jeho historické tikety ostávajú priradené k danej organizácii; reportovanie sa nikdy nenaruší
- **Pridanie člena** spustí automatické doplnenie existujúcich nepriradených konverzácií daného zákazníka

### Zdroj priradenia — tri režimy

Nastaviteľné v **Manage → Organizations → záložka System**:

| Režim | Správanie |
|-------|-----------|
| `member` | Priraďte tiket k organizácii, ktorej je autor tiketu členom |
| `tag` | Priraďte podľa FreeScout štítka priradeného k organizácii; záložná možnosť je členstvo, ak sa nenájde zodpovedajúci štítok |
| `tag_only` | Priraďujte výlučne podľa štítka; členstvo sa nepoužíva |

Režimy `tag` a `tag_only` sú deaktivované, keď je modul Tags neaktívny.

### Nástroje doplnenia

- **Indikátor priebehu** — zobrazuje X / Y priradených tikietov (%) s indikátorom „dokončené" keď je hotovo
- **Predbežné štatistiky** — pred spustením doplnenia sa zobrazí prehľad koľko tikietov bude priradených podľa štítka vs. podľa členstva vs. nezodpovedajúcich
- Tlačidlo **Spustiť doplnenie** — spracúva až 2000 tikietov na kliknutie; po dokončení sa zobrazí súhrnný výsledok (by_tag / by_member / unmatched)
- **Auto-cron** (`attribution_cron_enabled`) — plánuje doplnenie každých 5 minút, 1000 tikietov na spustenie, bez prekrývania
- **Resetovať priradenie** — vymaže všetky org snapshoty (nebezpečná akcia, vyžaduje potvrdenie)
- Príkazový riadok: `php artisan orgportal:backfill-attribution`

![Záložka System — zdroj priradenia, indikátor priebehu, predbežné štatistiky, ovládacie prvky doplnenia](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png)

---

## Integrácia s Kanban

*Udržujte váš vizuálny pracovný tok v súlade s vašimi B2B účtami.*

- Odznáčok organizácie na každej Kanban karte s priradenou farbou účtu
- **Filter organizácie** v paneli filtrov Kanban — modálne okno s viacnásobným výberom pomocou zaškrtávacích políčok; stav filtra pretrváva počas navigácie
- **Viacjazyčné štítky filtra stavu Kanban** — dajte každému stĺpcu Kanban vlastný názov pre každý jazyk portálu; prepínajte lokalizácie pomocou výberu jazyka v nastaveniach poštovej schránky; presúvajte filtre pretiahnutím
- Preložené štítky sa zobrazujú v paneli filtrov portálu aj v stĺpci **Stav** tabuľky tikietov spoločnosti; záložný reťazec: uložená lokalizácia → uložená angličtina → pôvodný názov stĺpca

![Kanban — odznáčky organizácií na kartách a modálne okno filtra organizácie](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png)

---

## Kontrola prístupu a oprávnení

*Delegujte správu organizácie bez udelenia prístupu správcu.*

- **„Povoliť správu organizácií"** (`can_manage_org`) — dve úrovne:
  - Ako **oprávnenie používateľa** v nastaveniach agenta — umožňuje vedúcemu tímu podpory spravovať všetky organizácie bez práv správcu
  - Ako **príznak pre jednotlivého člena** vo formulári úpravy organizácie — umožňuje konkrétnemu členovi organizácie spravovať danú organizáciu z panela správcu
- **„Povoliť správu šablón notifikácií"** — samostatné granulárne oprávnenie pre úpravu šablón
- Mazanie organizácií zostáva výlučne pre správcov
- Prístup k portálu je prísne obmedzený na poštovú schránku: manažér z Organizácie A nemôže pristupovať k Organizácii B

![Granulárne oprávnenia — povolenie správy organizácií a šablón notifikácií](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png)

---

## Systémové nastavenia — Manage → Organizations → záložka System

*Ovládacie prvky iba pre správcov pre priradenie, doplnenie a prepínač jazyka portálu.*

Záložka **System** je viditeľná iba pre správcov FreeScout.

### Panel 1: Priradenie tiketu

Pozrite [Org Snapshot](#org-snapshot--trvalé-priradenie-tiketu) vyššie pre úplný popis režimov priradenia, nástrojov doplnenia a auto-cron.

### Panel 2: Prepínač jazyka portálu

- **Aktivovať/deaktivovať** prepínač jazyka v navigačnej lište End-User Portal
- **Vyberte, ktoré z 19 lokalizácií** ponúknuť (mriežka zaškrtávacích políčok); všetky sú predvolene aktivované
- Keď je aktivovaný, manažéri môžu prepínať jazyk portálu; ich výber sa uloží a použije pre e-maily s notifikáciami
- Toto je vstavaný prepínač jazyka OrgPortal — funguje nezávisle od akéhokoľvek modulu prepínača jazyka tretej strany; oba môžu koexistovať

![Záložka System — panel prepínača jazyka portálu so zaškrtávacími políčkami lokalizácií](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png)

---

## End-User Portal — Samoobsluha pre firemných manažérov *(voliteľné)*

*Poskytnite svojim B2B klientom portál, kde spravujú vzťah svojej spoločnosti s podporou — bez toho, aby pri každej aktualizácii stavu kontaktovali váš tím.*

Vyžaduje modul [End-User Portal](https://freescout.net/module/end-user-portal/).

### Dashboard tikietov spoločnosti

Vyhradená sekcia **Tikety spoločnosti** v navigácii portálu s plnohodnotnou tabuľkou tikietov:

| Stĺpec | Popis |
|--------|-------|
| **#** | ID tiketu |
| **Predmet** | Skrátený s opisom pri hover |
| **Zodpovedný** | Priradený agent podpory |
| **Autor** | Zákazník, ktorý otvoril tiket; kliknutím filtrujte podľa tohto autora |
| **Stav** | Aktívny / Čakajúci / Uzavretý / Spam s ikonami |
| **Stav Kanban** | Názov stĺpca Kanban v aktuálnom jazyku portálu (iba keď je modul Kanban aktívny) |
| **Aktualizované** | Dátum a čas poslednej odpovede |

**Dva nezávislé indikátory stavu prečítania na riadok** — sledujú dve rôzne osoby a zobrazujú sa súčasne:

| Indikátor | Čí stav prečítania | Čo znamená |
|-----------|---------------------|------------|
| **Tučný riadok** | Manažér prezerajúci portál | Manažér má neprečítané notifikácie pre túto konverzáciu — niečo sa stalo, čo ešte nevidel |
| **👁 Ikona oka** | Autor tiketu (zákazník, ktorý ho odoslal) | Autor ešte neotvoril najnovšiu odpoveď agenta — užitočné pre zistenie, či klient naozaj videl odpoveď |

Tieto dva stavy sú úplne nezávislé: riadok môže byť tučný (manažér neprečítal), zatiaľ čo oko chýba (autor už prečítal), alebo naopak. Manažér vidí obe súčasne, čo poskytuje úplný prehľad o tom, čo sa deje na oboch stranách tiketu bez jeho otvorenia.

**Filter autora** — kliknutím na meno autora sa aktivuje filter; v hornej časti tabuľky sa zobrazí banner s menom aktívneho autora a odkazom × na vymazanie filtra.

Tabuľka pre počítač aj responzívne **rozloženie kariet pre mobil** sú zahrnuté; prepínajú sa automaticky podľa šírky obrazovky.

Šablóna panela filtrov podporuje **prepísanie** cez `enduserportal::partials.tickets_filters` — umiestniť vlastné zobrazenie na danú cestu pre nahradenie predvoleného panela filtrov OrgPortal pri zachovaní ostatnej funkčnosti.

![Tikety spoločnosti — úplná tabuľka s indikátormi prečítania, bannerom filtra autora, filtrami stavu](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png)

### Akcie tiketu v portáli

Manažéri môžu priamo konať — nie je potrebné kontaktovať podporu:

- **Odpoveď s prílohami** — drag & drop, viacero súborov na odpoveď; názvy príloh a veľkosti súborov sú zobrazené v každom vlákne
- **Uzatvoriť tiket** — nová odpoveď ho automaticky znovu otvorí; banner informuje manažéra o tomto pri uzavretom tikete
- **Zmeniť autora tiketu** — priradiť tiket inému členovi organizácie
- **Filter podľa jednotky** — globálni manažéri filtrujú zoznam tikietov podľa štrukturálnej jednotky
- **Filter podľa stavu Kanban** — nastaviteľné pre každú schránku, štítky sa zobrazujú v aktuálnom jazyku portálu

![Zobrazenie tiketu v portáli — formulár odpovede s drag & drop prílohami a bannerom pre uzavretý tiket](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png)

### Sledovanie zobrazení manažéra

- Pod odpoveďami agenta v zobrazení tiketu správcu sa zobrazí poznámka **„zobrazené"**, keď manažér otvorí tiket v portáli
- Zobrazuje meno manažéra, rolu (Manažér organizácie / Manažér jednotky) a uplynulý čas
- Zobrazenia globálneho manažéra a manažéra jednotky sa sledujú a zobrazujú nezávisle — rovnaké UX ako natívne „Zákazník zobrazil" vo FreeScout

![Sledovanie zobrazení manažéra — poznámka „zobrazené" sa zobrazí pod odpoveďou agenta v zobrazení tiketu správcu](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png)

---

## Notifikačný zvonček v reálnom čase *(voliteľné)*

*Informujte manažérov v momente, keď sa niečo stane s tiketmi ich spoločnosti.*

Vyžaduje modul [End-User Portal](https://freescout.net/module/end-user-portal/).

- 🔔 Ikona zvončeka so živým počtom neprečítaných správ v navigačnej lište EUP — automaticky sa premiestňuje na mobile (vedľa hamburger menu)
- Notifikácie pre: **nový tiket**, **odpoveď agenta**, **odpoveď zákazníka** — pre všetky roly manažéra
- Rozbaľovací panel s notifikáciami zoskupenými podľa dátumu: meno aktéra, typ udalosti, číslo tiketu, náhľad správy, časová pečiatka
- **Automatické označenie ako prečítané** keď manažér otvorí tiket
- Označovanie jednotlivých notifikácií ako prečítané cez ×; **Označiť všetky ako prečítané** v záhlaví panela
- Posiela požiadavky každých 15 sekúnd; obnovuje pri navigácii vpred/vzad (kompatibilné s bfcache)

![Notifikačný zvonček v reálnom čase — rozbaľovací panel so zoskupenými neprečítanými notifikáciami](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png)

---

## Predplatné notifikácií *(voliteľné)*

*Nechajte manažérov rozhodnúť, o čom chcú byť informovaní — nič viac, nič menej.*

- **Vizuálna matica predplatného** na záložke „Notifications" v Nastaveniach organizácie portálu
- **Tri typy udalostí:** Nový tiket · Odpoveď agenta · Odpoveď zákazníka
- **Dve úrovne rozsahu:** Celá organizácia (globálni manažéri) · Jednotlivé štrukturálne jednotky
- Členovia bez jednotky sú zoskupení do samostatného rozbaľovacieho riadku **„Bez jednotky"**
- **Prepísanie pre jednotlivých členov** — rozbaľte ktorýkoľvek riadok jednotky pre zobrazenie jednotlivých členov a prepnutie ich predplatného v mieste; manažéri jednotiek so scoped rolou sú zodpovedajúco označení
- **Kaskádová logika v oboch smeroch:**
  - Aktivácia „Celá organizácia" → aktivuje všetky jednotky a všetkých členov
  - Aktivácia jednotky → aktivuje všetkých jej členov
  - Deaktivácia člena → automaticky zosúladí zaškrtávacie políčka jednotky a organizácie
- Globálni manažéri spravujú všetkých členov; manažéri jednotiek spravujú iba svoju vlastnú jednotku
- Notifikácie používajú mail driver zodpovedajúcej poštovej schránky

![Matica predplatného notifikácií — prepínače pre jednotlivé jednotky a členov](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png)

---

## Nastavenia organizácie v portáli

*Manažéri konfigurujú štruktúru svojej organizácie bez prístupu správcu.*

**Nastavenia organizácie** v navigácii portálu majú tri záložky:

### Záložka Notifikácie

Matica predplatného opísaná vyššie.

### Záložka Jednotky *(iba globálni manažéri)*

- **Vytvoriť jednotku** — inline formulár s poľom názvu
- **Premenovať jednotku** — inline úprava priamo v riadku tabuľky
- **Zmazať jednotku** — tlačidlo s potvrdením; manažéri jednotiek sú automaticky znížení na member
- Počet členov zobrazený pre každú jednotku

### Záložka Členovia

- Tabuľka všetkých členov organizácie: meno, štrukturálna jednotka, rola, odznak stavu aktívny/neaktívny
- Štítok **„Globálny manažér"** zobrazený vedľa mena člena kde je to relevantné
- Zaškrtávacie políčko **Zobraziť deaktivovaných** — zobrazuje sa iba keď existujú neaktívni členovia; predvolene skryté
- **Globálni manažéri** môžu aktualizovať jednotku a rolu každého člena pomocou inline formulára (výber jednotky + výber roly + Použiť)
- **Globálni manažéri nemôžu povýšiť člena na globálneho manažéra** z portálu — toto vyžaduje prístup správcu
- Tlačidlo **Aktivovať / deaktivovať** pre každého člena s potvrdením pre deaktiváciu

![Nastavenia organizácie v portáli — záložky Jednotky a Členovia](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png)

---

## Viacjazyčné šablóny e-mailov notifikácií *(voliteľné)*

*Vaši firemní klienti dostávajú e-maily podpory v ich vlastnom jazyku — automaticky, bez manuálneho úsilia.*

Nastaviteľné v **Manage → Organizations → záložka Templates** (viditeľné pre používateľov s oprávnením „spravovať šablóny").

- **Šablóny pre každú lokalizáciu** — samostatný predmet a telo pre každý jazyk portálu; prepínajte medzi nimi pomocou rozbaľovacieho zoznamu lokalizácie; hodnoty sa vymieňajú v pamäti bez obnovenia stránky
- **Skladateľné panely** pre každý typ udalosti (Nový tiket / Odpoveď agenta / Odpoveď zákazníka) — Summernote editor sa inicializuje lenivo pri otvorení panela
- Tlačidlo **Načítať predvolenú šablónu** v každom paneli — obnoví vstavaná šablóna pre aktuálne vybranú lokalizáciu (záložná je anglická vstavaná šablóna, ak neexistuje lokalizovaná predvolená)
- **Summernote WYSIWYG editor** pre tvorbu e-mailov s bohatým HTML
- **Výber makro premenných** — vkladanie zástupných symbolov do predmetu alebo tela jedným kliknutím; pozícia kurzora je zachovaná v poli predmetu
- **19 vstavaných predvolených šablón** — pripravené na použitie ihneď; nie je potrebná žiadna konfigurácia

**Dostupné makro premenné:**

| Premenná | Popis |
|----------|-------|
| `{manager_name}` | Meno manažéra prijímajúceho notifikáciu |
| `{author_name}` | Zákazník, ktorý vytvoril alebo odpovedal na tiket |
| `{org_name}` | Názov organizácie |
| `{unit_name}` | Názov štrukturálnej jednotky |
| `{subject}` | Predmet tiketu |
| `{ticket_number}` | ID tiketu |
| `{ticket_url}` | Priamy odkaz na tiket v portáli |
| `{ticket_text}` | Celý text počiatočnej správy (HTML) |
| `{reply_text}` | Celý text najnovšej odpovede (HTML) |
| `{created_date}` | Dátum vytvorenia tiketu |
| `{created_time}` | Čas vytvorenia tiketu |
| `{created_datetime}` | Dátum a čas vytvorenia tiketu |
| `{reply_date}` | Dátum odpovede |
| `{reply_time}` | Čas odpovede |
| `{reply_datetime}` | Dátum a čas odpovede |

**Záložný reťazec:** uložená šablóna lokalizácie → vstavaná šablóna lokalizácie → uložená anglická šablóna → vstavaná anglická šablóna

Jazyk notifikácií je určený výberom jazyka portálu každého manažéra, uloženým automaticky keď použijú prepínač jazyka.

![Šablóny e-mailov — skladateľné panely pre každú lokalizáciu, tlačidlo Načítať predvolenú, Summernote editor](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png)

---

## REST API *(voliteľné)*

*Integrujte OrgPortal do svojho CRM, ERP alebo onboarding procesu zákazníkov.*

Vyžaduje modul [API and Webhooks](https://freescout.net/module/api-webhooks/).

- Plné CRUD pre organizácie, štrukturálne jednotky, členstvá zákazníkov a štítky
- **Polia organizácie:** `name`, `color`, `mailboxId`, `isActive` — všetky čitateľné a aktualizovateľné cez API
- **Súčasť členovia** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — aktualizujte rolu, jednotku, `canManageOrg` a príznak `isActive` pre jednotlivého člena nezávisle bez dotyku zvyšku členstva
- **Súčasť štítky** — `GET/PUT /api/organizations/{id}/tags` — zobrazte alebo úplne nahraďte priradenia štítkov (vyžaduje modul Tags; vracia `503` ak je neaktívny)
- Autentifikácia cez hlavičku `X-FreeScout-API-Key` alebo parameter `api_key`
- Interaktívna **ReDoc dokumentácia** na **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Úplná referencia API → [docs/api/README.md](docs/api/README.md)**

![Interaktívna dokumentácia API — ReDoc so všetkými OrgPortal endpointmi](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png)

---

## Inštalácia

> [!IMPORTANT]
> Stiahnite `OrgPortal.zip` zo **[stránky Releases](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)** — **nepoužívajte** "Code → Download ZIP" ani neklonujte repozitár. Iba ZIP verzie má správnu štruktúru pre FreeScout a podporuje automatické aktualizácie.

1. Stiahnite `OrgPortal.zip` z [najnovšej verzie](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)
2. Rozbaľte a skopírujte priečinok `OrgPortal` do `Modules/` vašej inštalácie FreeScout
2. Prejdite na **Manage → Modules → OrgPortal → Activate**
3. Spustite migrácie:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Vyčistite cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Podpora gruzínskeho jazyka** je nasadená automaticky pri prvom spustení — nie je potrebné manuálne kopírovanie súborov.

---

## Automatické aktualizácie

OrgPortal podporuje **aktualizácie jedným kliknutím** cez vstavaný mechanizmus aktualizácie modulov FreeScout.

> **Vyžaduje FreeScout 1.8.170 alebo novší.** Na starších verziách aktualizujte manuálne nahradením priečinka `OrgPortal` najnovším release ZIP.

Keď je k dispozícii nová verzia, na **Manage → Modules** sa zobrazí banner. Kliknite na **Update now** — FreeScout automaticky stiahne a nainštaluje najnovšiu verziu.

---

## Kompatibilita modulov

| Modul | Stav | Poznámky |
|-------|------|----------|
| End-User Portal ≥ 1.0.85 | Voliteľný | Portál manažéra, notifikačný zvonček, predplatné |
| API and Webhooks ≥ 1.0.80 | Voliteľný | REST API endpointy |
| Kanban ≥ 1.0.23 | Voliteľný | Odznáčok na kartách, filter organizácie, viacjazyčné štítky stĺpca Stav |
| Custom Fields | ✅ Kompatibilný | — |
| Workflows | ✅ Kompatibilný | — |
| Tags | ✅ Kompatibilný | Čipy štítkov vo formulári úpravy organizácie; priradenia štítkov cez API (`/organizations/{id}/tags`); priradenie tiketu podľa štítka |

---

## Konfigurácia

### Globálne nastavenia — **Manage → Organizations → záložka System**

| Možnosť | Popis |
|---------|-------|
| Zobraziť odznáčok na stránke tiketu | Odznáčok organizácie v zozname konverzácií a zobrazení tiketu |
| Zobraziť odznáčok na Kanban kartách | Odznáčok organizácie na kartách Kanban boardu |
| Zdroj priradenia | `member` / `tag` / `tag_only` — ako sú tikety priradené k organizáciám |
| Auto-cron doplnenie | Spustiť doplnenie každých 5 minút automaticky |
| Viditeľnosť snapshotov | Zobraziť/skryť údaje o priradení v bočnom paneli tiketu |
| Prepínač jazyka portálu | Aktivovať prepínač jazyka v navigačnej lište EUP; vyberte, ktoré z 19 lokalizácií ponúknuť |

### Nastavenia poštovej schránky — **Mailbox Settings → OrgPortal**

Prepíše globálne hodnoty pre konkrétnu poštovú schránku.

| Možnosť | Popis |
|---------|-------|
| Zobraziť odznáčok na stránke tiketu | Aktivovať/deaktivovať odznáčok pre túto schránku |
| Zobraziť odznáčok na Kanban kartách | Aktivovať/deaktivovať odznáčok pre túto schránku |
| Zobraziť blok organizácie v profile zákazníka | Prepnúť blok informácií o organizácii v bočnom paneli tiketu |
| Filtre stavu tikietov spoločnosti | Namapovať stĺpce Kanban na pomenované filtre v portáli; štítky pre každý jazyk s prepínačom lokalizácie; presúvanie pretiahnutím pre zoradenie |

![Nastavenia poštovej schránky — viditeľnosť odznáčka a filtre stavu Kanban s viacjazyčnými štítkami](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png)

---

## Preklady

OrgPortal je plne lokalizovaný v **19 jazykoch**:

| Jazyk | Kód | Jazyk | Kód |
|-------|-----|-------|-----|
| Angličtina | `en` | Holandčina | `nl` |
| Ukrajinskčina | `uk` | Nórčina | `no` |
| Nemčina | `de` | Dánčina | `da` |
| Francúzština | `fr` | Švédčina | `sv` |
| Španielčina | `es` | Fínčina | `fi` |
| Taliančina | `it` | Portugalčina (BR) | `pt-BR` |
| Čeština | `cs` | Portugalčina (PT) | `pt-PT` |
| Slovenčina | `sk` | Rumunčina | `ro` |
| Poľština | `pl` | Zjednodušená čínština | `zh-CN` |
| Gruzínčina | `ka` | | |

Súbory prekladov: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Šablóny e-mailov notifikácií majú vstavaných predvolených hodnôt pre všetkých 19 jazykov.

### Integrácia prepínača jazyka

OrgPortal obsahuje vstavaný prepínač jazyka portálu (aktivujte v **záložka System → Prepínač jazyka portálu**). Integruje sa aj s [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — oba môžu byť aktívne súčasne.

Jazyk, ktorý manažér vyberie, sa vzťahuje na všetky UI reťazce OrgPortal a ukladá sa ako jeho notifikačný jazyk — e-maily sa automaticky odosielajú v ich zvolenom jazyku.

> **Technická poznámka:** Middleware `OrgPortalSetLocale` znovu aplikuje lokalizáciu portálu po middleware `Localize` FreeScout, aby zabránil jej resetovaniu na systémové predvolené nastavenie pri každej požiadavke.

---

## Snímky obrazovky

| | |
|---|---|
| ![Zoznam organizácií](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png) | ![Úprava organizácie](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png) |
| *Zoznam organizácií — filter stavu, živé vyhľadávanie, farebné odznáčky* | *Úprava organizácie — výber farby, čipy štítkov, tabuľka členov* |
| ![Záložka System](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png) | ![Úprava zákazníka](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png) |
| *Záložka System — režimy priradenia, doplnenie, prepínač jazyka* | *Úprava zákazníka — pole organizácie s automatickým dopĺňaním* |
| ![Portál tikietov spoločnosti](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png) | ![Odpoveď v portáli](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png) |
| *Tikety spoločnosti — tabuľka, filter autora, indikátory prečítania* | *Tiket v portáli — odpoveď s prílohami, banner uzavretého tiketu* |
| ![Nastavenia organizácie v portáli](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png) | ![Notifikačný zvonček](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png) |
| *Nastavenia organizácie v portáli — záložky Jednotky a Členovia* | *Notifikačný zvonček v reálnom čase s rozbaľovacím panelom* |
| ![Matica predplatného](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png) | ![Šablóny e-mailov](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png) |
| *Matica predplatného notifikácií — pre jednotlivé jednotky a členov* | *Šablóny e-mailov — prepínač lokalizácie, Načítať predvolenú, Summernote* |
| ![Integrácia Kanban](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png) | ![Nastavenia schránky](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png) |
| *Kanban — odznáčky organizácií a modálne okno filtra* | *Nastavenia schránky — filtre Kanban s viacjazyčnými štítkami* |
| ![Dokumentácia API](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png) | |
| *Interaktívna dokumentácia API — ReDoc* | |

---

## Licencia

[MIT](LICENSE) — © 2026 ASTIN-UA
