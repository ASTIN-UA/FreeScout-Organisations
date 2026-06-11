# OrgPortal — Organisationsportal til FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Et FreeScout-modul, der tilføjer begrebet **Organisationer** (virksomheder/teams) til kunder, udvider End-User Portal for ledere og viser et organisationsbadge på billetter og Kanban-kort.

**Minimumsversion af FreeScout:** 1.8.147  
**Afhængigheder:** ingen påkrævet  
**Valgfrit:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API og webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Sprog:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Funktioner

### Organisationsstyring (admin)
- **Administrer → Organisationer** — fuldt CRUD: opret, rediger, slet organisationer
- **Postkassebinding** — en organisation kan være **global** (synlig i alle postkasser) eller **bundet til en bestemt postkasse**; det tilsvarende label vises i organisationslisten
- Tildel kunder til organisationer med rollevalg: `medlem` eller `leder`
- **Skift medlemsrolle** direkte i tabellen (uden at fjerne og tilføje igen)
- Kundesøgning med autofuldførelse efter navn eller email; kunder, der allerede er i en organisation, udelukkes fra resultater
- Medlemmets email vises under navnet i medlemstabellen
- En kunde — en organisation (håndhævet på database- og API-niveau)
- **Badgefarve** — visuelt paletter med 12 farver i organisationsredigeringsformularen; standard er grå

### Brugerrettigheder
- Ny tilladelse **"Tillad organisationsstyring"** — ikke-admins med denne tilladelse får adgang til liste-, opret- og rediger-organisationssiderne
- Sletning af organisationer forbliver eksklusivt for admins

### Kundekort
- **Organisationsfelt** i kundenredigeringsformularen — vælg organisation og rolle
- **Organisationsbilletter-knap** — åbner en søgning efter alle billetter i organisationen

### Organisationsbadge på billetter
- Vises under emnet på billetsiden og før navnet i samtalelisten
- Kan klikkes på — åbner en søgning efter alle billetter i denne organisation
- Badgefarven bestemmes af organisationsindstillingen (standard grå)
- Aktivér/deaktivér **pr. postkasse** via **Postkasseindstillinger → OrgPortal**; global værdi bruges som fallback

### Organisationsbadge på Kanban-kort
- Vises efter beskedtælleren på hvert kort
- Kan klikkes på — fører til organisationssøgning
- Farven matcher organisationsindstillingen
- **Organisations**-filter er indbygget i standard Kanban-filterdropdown: modal med afkrydsningsfelter, svarende til tagsfilter; tilstand bevares mellem navigationer
- Aktivér/deaktivér **pr. postkasse** via **Postkasseindstillinger → OrgPortal**

### Organisationssøgefilter
- Udvider standard FreeScout-søgningen med et **Organisations**-filter
- Viser alle billetter fra kunder, der tilhører den valgte organisation

### End-User Portal — lederadgang *(valgfrit)*

En organisationsleder får udvidet adgang gennem EUP:

- **Virksomhedsbilletter**-element i portalnavigationen
- Virksomhedsbilletter-tabel med kolonner:
  - **#** og **Emne** med ellipsis-forkortelse og tooltip ved hovering
  - **Ansvarlig** — tildelt agent
  - **Forfatter** — den kunde, der åbnede billetten; klik filtrerer billetter efter forfatter inden for organisationen
  - **Status** — Aktiv / Afventende / Lukket / Spam med ikoner
  - **Tilstand** — Kanban-kolonnenavn (med brugerdefineret label, hvis konfigureret); vises kun, hvis Kanban-modulet er aktivt
  - **Opdateret** — dato og tidspunkt for det sidste svar
- Søg efter billetsemne
- Filtre efter Kanban-statusser (konfigurerbar via **Postkasseindstillinger → OrgPortal**)
- Svar på billet med **vedhæftelsesunderstøttelse** (træk og slip, multi-fil)
- **Luk billet** — leder kan lukke en billet; et nyt svar åbner den igen automatisk
- Ændring af billetforfatter — tildel en billet til et andet organisationsmedlem igen
- **Organisationsindstillinger**-side til konfiguration af mailnotifikationer
- Billadadgang er **strengt begrænset til den aktuelle postkasse** (organisation kopieret til anden postkasse — portal 403)

