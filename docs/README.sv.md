# OrgPortal — Organisationsportal för FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

En FreeScout-modul som lägger till begreppet **Organisationer** (företag/team) till kunder, utökar End-User Portal för chefer och visar ett organisationsmärke på biljetter och Kanban-kort.

**Minsta FreeScout-version:** 1.8.147  
**Beroenden:** inga obligatoriska  
**Valfritt:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API och webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Språk:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Funktioner

### Organisationshantering (admin)
- **Hantera → Organisationer** — fullständigt CRUD: skapa, redigera, ta bort organisationer
- **Brevlådebindning** — en organisation kan vara **global** (synlig i alla brevlådor) eller **bunden till en specifik brevlåda**; motsvarande etikett visas i organisationslistan
- Tilldela kunder till organisationer med rollval: `medlem` eller `chef`
- **Ändra medlemsroll** direkt i tabellen (utan att ta bort och lägga till igen)
- Kundsökning med autokomplettering efter namn eller e-post; kunder som redan är i en organisation är uteslutna från resultat
- Medlems e-post visas under namn i medlemstabellen
- En kund — en organisation (framtvingad på databas- och API-nivå)
- **Märkesfärg** — visuell palett med 12 färger i organisationsredigeringsformuläret; standard är grå

### Användarbehörigheter
- Ny behörighet **"Tillåt organisationshantering"** — icke-administratörer med denna behörighet får åtkomst till sidor för lista, skapa och redigera organisationer
- Borttagning av organisationer förblir exklusivt för administratörer

### Kundkort
- **Organisationsfält** i kundredigeringsformuläret — välj organisation och roll
- **Organisationsbiljetter**-knapp — öppnar en sökning efter alla biljetter för organisationen

### Organisationsmärke på biljetter
- Visas under ämnet på biljettsidan och före namn i konversationslistan
- Klickbar — öppnar en sökning efter alla biljetter för denna organisation
- Märkesfärgen bestäms av organisationsinställningen (standard grå)
- Aktivera/inaktivera **per brevlåda** via **Brevlådeinställningar → OrgPortal**; globalt värde används som reserv

### Organisationsmärke på Kanban-kort
- Visas efter meddelanderäknaren på varje kort
- Klickbar — leder till organisationssökning
- Färgen matchar organisationsinställningen
- **Organisations**filter inbyggt i standard Kanban-filterdropdown: modal med checkboxar, liknar taggfiltret; tillståndet bevaras mellan navigeringar
- Aktivera/inaktivera **per brevlåda** via **Brevlådeinställningar → OrgPortal**

### Organisationssökfilter
- Utökar standard FreeScout-sökning med ett **Organisations**-filter
- Visar alla biljetter från kunder som tillhör den valda organisationen

### End-User Portal — chefåtkomst *(valfritt)*

En organisationschef får utökad åtkomst genom EUP:

- **Företagsbiljetter**-objekt i portalnavigationen
- Företagsbiljetter-tabell med kolumner:
  - **#** och **Ämne** med ellipsförkorting och verktygstips vid hovring
  - **Ansvarig** — tilldelad agent
  - **Författare** — kunden som öppnade biljetten; klick filtrerar biljetter efter författare inom organisationen
  - **Status** — Aktiv / Väntande / Stängd / Spam med ikoner
  - **Tillstånd** — Kanban-kolumnnamn (med anpassad etikett om konfigurerad); visas endast om Kanban-modulen är aktiv
  - **Uppdaterad** — datum och tid för senaste svar
- Sök efter biljettpämne
- Filter efter Kanban-status (konfigurerbar via **Brevlådeinställningar → OrgPortal**)
- Svara på biljett med **bilage-stöd** (dra och släpp, flerfiler)
- **Stäng biljett** — chef kan stänga en biljett; ett nytt svar öppnar det automatiskt igen
- Ändra biljettskapare — omtilldela en biljett till en annan organisationsmedlem
- **Organisationsinställningar**-sida för konfiguration av e-postmeddelanden
- Biljettpåtkomst är **strikt begränsad till den aktuella brevlådan** (organisation kopierad till annan brevlåda — portal 403)

