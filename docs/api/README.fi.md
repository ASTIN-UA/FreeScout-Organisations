# OrgPortal REST API

[← Takaisin READMEen](../README.fi.md)

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

*Valinnainen — vaatii [API:n ja webhookit](https://freescout.net/module/api-webhooks/) -moduulin.*

Todentaminen — `X-FreeScout-API-Key`-otsikko tai `api_key`-kyselyparametri.

> **Interaktiivinen dokumentaatio** (ReDoc) on saatavilla sivulla **Hallinta → API ja webhookit** (linkki "OrgPortal API-dokumentaatio") tai suoraan osoitteessa `/orgportal/admin/api-docs`.

## Päätepisteet

| Menetelmä | Päätepiste | Kuvaus |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Luettele organisaatiot (sivutus, postilaatikon suodatin) |
| `POST` | `/api/organizations` | Luo organisaatio |
| `GET` | `/api/organizations/{id}` | Hae organisaatio jäsenineen ja yksiköineen |
| `PUT` | `/api/organizations/{id}` | Päivitä organisaatio |
| `DELETE` | `/api/organizations/{id}` | Poista organisaatio |
| `GET` | `/api/organizations/{id}/units` | Luettele rakenneyksiköt |
| `POST` | `/api/organizations/{id}/units` | Luo rakennetuokikko |
| `PUT` | `/api/units/{unitId}` | Nimeä yksikkö uudelleen |
| `DELETE` | `/api/units/{unitId}` | Poista yksikkö (jäsenet määrittämätöntä, yksikkö- johtajat alennetaan) |
| `GET` | `/api/customers/{id}/organization` | Asiakkaan organisaatiojäsenyys |
| `PUT` | `/api/customers/{id}/organization` | Aseta/päivitä asiakkaan jäsenyys |
| `DELETE` | `/api/customers/{id}/organization` | Poista asiakas organisaatiosta |

## Vastauksen koodit

| Koodi | Merkitys |
|------|---------|
| `200` | Onnistui tai no-op (mitään ei muuttunut) |
| `201` | Resurssi luotu; `Resource-ID`-otsikko sisältää ID:n |
| `400` | Validointivirhe — tiedot osoitteessa `_embedded.errors` |
| `401` | Virheellinen tai puuttuva API-avain |
| `404` | Resurssia ei löydy |
| `409` | Ristiriita — asiakas kuuluu jo toiseen organisaatioon |

---

## Organisaatiot

### GET /api/organizations

**Kyselyparametrit**

| Parametri | Tyyppi | Oletus | Kuvaus |
|-----------|------|:-------:|-------------|
| `page` | kokonaisluku | `1` | Sivunumero |
| `pageSize` | kokonaisluku | `25` | Tietueita sivua kohti (enintään 100) |
| `mailboxId` | kokonaisluku | — | Postilaatikon suodatin: palauttaa globaalit organisaatiot + niihin sidotut |

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

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|-------|------|:--------:|-------------|
| `name` | merkkijono | ✅ | Organisaation nimi (enintään 255 merkkiä, uniikki) |
| `mailboxId` | kokonaisluku\|null | — | Postilaatikon ID tai `null` / jätä pois globaalin organisaation osalta |

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
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Palauttaa organisaation sisäänrakennettujen **jäsenten** ja **yksiköiden** kanssa.

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

**Jäsenen kentät**

| Kenttä | Tyyppi | Kuvaus |
|-------|------|-------------|
| `unitId` | kokonaisluku\|null | Rakennetuokikko, johon jäsen kuuluu, tai `null` koko organisaatiolle |
| `role` | merkkijono | `member` tai `manager` |
| `canManageOrg` | boolean | Voiko tämä johtaja ylennetä muita globaaliksi johtajaksi portaalista |
| `isActive` | boolean | Aktiivinen jäsenyys; passiiviset jäsenet eivät saa lippujen määrittelyä tai ilmoituksia |
| `notifyOnNewTicket` | boolean | Perintö per-jäsenen uuden lipun ilmoituslippu |

---

### PUT /api/organizations/{id}

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|-------|------|:--------:|-------------|
| `name` | merkkijono | ✅ | Uusi organisaation nimi (enintään 255 merkkiä, uniikki) |
| `mailboxId` | kokonaisluku\|null | — | Uusi postilaatikko; `null` — tee globaali; jätä pois — jätä muuttumattomaksi |

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

Kun mitään ei muutu, vastausviesti on `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(kaikki jäsenet poistetaan kaskadissa)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Rakenneyksiköt

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
| `name` | merkkijono | ✅ | Yksikön nimi (uniikki organisaation sisällä) |

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
| `name` | merkkijono | ✅ | Uusi yksikön nimi (uniikki organisaation sisällä) |

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

Poistaa yksikön. Tähän yksikköön rajoitetut johtajat alennetaan `member`-tasoon; kaikki yksikön jäsenet määritetään uudelleen (heidän `unitId` muuttuu `null`-arvoksi).

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

Määritä asiakas organisaatioon tai päivitä heidän jäsenyyttään. **Yksi aktiivinen jäsenyys asiakasta kohti**: jos asiakas kuuluu jo *aktiivisesti* *toiseen* organisaatioon, pyyntö hylätään `409 Ristiriita`-virheellä. Siirtämiseksi — poista ensin nykyinen jäsenyys `DELETE`-metodilla.

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|-------|------|:--------:|-------------|
| `organizationId` | kokonaisluku | ✅ | Organisaation ID |
| `role` | merkkijono | — | `"member"` (oletus) tai `"manager"` |
| `unitId` | kokonaisluku\|null | — | Rakennetuokikko (on kuuluttava kohde-organisaatioon), tai `null` koko organisaatiolle |
| `canManageOrg` | boolean | — | Myönnä tälle johtajalle oikeus ylennetä muita globaaliksi johtajaksi (oletus `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(uusi jäsenyys)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(jäsenyys päivitetty)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(asiakas jo aktiivinen toisessa organisaatiossa)*
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
