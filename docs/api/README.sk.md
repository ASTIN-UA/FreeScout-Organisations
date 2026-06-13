# OrgPortal REST API

[← Späť na README](../README.sk.md)

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

*Voliteľné — vyžaduje modul [API a Webhooks](https://freescout.net/module/api-webhooks/).*

Overenie — hlavička `X-FreeScout-API-Key` alebo parameter dotazu `api_key`.

> **Interaktívna dokumentácia** (ReDoc) je dostupná na stránke **Správa → API a Webhooks** (odkaz "Dokumentácia OrgPortal API") alebo priamo na `/orgportal/admin/api-docs`.

## Koncové body

| Metóda | Koncový bod | Popis |
|--------|-------------|-------|
| `GET` | `/api/organizations` | Zoznam organizácií (stránkovanie, filter poštovej schránky) |
| `POST` | `/api/organizations` | Vytvorí organizáciu |
| `GET` | `/api/organizations/{id}` | Získa organizáciu s členmi a jednotkami |
| `PUT` | `/api/organizations/{id}` | Aktualizuje organizáciu |
| `DELETE` | `/api/organizations/{id}` | Zmaže organizáciu |
| `GET` | `/api/organizations/{id}/units` | Zoznam štrukturálnych jednotiek |
| `POST` | `/api/organizations/{id}/units` | Vytvorí štrukturálnu jednotku |
| `PUT` | `/api/units/{unitId}` | Premenovanie jednotky |
| `DELETE` | `/api/units/{unitId}` | Zmazanie jednotky (členovia nepriradení, manažéri jednotky degradovaní) |
| `GET` | `/api/customers/{id}/organization` | Členstvo zákazníka v organizácii |
| `PUT` | `/api/customers/{id}/organization` | Nastaví/aktualizuje členstvo zákazníka |
| `DELETE` | `/api/customers/{id}/organization` | Odstráni zákazníka z organizácie |

## Kódy odpovedí

| Kód | Význam |
|-----|--------|
| `200` | Úspech alebo bez zmeny (nič sa nezmenilo) |
| `201` | Zdroj vytvorený; hlavička `Resource-ID` obsahuje ID |
| `400` | Chyba validácie — detaily v `_embedded.errors` |
| `401` | Neplatný alebo chýbajúci kľúč API |
| `404` | Zdroj nenájdený |
| `409` | Konflikt — zákazník už má aktívne členstvo v inej organizácii |

---

## Organizácie

### GET /api/organizations

**Parametre dotazu**

| Parameter | Typ | Predvolené | Popis |
|-----------|-----|:-------:|-------|
| `page` | integer | `1` | Číslo stránky |
| `pageSize` | integer | `25` | Záznamov na stranu (max 100) |
| `mailboxId` | integer | — | Filter poštovej schránky: vracia globálne organizácie + viazané na túto schránku |

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

### POST /api/organizations

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|------|-----|:--------:|-------|
| `name` | string | ✅ | Názov organizácie (max 255 znakov, jedinečný) |
| `mailboxId` | integer\|null | — | ID poštovej schránky alebo `null` / vynechajte pre globálnu organizáciu |

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
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Vracia organizáciu s vloženými **členmi** a **jednotkami**.

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
|------|-----|-------|
| `unitId` | integer\|null | Štrukturálna jednotka, do ktorej člen patrí, alebo `null` pre celú organizáciu |
| `role` | string | `member` alebo `manager` |
| `canManageOrg` | boolean | Či môže tento manažér poveriť iných globálnym manažérom z portálu |
| `isActive` | boolean | Aktívne členstvo; neaktívni členovia neprijímajú pridelenie lístkov ani upozornenia |
| `notifyOnNewTicket` | boolean | Staršia vlajka upozornenia na nový lístek pre jednotlivého člena |

---

### PUT /api/organizations/{id}

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|------|-----|:--------:|-------|
| `name` | string | ✅ | Nový názov organizácie (max 255 znakov, jedinečný) |
| `mailboxId` | integer\|null | — | Nová schránka; `null` — urobiť globálnou; vynechajte — ponechať nezmenené |

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

Keď sa nič nezmení, správa odpovedí je `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(všetci členovia budú zmazaní kaskádovo)*
```json
{"success": true, "message": "Organization deleted."}
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
|------|-----|:--------:|-------|
| `name` | string | ✅ | Názov jednotky (jedinečný v rámci organizácie) |

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
|------|-----|:--------:|-------|
| `name` | string | ✅ | Nový názov jednotky (jedinečný v rámci organizácie) |

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

Zmaže jednotku. Manažéri ohraničení na túto jednotku sú degradovaní na `member`; všetci členovia jednotky sú nepriradení (ich `unitId` sa zmení na `null`).

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

Priradí zákazníka organizácii alebo aktualizuje jeho členstvo. **Jedno aktívne členstvo na zákazníka**: Ak zákazník už má *aktívne* členstvo v *inej* organizácii, požiadavka bude odmietnutá s `409 Conflict`. Aby sa pridelil — najskôr deaktivujte alebo odstráňte aktuálne členstvo cez `DELETE`.

**Telo požiadavky**

| Pole | Typ | Povinné | Popis |
|------|-----|:--------:|-------|
| `organizationId` | integer | ✅ | ID organizácie |
| `role` | string | — | `"member"` (predvolené) alebo `"manager"` |
| `unitId` | integer\|null | — | Štrukturálna jednotka (musí patriť cieľovej organizácii), alebo `null` pre celú organizáciu |
| `canManageOrg` | boolean | — | Udeliť tomuto manažérovi právo poveriť iných globálnym manažérom (predvolené `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(nové členstvo)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(členstvo aktualizované)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(zákazník už aktívny v inej organizácii)*
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
