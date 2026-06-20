# OrgPortal — B2B modul pro správu organizací ve FreeScoutu

[← Zpět na README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B modul" width="140" align="right">

**OrgPortal** je modul pro FreeScout, který přidává kompletní **B2B správu organizací** do vašeho helpdesku: seskupte zákazníky do firem, definujte firemní hierarchie, dejte firemním manažerům samoobslužný portál a automatizujte notifikace — vše přímo ve FreeScoutu, bez externích nástrojů.

> Hledáte způsob, jak spravovat firemní účty ve FreeScoutu? Jak dát firemním klientům vlastní portál podpory? Jak řídit, které tickety může každý B2B kontakt vidět na základě své role a oddělení? OrgPortal to vše řeší.

**Funguje s:** FreeScout 1.8.147+  
**Volitelné integrace:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Také dostupné v:**
[Українська](docs/README.uk.md) · [Deutsch](docs/README.de.md) · [Français](docs/README.fr.md) · [Español](docs/README.es.md) · [Italiano](docs/README.it.md) · [Polski](docs/README.pl.md) · [Čeština](docs/README.cs.md) · [Slovenčina](docs/README.sk.md) · [Nederlands](docs/README.nl.md) · [Norsk](docs/README.no.md) · [Dansk](docs/README.da.md) · [Svenska](docs/README.sv.md) · [Suomi](docs/README.fi.md) · [Português (BR)](docs/README.pt-BR.md) · [Português (PT)](docs/README.pt-PT.md) · [Română](docs/README.ro.md) · [中文 (简体)](docs/README.zh-CN.md)

---

## Co OrgPortal přidává do FreeScoutu

FreeScout je postaven okolo jednotlivých zákazníků — každý e-mail je od konkrétní osoby a neexistuje žádný vestavěný koncept firmy, pro kterou tato osoba pracuje. Pro B2C helpdesky to funguje dobře. Pro B2B to nestačí.

OrgPortal tuto mezeru vyplňuje:

- **Firemní účty** — seskupte zákazníky do organizací s názvem, barevným odznakem, vazbou na schránku a stavem aktivní/neaktivní
- **Firemní hierarchie** — rozdělte organizace na strukturální jednotky (oddělení, pobočky, týmy); každý člen je přiřazen ke své jednotce
- **Přístup podle rolí** — `member` vidí pouze vlastní tickety; `unit_manager` vidí celou jednotku; `manager` vidí celou organizaci
- **Firemní samoobslužný portál** — manažeři vidí všechny firemní tickety, odpovídají, uzavírají, přeřazují autory a spravují notifikace bez nutnosti kontaktovat váš tým
- **Trvalé přiřazení ticketů** — každý ticket je při vytvoření snapshotován do organizace; historické reporty přežijí změny v seznamu klientů
- **Vícejazyčné notifikace** — automatické e-mailové upozornění v jazyce každého manažera, s šablonami pro jednotlivá jazyková nastavení a vestavěným WYSIWYG editorem
- **REST API** — synchronizujte členství z vašeho CRM, automatizujte onboarding, spravujte tagy programaticky

---

## Organizace

*Jedno místo pro vše, co se týká firemního účtu.*

**Manage → Organizations** otevírá rozhraní s kartami se třemi sekcemi: Organizations, Templates a System.

### Seznam organizací

- **Vytváření, úprava, mazání, aktivace/deaktivace** organizací
- **Filtr stavu** — přepínání mezi Active / Inactive / All pomocí skupiny přepínačů; okamžitě filtruje tabulku na straně klienta
- **Živé vyhledávání** — filtrování začíná při 2+ znacích, bez obnovení stránky
- **Barevně označené odznaky** — interaktivní výběr barev s 12 vzorky a živým náhledem odznaku vedle výběru; odznak se zobrazuje na každém ticketu a kartě Kanbanu
- Kliknutím na odznak nebo počet ticketů se otevře vyhledávání ve FreeScoutu filtrované pro danou organizaci
- **Vazba na schránku** — organizace mohou být globální (všechny schránky) nebo omezené na konkrétní schránku
- **Sloupec Tags** — zobrazuje ✓/✗, zda jsou k organizaci přiřazeny nějaké FreeScout tagy (vyžaduje modul Tags); tagy se přiřazují ve formuláři pro úpravy pomocí widgetu s čipy a vyhledáváním s automatickým doplňováním
- **Sloupec počtu ticketů** — celkový počet konverzací pro organizaci; klikací odkaz na úplné výsledky vyhledávání
- **Sloupec počtu členů**
- **Aktivovat / deaktivovat** — pozastavit účet bez ztráty historie; vyžaduje, aby byl povolen Org Snapshot (tlačítko je deaktivováno s popisem, pokud není)
- **Smazat** — dostupné pouze tehdy, když má organizace 0 členů a 0 ticketů (ochranné opatření)
- Všechny akce mazání a deaktivace vyžadují potvrzení

![Seznam organizací — filtr stavu, živé vyhledávání, barevné odznaky, tagy, počty ticketů](docs/screenshots/org-list.png)

### Formulář pro úpravu organizace

- **Název** a **vazba na schránku**
- **Výběr barvy** — 12 vzorků s živým náhledem odznaku
- **Tags** — widget s čipy: vyhledávejte stávající FreeScout tagy, klikněte pro přidání, × pro odebrání
- **Tabulka členů** — pro každého člena: jméno, role, strukturální jednotka, zaškrtávací políčko `can_manage_org` (uděluje administrátorský přístup k organizacím bez plných administrátorských práv), přepínač aktivní/neaktivní
- **Panel strukturálních jednotek** — vytvářejte a přejmenovávejte jednotky přímo ve formuláři pro úpravy; členové jsou přiřazováni k jednotkám ve stejném zobrazení
- **Přidání člena** — automaticky doplní stávající nepřiřazené konverzace daného zákazníka

![Úprava organizace — výběr barvy, čipy tagů, tabulka členů s rolemi a jednotkami](docs/screenshots/org-edit.png)

### Integrace s profilem zákazníka

- **Pole organizace ve formuláři pro úpravu zákazníka ve FreeScoutu** — živé vyhledávání s automatickým doplňováním pro organizace; po výběru organizace se zobrazí rozbalovací seznam rolí; tlačítko × pro odebrání
- Zkratkový odkaz **„Zobrazit tickety organizace"** ve formuláři zákazníka
- **Informační blok organizace v postranním panelu administrátorského ticketu** — název organizace (klikací odkaz na stránku úpravy organizace), strukturální jednotka a role člena; přepínání viditelnosti pro každou schránku v nastavení
- **Jedno aktivní členství na zákazníka** — zákazníka nelze přidat do druhé organizace, pokud má aktivní členství; neaktivní/archivovaná členství jsou povolena

![Úprava zákazníka — pole organizace s automatickým doplňováním a výběrem role](docs/screenshots/customer-org-field.png)

---

## Strukturální jednotky — Řízení přístupu na úrovni oddělení

*Podpora velkých podniků se složitými interními hierarchiemi.*

Organizace lze rozdělit na neomezený počet **strukturálních jednotek** (oddělení, pobočky, regionální kanceláře, projektové týmy):

- Vytvářejte, přejmenovávejte a mažte jednotky ve formuláři pro úpravu administrátorské organizace nebo přímo z portálu (pouze globální manažeři)
- Přiřazujte členy k jednotkám — každý člen patří do jedné jednotky
- **Smazání jednotky** automaticky sníží roli jejích členů `unit_manager` na `member`

**Tři úrovně rolí:**

| Role | Rozsah přístupu |
|------|----------------|
| `member` | Pouze vlastní tickety |
| `unit_manager` | Všechny tickety v rámci strukturální jednotky |
| `manager` (globální) | Všechny tickety v celé organizaci |

- Manažeři jednotek mají plné možnosti portálu — odpovědi, přílohy, přeřazení autora, uzavření/znovuotevření, správa notifikací — omezené striktně na jejich jednotku
- Přístup k ticketům a doručování notifikací jsou vynuceny na hranicích jednotek

![Úprava organizace — členové s rolemi a jednotkami, panel správy jednotek](docs/screenshots/org-edit.png)

---

## Org Snapshot — Trvalé přiřazení ticketů

*Spolehlivé historické reporty i při změnách v seznamu klientů.*

Když je vytvořen ticket, OrgPortal zaznamená kontext organizace jako trvalý snapshot:

- `org_id`, `org_unit_id` a `org_attributed_at` jsou zapsány do konverzace v okamžiku vytvoření
- **Neměnné** — pokud zákazník organizaci opustí, jeho historické tickety zůstanou přiřazeny k dané organizaci; reporty se nikdy nenaruší
- **Přidání člena** spustí automatické doplnění stávajících nepřiřazených konverzací daného zákazníka

### Zdroj přiřazení — tři režimy

Konfigurováno v **Manage → Organizations → záložka System**:

| Režim | Chování |
|-------|---------|
| `member` | Přiřadit ticket k organizaci, jejímž členem je autor ticketu |
| `tag` | Přiřadit nejprve podle FreeScout tagu přiřazeného k organizaci; pokud se tag neshoduje, použít členství jako záložní možnost |
| `tag_only` | Přiřazovat výhradně podle tagu; členství se nepoužívá |

Režimy `tag` a `tag_only` jsou deaktivovány, pokud není aktivní modul Tags.

### Nástroje pro doplnění

- **Ukazatel průběhu** — zobrazuje X / Y přiřazených ticketů (%) s indikátorem „dokončeno" po skončení
- **Předletové statistiky** — před spuštěním doplnění se zobrazí přehled počtu ticketů přiřazených podle tagu vs. podle členství vs. nepřiřazených
- Tlačítko **Spustit doplnění** — zpracuje až 2000 ticketů na kliknutí; po dokončení se zobrazí souhrn výsledků (by_tag / by_member / unmatched)
- **Auto-cron** (`attribution_cron_enabled`) — plánuje doplnění každých 5 minut, 1000 ticketů na spuštění, bez překrývání
- **Resetovat přiřazení** — vymaže všechny snapshoty organizací (nebezpečná akce, vyžaduje potvrzení)
- Příkazová řádka: `php artisan orgportal:backfill-attribution`

![Záložka System — zdroj přiřazení, ukazatel průběhu, předletové statistiky, ovládací prvky doplnění](docs/screenshots/attribution-settings.png)

---

## Integrace s Kanbanem

*Udržujte vizuální pracovní postup v souladu s vašimi B2B účty.*

- Odznak organizace na každé kartě Kanbanu s přiřazenou barvou účtu
- **Filtr organizace** v panelu filtrů Kanbanu — modální okno s vícenásobným výběrem a zaškrtávacími políčky; stav filtru se zachovává při navigaci
- **Vícejazyčné štítky filtrů stavu Kanbanu** — pojmenujte každý sloupec Kanbanu vlastním názvem pro každý jazyk portálu; přepínejte jazyky pomocí výběru jazyka v nastavení schránky; přetažením změňte pořadí filtrů
- Přeložené štítky se zobrazují jak v panelu filtrů portálu, tak ve sloupci **Stav** tabulky firemních ticketů; záložní řetězec: uložené nastavení jazyka → uložená angličtina → původní název sloupce

![Kanban — odznaky organizací na kartách a modální okno filtru organizace](docs/screenshots/kanban-org.png)

---

## Řízení přístupu a oprávnění

*Delegujte správu organizace bez udělení administrátorského přístupu.*

- **„Povolit správu organizací"** (`can_manage_org`) — dvě úrovně:
  - Jako **uživatelské oprávnění** v nastavení agenta — umožní vedoucímu týmu podpory spravovat všechny organizace bez administrátorských práv
  - Jako **příznak pro konkrétního člena** ve formuláři pro úpravu organizace — umožní konkrétnímu členu organizace spravovat tuto jednu organizaci z administrátorského panelu
- **„Povolit správu šablon notifikací"** — oddělené granulární oprávnění pro úpravy šablon
- Mazání organizací zůstává výhradně pro administrátory
- Přístup k portálu je striktně omezen na schránku: manažer z organizace A nemůže přistupovat k organizaci B

![Granulární oprávnění — povolení správy organizací a šablon notifikací](docs/screenshots/user-permissions.png)

---

## Systémová nastavení — Manage → Organizations → záložka System

*Ovládací prvky pouze pro administrátory pro přiřazení, doplnění a přepínač jazyka portálu.*

Záložka **System** je viditelná pouze administrátorům FreeScoutu.

### Panel 1: Přiřazení ticketů

Viz [Org Snapshot](#org-snapshot--trvalé-přiřazení-ticketů) výše pro úplný popis režimů přiřazení, nástrojů pro doplnění a auto-cronu.

### Panel 2: Přepínač jazyka portálu

- **Povolení/zakázání** přepínače jazyka v navigační liště End-User Portal
- **Výběr, které z 19 jazykových nastavení** nabídnout (mřížka zaškrtávacích políček); ve výchozím nastavení jsou povolena všechna
- Pokud je povoleno, manažeři mohou přepnout jazyk portálu; jejich volba se uloží a použije pro notifikační e-maily
- Toto je vestavěný přepínač jazyka OrgPortalu — funguje nezávisle na jakémkoli modulu pro přepínání jazyka třetí strany; oba mohou koexistovat

![Záložka System — panel přepínače jazyka portálu se zaškrtávacími políčky pro jazyková nastavení](docs/screenshots/system-settings.png)

---

## End-User Portal — Samoobsluha pro firemní manažery *(volitelné)*

*Dejte svým B2B klientům portál, kde spravují podpůrný vztah své firmy — bez nutnosti kontaktovat váš tým při každé aktualizaci stavu.*

Vyžaduje modul [End-User Portal](https://freescout.net/module/end-user-portal/).

### Přehled firemních ticketů

Vyhrazená sekce **Firemní tickety** v navigaci portálu s plně vybavenou tabulkou ticketů:

| Sloupec | Popis |
|---------|-------|
| **#** | ID ticketu |
| **Předmět** | Zkráceno s popisem při najetí myší |
| **Zodpovědný** | Přiřazený agent podpory |
| **Autor** | Zákazník, který ticket otevřel; kliknutím filtrovat podle tohoto autora |
| **Stav** | Aktivní / Čekající / Uzavřeno / Spam s ikonami |
| **Stav Kanbanu** | Název sloupce Kanbanu v aktuálním jazyce portálu (pouze pokud je aktivní modul Kanban) |
| **Aktualizováno** | Datum a čas poslední odpovědi |

**Dva nezávislé indikátory stavu přečtení v každém řádku** — sledují dvě různé osoby a zobrazují se současně:

| Indikátor | Čí stav přečtení | Co znamená |
|-----------|------------------|------------|
| **Tučný řádek** | Manažer prohlížející portál | Manažer má nepřečtené notifikace pro tuto konverzaci — něco se stalo, co ještě neviděl |
| **Ikona 👁 oka** | Autor ticketu (zákazník, který ho podal) | Autor dosud neotevřel nejnovější odpověď agenta — užitečné pro zjištění, zda klient odpověď skutečně viděl |

Tyto dva stavy jsou zcela nezávislé: řádek může být tučný (manažer nepřečetl), zatímco oko chybí (autor již přečetl), nebo naopak. Manažer vidí obě informace současně a získává tak úplný přehled o tom, co se děje na obou stranách ticketu, aniž by ho musel otevřít.

**Filtr autora** — kliknutím na jméno autora se aktivuje filtr; v horní části tabulky se zobrazí banner se jménem aktivního autora s odkazem × pro zrušení filtru.

Zahrnuje jak desktopovou tabulku, tak responzivní **mobilní rozložení karet**; automaticky se přepínají podle šířky obrazovky.

Šablona panelu filtrů podporuje **přepsání** pomocí `enduserportal::partials.tickets_filters` — umístěte vlastní zobrazení na tuto cestu a nahraďte výchozí panel filtrů OrgPortalu při zachování veškeré ostatní funkčnosti.

![Firemní tickety — úplná tabulka s indikátory přečtení, bannerem filtru autora, filtry stavu](docs/screenshots/portal-tickets.png)

### Akce s tickety v portálu

Manažeři mohou jednat přímo — bez nutnosti kontaktovat podporu:

- **Odpovědět s přílohami** — přetáhnout a pustit, více souborů na odpověď; názvy příloh a velikosti souborů zobrazeny v každém vláknu
- **Uzavřít ticket** — nová odpověď ho automaticky znovu otevře; banner informuje manažera o tomto chování, když je ticket uzavřen
- **Změnit autora ticketu** — přeřadit ticket na jiného člena organizace
- **Filtrovat podle jednotky** — globální manažeři filtrují seznam ticketů podle strukturální jednotky
- **Filtrovat podle stavu Kanbanu** — konfigurovatelné pro každou schránku, štítky zobrazeny v aktuálním jazyce portálu

![Zobrazení ticketu v portálu — formulář odpovědi s přílohami metodou drag & drop a bannerem uzavřeného ticketu](docs/screenshots/portal-reply.png)

### Sledování zobrazení manažerem

- Pod odpověďmi agentů v administrátorském zobrazení ticketu se zobrazí poznámka **„zobrazeno"**, když manažer otevře ticket v portálu
- Zobrazuje jméno manažera, roli (Manažer organizace / Manažer jednotky) a uplynulý čas
- Zobrazení globálního manažera a manažera jednotky jsou sledována a zobrazena nezávisle — stejné UX jako nativní „Zákazník zobrazil" ve FreeScoutu

![Sledování zobrazení manažerem — poznámka „zobrazeno" se zobrazí pod odpovědí agenta v administrátorském zobrazení ticketu](docs/screenshots/manager-viewed.png)

---

## Notifikační zvon v reálném čase *(volitelné)*

*Informujte manažery okamžitě, jakmile se něco stane s tickety jejich firmy.*

Vyžaduje modul [End-User Portal](https://freescout.net/module/end-user-portal/).

- 🔔 Ikona zvonu s živým odznakem počtu nepřečtených zpráv v navigační liště EUP — automaticky se přemístí na mobilních zařízeních (vedle tlačítka hamburger menu)
- Notifikace pro: **nový ticket**, **odpověď agenta**, **odpověď zákazníka** — pro všechny manažerské role
- Rozbalovací panel s notifikacemi seskupenými podle data: jméno aktéra, typ události, číslo ticketu, náhled zprávy, časové razítko
- **Automatické označení jako přečtené** při otevření ticketu manažerem
- Označit jednotlivé notifikace jako přečtené pomocí ×; **Označit vše jako přečtené** v záhlaví panelu
- Dotazuje každých 15 sekund; obnovuje se při navigaci zpět/vpřed v prohlížeči (s podporou bfcache)

![Notifikační zvon v reálném čase — rozbalovací nabídka se skupinovými nepřečtenými notifikacemi](docs/screenshots/portal-bell.png)

---

## Odběry notifikací *(volitelné)*

*Nechte manažery rozhodnout, o čem chtějí dostávat informace — nic více, nic méně.*

- **Vizuální matice odběrů** na záložce „Notifikace" v Nastavení organizace portálu
- **Tři typy událostí:** Nový ticket · Odpověď agenta · Odpověď zákazníka
- **Dvě úrovně rozsahu:** Celá organizace (globální manažeři) · Jednotlivé strukturální jednotky
- Členové bez jednotky jsou seskupeni v samostatném rozbalitelném řádku **„Bez jednotky"**
- **Přepsání pro jednotlivé členy** — rozbalte libovolný řádek jednotky a zobrazte jednotlivé členy a přepínejte jejich odběry inline; manažeři jednotek s omezenou rolí jsou odpovídajícím způsobem označeni
- **Kaskádová logika v obou směrech:**
  - Povolení „Celá organizace" → povolí všechny jednotky a všechny členy
  - Povolení jednotky → povolí všechny její členy
  - Zakázání člena → automaticky sladí zaškrtávací políčka jednotky a organizace
- Globální manažeři spravují všechny členy; manažeři jednotek spravují pouze svou vlastní jednotku
- Notifikace používají poštovní ovladač příslušné schránky

![Matice odběrů notifikací — přepínače pro jednotlivé jednotky a členy](docs/screenshots/portal-subscriptions.png)

---

## Nastavení organizace portálu

*Manažeři konfigurují strukturu své organizace bez administrátorského přístupu.*

**Nastavení organizace** v navigaci portálu má tři záložky:

### Záložka Notifikace

Výše popsaná matice odběrů.

### Záložka Jednotky *(pouze globální manažeři)*

- **Vytvořit jednotku** — inline formulář s polem pro název
- **Přejmenovat jednotku** — inline úprava přímo v řádku tabulky
- **Smazat jednotku** — tlačítko s potvrzením; manažeři jednotek jsou automaticky degradováni na členy
- Počet členů zobrazený pro každou jednotku

### Záložka Členové

- Tabulka všech členů organizace: jméno, strukturální jednotka, role, odznak aktivní/neaktivní stav
- Štítek **„Globální manažer"** zobrazený vedle jména člena, kde je to relevantní
- Zaškrtávací políčko **Zobrazit deaktivované** — zobrazí se pouze tehdy, když existují neaktivní členové; ve výchozím nastavení skryto
- **Globální manažeři** mohou aktualizovat jednotku a roli libovolného člena pomocí inline formuláře (výběr jednotky + výběr role + Použít)
- **Globální manažeři nemohou povýšit člena na globálního manažera** z portálu — to vyžaduje administrátorský přístup
- Tlačítko **Aktivovat / deaktivovat** pro každého člena s potvrzením při deaktivaci

![Nastavení organizace portálu — záložky Jednotky a Členové](docs/screenshots/portal-settings.png)

---

## Vícejazyčné šablony notifikačních e-mailů *(volitelné)*

*Vaši firemní klienti dostávají e-maily podpory ve svém vlastním jazyce — automaticky, bez manuálního úsilí.*

Konfigurováno v **Manage → Organizations → záložka Templates** (viditelné pro uživatele s oprávněním „správa šablon").

- **Šablony pro jednotlivá jazyková nastavení** — samostatný předmět a tělo pro každý jazyk portálu; přepínejte mezi nimi pomocí rozbalovacího seznamu jazyka; hodnoty jsou přesouvány v paměti bez obnovení stránky
- **Sbalitelné panely** pro každý typ události (Nový ticket / Odpověď agenta / Odpověď zákazníka) — editor Summernote se inicializuje líně při otevření panelu
- Tlačítko **Načíst výchozí** v každém panelu — obnoví vestavěnou šablonu pro aktuálně vybrané jazykové nastavení (pokud neexistuje výchozí nastavení specifické pro jazyk, použije anglickou vestavěnou šablonu)
- **WYSIWYG editor Summernote** pro vytváření bohatých HTML e-mailů
- **Výběr maker** — vkládejte zástupné symboly do předmětu nebo těla jedním kliknutím; pozice kurzoru je zachována v poli předmětu
- **19 vestavěných výchozích šablon** — připraveny k použití hned po vybalení; není potřeba žádná konfigurace

**Dostupné makro proměnné:**

| Proměnná | Popis |
|----------|-------|
| `{manager_name}` | Jméno manažera přijímajícího notifikaci |
| `{author_name}` | Zákazník, který ticket vytvořil nebo na něj odpověděl |
| `{org_name}` | Název organizace |
| `{unit_name}` | Název strukturální jednotky |
| `{subject}` | Předmět ticketu |
| `{ticket_number}` | ID ticketu |
| `{ticket_url}` | Přímý odkaz na ticket v portálu |
| `{ticket_text}` | Úplný text počáteční zprávy (HTML) |
| `{reply_text}` | Úplný text nejnovější odpovědi (HTML) |
| `{created_date}` | Datum vytvoření ticketu |
| `{created_time}` | Čas vytvoření ticketu |
| `{created_datetime}` | Datum a čas vytvoření ticketu |
| `{reply_date}` | Datum odpovědi |
| `{reply_time}` | Čas odpovědi |
| `{reply_datetime}` | Datum a čas odpovědi |

**Záložní řetězec:** uložená šablona pro jazyk → vestavěná šablona pro jazyk → uložená anglická šablona → vestavěná anglická šablona

Jazyk notifikací je určen výběrem jazyka portálu každého manažera, který se automaticky uloží při použití přepínače jazyka.

![E-mailové šablony — sbalitelné panely pro jednotlivá jazyková nastavení, tlačítko Načíst výchozí, editor Summernote](docs/screenshots/admin-templates.png)

---

## REST API *(volitelné)*

*Integrujte OrgPortal do vašeho CRM, ERP nebo pracovního postupu pro onboarding zákazníků.*

Vyžaduje modul [API and Webhooks](https://freescout.net/module/api-webhooks/).

- Plné CRUD pro organizace, strukturální jednotky, členství zákazníků a tagy
- **Pole organizace:** `name`, `color`, `mailboxId`, `isActive` — vše čitelné a aktualizovatelné přes API
- **Dílčí zdroj Members** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — aktualizujte roli, jednotku, `canManageOrg` a příznak `isActive` pro jednotlivé členy nezávisle bez dotýkání se zbytku členství
- **Dílčí zdroj Tags** — `GET/PUT /api/organizations/{id}/tags` — vypsat nebo plně nahradit vazby tagů (vyžaduje modul Tags; vrací `503`, pokud není aktivní)
- Autentizace pomocí hlavičky `X-FreeScout-API-Key` nebo parametru dotazu `api_key`
- Interaktivní **dokumentace ReDoc** na **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Úplná reference API → [docs/api/README.md](docs/api/README.md)**

![Interaktivní dokumentace API — ReDoc se všemi koncovými body OrgPortalu](docs/screenshots/api-docs.png)

---

## Instalace

1. Zkopírujte složku `OrgPortal` do `Modules/` vaší instalace FreeScoutu
2. Přejděte na **Manage → Modules → OrgPortal → Activate**
3. Spusťte migrace:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Vyčistěte cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Podpora gruzínského jazyka** se nasadí automaticky při prvním spuštění — není potřeba žádné ruční kopírování souborů.

---

## Automatické aktualizace

OrgPortal podporuje **aktualizace jedním kliknutím** prostřednictvím vestavěného mechanismu aktualizace modulů FreeScoutu.

> **Vyžaduje FreeScout 1.8.170 nebo novější.** U starších verzí aktualizujte ručně nahrazením složky `OrgPortal` nejnovějším ZIP souborem vydání.

Když je k dispozici nová verze, na stránce **Manage → Modules** se zobrazí banner. Klikněte na **Aktualizovat nyní** — FreeScout automaticky stáhne a nainstaluje nejnovější verzi.

---

## Kompatibilita modulů

| Modul | Stav | Poznámky |
|-------|------|----------|
| End-User Portal ≥ 1.0.85 | Volitelné | Manažerský portál, notifikační zvon, odběry |
| API and Webhooks ≥ 1.0.80 | Volitelné | Koncové body REST API |
| Kanban ≥ 1.0.23 | Volitelné | Odznak na kartách, filtr organizace, vícejazyčné štítky sloupce Stav |
| Custom Fields | ✅ Kompatibilní | — |
| Workflows | ✅ Kompatibilní | — |
| Tags | ✅ Kompatibilní | Čipy tagů ve formuláři pro úpravu organizace; vazby tagů přes API (`/organizations/{id}/tags`); přiřazení ticketů podle tagů |

---

## Konfigurace

### Globální nastavení — **Manage → Organizations → záložka System**

| Možnost | Popis |
|---------|-------|
| Zobrazit odznak na stránce ticketu | Odznak organizace v seznamu konverzací a zobrazení ticketu |
| Zobrazit odznak na kartách Kanbanu | Odznak organizace na kartách nástěnky Kanban |
| Zdroj přiřazení | `member` / `tag` / `tag_only` — jak jsou tickety přiřazovány k organizacím |
| Auto-cron doplnění | Spouštět doplnění každých 5 minut automaticky |
| Viditelnost snapshotu | Zobrazit/skrýt data přiřazení v postranním panelu ticketu |
| Přepínač jazyka portálu | Povolit přepínač jazyka v navigační liště EUP; vyberte, která z 19 jazykových nastavení nabídnout |

### Nastavení pro jednotlivé schránky — **Mailbox Settings → OrgPortal**

Přepisuje globální hodnoty pro konkrétní schránku.

| Možnost | Popis |
|---------|-------|
| Zobrazit odznak na stránce ticketu | Povolit/zakázat odznak pro tuto schránku |
| Zobrazit odznak na kartách Kanbanu | Povolit/zakázat odznak pro tuto schránku |
| Zobrazit blok organizace v profilu zákazníka | Přepnout informační blok organizace v postranním panelu ticketu |
| Filtry stavu firemních ticketů | Mapovat sloupce Kanbanu na pojmenované filtry v portálu; štítky pro jednotlivé jazyky s přepínačem jazyka; přetažením změnit pořadí |

![Nastavení pro schránku — viditelnost odznaku a filtry stavu Kanbanu s vícejazyčnými štítky](docs/screenshots/mailbox-settings.png)

---

## Překlady

OrgPortal je plně lokalizován do **19 jazyků**:

| Jazyk | Kód | Jazyk | Kód |
|-------|-----|-------|-----|
| Angličtina | `en` | Nizozemština | `nl` |
| Ukrajinština | `uk` | Norština | `no` |
| Němčina | `de` | Dánština | `da` |
| Francouzština | `fr` | Švédština | `sv` |
| Španělština | `es` | Finština | `fi` |
| Italština | `it` | Portugalština (BR) | `pt-BR` |
| Čeština | `cs` | Portugalština (PT) | `pt-PT` |
| Slovenština | `sk` | Rumunština | `ro` |
| Polština | `pl` | Zjednodušená čínština | `zh-CN` |
| Gruzínština | `ka` | | |

Soubory překladů: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Šablony notifikačních e-mailů mají vestavěné výchozí hodnoty pro všech 19 jazyků.

### Integrace přepínače jazyka

OrgPortal zahrnuje vestavěný přepínač jazyka portálu (povolte v **záložce System → Přepínač jazyka portálu**). Také se integruje s [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — oba mohou být aktivní současně.

Jazyk, který manažer vybere, se vztahuje na všechny řetězce uživatelského rozhraní OrgPortalu a je uložen jako jeho jazyk notifikací — e-maily jsou automaticky odesílány v jimi zvoleném jazyce.

> **Technická poznámka:** Middleware `OrgPortalSetLocale` znovu použije jazyk portálu po middlewaru FreeScoutu `Localize`, aby zabránil jeho resetování na výchozí nastavení systému při každém požadavku.

---

## Snímky obrazovky

| | |
|---|---|
| ![Seznam organizací](docs/screenshots/org-list.png) | ![Úprava organizace](docs/screenshots/org-edit.png) |
| *Seznam organizací — filtr stavu, živé vyhledávání, barevné odznaky* | *Úprava organizace — výběr barvy, čipy tagů, tabulka členů* |
| ![Záložka System](docs/screenshots/system-settings.png) | ![Úprava zákazníka](docs/screenshots/customer-org-field.png) |
| *Záložka System — režimy přiřazení, doplnění, přepínač jazyka* | *Úprava zákazníka — pole organizace s automatickým doplňováním* |
| ![Portál firemních ticketů](docs/screenshots/portal-tickets.png) | ![Odpověď v portálu](docs/screenshots/portal-reply.png) |
| *Firemní tickety — tabulka, filtr autora, indikátory přečtení* | *Ticket v portálu — odpověď s přílohami, banner uzavřeného ticketu* |
| ![Nastavení organizace portálu](docs/screenshots/portal-settings.png) | ![Notifikační zvon](docs/screenshots/portal-bell.png) |
| *Nastavení organizace portálu — záložky Jednotky a Členové* | *Notifikační zvon v reálném čase s rozbalovací nabídkou* |
| ![Matice odběrů](docs/screenshots/portal-subscriptions.png) | ![E-mailové šablony](docs/screenshots/admin-templates.png) |
| *Matice odběrů notifikací — pro jednotky a členy* | *E-mailové šablony — přepínač jazyka, Načíst výchozí, Summernote* |
| ![Integrace Kanbanu](docs/screenshots/kanban-org.png) | ![Nastavení schránky](docs/screenshots/mailbox-settings.png) |
| *Kanban — odznaky organizací a modální okno filtru organizace* | *Nastavení schránky — filtry Kanbanu s vícejazyčnými štítky* |
| ![Dokumentace API](docs/screenshots/api-docs.png) | |
| *Interaktivní dokumentace API — ReDoc* | |

---

## Licence

[MIT](LICENSE) — © 2026 ASTIN-UA
