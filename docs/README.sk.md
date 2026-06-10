# OrgPortal — Portál organizácie pre FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Modul FreeScout, ktorý pridáva koncept **Organizácií** (podniky/tímy) ku zákazníkom, rozširuje End-User Portal pre manažérov a zobrazuje odznak organizácie na lístkoch a kartách Kanban.

**Minimálna verzia FreeScout:** 1.8.147  
**Závislosti:** žiadne požadované  
**Voliteľné:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API a Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

🌐 **Jazyk:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Funkcie

### Správa organizácií (admin)
- **Správa → Organizácie** — kompletný CRUD: vytvorenie, úprava, odstránenie organizácií
- **Väzba na poštovú schránku** — organizácia môže byť **globálna** (viditeľná vo všetkých poštových schránkach) alebo **viazaná na konkrétnu schránku**; zodpovedajúci popis sa zobrazí v zozname organizácií
- Priraďte zákazníkom organizácie s výberom roly: `Člen` alebo `Manažér`
- **Zmena roly člena** priamo v tabuľke (bez odobrania a opätovného pridania)
- Vyhľadávanie zákazníka s automatickým dopĺňaním podľa mena alebo e-mailu; zákazníci, ktorí sú už v organizácii, sú vylúčení z výsledkov
- E-mail člena sa zobrazuje pod menom v tabuľke členov
- Jeden zákazník — jedna organizácia (vynútené na úrovni databázy a API)
- **Farba odznáku** — vizuálna paleta s 12 farbami v editačnom formulári organizácie; predvolená farba je sivá

### Oprávnenia používateľa
- Nové oprávnenie **"Povolenie správy organizácií"** — neadministrátori s týmto oprávnením majú prístup na stránky zoznamu, vytvorenia a úpravy organizácií
- Mazanie organizácií zostáva vyhradené správcom

### Karta zákazníka
- Pole **Organizácia** v editačnom formulári zákazníka — vyberte organizáciu a rolu
- Tlačidlo **Lístky organizácie** — otvorí vyhľadávanie všetkých lístkov organizácie

### Odznak organizácie na lístkoch
- Zobrazený pod predmetom na stránke lístku a v zozname konverzácií
- Kliknuteľný — otvorí vyhľadávanie všetkých lístkov tejto organizácie
- Farba odznáku sa určuje podľa nastavenia organizácie (predvolená sivá)
- Zapnutie/vypnutie **na poštovú schránku** cez **Nastavenia poštovej schránky → OrgPortal**; globálna hodnota sa používa ako záloha

### Odznak organizácie na kartách Kanban
- Zobrazený za čítačom správ na každej karte
- Kliknuteľný — vedie na vyhľadávanie organizácie
- Farba zodpovedá nastaveniu organizácie
- Filter **Organizácia** zabudovaný do štandardného rozbaľovacieho zoznamu Kanban filtrov: modálne okno so začiarkavacími poliami, podobné filtru Značky; stav sa zachovává medzi navigáciou
- Zapnutie/vypnutie **na poštovú schránku** cez **Nastavenia poštovej schránky → OrgPortal**

### Filter vyhľadávania organizácie
- Rozširuje štandardné vyhľadávanie FreeScout o filter **Organizácia**
- Zobrazuje všetky lístky zákazníkov patriacich do vybranej organizácie

### End-User Portal — prístup manažérov *(voliteľné)*

Manažér organizácie má rozšírený prístup cez EUP:

- Položka **Lístky spoločnosti** v navigácii portálu
- Tabuľka lístkov spoločnosti so stĺpcami:
  - **#** a **Predmet** s rezaním troch bodiek a tooltip pri umiestnení myši
  - **Zodpovedný** — pridelený agent
  - **Autor** — zákazník, ktorý otvoril lístek; kliknutie filtruje lístky podľa autora v organizácii
  - **Stav** — Aktívny / Čakajúci / Zatvorený / Spam so symbolmi
  - **Stav** — názov stĺpca Kanban (s vlastným popisom, ak je nakonfigurovaný); zobrazované len pri aktívnom module Kanban
  - **Aktualizované** — dátum a čas poslednej odpovede