### E-postmeddelanden *(valfritt)*
- Chefer med alternativet aktiverat får ett e-postmeddelande när en ny biljett skapas av någon medlem av organisationen
- Använder postdrivrutinen för motsvarande brevlåda

### Brevlådeinställningar

**Brevlådeinställningar → OrgPortal** (per brevlåda):

| Alternativ | Beskrivning |
|-----------|-------------|
| Visa märke på biljettsida | Aktivera/inaktivera märke inom denna brevlåda |
| Visa märke på Kanban-kort | Aktivera/inaktivera märke inom denna brevlåda |
| Filter för företagsbiljettstatus | Välj Kanban-kolumner som visas som checkboxar på billettssidan; anpassad etikett för varje filter |

---

### REST API *(valfritt, kräver API och webhooks)*

Autentisering — `X-FreeScout-API-Key`-rubrik eller `api_key`-frågeparameter.

> **Interaktiv dokumentation** (ReDoc) är tillgänglig på sidan **Hantera → API & webhooks** (länk "OrgPortal API-dokumentation") eller direkt på `/orgportal/admin/api-docs`.

| Metod | Slutpunkt | Beskrivning |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Lista organisationer (sidindelning, brevlådefilter) |
| `POST` | `/api/organizations` | Skapa en organisation |
| `GET` | `/api/organizations/{id}` | Hämta organisation med medlemmar |
| `PUT` | `/api/organizations/{id}` | Uppdatera organisation |
| `DELETE` | `/api/organizations/{id}` | Ta bort organisation |
| `GET` | `/api/customers/{id}/organization` | Kundens organisation |
| `PUT` | `/api/customers/{id}/organization` | Ange/uppdatera kundmedlemskap |
| `DELETE` | `/api/customers/{id}/organization` | Ta bort kund från organisation |

#### Svarskoder

| Kod | Betydelse |
|------|-----------|
| `200` | Framgång eller ingen-op (ingenting ändrat) |
| `201` | Resurs skapad; `Resource-ID`-rubrik innehåller ID:t |
| `400` | Valideringsfel — detaljer i `_embedded.errors` |
| `401` | Ogiltig eller saknad API-nyckel |
| `404` | Resursen hittades inte |
| `409` | Konflikt — kunden tillhör redan en annan organisation |

---

#### GET /api/organizations

**Frågeparametrar**

| Parameter | Typ | Standard | Beskrivning |
|-----------|------|:-------:|-------------|
| `page` | heltal | `1` | Sidnummer |
| `pageSize` | heltal | `25` | Poster per sida (max 100) |
| `mailboxId` | heltal | — | Brevlådefilter: returnerar globala organisationer + de bundna till denna brevlåda |

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

**Begärandekropp**

| Fält | Typ | Obligatorisk | Beskrivning |
|------|------|:--------:|-------------|
| `name` | sträng | ✅ | Organisationsnamn (max 255 tecken, unikt) |
| `mailboxId` | heltal\|null | — | Brevlåde-ID eller `null` / utelämna för global organisation |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(rubrik `Resource-ID: 1`)*
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

**Begärandekropp**

| Fält | Typ | Obligatorisk | Beskrivning |
|------|------|:--------:|-------------|
| `name` | sträng | ✅ | Nytt organisationsnamn (max 255 tecken, unikt) |
| `mailboxId` | heltal\|null | — | Ny brevlåda; `null` — gör global; utelämna — lämna oförändrad |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corporation", "mailboxId": null}'
```

**200 OK**
```json
{"success": true, "message": "Organisation uppdaterad."}
```

---

#### DELETE /api/organizations/{id}

**200 OK** *(alla medlemmar kaskadbortagna)*
```json
{"success": true, "message": "Organisation borttagen."}
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

Tilldelar en kund till en organisation eller uppdaterar deras roll. **En kund — en organisation**: om kunden redan är medlem i *en annan* organisation, avvisas begäran med `409 Konflikt`. För att överföra — ta först bort det nuvarande medlemskapet via `DELETE`.

**Begärandekropp**

