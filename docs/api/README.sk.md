# OrgPortal REST API

[← Back to README](../../README.md)

🌐 **Language:**
[English](README.md) ·
[Українська](README.uk.md) ·
[Deutsch](README.de.md) ·
[Français](README.fr.md) ·
[Español](README.es.md) ·
[Italiano](README.it.md) ·
[Polski](README.pl.md) ·
[Čeština](README.cs.md) ·
[Slovenčina](README.sk.md) ·
[Nederlands](README.nl.md) ·
[Norsk](README.no.md) ·
[Dansk](README.da.md) ·
[Svenska](README.sv.md) ·
[Suomi](README.fi.md) ·
[Português (BR)](README.pt-BR.md) ·
[Português (PT)](README.pt-PT.md) ·
[Română](README.ro.md) ·
[中文 (简体)](README.zh-CN.md)

---

*Voliteľné — vyžaduje modul [API and Webhooks](https://freescout.net/module/api-webhooks/).*

Autentifikácia — hlavička `X-FreeScout-API-Key` alebo parameter dotazu `api_key`.

> **Interaktívna dokumentácia** (ReDoc) je dostupná na stránke **Spravovať → API a Webhooks** (odkaz "OrgPortal API Docs") alebo priamo na `/orgportal/admin/api-docs`.

## Koncové body

| Metóda | Koncový bod | Popis |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Zoznam organizácií (stránkovanie, filter poštovnej schránky) |
| `POST` | `/api/organizations` | Vytvoriť organizáciu |
| `GET` | `/api/organizations/{id}` | Získať organizáciu s členmi a jednotkami |
| `PUT` | `/api/organizations/{id}` | Aktualizovať organizáciu (názov, farba, poštovná schránka, isActive) |
| `DELETE` | `/api/organizations/{id}` | Odstrániť organizáciu |
| `GET` | `/api/organizations/{id}/members` | Zoznam členov organizácie |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Získať jedného člena |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Aktualizovať člena (rola, jednotka, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Odstrániť člena |
| `GET` | `/api/organizations/{id}/tags` | Zoznam viazaní značiek (vyžaduje modul Tags) |
| `PUT` | `/api/organizations/{id}/tags` | Nahradiť všetky viazania značiek (vyžaduje modul Tags) |
| `GET` | `/api/organizations/{id}/units` | Zoznam štrukturálnych jednotiek |
| `POST` | `/api/organizations/{id}/units` | Vytvoriť štrukturálnu jednotku |
| `PUT` | `/api/units/{unitId}` | Premenovať jednotku |
| `DELETE` | `/api/units/{unitId}` | Odstrániť jednotku (členovia nepriradení, vedúci jednotky degradovaní) |
| `GET` | `/api/customers/{id}/organization` | Členstvo zákazníka v organizácii |
| `PUT` | `/api/customers/{id}/organization` | Nastaviť/aktualizovať členstvo zákazníka |
| `DELETE` | `/api/customers/{id}/organization` | Odstrániť zákazníka z organizácie |

## Kódy odpovedí

| Kód | Význam |
|------|---------|
| `200` | Úspech |
| `201` | Zdroj vytvorený; hlavička `Resource-ID` obsahuje ID |
| `400` | Chyba validácie — podrobnosti v `_embedded.errors` |
| `401` | Neplatný alebo chýbajúci kľúč API |
| `404` | Zdroj nenájdený |
| `409` | Konflikt — zákazník už má aktívne členstvo v inej organizácii |
| `503` | Požadovaný modul (napr. Tags) nie je aktívny |

---

## Organizácie

### GET /api/organizations

**Parametre dotazu**

| Parameter | Typ | Predvolené | Popis |
|-----------|------|:-------:|-------------|
| `page` | celé číslo | `1` | Číslo stránky |
| `pageSize` | celé číslo | `25` | Záznamy na stránku (max 100) |
| `mailboxId` | celé číslo | — | Filter poštovnej schránky: vrátí globálne organizácie + tie viazané na túto schránku |

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
        "color": "#4a90d9",
        "isActive": true,
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

### POST /api/organizations

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|-------|------|:--------:|-------------|
| `name` | reťazec | ✅ | Názov organizácie (max 255 znakov, jedinečný) |
| `mailboxId` | celé číslo\|null | — | ID poštovnej schránky alebo `null` / vynechať pre globálnu organizáciu |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(hlavička `Resource-ID: 1`)*
```json
{
  "id": 1,
  "name": "Acme Corp",
  "color": null,
  "isActive": true,
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Vrátí organizáciu s vloženými **členmi** a **jednotkami**.

**200 OK**
```json
{
  "id": 1,
  "name": "Acme Corp",
  "color": "#4a90d9",
  "isActive": true,
  "mailboxId": null,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00",
  "_embedded": {
    "members": [
      {
        "id": 5,
        "organizationId": 1,
        "unitId": 2,
        "customerId": 42,
        "role": "manager",
        "canManageOrg": false,
        "isActive": true,
        "notifyOnNewTicket": true,
        "createdAt": "2026-06-01T10:05:00+00:00",
        "updatedAt": "2026-06-01T10:05:00+00:00"
      }
    ],
    "units": [
      {
        "id": 2,
        "organizationId": 1,
        "name": "Sales department",
        "createdAt": "2026-06-01T10:02:00+00:00",
        "updatedAt": "2026-06-01T10:02:00+00:00"
      }
    ]
  }
}
```

**Polia člena**

| Pole | Typ | Popis |
|-------|------|-------------|
| `unitId` | celé číslo\|null | Štrukturálna jednotka, do ktorej člen patrí, alebo `null` pre celú organizáciu |
| `role` | reťazec | `member` alebo `manager` |
| `canManageOrg` | logická hodnota | Či môže tento vedúci podporovať ostatných na globálneho vedúceho z portálu |
| `isActive` | logická hodnota | Aktívne členstvo; neaktívni členovia nebudú dostávať priradenia lístkov ani upozornenia |
| `notifyOnNewTicket` | logická hodnota | Príznak upozornenia na nový lístok na člena |

---

### PUT /api/organizations/{id}

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|-------|------|:--------:|-------------|
| `name` | reťazec | ✅ | Nový názov organizácie (max 255 znakov, jedinečný) |
| `color` | reťazec\|null | — | Farba odznáku ako hexadecimálny (`"#ff0000"`), `null` na resetovanie na predvolené sivé; vynechať na zachovanie aktuálneho |
| `mailboxId` | celé číslo\|null | — | Nová poštovná schránka; `null` — urobiť globálne; vynechať — ponechať bez zmien |
| `isActive` | logická hodnota | — | `false` na deaktiváciu organizácie; vynechať na zachovanie aktuálneho |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corporation", "color": "#4a90d9", "isActive": true}'
```

**200 OK**
```json
{"success": true, "message": "Organization updated."}
```

---

### DELETE /api/organizations/{id}

**200 OK** *(všetci členovia sú kaskádovo odstránení)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Členovia organizácie

### GET /api/organizations/{id}/members

Vrátí zoznam všetkých záznamov členov pre organizáciu.

**200 OK**
```json
{
  "_embedded": {
    "members": [
      {
        "id": 5,
        "organizationId": 1,
        "unitId": 2,
        "customerId": 42,
        "role": "manager",
        "canManageOrg": false,
        "isActive": true,
        "notifyOnNewTicket": true,
        "createdAt": "2026-06-01T10:05:00+00:00",
        "updatedAt": "2026-06-01T10:05:00+00:00"
      }
    ]
  }
}
```

---

### GET /api/organizations/{id}/members/{memberId}

Vrátí jediný záznam člena.

**200 OK**
```json
{
  "id": 5,
  "organizationId": 1,
  "unitId": 2,
  "customerId": 42,
  "role": "manager",
  "canManageOrg": false,
  "isActive": true,
  "notifyOnNewTicket": true,
  "createdAt": "2026-06-01T10:05:00+00:00",
  "updatedAt": "2026-06-01T10:05:00+00:00"
}
```

---

### PUT /api/organizations/{id}/members/{memberId}

Aktualizujte rolu člena, priradenie jednotky, príznak canManageOrg alebo aktívny stav. Aktualizujú sa iba polia prítomné v tele (čiastková aktualizácia).

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|-------|------|:--------:|-------------|
| `role` | reťazec | — | `"member"` alebo `"manager"` |
| `unitId` | celé číslo\|null | — | Štrukturálna jednotka (musí patriť do tejto organizácie), alebo `null` na nepridelenie |
| `canManageOrg` | logická hodnota | — | Prideliť práva globálneho vedúceho v portáli |
| `isActive` | logická hodnota | — | `false` na deaktiváciu bez odstránenia |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1/members/5" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"role": "manager", "unitId": 2, "canManageOrg": true, "isActive": true}'
```

**200 OK**
```json
{"success": true, "message": "Member updated."}
```

---

### DELETE /api/organizations/{id}/members/{memberId}

Odstrániť člena z organizácie.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## Značky organizácie

> Vyžaduje, aby bol modul [Tags](https://freescout.net/module/tags/) aktívny. Vrátí `503` ak modul nie je nainštalovaný.

### GET /api/organizations/{id}/tags

Vrátí všetky viazania značiek pre organizáciu. Každé viazanie voliteľne ohraničuje značku na špecifickú jednotku.

**200 OK**
```json
{
  "_embedded": {
    "tags": [
      { "id": 1, "organizationId": 1, "tagId": 5, "unitId": null },
      { "id": 2, "organizationId": 1, "tagId": 8, "unitId": 2 }
    ]
  }
}
```

---

### PUT /api/organizations/{id}/tags

**Úplne nahradenie** — nahrádza všetky existujúce viazania značiek pre túto organizáciu poskytnutým zoznamom. Odošlite prázdne pole `[]` na odstránenie všetkých viazaní.

**Telo požiadavky** — pole JSON objektov viazaní značiek:

| Pole | Typ | Povinné | Popis |
|-------|------|:--------:|-------------|
| `tagId` | celé číslo | ✅ | ID značky FreeScout |
| `unitId` | celé číslo\|null | — | Ohraničiť značku na špecifickú jednotku, alebo vynechať/`null` pre celú organizáciu |

```bash
curl -X PUT "https://your-freescout.com/api/organizations/1/tags" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '[{"tagId": 5}, {"tagId": 8, "unitId": 2}]'
```

**200 OK**
```json
{"success": true, "message": "Tags updated."}
```

---

## Štrukturálne jednotky

### GET /api/organizations/{id}/units

**200 OK**
```json
{
  "_embedded": {
    "units": [
      {
        "id": 2,
        "organizationId": 1,
        "name": "Sales department",
        "createdAt": "2026-06-01T10:02:00+00:00",
        "updatedAt": "2026-06-01T10:02:00+00:00"
      }
    ]
  }
}
```

---

### POST /api/organizations/{id}/units

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|-------|------|:--------:|-------------|
| `name` | reťazec | ✅ | Názov jednotky (jedinečný v rámci organizácie) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(hlavička `Resource-ID: 2`)*
```json
{
  "id": 2,
  "organizationId": 1,
  "name": "Sales department",
  "createdAt": "2026-06-01T10:02:00+00:00",
  "updatedAt": "2026-06-01T10:02:00+00:00"
}
```

---

### PUT /api/units/{unitId}

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|-------|------|:--------:|-------------|
| `name` | reťazec | ✅ | Nový názov jednotky (jedinečný v rámci organizácie) |

```bash
curl -X PUT "https://your-freescout.com/api/units/2" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales & Marketing"}'
```

**200 OK**
```json
{"success": true, "message": "Unit updated."}
```

---

### DELETE /api/units/{unitId}

Odstrániť jednotku. Vedúci ohraničení na túto jednotku sú degradovaní na `member`; všetci členovia jednotky sú nepriradení (ich `unitId` sa stane `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Členstvo zákazníka

### GET /api/customers/{id}/organization

**200 OK**
```json
{
  "customerId": 42,
  "organizationId": 1,
  "organizationName": "Acme Corp",
  "unitId": 2,
  "unitName": "Sales department",
  "role": "manager",
  "canManageOrg": false,
  "isActive": true,
  "notifyOnNewTicket": true
}
```

---

### PUT /api/customers/{id}/organization

Priradiť zákazníka k organizácii alebo aktualizovať jeho členstvo. **Jedno aktívne členstvo na zákazníka**: ak má zákazník už *aktívne* členstvo v *inej* organizácii, požiadavka je odmietnutá s `409 Conflict`. Na prenos — najskôr deaktivujte alebo odstráňte aktuálne členstvo prostredníctvom `DELETE`.

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|-------|------|:--------:|-------------|
| `organizationId` | celé číslo | ✅ | ID organizácie |
| `role` | reťazec | — | `"member"` (predvolené) alebo `"manager"` |
| `unitId` | celé číslo\|null | — | Štrukturálna jednotka (musí patriť do cieľovej organizácie), alebo `null` pre celú organizáciu |
| `canManageOrg` | logická hodnota | — | Prideliť tomuto vedúcemu právo na podporu ostatných na globálneho vedúceho (predvolené `false`) |
| `isActive` | logická hodnota | — | `false` na vytvorenie/aktualizáciu ako neaktívny (predvolené `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(nové členstvo)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(členstvo aktualizované)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(zákazník je už aktívny v inej organizácii)*
```json
{
  "message": "Customer already has an active membership in another organization.",
  "errorCode": "CUSTOMER_ALREADY_HAS_AN_ACTIVE_MEMBERSHIP_IN_ANOTHER_ORGANIZATION.",
  "_embedded": {
    "errors": [
      {
        "path": "organizationId",
        "message": "Customer is an active member of organization #3. Deactivate or remove it first via DELETE /api/customers/42/organization.",
        "source": "JSON"
      }
    ]
  }
}
```

---

### DELETE /api/customers/{id}/organization

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```
