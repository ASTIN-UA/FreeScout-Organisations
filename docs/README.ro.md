# OrgPortal — Modul de management al organizațiilor B2B pentru FreeScout

[← Înapoi la README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B module" width="140" align="right">

**OrgPortal** este un modul FreeScout care adaugă un **management complet al organizațiilor B2B** la helpdesk-ul tău: grupează clienții în companii, definește ierarhii de departamente, oferă managerilor corporativi un portal de auto-servire și automatizează notificările — totul în FreeScout, fără instrumente externe.

> Cauți o modalitate de a gestiona conturile de companie în FreeScout? Să oferi clienților corporativi propriul portal de asistență? Să controlezi ce tichete poate vedea fiecare contact B2B în funcție de rol și departament? OrgPortal rezolvă toate acestea.

**Compatibil cu:** FreeScout 1.8.147+  
**Integrări opționale:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/), [Custom Fields](https://freescout.net/module/custom-fields/)

> [!IMPORTANT]
> **Instalează întotdeauna din [cea mai recentă versiune](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest), nu din codul sursă al repozitoriului.**
> Descarcă `OrgPortal.zip` de pe pagina Releases — conține structura de directoare corectă necesară pentru FreeScout.
> Descărcarea codului sursă (prin "Code → Download ZIP" sau `git clone`) **nu va funcționa** și va strica structura modulului.
> Actualizările automate necesită, de asemenea, ca ZIP-ul versiunii să fi fost utilizat pentru instalarea inițială.

---

🌐 **Disponibil și în:**
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

## Cuprins

- [Ce adaugă OrgPortal în FreeScout](#ce-adaugă-orgportal-în-freescout)
- [Organizații](#organizații)
- [Unități Structurale — Control de acces la nivel de departament](#unități-structurale--control-de-acces-la-nivel-de-departament)
- [Domenii de E-mail — Apartenența Automată](#domenii-de-e-mail--apartenența-automată)
- [Org Snapshot — Atribuire permanentă a tichetelor](#org-snapshot--atribuire-permanentă-a-tichetelor)
- [Integrare Kanban](#integrare-kanban)
- [Integrare cu câmpuri personalizate](#integrare-cu-câmpuri-personalizate)
- [Control acces și permisiuni](#control-acces-și-permisiuni)
- [Setări de sistem](#setări-de-sistem--manage--organizations--fila-system)
- [End-User Portal — Auto-servire pentru managerii corporativi](#end-user-portal--auto-servire-pentru-managerii-corporativi-opțional)
- [Clopoțel de notificare în timp real](#clopoțel-de-notificare-în-timp-real-opțional)
- [Abonamente la notificări](#abonamente-la-notificări-opțional)
- [Setările organizației din portal](#setările-organizației-din-portal)
- [Șabloane de e-mail pentru notificări multilingve](#șabloane-de-e-mail-pentru-notificări-multilingve-opțional)
- [REST API](#rest-api-opțional)
- [Instalare](#instalare)
- [Actualizări automate](#actualizări-automate)
- [Compatibilitate module](#compatibilitate-module)
- [Configurare](#configurare)
- [Traduceri](#traduceri)
- [Capturi de ecran](#capturi-de-ecran)
- [Licență](#licență)

---

## Ce adaugă OrgPortal în FreeScout

FreeScout este construit în jurul clienților individuali — fiecare e-mail provine de la o persoană, iar conceptul de companie la care lucrează acea persoană nu există în mod nativ. Aceasta funcționează bine pentru helpdesk-urile B2C. Pentru B2B, este insuficient.

OrgPortal umple acest gol:

- **Conturi de companie** — grupează clienții în organizații cu un nume, insignă colorată, domeniu de căsuță poștală și stare activă/inactivă
- **Apartenență automată prin domeniu de e-mail** — asociază `company.com` la o organizație și fiecare client care scrie de la aceasta este înscris și atribuit automat
- **Ierarhii de departamente** — împarte organizațiile în unități structurale (departamente, sucursale, echipe); fiecare membru este limitat la unitatea sa
- **Acces bazat pe rol** — `member` vede doar propriile tichete; `unit_manager` vede întreaga unitate; `manager` vede întreaga organizație
- **Portal corporativ de auto-servire** — managerii vizualizează toate tichetele companiei, răspund, închid, reatribuie autori și gestionează preferințele de notificare fără a contacta echipa ta
- **Atribuire permanentă a tichetelor** — fiecare tichet este înregistrat în organizația sa la creare; raportările istorice supraviețuiesc schimbărilor în lista de clienți
- **Notificări multilingve** — alerte automate prin e-mail în limba proprie a fiecărui manager, cu șabloane pe locale și un editor WYSIWYG integrat
- **REST API** — sincronizează apartenența din CRM-ul tău, automatizează onboarding-ul, gestionează etichetele programatic

---

## Organizații

*Un singur loc pentru tot ce ține de un cont corporativ.*

**Manage → Organizations** deschide o interfață cu file cu trei secțiuni: Organizations, Templates și System.

### Lista organizațiilor

- **Creare, editare, ștergere, activare/dezactivare** a organizațiilor
- **Filtru de stare** — comutare între Active / Inactive / All cu un grup radio; filtrează tabelul instant pe partea clientului
- **Căutare live** — pornește filtrarea de la 2+ caractere, fără reîncărcarea paginii
- **Insigne cu coduri de culoare** — selector de culori interactiv cu 12 mostre și o previzualizare live a insignei lângă selector; insigna apare pe fiecare tichet și card Kanban
- Clic pe insignă sau pe numărul de tichete deschide o căutare FreeScout filtrată pentru acea organizație
- **Legare la căsuță poștală** — organizațiile pot fi globale (toate căsuțele poștale) sau limitate la o căsuță poștală specifică
- **Coloana Tags** — afișează ✓/✗ dacă există etichete FreeScout legate de organizație (necesită modulul Tags); etichetele sunt atribuite în formularul de editare cu un widget bazat pe chip-uri și căutare cu autocompletare
- **Coloana numărului de tichete** — total conversații per organizație; link clicabil spre rezultate complete ale căutării
- **Coloana numărului de membri**
- **Activare / dezactivare** — suspendă un cont fără a pierde istoricul; necesită activarea Org Snapshot (butonul este dezactivat cu un tooltip când nu este activat)
- **Ștergere** — disponibilă doar când organizația are 0 membri și 0 tichete (gardă de siguranță)
- Toate acțiunile de ștergere și dezactivare necesită confirmare

![Lista organizațiilor — filtru de stare, căutare live, insigne colorate, etichete, număr de tichete](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png)

### Formularul de editare a organizației

- **Nume** și **legare la căsuță poștală**
- **Selector de culori** — 12 mostre cu previzualizare live a insignei
- **Tags** — widget bazat pe chip-uri: tastează pentru a căuta etichete FreeScout existente, clic pentru adăugare, × pentru eliminare
- **Tabelul membrilor** — per membru: nume, rol, unitate structurală, checkbox `can_manage_org` (acordă acces de administrator la organizații fără drepturi complete de administrator), comutator activ/inactiv
- **Panoul unităților structurale** — creează și redenumește unități direct în formularul de editare; membrii sunt atribuiți unităților în aceeași vizualizare
- **Adăugarea unui membru** — completează automat conversațiile existente neatribuite ale acelui client

![Editare organizație — selector de culori, chip-uri de etichete, tabel de membri cu roluri și unități](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

### Integrarea profilului clientului

- **Câmpul Organization în formularul de editare a clientului FreeScout** — căutare live cu autocompletare pentru organizații; meniul derulant de roluri apare după selectarea unei organizații; buton × pentru eliminare
- **Link de comandă rapidă „View org tickets"** în formularul clientului
- **Bloc de informații despre organizație în bara laterală a tichetului din admin** — numele organizației (link clicabil spre pagina de editare a organizației), unitatea structurală și rolul membrului; comutare vizibilitate per căsuță poștală în setări
- **O singură apartenența activă per client** — un client nu poate fi adăugat la o a doua organizație în timp ce are o apartenența activă; apartenența inactivă/arhivată este permisă

![Editare client — câmp organizație cu autocompletare și selector de rol](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png)

---

## Unități Structurale — Control de acces la nivel de departament

*Sprijin pentru marile întreprinderi cu ierarhii interne complexe.*

Organizațiile pot fi împărțite în **unități structurale** nelimitate (departamente, sucursale, birouri regionale, echipe de proiect):

- Creează, redenumește și șterge unități în formularul de editare a organizației din admin, sau direct din portal (numai managerii globali)
- Atribuie membri la unități — fiecare membru aparține unei singure unități
- **Ștergerea unei unități** retrogradează automat membrii săi `unit_manager` la `member`

**Trei niveluri de rol:**

| Rol | Domeniu de acces |
|-----|-----------------|
| `member` | Doar propriile tichete |
| `unit_manager` | Toate tichetele din unitatea sa structurală |
| `manager` (global) | Toate tichetele din întreaga organizație |

- Managerii de unitate au capabilități complete în portal — răspunsuri, atașamente, reatribuire autor, închidere/redeschidere, gestionarea notificărilor — limitate strict la unitatea lor
- Accesul la tichete și livrarea notificărilor sunt aplicate la granițele unității

![Editare organizație — membri cu roluri și unități, panel de gestionare a unităților](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

---

## Domenii de E-mail — Apartenența Automată

*Încetează să adaugi de fiecare dată aceiași angajații ai companiei.*

Asociază un domeniu de e-mail la o organizație și fiecare client care scrie de la acel domeniu este atribuit automat și înscris ca membru — fără pași manuali, nimic de uitat când o persoană nouă trimite pentru prima dată un e-mail.

Configurat per organizație în **Manage → Organizations → Edit → Email domains**.

### Cum funcționează potrivirea

| Regulă | Comportament |
|--------|----------|
| **Doar potrivire exactă** | `company.com` se potrivește cu `jane@company.com`. **Nu** se potrivește cu `jane@mail.company.com` sau `jane@www.company.com` — adaugă-le ca intrări separate dacă dorești |
| **Normalizare** | `@Company.COM`, `https://www.company.com/` și `company.com.` sunt toate salvate ca `company.com` |
| **Atribuirea manuală nu poate fi suprascrisă** | Un client care aparține deja altei organizații nu este niciodată mutat. Contractorii și deciziile deliberate ale administratorului sunt sigure |
| **Revocarea este permanentă** | Dezactivarea unui membru este permanentă până când cineva o inversează. Clientul poate continua să trimită e-mailuri; automatizarea nu va restaura accesul |
| **Domeniu de căsuță poștală** | Un domeniu pe o organizație specifică a unei căsuțe poștale se aplică doar în acea căsuță poștală. O asociere specifică a unei căsuțe poștale are prioritate asupra unei globale pentru același domeniu |
| **Domenii multiple** | O organizație poate avea cât de multe domenii necesită (`company.com`, `company.co.uk`, o marcă achiziționată…) |

### Furnizorii publici sunt blocați

`gmail.com`, `outlook.com`, `ukr.net`, `icloud.com`, serviciile de e-mail disponibil și similare sunt **respinse la salvare**. Asocierea unuia ar pune sute de clienți neînrudiți într-o singură organizație și — prin End-User Portal — le-ar da acces la tichetele reciproce.

Lista vine cu modulul și poate fi **extinsă** (niciodată micșorată) prin opțiunea `orgportal.public_domains_extra` pentru furnizori regionali. Un fallback codificat garantează că furnizorii majori rămân blocați chiar dacă fișierul de configurare lipsește sau este deteriorat.

Organizațiile dezactivate încetează complet să înscrie clienți.

### Adăugarea clienților existenți

O asociere afectează doar mesajele viitoare: clienții care deja există nu sunt înscriși retroactiv. Sunt preluați imediat ce scriu din nou.

### Ștergerea unei asocieri

Ștergerea unui domeniu oprește atribuirea automată viitoare. Membrii pe care i-a creat deja sunt **păstrați în mod implicit** — pot deja folosi portalul. Ți se cere separat dacă vrei să-i dezactivezi; acest rollback atinge doar membrii înscriși de acel domeniu specific, niciodată pe cei adăugați manual.

Membrii creați automat sunt marcați cu un crochet **@** în lista membrilor.

---

## Org Snapshot — Atribuire permanentă a tichetelor

*Raportări istorice fiabile chiar și atunci când lista de clienți se schimbă.*

Când este creat un tichet, OrgPortal înregistrează contextul organizației ca un snapshot permanent:

- `org_id`, `org_unit_id` și `org_attributed_at` sunt scrise în conversație la momentul creării
- **Imuabil** — dacă un client părăsește ulterior o organizație, tichetele sale istorice rămân atribuite acelei organizații; raportările nu se întrerup niciodată
- **Adăugarea unui membru** declanșează completarea automată a conversațiilor existente neatribuite ale acelui client

### Sursa de atribuire — trei moduri

Configurat în **Manage → Organizations → fila System**:

| Mod | Comportament |
|-----|-------------|
| `member` | Atribuie tichetul organizației din care face parte autorul tichetului |
| `tag` | Atribuie mai întâi după eticheta FreeScout legată de o organizație; folosește apartenența dacă nicio etichetă nu se potrivește |
| `tag_only` | Atribuie exclusiv după etichetă; apartenența nu este utilizată |

Modurile `tag` și `tag_only` sunt dezactivate când modulul Tags este inactiv.

**Domeniile de e-mail acționează ca ultimul resort** în modurile `member` și `tag`: când nici o asociere de tag nici o apartenență existentă nu rezolvă tichetul, se verifică domeniul de e-mail al autorului. Niciodată nu suprascrie niciodată din ele, deci o regulă de tag sau o atribuire manuală a administratorului are întotdeauna prioritate. În modul `tag_only`, potrivirea domeniului nu este utilizată.

### Instrumente de completare retrospectivă

- **Bara de progres** — afișează X / Y tichete atribuite (%) cu un indicator „complete" când se termină
- **Statistici preliminare** — înainte de rularea completării, un rezumat arată câte tichete vor fi atribuite prin etichetă vs. prin apartenența vs. neatribuite
- **Butonul Run backfill** — procesează până la 2000 de tichete per clic; rezumatul rezultatelor (by_tag / by_member / unmatched) este afișat după
- **Auto-cron** (`attribution_cron_enabled`) — programează completarea la fiecare 5 minute, 1000 de tichete per rulare, fără suprapunere
- **Resetare atribuire** — șterge toate snapshot-urile organizației (acțiune periculoasă, necesită confirmare)
- Linie de comandă: `php artisan orgportal:backfill-attribution`

![Fila System — sursa de atribuire, bară de progres, statistici preliminare, controale de completare](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png)

---

## Integrare Kanban

*Menține fluxul de lucru vizual aliniat cu conturile tale B2B.*

- Insigna organizației pe fiecare card Kanban cu culoarea atribuită contului
- **Filtrul de organizație** în panoul de filtrare Kanban — modal cu selecție multiplă cu checkbox-uri; starea filtrului persistă între navigări
- **Etichete multilingve pentru filtrele de stare Kanban** — oferă fiecărei coloane Kanban un nume personalizat per limbă a portalului; comutare locale cu selectorul de limbă din setările per căsuță poștală; trage pentru a reordona filtrele
- Etichetele traduse apar atât în bara de filtrare a portalului, cât și în coloana **State** a tabelului de tichete al companiei; lanț de rezervă: locale salvat → engleza salvată → numele original al coloanei

![Kanban — insigne de organizație pe carduri și modal de filtru al organizației](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png)

---

## Integrare cu câmpuri personalizate

*Afișați datele din modulul Câmpuri personalizate direct pe pagina tichetului din portal.*

Necesită ca modulul [Custom Fields](https://freescout.net/module/custom-fields/) să fie instalat și activ.

- Un panou per căsuță poștală în Setări căsuță poștală → OrgPortal vă permite să alegeți ce câmpuri personalizate apar pe pagina tichetului din portal
- Trageți câmpurile pentru a le reordona; fiecare câmp poate avea o etichetă personalizată pentru fiecare limbă a portalului, cu revenire la eticheta în engleză salvată și apoi la numele original al câmpului
- Pe pagina tichetului din portal, câmpurile activate sunt afișate într-o grilă responsive cu două coloane între subiectul tichetului și fir — sunt afișate doar câmpurile cu o valoare nevidă
- Complet opțional — panoul și blocul de pe pagina tichetului sunt ascunse automat atunci când modulul Câmpuri personalizate nu este instalat sau activ

---

## Control acces și permisiuni

*Deleghează gestionarea organizației fără a acorda acces de administrator.*

- **„Allow managing organizations"** (`can_manage_org`) — două niveluri:
  - Ca **permisiune de utilizator** în setările agentului — permite unui responsabil de echipă de asistență să gestioneze toate organizațiile fără drepturi de administrator
  - Ca **flag per-membru** în formularul de editare a organizației — permite unui anumit membru al organizației să gestioneze acea organizație din panoul de administrare
- **„Allow managing notification templates"** — permisiune granulară separată pentru editarea șabloanelor
- Ștergerea organizațiilor rămâne exclusiv pentru administratori
- Accesul la portal este limitat strict per căsuță poștală: un manager din Organizația A nu poate accesa Organizația B

![Permisiuni granulare — permite gestionarea organizațiilor și a șabloanelor de notificare](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png)

---

## Setări de sistem — Manage → Organizations → fila System

*Controale exclusiv pentru administratori pentru atribuire, completare și comutatorul de limbă al portalului.*

Fila **System** este vizibilă doar administratorilor FreeScout.

### Panoul 1: Atribuirea tichetelor

Vezi [Org Snapshot](#org-snapshot--atribuire-permanent-a-tichetelor) de mai sus pentru descrierea completă a modurilor de atribuire, instrumentelor de completare și auto-cron.

### Panoul 2: Comutatorul de limbă al portalului

- **Activare/dezactivare** a comutatorului de limbă în bara de navigare End-User Portal
- **Alege care dintre cele 19 locale** să fie oferite (grilă de checkbox-uri); toate sunt activate implicit
- Când este activat, managerii pot comuta limba portalului; alegerea lor este salvată și utilizată pentru e-mailurile de notificare
- Acesta este comutatorul de limbă integrat al OrgPortal — funcționează independent de orice modul terță parte de comutare a limbii; ambele pot coexista

![Fila System — panoul comutatorului de limbă al portalului cu checkbox-uri de locale](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png)

---

## End-User Portal — Auto-servire pentru managerii corporativi *(opțional)*

*Oferă clienților tăi B2B un portal unde gestionează relația de asistență a companiei lor — fără a contacta echipa ta pentru fiecare actualizare de stare.*

Necesită modulul [End-User Portal](https://freescout.net/module/end-user-portal/).

### Tabloul de bord al tichetelor companiei

O secțiune dedicată **Company Tickets** în navigarea portalului cu un tabel complet de tichete:

| Coloană | Descriere |
|---------|-----------|
| **#** | ID tichet |
| **Subiect** | Trunchiat cu tooltip la hover |
| **Responsabil** | Agent de asistență atribuit |
| **Autor** | Clientul care a deschis tichetul; clic pentru a filtra după acest autor |
| **Stare** | Active / Pending / Closed / Spam cu pictograme |
| **State** | Numele coloanei Kanban în limba curentă a portalului (numai când modulul Kanban este activ) |
| **Actualizat** | Data și ora ultimului răspuns |

**Doi indicatori independenți de stare de citire per rând** — aceștia urmăresc două persoane diferite și sunt afișați simultan:

| Indicator | Starea de citire a cui | Ce înseamnă |
|-----------|----------------------|-------------|
| **Rând îngroșat** | Managerul care vizualizează portalul | Managerul are notificări necitite pentru această conversație — s-a întâmplat ceva pe care nu l-a văzut încă |
| **Pictograma 👁 Ochi** | Autorul tichetului (clientul care l-a trimis) | Autorul nu a deschis încă ultimul răspuns al agentului — util pentru a ști dacă un client a văzut de fapt răspunsul |

Aceste două stări sunt complet independente: un rând poate fi îngroșat (managerul nu a citit) în timp ce ochiul este absent (autorul a citit deja), sau invers. Managerul le vede pe amândouă în același timp, oferind o imagine completă a ceea ce se întâmplă pe ambele laturi ale tichetului fără a-l deschide.

**Filtrul după autor** — clicul pe un nume de autor activează un filtru; un banner apare în partea de sus a tabelului afișând numele autorului activ cu un link × pentru a șterge filtrul.

Atât tabelul pentru desktop, cât și un **layout de card mobil** responsiv sunt incluse; se comută automat în funcție de lățimea ecranului.

Șablonul barei de filtrare suportă **suprascrierea** prin `enduserportal::partials.tickets_filters` — plasează o vizualizare personalizată la acea cale pentru a înlocui bara de filtrare implicită a OrgPortal, păstrând toate celelalte funcționalități.

![Company Tickets — tabel complet cu indicatori de citire, banner filtru autor, filtre de stare](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png)

### Acțiuni asupra tichetelor în portal

Managerii pot acționa direct — fără a fi nevoie să contacteze asistența:

- **Răspuns cu atașamente** — drag & drop, mai multe fișiere per răspuns; numele atașamentelor și dimensiunile fișierelor sunt afișate în fiecare fir
- **Închidere tichet** — un nou răspuns îl redeschide automat; un banner informează managerul despre acest lucru când tichetul este închis
- **Schimbarea autorului tichetului** — reatribuie un tichet unui alt membru al organizației
- **Filtrare după unitate** — managerii globali filtrează lista de tichete după unitatea structurală
- **Filtrare după starea Kanban** — configurabil per căsuță poștală, etichetele sunt afișate în limba curentă a portalului

![Vizualizare tichet în portal — formular de răspuns cu atașamente drag & drop și banner tichet închis](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png)

### Urmărirea vizualizării de către manager

- O notă **„viewed"** apare sub răspunsurile agentului în vizualizarea tichetului din admin când un manager deschide tichetul în portal
- Afișează numele managerului, rolul (Organization manager / Unit manager) și timpul scurs
- Vizualizările managerului global și ale managerului de unitate sunt urmărite și afișate independent — același UX ca „Customer viewed" nativ al FreeScout

![Urmărire vizualizare manager — nota 'viewed' apare sub răspunsul agentului în vizualizarea tichetului din admin](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png)

---

## Clopoțel de notificare în timp real *(opțional)*

*Ține managerii informați în momentul în care se întâmplă ceva cu tichetele companiei lor.*

Necesită modulul [End-User Portal](https://freescout.net/module/end-user-portal/).

- 🔔 Pictograma clopoțel cu insignă cu numărul de necitite în bara de navigare EUP — se repoziționează automat pe mobil (lângă meniul hamburger)
- Notificări pentru: **tichet nou**, **răspuns agent**, **răspuns client** — pentru toate rolurile de manager
- Panel dropdown cu notificări grupate după dată: numele actorului, tipul evenimentului, numărul tichetului, previzualizare mesaj, marcaj temporal
- **Marcare automată ca citit** când managerul deschide tichetul
- Marchează notificările individuale ca citite prin ×; **Mark all as read** în antetul panoului
- Interogare la fiecare 15 secunde; se reîmprospătează la navigarea înainte/înapoi în browser (conștient de bfcache)

![Clopoțel de notificare în timp real — dropdown cu notificări necitite grupate](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png)

---

## Abonamente la notificări *(opțional)*

*Lasă managerii să decidă ce primesc — nimic mai mult, nimic mai puțin.*

- **Matricea vizuală de abonamente** în fila „Notifications" din Setările organizației din portal
- **Trei tipuri de evenimente:** Tichet nou · Răspuns agent · Răspuns client
- **Două niveluri de domeniu:** Întreaga organizație (manageri globali) · Unități structurale individuale
- Membrii fără unitate sunt grupați într-un rând **„No unit"** extensibil separat
- **Suprascrieri per-membru** — extinde orice rând de unitate pentru a dezvălui membrii individuali și a comuta abonamentele lor inline; managerii de unitate cu rol limitat sunt etichetați corespunzător
- **Logică în cascadă în ambele direcții:**
  - Activarea „Entire organization" → activează toate unitățile și toți membrii
  - Activarea unei unități → activează toți membrii săi
  - Dezactivarea unui membru → reconciliază automat checkbox-urile unității și organizației
- Managerii globali gestionează toți membrii; managerii de unitate gestionează doar propria unitate
- Notificările folosesc driver-ul de mail al căsuței poștale corespunzătoare

![Matricea de abonamente la notificări — comutatoare per-unitate și per-membru](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png)

---

## Setările organizației din portal

*Managerii configurează structura organizației fără acces de administrator.*

**Organization Settings** în navigarea portalului are trei file:

### Fila Notifications

Matricea de abonamente descrisă mai sus.

### Fila Units *(numai manageri globali)*

- **Creare unitate** — formular inline cu câmp pentru nume
- **Redenumire unitate** — editare inline direct în rândul tabelului
- **Ștergere unitate** — buton cu confirmare; managerii de unitate sunt retrogradați automat la member
- Numărul de membri afișat per unitate

### Fila Members

- Tabel cu toți membrii organizației: nume, unitate structurală, rol, insignă de stare activă/inactivă
- Eticheta **„Global manager"** afișată lângă numele membrului acolo unde este aplicabil
- Checkbox **Show deactivated** — apare doar când există membri inactivi; ascuns implicit
- **Managerii globali** pot actualiza unitatea și rolul oricărui membru cu un formular inline (selectare unitate + selectare rol + Aplică)
- **Managerii globali nu pot promova un membru la manager global** din portal — aceasta necesită acces de administrator
- Buton **Activare / dezactivare** per membru cu confirmare pentru dezactivare

![Setările organizației din portal — filele Units și Members](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png)

---

## Șabloane de e-mail pentru notificări multilingve *(opțional)*

*Clienții tăi corporativi primesc e-mailuri de asistență în propria lor limbă — automat, fără efort manual.*

Configurat în **Manage → Organizations → fila Templates** (vizibil utilizatorilor cu permisiunea „manage templates").

- **Șabloane per-locale** — subiect și corp separat pentru fiecare limbă a portalului; comutare între ele cu meniul derulant de locale; valorile sunt schimbate în memorie fără reîncărcarea paginii
- **Panouri pliabile** per tip de eveniment (Tichet nou / Răspuns agent / Răspuns client) — editorul Summernote se inițializează leneș când un panou este deschis
- Buton **Load Default** în fiecare panou — restaurează șablonul integrat pentru locale-ul selectat curent (revine la șablonul integrat în engleză dacă nu există un implicit specific pentru locale)
- **Editor Summernote WYSIWYG** pentru compoziția bogată de e-mailuri HTML
- **Selector de variabile macro** — inserează substituenți în subiect sau corp cu un singur clic; poziția cursorului este păstrată în câmpul subiect
- **19 șabloane implicite integrate** — gata de utilizare din cutie; nu este necesară nicio configurare

**Variabile macro disponibile:**

| Variabilă | Descriere |
|-----------|-----------|
| `{manager_name}` | Numele managerului care primește notificarea |
| `{author_name}` | Clientul care a creat sau a răspuns la tichet |
| `{org_name}` | Numele organizației |
| `{unit_name}` | Numele unității structurale |
| `{subject}` | Subiectul tichetului |
| `{ticket_number}` | ID-ul tichetului |
| `{ticket_url}` | Link direct la tichet în portal |
| `{ticket_text}` | Textul complet al mesajului inițial (HTML) |
| `{reply_text}` | Textul complet al ultimului răspuns (HTML) |
| `{created_date}` | Data creării tichetului |
| `{created_time}` | Ora creării tichetului |
| `{created_datetime}` | Data și ora creării tichetului |
| `{reply_date}` | Data răspunsului |
| `{reply_time}` | Ora răspunsului |
| `{reply_datetime}` | Data și ora răspunsului |

**Lanț de rezervă:** șablon locale salvat → șablon locale integrat → șablon englez salvat → șablon englez integrat

Limba notificării este determinată de selecția limbii portalului a fiecărui manager, salvată automat când folosesc comutatorul de limbă.

![Șabloane de e-mail — panouri pliabile per-locale, buton Load Default, editor Summernote](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png)

---

## REST API *(opțional)*

*Integrează OrgPortal în CRM-ul, ERP-ul sau fluxul de lucru de onboarding al clienților tăi.*

Necesită modulul [API and Webhooks](https://freescout.net/module/api-webhooks/).

- CRUD complet pentru organizații, unități structurale, apartenența clienților și etichete
- **Câmpurile organizației:** `name`, `color`, `mailboxId`, `isActive` — toate citibile și actualizabile prin API
- **Sub-resursă members** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — actualizează rolul, unitatea, `canManageOrg` și flag-ul `isActive` per-membru independent fără a afecta restul apartenența
- **Sub-resursă tags** — `GET/PUT /api/organizations/{id}/tags` — listează sau înlocuiește complet legăturile de etichete (necesită modulul Tags; returnează `503` dacă este inactiv)
- Autentificare prin headerul `X-FreeScout-API-Key` sau parametrul de interogare `api_key`
- **Documentație ReDoc interactivă** la **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Referință API completă → [docs/api/README.md](docs/api/README.md)**

![Documentație API interactivă — ReDoc cu toate endpoint-urile OrgPortal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png)

---

## Instalare

> [!IMPORTANT]
> Descarcă `OrgPortal.zip` de pe **[pagina Releases](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)** — **nu** folosi "Code → Download ZIP" și nu clona repozitoriul. Numai ZIP-ul versiunii are structura corectă pentru FreeScout și suportă actualizări automate.

1. Descarcă `OrgPortal.zip` din [cea mai recentă versiune](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)
2. Dezarhivează și copiază dosarul `OrgPortal` în `Modules/` al instalației tale FreeScout
2. Mergi la **Manage → Modules → OrgPortal → Activate**
3. Rulează migrările:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Curăță cache-ul:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Suportul pentru limba georgiană** este implementat automat la prima pornire — nu este necesară copierea manuală a fișierelor.

---

## Actualizări automate

OrgPortal suportă **actualizări cu un singur clic** prin mecanismul integrat de actualizare a modulelor FreeScout.

> **Necesită FreeScout 1.8.170 sau mai recent.** Pe versiunile mai vechi, actualizează manual prin înlocuirea dosarului `OrgPortal` cu cel mai recent ZIP de release.

Când o versiune nouă este disponibilă, un banner apare pe **Manage → Modules**. Clic pe **Update now** — FreeScout descarcă și instalează automat cea mai recentă versiune.

---

## Compatibilitate module

| Modul | Stare | Note |
|-------|-------|------|
| End-User Portal ≥ 1.0.85 | Opțional | Portal manager, clopoțel de notificare, abonamente |
| API and Webhooks ≥ 1.0.80 | Opțional | Endpoint-uri REST API |
| Kanban ≥ 1.0.23 | Opțional | Insignă pe carduri, filtru organizație, etichete multilingve pentru coloana State |
| Custom Fields | ✅ Compatibil | — |
| Workflows | ✅ Compatibil | — |
| Tags | ✅ Compatibil | Chip-uri de etichete în formularul de editare a organizației; legături de etichete prin API (`/organizations/{id}/tags`); atribuire tichete bazată pe etichete |

---

## Configurare

### Setări globale — **Manage → Organizations → fila System**

| Opțiune | Descriere |
|---------|-----------|
| Show badge on ticket page | Insigna organizației în lista de conversații și vizualizarea tichetului |
| Show badge on Kanban cards | Insigna organizației pe cardurile tablei Kanban |
| Attribution source | `member` / `tag` / `tag_only` — cum sunt atribuite tichetele organizațiilor |
| Auto-cron backfill | Rulează completarea automată la fiecare 5 minute |
| Snapshot visibility | Afișează/ascunde datele de atribuire în bara laterală a tichetului |
| Portal Language Switcher | Activează comutatorul de limbă în bara de navigare EUP; alege care dintre cele 19 locale să fie oferite |

### Setări per căsuță poștală — **Mailbox Settings → OrgPortal**

Suprascrie valorile globale pentru căsuța poștală specifică.

| Opțiune | Descriere |
|---------|-----------|
| Show badge on ticket page | Activează/dezactivează insigna pentru această căsuță poștală |
| Show badge on Kanban cards | Activează/dezactivează insigna pentru această căsuță poștală |
| Show organization block in customer profile | Comutare bloc informații organizație în bara laterală a tichetului |
| Company ticket status filters | Mapează coloanele Kanban la filtre numite în portal; etichete per limbă cu comutator de locale; trage pentru a reordona |

![Setări per căsuță poștală — vizibilitate insignă și filtre de stare Kanban cu etichete multilingve](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png)

---

## Traduceri

OrgPortal este complet localizat în **19 limbi**:

| Limbă | Cod | Limbă | Cod |
|-------|-----|-------|-----|
| Engleză | `en` | Olandeză | `nl` |
| Ucraineană | `uk` | Norvegiană | `no` |
| Germană | `de` | Daneză | `da` |
| Franceză | `fr` | Suedeză | `sv` |
| Spaniolă | `es` | Finlandeză | `fi` |
| Italiană | `it` | Portugheză (BR) | `pt-BR` |
| Cehă | `cs` | Portugheză (PT) | `pt-PT` |
| Slovacă | `sk` | Română | `ro` |
| Poloneză | `pl` | Chineză simplificată | `zh-CN` |
| Georgiană | `ka` | | |

Fișiere de traducere: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Șabloanele de e-mail pentru notificări au implicite integrate pentru toate cele 19 limbi.

### Integrarea comutatorului de limbă

OrgPortal include un comutator de limbă integrat al portalului (activează în **fila System → Portal Language Switcher**). De asemenea, se integrează cu [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — ambele pot fi active simultan.

Limba selectată de un manager se aplică tuturor șirurilor de interfață OrgPortal și este salvată ca limba de notificare — e-mailurile sunt trimise automat în limba aleasă.

> **Notă tehnică:** Middleware-ul `OrgPortalSetLocale` re-aplică locale-ul portalului după middleware-ul `Localize` al FreeScout pentru a preveni resetarea sa la valoarea implicită a sistemului la fiecare cerere.

---

## Capturi de ecran

| | |
|---|---|
| ![Lista organizațiilor](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png) | ![Editare organizație](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png) |
| *Lista organizațiilor — filtru de stare, căutare live, insigne colorate* | *Editare organizație — selector culori, chip-uri etichete, tabel membri* |
| ![Fila System](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png) | ![Editare client](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png) |
| *Fila System — moduri de atribuire, completare, comutator de limbă* | *Editare client — câmp organizație cu autocompletare* |
| ![Portalul Company Tickets](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png) | ![Răspuns în portal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png) |
| *Company Tickets — tabel, filtru autor, indicatori de citire* | *Tichet în portal — răspuns cu atașamente, banner închis* |
| ![Setările organizației în portal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png) | ![Clopoțel de notificare](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png) |
| *Setările organizației în portal — filele Units și Members* | *Clopoțel de notificare în timp real cu dropdown* |
| ![Matricea de abonamente](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png) | ![Șabloane de e-mail](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png) |
| *Matricea de abonamente la notificări — per-unitate, per-membru* | *Șabloane e-mail — comutator locale, Load Default, Summernote* |
| ![Integrare Kanban](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png) | ![Setări căsuță poștală](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png) |
| *Kanban — insigne organizație și modal filtru organizație* | *Setări per căsuță poștală — filtre Kanban cu etichete multilingve* |
| ![Documentație API](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png) | |
| *Documentație API interactivă — ReDoc* | |

---

## Licență

[MIT](LICENSE) — © 2026 ASTIN-UA
