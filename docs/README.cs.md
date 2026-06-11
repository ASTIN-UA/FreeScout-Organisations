# OrgPortal — Portál organizace pro FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Modul FreeScout, který přidává koncept **Organizací** (společnosti/týmy) ke zákazníkům, rozšiřuje End-User Portal pro manažery a zobrazuje odznak organizace na lístcích a kartách Kanban.

**Minimální verze FreeScout:** 1.8.147  
**Závislosti:** žádné vyžadované  
**Volitelné:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API a Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

🌐 **Jazyk:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Funkce

### Správa organizací (admin)
- **Správa → Organizace** — kompletní CRUD: vytvoření, úprava, odstranění organizací
- **Vazba na poštovní schránku** — organizace může být **globální** (viditelná ve všech poštovních schránkách) nebo **vázána na konkrétní schránku**; odpovídající popisek se zobrazí v seznamu organizací
- Přiřaďte zákazníkům organizace s výběrem role: `Člen` nebo `Manažer`
- **Změna role člena** přímo v tabulce (bez odebrání a opětovného přidání)
- Vyhledávání zákazníka s automatickým doplňováním podle jména nebo e-mailu; zákazníci, již v organizaci, jsou vyloučeni z výsledků
- E-mail člena se zobrazuje pod jménem v tabulce členů
- Jeden zákazník — jedna organizace (vynuceno na úrovni databáze a API)
- **Barva odznáku** — vizuální paleta s 12 barvami v editačním formuláři organizace; výchozí barva je šedá

### Uživatelská oprávnění
- Nové oprávnění **"Povolení správy organizací"** — neadministrátoři s tímto oprávněním mají přístup na stránky seznamu, vytvoření a úpravy organizací
- Mazání organizací zůstává vyhrazeno administrátorům

### Karta zákazníka
- Pole **Organizace** v editačním formuláři zákazníka — vyberte organizaci a roli
- Tlačítko **Lístky organizace** — otevře vyhledávání všech lístků organizace

### Odznak organizace na lístcích
- Zobrazen pod předmětem na stránce lístku a v seznamu konverzací
- Kliknutelný — otevře vyhledávání všech lístků této organizace
- Barva odznáku se určuje podle nastavení organizace (výchozí šedá)
- Zapnutí/vypnutí **na poštovní schránku** přes **Nastavení poštovní schránky → OrgPortal**; globální hodnota se používá jako záloha

### Odznak organizace na kartách Kanban
- Zobrazen za čítačem zpráv na každé kartě
- Kliknutelný — vede na vyhledávání organizace
- Barva odpovídá nastavení organizace
- Filtr **Organizace** vestavěný do standardního rozbalovacího seznamu Kanban filtrů: modální okno se zaškrtávacími poli, podobné filtru Značky; stav se zachovává mezi navigací
- Zapnutí/vypnutí **na poštovní schránku** přes **Nastavení poštovní schránky → OrgPortal**

### Filtr vyhledávání organizace
- Rozšiřuje standardní vyhledávání FreeScout o filtr **Organizace**
- Zobrazuje všechny lístky zákazníků patřících do vybrané organizace

### End-User Portal — přístup manažerů *(volitelné)*

Manažer organizace má rozšířený přístup přes EUP:

- Položka **Lístky společnosti** v navigaci portálu
- Tabulka lístků společnosti se sloupci:
  - **#** a **Předmět** s řezáním tří teček a tooltipem při najetí myší
  - **Zodpovědný** — přidělený agent
  - **Autor** — zákazník, který otevřel lístek; kliknutí filtruje lístky podle autora v organizaci
  - **Stav** — Aktivní / Čekající / Uzavřeno / Spam se symboly
  - **Stav** — název sloupce Kanban (s vlastním popiskem, je-li nakonfigurován); zobrazeno pouze při aktivním modulu Kanban
  - **Aktualizováno** — datum a čas poslední odpovědi