| Fält | Typ | Obligatorisk | Beskrivelse |
|------|------|:--------:|-------------|
| `organizationId` | heltal | ✅ | Organisation-ID |
| `role` | sträng | — | `"medlem"` (standard) eller `"chef"` |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager"}'
```

**201 Created** *(nytt medlemskap)*
```json
{"success": true, "message": "Medlemskap skapat."}
```

**200 OK** *(roll uppdaterad eller ingen-op)*
```json
{"success": true, "message": "Medlemskap uppdaterat."}
```

**409 Conflict** *(kund redan i annan organisation)*
```json
{
  "message": "Kund tillhör redan en annan organisation.",
  "errorCode": "CUSTOMER_ALREADY_BELONGS_TO_ANOTHER_ORGANIZATION.",
  "_embedded": {
    "errors": [
      {
        "path": "organizationId",
        "message": "Kund är redan medlem i organisation #3. Ta bort det befintliga medlemskapet först via DELETE /api/customers/42/organization.",
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
{"success": true, "message": "Medlemskap borttagen."}
```

---

## Installation

1. Kopiera `OrgPortal`-mappen till `Modules/` i din FreeScout
2. I administratörspanelen: **Hantera → Moduler → OrgPortal → Aktivera**
3. Kör migreringar:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Rensa cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Uppdateringar

OrgPortal stöder **automatiska uppdateringar** via FreeScouts inbyggda moduluppdateringsmekanism.

När en ny version är tillgänglig visas en banner på sidan **Hantera → Moduler**. Klicka på **Uppdatera nu** — FreeScout laddar ner och installerar den senaste versionen automatiskt.

Ingen manuell filkopiering krävs.

---

## Modulkompatibilitet

| Modul | Status |
|--------|--------|
| End-User Portal ≥ 1.0.85 | Valfritt — portalfunktioner för chefer |
| API och webhooks ≥ 1.0.80 | Valfritt — REST API-slutpunkter |
| Kanban ≥ 1.0.23 | Valfritt — märke, filter, "Tillstånd"-kolumn i företagsbiljetter |
| Anpassade fält | Kompatibel |
| Arbetsflöden | Kompatibel |
| Taggar | Kompatibel |

---

## Konfiguration

### Global (**Hantera → OrgPortal-inställningar**)

| Alternativ | Standard |
|---------|---------|
| Visa märke på biljettsida | ✅ |
| Visa märke på Kanban-kort | ✅ |

### Per brevlåda (**Brevlådeinställningar → OrgPortal**)

Åsidosätter globala värden för den specifika brevlådan.

| Alternativ | Beskrivning |
|---------|-------------|
| Visa märke på biljettsida | Märke i konversationslista och på biljettsida |
| Visa märke på Kanban-kort | Märke på Kanban-kort |
| Filter för företagsbiljettstatus | Kanban-kolumner som checkboxar på företagsbillettssidan; varje filter har en anpassad etikett synlig för portalanvändare |

---

## Översättningar

Språk som stöds: **Engelska** (`en`), **Ukrainska** (`uk`), **Rumänska** (`ro`), **Georgiska** (`ka`), **Tyska** (`de`), **Franska** (`fr`), **Spanska** (`es`), **Italienska** (`it`), **Tjeckiska** (`cs`), **Slovakiska** (`sk`), **Polska** (`pl`), **Ryska** (`ru`), **Nederländska** (`nl`), **Norska** (`no`), **Danska** (`da`), **Svenska** (`sv`), **Finska** (`fi`), **Portugisiska BR** (`pt-BR`), **Portugisiska PT** (`pt-PT`), **Förenklad kinesiska** (`zh-CN`).

Filer: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### EUPSWLANG-integration

Modulen fungerar korrekt med [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): det språk som väljs i portalen gäller även för OrgPortal-strängar.

För att ett språk ska visas i EUPSWLANG-listan måste motsvarande `Modules/EndUserPortal/Resources/lang/{locale}.json`-fil finnas. Filer för **Rumänska** (`ro`) ingår i paketet; **Georgiska** (`ka`) stöds endast i adminavsnittet (inget systemstöd i FreeScout core).

> **Teknisk detalj:** `ReapplyEupLocale`-middleware (registrerad sist i portalruttgruppen) återställer lokalen efter FreeScouts `Localize`-middleware, som annars skulle återställa portalspråksvalet till systemstandardvärdet.

---

## Licens

[MIT](../LICENSE) — © 2026 ASTIN-UA
