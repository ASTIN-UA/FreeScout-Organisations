# OrgPortal REST API

[← Takaisin README-tiedostoon](../../README.md)

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

*Valinnainen — vaatii [API ja Webhook-moduulin](https://freescout.net/module/api-webhooks/).*

Todentaminen — `X-FreeScout-API-Key`-otsikko tai `api_key`-kyselyparametri.

> **Interaktiivinen dokumentaatio** (ReDoc) on saatavilla **Hallinta → API & Webhook-moduuli** -sivulla (linkki "OrgPortal API Docs") tai suoraan osoitteessa `/orgportal/admin/api-docs`.

## Päätepisteistö

| Metodi | Pääteiste | Kuvaus |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Listaa organisaatiot (sivutus, postilaatikon suodatin) |
| `POST` | `/api/organizations` | Luo organisaatio |
| `GET` | `/api/organizations/{id}` | Hae organisaatio jäsenineen ja yksiköineen |
| `PUT` | `/api/organizations/{id}` | Päivitä organisaatio (nimi, väri, postilaatikko, isActive) |
| `DELETE` | `/api/organizations/{id}` | Poista organisaatio |
| `GET` | `/api/organizations/{id}/members` | Listaa organisaation jäsenet |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Hae yksittäinen jäsen |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Päivitä jäsenen tiedot (rooli, yksikkö, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Poista jäsen |
| `GET` | `/api/organizations/{id}/tags` | Listaa tunnisteen sitoutuneisuudet (vaatii Tags-moduulin) |
| `PUT` | `/api/organizations/{id}/tags` | Korvaa kaikki tunnisteen sitoutuneisuudet (vaatii Tags-moduulin) |
| `GET` | `/api/organizations/{id}/units` | Listaa rakenteelliset yksiköt |
| `POST` | `/api/organizations/{id}/units` | Luo rakenteellinen yksikkö |
| `PUT` | `/api/units/{unitId}` | Nimeä yksikkö uudelleen |
| `DELETE` | `/api/units/{unitId}` | Poista yksikkö (jäsenet poistetaan, yksikön johtajat alennetaan) |
| `GET` | `/api/customers/{id}/organization` | Asiakkaan organisaatiojäsenyys |
| `PUT` | `/api/customers/{id}/organization` | Aseta/päivitä asiakkaan jäsenyys |
| `DELETE` | `/api/customers/{id}/organization` | Poista asiakas organisaatiosta |

## Vastauskodit

| Koodi | Merkitys |
|------|---------|
| `200` | Onnistui |
| `201` | Resurssi luotu; `Resource-ID`-otsikko sisältää tunnisteen |
| `400` | Vahvistusvirhe — yksityiskohdat `_embedded.errors`-osassa |
| `401` | Virheellinen tai puuttuva API-avain |
| `404` | Resurssia ei löytynyt |
| `409` | Ristiriita — asiakas on jo aktiivinen jäsen toisessa organisaatiossa |
| `503` | Vaadittu moduuli (esim. Tags) ei ole aktiivinen |

---

## Organisaatiot

### GET /api/organizations

**Kyselyparametrit**

| Parametri | Tyyppi | Oletus | Kuvaus |
|-----------|------|:-------:|-------------|
| `page` | kokonaisluku | `1` | Sivunumero |
| `pageSize` | kokonaisluku | `25` | Tietueet sivua kohden (enimmäismäärä 100) |
| `mailboxId` | kokonaisluku | — | Postilaatikon suodatin: palauttaa globaalit organisaatiot + tähän postilaatikkoon sidotut |

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

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|-------|------|:--------:|-------------|
| `name` | merkkijono | ✅ | Organisaation nimi (enintään 255 merkkiä, yksilöllinen) |
| `mailboxId` | kokonaisluku\|null | — | Postilaatikon tunnus tai `null` / jätä pois globaalille organisaatiolle |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(otsikko `Resource-ID: 1`)*
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

Palauttaa organisaation sen sisältyvien **jäsenten** ja **yksiköiden** kanssa.

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

**Jäsenen kentät**

| Kenttä | Tyyppi | Kuvaus |
|-------|------|-------------|
| `unitId` | kokonaisluku\|null | Rakenteellinen yksikkö, johon jäsen kuuluu, tai `null` koko organisaatiolle |
| `role` | merkkijono | `member` tai `manager` |
| `canManageOrg` | looginen | Oikeuttaako tämä johtaja muita globaaleiksi johtajiksi portaalissa |
| `isActive` | looginen | Aktiivinen jäsenyys; passiiviset jäsenet eivät saa lippujen tehtäviä tai ilmoituksia |
| `notifyOnNewTicket` | looginen | Jäsenkohtainen uuden lipun ilmoituslippu |

---

### PUT /api/organizations/{id}

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|-------|------|:--------:|-------------|
| `name` | merkkijono | ✅ | Organisaation uusi nimi (enintään 255 merkkiä, yksilöllinen) |
| `color` | merkkijono\|null | — | Merkin väri heksadesimaalimuodossa (`"#ff0000"`), `null` nollaa oletusharmaaksi; jätä pois säilyttääksesi nykyisen |
| `mailboxId` | kokonaisluku\|null | — | Uusi postilaatikko; `null` — tee globaaliksi; jätä pois — jätä muuttumattomaksi |
| `isActive` | looginen | — | `false` organisaation poistamiseksi käytöstä; jätä pois säilyttääksesi nykyisen |

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

**200 OK** *(kaikki jäsenet poistetaan kaskadilla)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Organisaation jäsenet

### GET /api/organizations/{id}/members

Palauttaa listan kaikista organisaation jäsentietueista.

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

Palauttaa yksittäisen jäsentietueen.

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

Päivitä jäsenen rooli, yksikön sijoittelu, canManageOrg-merkki tai aktiivinen tila. Vain rungossa läsnä olevat kentät päivitetään (osittainen päivitys).

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|-------|------|:--------:|-------------|
| `role` | merkkijono | — | `"member"` tai `"manager"` |
| `unitId` | kokonaisluku\|null | — | Rakenteellinen yksikkö (tulee kuulua tähän organisaatioon), tai `null` poistaaksesi |
| `canManageOrg` | looginen | — | Anna globaalin johtajan oikeudet portaalissa |
| `isActive` | looginen | — | `false` poistaaksesi käytöstä poistamatta |

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

Poista jäsen organisaatiosta.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## Organisaation tunnisteet

> Vaatii [Tags](https://freescout.net/module/tags/) -moduulin olevan aktiivinen. Palauttaa `503`, jos moduulia ei ole asennettu.

### GET /api/organizations/{id}/tags

Palauttaa kaikki organisaation tunnisteen sitoutuneisuudet. Jokainen sitoutuneisuus voi valinnaisesti rajoittaa tunnisteen tiettyyn yksikköön.

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

**Täysi korvaaminen** — korvaa kaikki olemassa olevat tunnisteen sitoutuneisuudet tälle organisaatiolle toimitetulla luettelolla. Lähetä tyhjä matriisi `[]` poistaaksesi kaikki sitoutuneisuudet.

**Pyynnön runko** — JSON-matriisi tunnisteen sitoutuneisuuden objekteista:

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|-------|------|:--------:|-------------|
| `tagId` | kokonaisluku | ✅ | FreeScout-tunnisteen tunnus |
| `unitId` | kokonaisluku\|null | — | Rajoita tunniste tiettyyn yksikköön, tai jätä pois/`null` organisaation laajuiselle |

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

## Rakenteelliset yksiköt

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

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|-------|------|:--------:|-------------|
| `name` | merkkijono | ✅ | Yksikön nimi (yksilöllinen organisaatiossa) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(otsikko `Resource-ID: 2`)*
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

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|-------|------|:--------:|-------------|
| `name` | merkkijono | ✅ | Uusi yksikön nimi (yksilöllinen organisaatiossa) |

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

Poista yksikkö. Tähän yksikköön sidotut johtajat alennetaan `memberiksi`; kaikki yksikön jäsenet poistetaan (heidän `unitId` tulee `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Asiakkaan jäsenyys

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

Määritä asiakas organisaatioon tai päivitä heidän jäsenyyttään. **Yksi aktiivinen jäsenyys per asiakas**: jos asiakkaalla on jo *aktiivinen* jäsenyys *toisessa* organisaatiossa, pyyntö hylätään `409 Conflict`-virheellä. Siirron suorittamiseksi — poista tai passivoi ensin nykyinen jäsenyys `DELETE`-pyynnöllä.

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|-------|------|:--------:|-------------|
| `organizationId` | kokonaisluku | ✅ | Organisaation tunnus |
| `role` | merkkijono | — | `"member"` (oletus) tai `"manager"` |
| `unitId` | kokonaisluku\|null | — | Rakenteellinen yksikkö (tulee kuulua kohdeorganisaatioon), tai `null` koko organisaatiolle |
| `canManageOrg` | looginen | — | Anna tälle johtajalle oikeus edistää muita globaaliksi johtajiksi (oletus `false`) |
| `isActive` | looginen | — | `false` luodaksesi/päivittääksesi passiivisena (oletus `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(uusi jäsenyys)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(jäsenyys päivitettynä)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(asiakas on jo aktiivinen toisessa organisaatiossa)*
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