- Vyhledávání v předmětu lístku
- Filtrování podle stavu Kanban (konfigurovatelné přes **Nastavení poštovní schránky → OrgPortal**)
- Odpověď na lístek s podporou **Příloh** (Drag & Drop, více souborů)
- **Zavřít lístek** — manažer může zavřít lístek; nová odpověď jej automaticky znovu otevře
- Změna autora lístku — přiřazení lístku jinému členu organizace
- Stránka **Nastavení org.** pro konfiguraci e-mailových oznámení
- Přístup k lístků je **přísně omezen na aktuální poštovní schránku** (organizace zkopírovaná do jiné schránky — portál 403)

### E-mailová oznámení *(volitelné)*
- Manažeři s aktivovanou možností obdrží e-mail, když člen organizace vytvoří nový lístek
- Používá poštovní driver odpovídající poštovní schránky

### Nastavení poštovní schránky

**Nastavení poštovní schránky → OrgPortal** (na poštovní schránku):

| Možnost | Popis |
|---------|-------|
| Zobrazit odznak na stránce lístku | Zapnutí/vypnutí odznáku v této schránce |
| Zobrazit odznak na kartách Kanban | Zapnutí/vypnutí odznáku v této schránce |
| Filtry stavu lístků společnosti | Vyberte sloupce Kanban zobrazené jako zaškrtávací pole na stránce lístků; vlastní popisek pro každý filtr |

---

### REST API *(volitelné, vyžaduje API a Webhooks)*

Ověření — hlavička `X-FreeScout-API-Key` nebo parametr dotazu `api_key`.

> **Interaktivní dokumentace** (ReDoc) je dostupná na stránce **Správa → API a Webhooks** (odkaz "Dokumentace OrgPortal API") nebo přímo na `/orgportal/admin/api-docs`.

| Metoda | Koncový bod | Popis |
|--------|-------------|-------|
| `GET` | `/api/organizations` | Seznam organizací (stránkování, filtr poštovní schránky) |
| `POST` | `/api/organizations` | Vytvoří organizaci |
| `GET` | `/api/organizations/{id}` | Získá organizaci se členy |
| `PUT` | `/api/organizations/{id}` | Aktualizuje organizaci |
| `DELETE` | `/api/organizations/{id}` | Smaže organizaci |
| `GET` | `/api/customers/{id}/organization` | Organizace zákazníka |
| `PUT` | `/api/customers/{id}/organization` | Nastaví/aktualizuje členství zákazníka |
| `DELETE` | `/api/customers/{id}/organization` | Odebere zákazníka z organizace |

#### Kódy odpovědí

| Kód | Význam |
|-----|---------|
| `200` | Úspěch nebo No-Op (nic se nezměnilo) |
| `201` | Zdroj vytvořen; hlavička `Resource-ID` obsahuje ID |
| `400` | Chyba ověření — detaily v `_embedded.errors` |
| `401` | Neplatný nebo chybějící klíč API |
| `404` | Zdroj nenalezen |
| `409` | Konflikt — zákazník již patří do jiné organizace |

---

#### GET /api/organizations

**Parametry dotazu**

| Parametr | Typ | Výchozí | Popis |
|----------|-----|--------|-------|
| `page` | integer | `1` | Číslo stránky |
| `pageSize` | integer | `25` | Záznamů na stránku (max 100) |
| `mailboxId` | integer | — | Filtr poštovní schránky: vrací globální organizace + vázané na tuto schránku |

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

**Tělo požadavku**

| Pole | Typ | Povinné | Popis |
|------|-----|--------|-------|
| `name` | string | ✅ | Název organizace (max 255 znaků, jedinečný) |
| `mailboxId` | integer\|null | — | ID poštovní schránky nebo `null` / vynechte pro globální organizaci |

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

**Tělo požadavku**

| Pole | Typ | Povinné | Popis |
|------|-----|--------|-------|
| `name` | string | ✅ | Nový název organizace (max 255 znaků, jedinečný) |
| `mailboxId` | integer\|null | — | Nová schránka; `null` — učinit globální; vynechte — ponechat nezměněno |

**200 OK**
```json
{"success": true, "message": "Organization updated."}
```

---

#### DELETE /api/organizations/{id}

**200 OK** *(všichni členové budou vymazáni kaskádově)*
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

