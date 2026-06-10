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

Autentificare — header `X-FreeScout-API-Key` sau parametru de interogare `api_key`.

> **Documentație interactivă** (ReDoc) este disponibilă pe pagina **Gestionare → API & Webhooks** (link "OrgPortal API Docs") sau direct la `/orgportal/admin/api-docs`.

| Metoda | Endpoint | Descriere |
|--------|----------|-----------|
| `GET` | `/api/organizations` | Listare organizații (paginare, filtru căsuță) |
| `POST` | `/api/organizations` | Creare organizație |
| `GET` | `/api/organizations/{id}` | Obținere organizație cu membri |
| `PUT` | `/api/organizations/{id}` | Actualizare organizație |
| `DELETE` | `/api/organizations/{id}` | Ștergere organizație |
| `GET` | `/api/customers/{id}/organization` | Organizația clientului |
| `PUT` | `/api/customers/{id}/organization` | Setare/actualizare asociere client |
| `DELETE` | `/api/customers/{id}/organization` | Eliminare client din organizație |

#### Coduri de răspuns

| Cod | Semnificație |
|-----|--------------|
| `200` | Succes sau fără operație (nimic nu s-a schimbat) |
| `201` | Resursă creată; header `Resource-ID` conține ID-ul |
| `400` | Eroare de validare — detalii în `_embedded.errors` |
| `401` | Cheie API invalidă sau lipsă |
| `404` | Resursă negăsită |
| `409` | Conflict — client aparține deja altei organizații |

---

#### GET /api/organizations

**Parametri de interogare**

| Parametru | Tip | Implicit | Descriere |
|-----------|-----|:--------:|-----------|
| `page` | integer | `1` | Numărul paginii |
| `pageSize` | integer | `25` | Înregistrări pe pagină (max 100) |
| `mailboxId` | integer | — | Filtru căsuță: returnează organizații globale + cele legate de această căsuță |

```bash
curl -X GET "https://your-freescout.com/api/organizations?mailboxId=3" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY"
```

**200 OK**
```json
{
  "_embedded": {
    "organizations": [
      {
        "id": 1,
        "name": "Acme Corp",
        "mailboxId": null,
        "createdAt": "2026-06-01T10:00:00+00:00",
        "updatedAt": "2026-06-01T10:00:00+00:00"
      }
    ]
  },
  "page": { "size": 25, "totalElements": 1, "totalPages": 1, "number": 1 }
}
```

---

#### POST /api/organizations

**Corp cerere**

| Câmp | Tip | Obligatoriu | Descriere |
|------|-----|:----------:|-----------|
| `name` | string | ✅ | Nume organizație (max 255 caractere, unic) |
| `mailboxId` | integer\|null | — | ID căsuță sau `null` / omitere pentru organizație globală |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(header `Resource-ID: 1`)*
```json
{
  "id": 1,
  "name": "Acme Corp",
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

#### GET /api/organizations/{id}

**200 OK**
```json
{
  "id": 1,
  "name": "Acme Corp",
  "mailboxId": null,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00",
  "_embedded": {
    "members": [
      {
        "id": 5,
        "organizationId": 1,
        "customerId": 42,
        "role": "manager",
        "notifyOnNewTicket": true,
        "createdAt": "2026-06-01T10:05:00+00:00",
        "updatedAt": "2026-06-01T10:05:00+00:00"
      }
    ]
  }
}
```

---

#### PUT /api/organizations/{id}

**Corp cerere**

| Câmp | Tip | Obligatoriu | Descriere |
|------|-----|:----------:|-----------|
| `name` | string | ✅ | Nume nou organizație (max 255 caractere, unic) |
| `mailboxId` | integer\|null | — | Căsuță nouă; `null` — faceți global; omitere — lăsați neschimbat |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corporation", "mailboxId": null}'
```

**200 OK**
```json
{"success": true, "message": "Organization updated."}
```

---

#### DELETE /api/organizations/{id}

**200 OK** *(toți membrii sunt șterși în cascadă)*
```json
{"success": true, "message": "Organization deleted."}
```

---

#### GET /api/customers/{id}/organization

**200 OK**
```json
{
  "customerId": 42,
  "organizationId": 1,
  "organizationName": "Acme Corp",
  "role": "manager",
  "notifyOnNewTicket": true
}
```

---

#### PUT /api/customers/{id}/organization

Atribuie un client unei organizații sau actualizează rolul acestuia. **Un client — o organizație**: dacă clientul este deja membru al *altei* organizații, cererea este respinsă cu `409 Conflict`. Pentru transfer — mai întâi eliminați asocierea curentă via `DELETE`.

**Corp cerere**

| Câmp | Tip | Obligatoriu | Descriere |
|------|-----|:----------:|-----------|
| `organizationId` | integer | ✅ | ID organizație |
| `role` | string | — | `"member"` (implicit) sau `"manager"` |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager"}'
```

**201 Created** *(asociere nouă)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(rol actualizat sau fără operație)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(client deja în altă organizație)*
```json
{
  "message": "Customer already belongs to another organization.",
  "errorCode": "CUSTOMER_ALREADY_BELONGS_TO_ANOTHER_ORGANIZATION.",
  "_embedded": {
    "errors": [
      {
        "path": "organizationId",
        "message": "Customer is already a member of organization #3. Remove the existing membership first via DELETE /api/customers/42/organization.",
        "source": "JSON"
      }
    ]
  }
}
```

---

#### DELETE /api/customers/{id}/organization

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```

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

Proprietary — ASTIN UA.