- Vyhľadávanie v predmete lístku
- Filtrovanie podľa stavu Kanban (konfigurovateľné cez **Nastavenia poštovej schránky → OrgPortal**)
- Odpoveď na lístek s podporou **Príloh** (Drag & Drop, viacero súborov)
- **Zatvoriť lístek** — manažér môže zatvoriť lístek; nová odpoveď ho automaticky znovu otvorí
- Zmena autora lístku — priradenie lístku inému členu organizácie
- Stránka **Nastavenia org.** pre konfiguráciu e-mailových upozornení
- Prístup k lístkom je **prísne obmedzený na aktuálnu poštovú schránku** (organizácia skopírovaná do inej schránky — portál 403)

### E-mailové upozornenia *(voliteľné)*
- Manažéri s aktivovanou možnosťou obdržia e-mail, keď člen organizácie vytvorí nový lístek
- Používa poštový ovládač zodpovedajúcej poštovej schránky

### Nastavenia poštovej schránky

**Nastavenia poštovej schránky → OrgPortal** (na poštovú schránku):

| Možnosť | Popis |
|---------|-------|
| Zobraziť odznak na stránke lístku | Zapnutie/vypnutie odznáku v tejto schránke |
| Zobraziť odznak na kartách Kanban | Zapnutie/vypnutie odznáku v tejto schránke |
| Filtre stavu lístkov spoločnosti | Vyberte stĺpce Kanban zobrazené ako začiarkavacie políčka na stránke lístkov; vlastný popis pre každý filter |

---

### REST API *(voliteľné, vyžaduje API a Webhooks)*

Overenie — hlavička `X-FreeScout-API-Key` alebo parameter dotazu `api_key`.

> **Interaktívna dokumentácia** (ReDoc) je dostupná na stránke **Správa → API a Webhooks** (odkaz "Dokumentácia OrgPortal API") alebo priamo na `/orgportal/admin/api-docs`.

| Metóda | Koncový bod | Popis |
|--------|-------------|-------|
| `GET` | `/api/organizations` | Zoznam organizácií (stránkovanie, filter poštovej schránky) |
| `POST` | `/api/organizations` | Vytvorí organizáciu |
| `GET` | `/api/organizations/{id}` | Získa organizáciu s členmi |
| `PUT` | `/api/organizations/{id}` | Aktualizuje organizáciu |
| `DELETE` | `/api/organizations/{id}` | Zmaže organizáciu |
| `GET` | `/api/customers/{id}/organization` | Organizácia zákazníka |
| `PUT` | `/api/customers/{id}/organization` | Nastaví/aktualizuje členstvo zákazníka |
| `DELETE` | `/api/customers/{id}/organization` | Odstráni zákazníka z organizácie |

#### Kódy odpovedí

| Kód | Význam |
|-----|--------|
| `200` | Úspech alebo No-Op (nič sa nezmenilo) |
| `201` | Zdroj vytvorený; hlavička `Resource-ID` obsahuje ID |
| `400` | Chyba validácie — detaily v `_embedded.errors` |
| `401` | Neplatný alebo chýbajúci kľúč API |
| `404` | Zdroj nenájdený |
| `409` | Konflikt — zákazník už patrí do inej organizácie |

---

#### GET /api/organizations

**Parametre dotazu**

| Parameter | Typ | Predvolené | Popis |
|-----------|-----|-----------|-------|
| `page` | integer | `1` | Číslo stránky |
| `pageSize` | integer | `25` | Záznamov na stranu (max 100) |
| `mailboxId` | integer | — | Filter poštovej schránky: vracia globálne organizácie + viazané na túto schránku |

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

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|------|-----|--------|-------|
| `name` | string | ✅ | Názov organizácie (max 255 znakov, jedinečný) |
| `mailboxId` | integer\|null | — | ID poštovej schránky alebo `null` / vynechajte pre globálnu organizáciu |

**201 Created** *(hlavička `Resource-ID: 1`)*
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

#### PUT /api/organizations/{id}

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|------|-----|--------|-------|
| `name` | string | ✅ | Nový názov organizácie (max 255 znakov, jedinečný) |
| `mailboxId` | integer\|null | — | Nová schránka; `null` — urobiť globálnou; vynechajte — ponechať nezmenené |

**200 OK**
```json
{"success": true, "message": "Organization updated."}
```

---

#### DELETE /api/organizations/{id}

