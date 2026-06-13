# OrgPortal — Portalul de Organizație pentru FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Un modul FreeScout care adaugă conceptul de **Organizații** (companii/echipe) la clienți, extinde Portalul de Utilizator Final pentru manageri și afișează un badge de organizație pe tichetele și cardurile Kanban.

**Versiunea minimă FreeScout:** 1.8.147  
**Dependențe:** niciuna obligatorie  
**Opționale:** [Portalul de Utilizator Final](https://freescout.net/module/end-user-portal/), [API și Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

🌐 **Limbă:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Funcționalități

### Gestionarea organizațiilor (admin)
- **Gestionare → Organizații** — CRUD complet: creare, editare, ștergere organizații
- **Legarea căsuței** — o organizație poate fi **globală** (vizibilă în toate căsuțele) sau **legată de o căsuță specifică**; eticheta corespunzătoare se afișează în lista de organizații
- Atribuiți clienți organizațiilor cu selectarea rolului: `member` sau `manager`
- **Schimbați rolul membrului** direct în tabel (fără a elimina și readăuga)
- Autocompletare de căutare pentru clienți după nume sau email; clienții care sunt deja într-o organizație sunt excluși din rezultate
- E-mailul membrului se afișează sub nume în tabelul de membri
- Un client — o organizație (aplicat la nivel de bază de date și API)
- **Culoarea badge-ului** — paletă vizuală cu 12 culori în formularul de editare a organizației; implicit este gri

### Permisiuni utilizator
- Noua permisiune **"Permitere gestionare organizații"** — utilizatorii non-administratori cu această permisiune obțin acces la paginile de listă, creare și editare a organizațiilor
- Ștergerea organizațiilor rămâne exclusivă pentru administratori

### Card client
- Câmpul **Organizație** în formularul de editare a clientului — selectați organizația și rolul
- Butonul **Tichete Organizație** — deschide o căutare pentru toate tichetele organizației

### Badge de organizație pe tichetele
- Afișat sub subiect pe pagina tichetului și înainte de nume în lista conversațiilor
- Clickabil — deschide o căutare pentru toate tichetele acestei organizații
- Culoarea badge-ului este determinată de setarea organizației (implicit gri)
- Activare/dezactivare **per căsuță** via **Setări Căsuță → OrgPortal**; valoarea globală este folosită ca fallback

### Badge de organizație pe cardurile Kanban
- Afișat după contorul de mesaje pe fiecare card
- Clickabil — duce la căutarea de organizații
- Culoarea se potrivește cu setarea organizației
- Filtrul **Organizație** integrat în dropdown-ul de filtru Kanban standard: modal cu casete de bifat, similar filtrului de etichete; starea se păstrează între navigări
- Activare/dezactivare **per căsuță** via **Setări Căsuță → OrgPortal**

### Filtru de căutare după organizație
- Extinde căutarea standard FreeScout cu un filtru **Organizație**
- Afișează toate tichetele clienților care aparțin organizației selectate

### Portalul de Utilizator Final — acces manager *(opțional)*

Un manager de organizație obține acces extins prin EUP:

- Element **Tichete Companie** în navigarea portalului
- Tabel de tichete companie cu coloane:
  - **#** și **Subiect** cu truncare elipsă și tooltip la hover
  - **Responsabil** — agent asignat
  - **Autor** — clientul care a deschis tichetul; clic filtrează tichetele după autor în organizație
  - **Status** — Activ / În așteptare / Închis / Spam cu pictograme
  - **Stare** — nume coloană Kanban (cu etichetă personalizată dacă configurată); afișat doar dacă modulul Kanban este activ
  - **Actualizat** — data și ora ultimului răspuns
- Căutare după subiect tichet
- Filtrare după statusuri Kanban (configurabil via **Setări Căsuță → OrgPortal**)
- Răspuns la tichet cu suport **anexe** (drag & drop, mai multe fișiere)
- **Închidere tichet** — managerul poate închide un tichet; un nou răspuns îl redeschide automat
- Schimbă autorul tichetului — reatribuie tichetul altui membru al organizației
- Pagina **Setări Org** pentru configurarea notificărilor prin e-mail
- Accesul la tichet este **strict limitat la căsuța curentă** (organizație copiată în altă căsuță — portal 403)

### Abonări la Notificări *(opțional)*

Managerii portalului pot personaliza care evenimente și domenii declanșează notificări prin e-mail:

- **Matricea abonărilor** pe fila "Notificări" în Setările Organizației portalului
- **Evenimente:** Tichet nou, Răspuns agent, Răspuns client
- **Domenii:** Întreaga organizație (doar manageri globali) sau unități structurale specifice
- **Abonări per-membru:** fiecare rând de unitate se poate extinde — clic pentru a dezvălui fiecare membru al unității și activați/dezactivați abonările individuale inline. Un manager global gestionează membri din toate unitățile; un manager de unitate doar ai săi.
- **Cascadă complet tranzitivă:** "Întreaga organizație" conduce fiecare unitate și fiecare membru; o casetă de bifat a unității conduce toți membrii săi; debifarea unui membru reconciliază unitatea acestuia (și organizația) automat — în ambele direcții, per coloană de eveniment.

### Notificări prin e-mail *(opțional)*
- Managerii cu opțiunea activată primesc e-mail atunci când un nou tichet este creat de orice membru al organizației
- Folosește driver-ul de e-mail al căsuței corespunzătoare

### Setări căsuță

**Setări Căsuță → OrgPortal** (per căsuță):

| Opțiune | Descriere |
|---------|-----------|
| Afișare badge pe pagina tichetului | Activare/dezactivare badge în această căsuță |
| Afișare badge pe carduri Kanban | Activare/dezactivare badge în această căsuță |
| Filtre status tichete companie | Selectați coloane Kanban afișate ca casete de bifat pe pagina de tichete; etichetă personalizată pentru fiecare filtru |

---

### REST API *(opțional, necesită API și Webhooks)*

OrgPortal oferă un API REST complet pentru gestionarea organizațiilor, unităților structurale și apartenențelor clienților — autentificare prin antetul `X-FreeScout-API-Key` sau parametrul de interogare `api_key`.

📖 **Referință API completă → [docs/api/README.ro.md](api/README.ro.md)** (toate punctele finale, exemple de cerere/răspuns, coduri de eroare)

Documentația interactivă ReDoc este disponibilă și în **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

---

## Instalare

1. Copiați folderul `OrgPortal` în `Modules/` al FreeScout-ului dvs.
2. În panoul de administrare: **Gestionare → Module → OrgPortal → Activare**
3. Executați migrațiile:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Ștergeți cache-ul:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Actualizări

OrgPortal suportă **actualizări automate** prin mecanismul de actualizare a modulelor integrat al FreeScout.

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

Când este disponibilă o versiune nouă, un banner va apărea pe pagina **Gestionare → Module**. Faceți clic pe **Actualizare acum** — FreeScout va descărca și instala automat cea mai nouă versiune.

Nicio copiere manuală de fișiere necesară.

---

## Compatibilitate module

| Modul | Stare |
|-------|-------|
| Portalul de Utilizator Final ≥ 1.0.85 | Opțional — funcționalități portal pentru manageri |
| API și Webhooks ≥ 1.0.80 | Opțional — endpoint-uri API REST |
| Kanban ≥ 1.0.23 | Opțional — badge, filtru, coloană "Stare" în tichete companie |
| Custom Fields | Compatibil |
| Workflows | Compatibil |
| Tags | Compatibil |

---

## Configurare

### Global (**Gestionare → Setări OrgPortal**)

| Opțiune | Implicit |
|---------|----------|
| Afișare badge pe pagina tichetului | ✅ |
| Afișare badge pe carduri Kanban | ✅ |

### Per căsuță (**Setări Căsuță → OrgPortal**)

Suprascrie valorile globale pentru căsuța specifică.

| Opțiune | Descriere |
|---------|-----------|
| Afișare badge pe pagina tichetului | Badge în lista conversațiilor și pe pagina tichetului |
| Afișare badge pe carduri Kanban | Badge pe carduri Kanban |
| Filtre status tichete companie | Coloane Kanban ca casete de bifat pe pagina de tichete companie; fiecare filtru are o etichetă personalizată vizibilă utilizatorilor portalului |

---

## Traduceri

Limbi suportate: **Engleză** (`en`), **Ucraineană** (`uk`), **Română** (`ro`), **Georgiană** (`ka`), **Germană** (`de`), **Franceză** (`fr`), **Spaniolă** (`es`), **Italiană** (`it`), **Cehă** (`cs`), **Slovacă** (`sk`), **Poloneză** (`pl`), **Rusă** (`ru`), **Olandeză** (`nl`), **Norvegiană** (`no`), **Daneză** (`da`), **Suedeză** (`sv`), **Finlandeză** (`fi`), **Portugheză BR** (`pt-BR`), **Portugheză PT** (`pt-PT`), **Chineză Simplificată** (`zh-CN`).

Fișiere: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### Integrare EUPSWLANG

Modulul funcționează corect cu [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): limba selectată în portal se aplică și șirurilor OrgPortal.

Pentru ca o limbă să apară în lista EUPSWLANG, fișierul `Modules/EndUserPortal/Resources/lang/{locale}.json` corespunzător trebuie să existe. Fișiere pentru **Română** (`ro`) sunt incluse în pachet; **Georgiană** (`ka`) este suportată doar în secțiunea de administrare (fără suport în sistemul FreeScout core).

> **Detaliu tehnic:** Middleware-ul `ReapplyEupLocale` (registrat ultim în grupul de rute al portalului) restabilește locale-ul după middleware-ul `Localize` al FreeScout, care ar reseta altfel selecția limbii portalului la implicit-ul sistemului.

---

## Licență

[MIT](../LICENSE) — © 2026 ASTIN-UA