### Mailnotifikationer *(valgfrit)*
- Ledere med muligheden aktiveret modtager en email, når der oprettes en ny billet af et medlem af organisationen
- Bruger maildriver'en for den tilsvarende postkasse

### Postkasseindstillinger

**Postkasseindstillinger → OrgPortal** (pr. postkasse):

| Mulighed | Beskrivelse |
|----------|-------------|
| Vis badge på billetsiden | Aktivér/deaktivér badge inden for denne postkasse |
| Vis badge på Kanban-kort | Aktivér/deaktivér badge inden for denne postkasse |
| Filtre for virksomhedsbilletstatuser | Vælg Kanban-kolonner vist som afkrydsningsfelter på billetsiden; brugerdefineret label for hvert filter |

---

### REST API *(valgfrit, kræver API og webhooks)*

Godkendelse — `X-FreeScout-API-Key`-header eller `api_key`-forespørgselsparameter.

> **Interaktiv dokumentation** (ReDoc) er tilgængelig på siden **Administrer → API & webhooks** (link "OrgPortal API-dokumentation") eller direkte på `/orgportal/admin/api-docs`.

| Metode | Endpoint | Beskrivelse |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Vis organisationer (pagination, postkassefilter) |
| `POST` | `/api/organizations` | Opret en organisation |
| `GET` | `/api/organizations/{id}` | Hent organisation med medlemmer |
| `PUT` | `/api/organizations/{id}` | Opdater organisation |
| `DELETE` | `/api/organizations/{id}` | Slet organisation |
| `GET` | `/api/customers/{id}/organization` | Kundens organisation |
| `PUT` | `/api/customers/{id}/organization` | Indstil/opdater kundemedlemskab |
| `DELETE` | `/api/customers/{id}/organization` | Fjern kunde fra organisation |

#### Svarkoder

| Kode | Betydning |
|------|-----------|
| `200` | Succes eller ingen-op (intet ændret) |
| `201` | Ressource oprettet; `Resource-ID`-header indeholder ID'et |
| `400` | Valideringsfejl — detaljer i `_embedded.errors` |
| `401` | Ugyldig eller manglende API-nøgle |
| `404` | Ressource ikke fundet |
| `409` | Konflikt — kunde tilhører allerede en anden organisation |

---

#### GET /api/organizations

**Forespørgselsparametre**

| Parameter | Type | Standard | Beskrivelse |
|-----------|------|:-------:|-------------|
| `page` | heltal | `1` | Sidenummer |
| `pageSize` | heltal | `25` | Poster pr. side (maks 100) |
| `mailboxId` | heltal | — | Postkassefilter: returnerer globale organisationer + dem bundet til denne postkasse |

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

**Requestkrop**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:--------:|-------------|
| `name` | streng | ✅ | Organisationsnavn (maks 255 tegn, unik) |
| `mailboxId` | heltal\|null | — | Postkasse-ID eller `null` / udelad for global organisation |

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

**Requestkrop**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:--------:|-------------|
| `name` | streng | ✅ | Nyt organisationsnavn (maks 255 tegn, unik) |
| `mailboxId` | heltal\|null | — | Ny postkasse; `null` — gør global; udelad — lad være uændret |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corporation", "mailboxId": null}'
```

**200 OK**
```json
{"success": true, "message": "Organisation opdateret."}
```

---

#### DELETE /api/organizations/{id}

**200 OK** *(alle medlemmer kaskadeslettet)*
```json
{"success": true, "message": "Organisation slettet."}
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

Tildeler en kunde til en organisation eller opdaterer deres rolle. **En kunde — en organisation**: hvis kunden allerede er medlem af *anden* organisation, afvises anmodningen med `409 Konflikt`. For at overføre — først fjern det aktuelle medlemskab via `DELETE`.

**Requestkrop**