**200 OK** *(všetci členovia budú zmazaní kaskádovo)*
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

Priradí zákazníka organizácii alebo aktualizuje jeho rolu. **Jeden zákazník — jedna organizácia**: Ak zákazník už patrí do *inej* organizácie, požiadavka bude odmietnutá s `409 Conflict`. Aby sa pridelil — najskôr odstráňte aktuálne členstvo cez `DELETE`.

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|------|-----|--------|-------|
| `organizationId` | integer | ✅ | ID organizácie |
| `role` | string | — | `"member"` (predvolené) alebo `"manager"` |

**201 Created** *(nové členstvo)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(rola aktualizovaná alebo No-Op)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(zákazník už v inej organizácii)*
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

## Inštalácia

1. Skopírujte priečinok `OrgPortal` do `Modules/` vášho FreeScout
2. V admin paneli: **Správa → Moduly → OrgPortal → Aktivovať**
3. Spustite migrácie:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Vymažte cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Aktualizácie

OrgPortal podporuje **automatické aktualizácie** prostredníctvom integrovaného mechanizmu aktualizácie modulov FreeScout.

Keď je dostupná nová verzia, na stránke **Správa → Moduly** sa objaví banner. Kliknite na **Aktualizovať teraz** — FreeScout automaticky stiahne a nainštaluje najnovšiu verziu.

Nie je potrebné ručné kopírovanie súborov.

---

## Kompatibilita modulov

| Modul | Stav |
|-------|------|
| End-User Portal ≥ 1.0.85 | Voliteľný — funkcie portálu pre manažérov |
| API a Webhooks ≥ 1.0.80 | Voliteľný — koncové body REST API |
| Kanban ≥ 1.0.23 | Voliteľný — odznak, filter, stĺpec "Stav" v lístkoch spoločnosti |
| Vlastné polia | Kompatibilné |
| Workflows | Kompatibilné |
| Značky | Kompatibilné |

---

## Konfigurácia

### Globálna (**Správa → Nastavenia OrgPortal**)

| Možnosť | Predvolené |
|---------|-----------|
| Zobraziť odznak na stránke lístku | ✅ |
| Zobraziť odznak na kartách Kanban | ✅ |

### Na poštovú schránku (**Nastavenia poštovej schránky → OrgPortal**)

Prepíše globálne hodnoty pre konkrétnu schránku.

| Možnosť | Popis |
|---------|-------|
| Zobraziť odznak na stránke lístku | Odznak v zozname konverzácií a na stránke lístku |
| Zobraziť odznak na kartách Kanban | Odznak na kartách Kanban |
| Filtre stavu lístkov spoločnosti | Stĺpce Kanban ako začiarkavacie políčka na stránke Lístky spoločnosti; každý filter má vlastný popis viditeľný pre používateľov portálu |

---

## Preklady

Podporované jazyky: **English** (`en`), **Українська** (`uk`), **Română** (`ro`), **Georgian** (`ka`), **Deutsch** (`de`), **Français** (`fr`), **Español** (`es`), **Italiano** (`it`), **Čeština** (`cs`), **Slovenčina** (`sk`), **Polski** (`pl`), **Русский** (`ru`), **Nederlands** (`nl`), **Norsk** (`no`), **Dansk** (`da`), **Svenska** (`sv`), **Suomi** (`fi`), **Português BR** (`pt-BR`), **Português PT** (`pt-PT`), **中文 (简体)** (`zh-CN`).

Súbory: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### Integrácia EUPSWLANG

Modul správne funguje s [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): jazyk vybraný v portáli platí aj pre reťazce OrgPortal.

Aby sa jazyk objavil v zozname EUPSWLANG, musí existovať zodpovedajúci súbor `Modules/EndUserPortal/Resources/lang/{locale}.json`. Súbory pre **Română** (`ro`) sú zahrnuté v balíčku; **Georgian** (`ka`) je podporovaný len v oblasti správy (žiadna systémová podpora v jadre FreeScout).

> **Technický detail:** Middleware `ReapplyEupLocale` (registrovaný ako posledný v skupine trás portálu) obnovuje jazykové nastavenie po tom, čo by ho middleware `Localize` FreeScout inak resetoval na výchozí systémový jazyk.

---

## Licencia

Proprietária — ASTIN UA.