Přiřadí zákazníka organizaci nebo aktualizuje jeho roli. **Jeden zákazník — jedna organizace**: Pokud zákazník již patří do *jiné* organizace, požadavek bude odmítnut s `409 Conflict`. K přesunu — nejdříve odeberte aktuální členství přes `DELETE`.

**Tělo požadavku**

| Pole | Typ | Povinné | Popis |
|------|-----|--------|-------|
| `organizationId` | integer | ✅ | ID organizace |
| `role` | string | — | `"member"` (výchozí) nebo `"manager"` |

**201 Created** *(nové členství)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(role aktualizována nebo No-Op)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(zákazník již v jiné organizaci)*
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

## Instalace

1. Zkopírujte složku `OrgPortal` do `Modules/` vašeho FreeScout
2. V admin panelu: **Správa → Moduly → OrgPortal → Aktivovat**
3. Spusťte migrace:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Vymažte mezipaměť:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Aktualizace

OrgPortal podporuje **automatické aktualizace** prostřednictvím integrovaného mechanismu aktualizace modulů FreeScout.

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

Když je dostupná nová verze, na stránce **Správa → Moduly** se zobrazí banner. Kliknutím na **Aktualizovat nyní** — FreeScout automaticky stáhne a nainstaluje nejnovější verzi.

Není vyžadováno ruční kopírování souborů.

---

## Kompatibilita modulů

| Modul | Stav |
|-------|------|
| End-User Portal ≥ 1.0.85 | Volitelný — funkce portálu pro manažery |
| API a Webhooks ≥ 1.0.80 | Volitelný — koncové body REST API |
| Kanban ≥ 1.0.23 | Volitelný — odznak, filtr, sloupec "Stav" v lístcích společnosti |
| Vlastní pole | Kompatibilní |
| Workflows | Kompatibilní |
| Značky | Kompatibilní |

---

## Konfigurace

### Globální (**Správa → Nastavení OrgPortal**)

| Možnost | Výchozí |
|---------|---------|
| Zobrazit odznak na stránce lístku | ✅ |
| Zobrazit odznak na kartách Kanban | ✅ |

### Na poštovní schránku (**Nastavení poštovní schránky → OrgPortal**)

Přepíše globální hodnoty pro konkrétní schránku.

| Možnost | Popis |
|---------|-------|
| Zobrazit odznak na stránce lístku | Odznak v seznamu konverzací a na stránce lístku |
| Zobrazit odznak na kartách Kanban | Odznak na kartách Kanban |
| Filtry stavu lístků společnosti | Sloupce Kanban jako zaškrtávací pole na stránce Lístky společnosti; každý filtr má vlastní popisek viditelný pro uživatele portálu |

---

## Překlady

Podporované jazyky: **English** (`en`), **Українська** (`uk`), **Română** (`ro`), **Georgian** (`ka`), **Deutsch** (`de`), **Français** (`fr`), **Español** (`es`), **Italiano** (`it`), **Čeština** (`cs`), **Slovenčina** (`sk`), **Polski** (`pl`), **Русский** (`ru`), **Nederlands** (`nl`), **Norsk** (`no`), **Dansk** (`da`), **Svenska** (`sv`), **Suomi** (`fi`), **Português BR** (`pt-BR`), **Português PT** (`pt-PT`), **中文 (简体)** (`zh-CN`).

Soubory: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### Integrace EUPSWLANG

Modul správně funguje s [EUP Switch Language](https://freescout.net/module/eup-sw-lang/): jazyk vybraný v portálu platí i pro řetězce OrgPortal.

Aby se jazyk objevil v seznamu EUPSWLANG, musí existovat odpovídající soubor `Modules/EndUserPortal/Resources/lang/{locale}.json`. Soubory pro **Română** (`ro`) jsou zahrnuty v balíčku; **Georgian** (`ka`) je podporován pouze v oblasti správy (žádná systémová podpora v jádru FreeScout).

> **Technický detail:** Middleware `ReapplyEupLocale` (registrován jako poslední ve skupině tras portálu) obnovuje místní nastavení poté, co by jej middleware `Localize` FreeScout jinak resetoval na výchozí systémový jazyk.

---

## Licence

[MIT](../LICENSE) — © 2026 ASTIN-UA