| Felt | Type | Påkrævet | Beskrivelse |
|------|------|:--------:|-------------|
| `organizationId` | heltal | ✅ | Organisation-ID |
| `role` | streng | — | `"medlem"` (standard) eller `"leder"` |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager"}'
```

**201 Created** *(nyt medlemskab)*
```json
{"success": true, "message": "Medlemskab oprettet."}
```

**200 OK** *(rolle opdateret eller ingen-op)*
```json
{"success": true, "message": "Medlemskab opdateret."}
```

**409 Conflict** *(kunde allerede i anden organisation)*
```json
{
  "message": "Kunde tilhører allerede en anden organisation.",
  "errorCode": "CUSTOMER_ALREADY_BELONGS_TO_ANOTHER_ORGANIZATION.",
  "_embedded": {
    "errors": [
      {
        "path": "organizationId",
        "message": "Kunde er allerede medlem af organisation #3. Fjern det eksisterende medlemskab først via DELETE /api/customers/42/organization.",
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
{"success": true, "message": "Medlemskab fjernet."}
```

---

## Installation

1. Kopier `OrgPortal`-mappen til `Modules/` af din FreeScout
2. I adminpanelet: **Administrer → Moduler → OrgPortal → Aktivér**
3. Kør migrations:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Ryd cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Opdateringer

OrgPortal understøtter **automatiske opdateringer** via FreeScout's indbyggede modulopdateringsmekanisme.

Når en ny version er tilgængelig, vil et banner blive vist på siden **Administrer → Moduler**. Klik på **Opdater nu** — FreeScout downloader og installerer den nyeste version automatisk.

Ingen manuel filkopiering påkrævet.

---

## Modulkompatibilitet

| Modul | Status |
|--------|--------|
| End-User Portal ≥ 1.0.85 | Valgfrit — portalfunktioner for ledere |
| API og webhooks ≥ 1.0.80 | Valgfrit — REST API-endpoints |
| Kanban ≥ 1.0.23 | Valgfrit — badge, filter, "Tilstand"-kolonne i virksomhedsbilletter |
| Brugerdefinerede felter | Kompatibel |
| Arbejdsflows | Kompatibel |
| Tags | Kompatibel |

---

## Konfiguration

### Global (**Administrer → OrgPortal-indstillinger**)

| Mulighed | Standard |
|--------|---------|
| Vis badge på billetsiden | ✅ |
| Vis badge på Kanban-kort | ✅ |

### Pr. postkasse (**Postkasseindstillinger → OrgPortal**)

Tilsidesætter globale værdier for den specifikke postkasse.

| Mulighed | Beskrivelse |
|--------|-------------|
| Vis badge på billetsiden | Badge i samtaleliste og på billetsiden |
| Vis badge på Kanban-kort | Badge på Kanban-kort |
| Filtre for virksomhedsbilletstatuser | Kanban-kolonner som afkrydsningsfelter på virksomhedsbilletsiden; hvert filter har et brugerdefineret label synligt for portalbrugere |

---

## Oversættelser

Understøttede sprog: **Engelsk** (`en`), **Ukrainsk** (`uk`), **Rumænsk** (`ro`), **Georgisk** (`ka`), **Tysk** (`de`), **Fransk** (`fr`), **Spansk** (`es`), **Italiensk** (`it`), **Tjekkisk** (`cs`), **Slovakisk** (`sk`), **Polsk** (`pl`), **Russisk** (`ru`), **Hollandsk** (`nl`), **Norsk** (`no`), **Dansk** (`da`), **Svensk** (`sv`), **Finsk** (`fi`), **Portugisisk BR** (`pt-BR`), **Portugisisk PT** (`pt-PT`), **Kinesisk forenklet** (`zh-CN`).

Filer: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### EUPSWLANG-integration

Modulet fungerer korrekt med [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): det sprog, der vælges i portalen, gælder også for OrgPortal-strenge.

For at et sprog skal vises på EUPSWLANG-listen, skal den tilsvarende `Modules/EndUserPortal/Resources/lang/{locale}.json`-fil eksistere. Filer til **Rumænsk** (`ro`) er inkluderet i pakken; **Georgisk** (`ka`) understøttes kun i adminafsnittet (ingen systemunderstøttelse i FreeScout core).

> **Teknisk detalje:** `ReapplyEupLocale`-middleware (registreret sidst i portalrutegruppen) gendanner lokalen efter FreeScout's `Localize`-middleware, som ellers nulstille portalsprogvalget til systemstandarden.

---

## Licens

[MIT](../LICENSE) — © 2026 ASTIN-UA
