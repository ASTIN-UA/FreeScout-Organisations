# OrgPortal — Organisaatioportaali FreeScout-sovellukselle

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

FreeScout-moduuli, joka lisää **Organisaatiot**-käsitteen (yritykset/tiimit) asiakkaisiin, laajentaa End-User Portalia johtajille ja näyttää organisaatiomallin avulla niitä lippuissa ja Kanban-korteissa.

**Vähimmäis-FreeScout-versio:** 1.8.147  
**Riippuvuudet:** ei vaadittuja  
**Valinnainen:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API ja webhookit](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Kieli:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Ominaisuudet

### Organisaatioiden hallinta (admin)
- **Hallinta → Organisaatiot** — täydellinen CRUD: luo, muokkaa, poista organisaatioita
- **Postilaatikon sitominen** — organisaatio voi olla **yleinen** (näkyvä kaikissa postilaatikoissa) tai **sidottu tiettyyn postilaatikkoon**; vastaava tunniste näytetään organisaatioiden luettelossa
- Asiakkaiden määritys organisaatioihin roolin valinnalla: `jäsen` tai `johtaja`
- **Muuta jäsenen roolia** suoraan taulukossa (poistamatta ja lisäämättä uudelleen)
- Asiakashaku automaattisen täydennyksen kanssa nimen tai sähköpostin perusteella; jo organisaatiossa olevat asiakkaat on jätetty pois tuloksista
- Jäsenen sähköposti näytetään nimen alla jäsenen taulukossa
- Yksi asiakas — yksi organisaatio (pakotettu tietokanta- ja API-tasolla)
- **Merkin väri** — visuaalinen paletissa 12 väriä organisaation muokkauslomakkeessa; oletus on harmaa

### Käyttäjäoikeudet
- Uusi oikeus **"Salli organisaatioiden hallinta"** — ei-pääkäyttäjät, joilla on tämä oikeus, saavat pääsyn luettelon, luonti- ja muokkaa-organisaatiosivuille
- Organisaatioiden poistaminen jää yksinomaan pääkäyttäjille

### Asiakaskortti
- **Organisaatio**-kenttä asiakkaan muokkauslomakkeessa — valitse organisaatio ja rooli
- **Organisaation liput**-painike — avaa hakua kaikkiin organisaation lipuille

### Organisaatiomallia lipuissa
- Näytetään lippusivun otsikon alapuolella ja ennen nimeä keskustelun luettelossa
- Voidaan napsauttaa — avaa haun kaikkiin tämän organisaation lipuille
- Merkin väri määräytyy organisaation asetuksesta (oletus harmaa)
- Ota käyttöön/poista käytöstä **postilaatikko kerrallaan** via **Postilaatikon asetukset → OrgPortal**; yleistä arvoa käytetään varamenetelmänä

### Organisaatiomallia Kanban-korteissa
- Näytetään viestittelylaskurin jälkeen jokaisella kortilla
- Voidaan napsauttaa — johtaa organisaatiohakuun
- Väri vastaa organisaation asetusta
- **Organisaatio**-suodatin on sisäänrakennettu standardi Kanban-suodattimen avattavaan luetteloon: modaali, jossa on valintaruudut, samankaltainen kuin tunnisteiden suodatin; tila säilyy navigoinnin välillä
- Ota käyttöön/poista käytöstä **postilaatikko kerrallaan** via **Postilaatikon asetukset → OrgPortal**

### Organisaatiohaun suodatin
- Laajentaa FreeScout-haun **Organisaatio**-suodattimella
- Näyttää kaikki liput valittuun organisaatioon kuuluvista asiakkaista

### End-User Portal — johtajien pääsy *(valinnainen)*

Organisaation johtaja saa laajennettua pääsyä EUP:n kautta:

- **Yrityksen liput** -kohta portaalin navigoinnissa
- Yrityksen liput -taulukko sarakkeilla:
  - **#** ja **Aihe** ellipsin lyhennyksellä ja työkaluvihjeen vierityksessä
  - **Vastuuhenkilö** — määritetty agentti
  - **Kirjoittaja** — asiakas, joka avasi lipun; napsauta suodattaa lippuja kirjoittajan mukaan organisaatiossa
  - **Tila** — Aktiivinen / Odottava / Suljettu / Roskaposti kuvakkeella
  - **Asema** — Kanban-sarakkeen nimi (mukautetulla tunnisteen, jos määritetty); näytetään vain, jos Kanban-moduuli on aktiivinen
  - **Päivitetty** — viimeisen vastauksen päivämäärä ja aika
- Hae lippujen aiheella
- Suodata Kanban-tilojen mukaan (mukautettavissa via **Postilaatikon asetukset → OrgPortal**)
- Vastaa lippuun **liitteen tuella** (vedä ja pudota, useita tiedostoja)
- **Sulje lippu** — johtaja voi sulkea lipun; uusi vastaus avaa sen uudelleen automaattisesti
- Muuta lipun tekijää — määritä lippu uudelleen toiselle organisaation jäsenelle
- **Organisaation asetukset** -sivu sähköpostilmoitusten konfigurointiin
- Lipun pääsy on **tiukasti rajoitettu nykyiseen postilaatikkoon** (organisaatio kopioitu toiseen postilaatikkoon — portaali 403)

### Sähköpostilmoitukset *(valinnainen)*
- Johtajat, joilla on vaihtoehto käytössä, saavat sähköpostiviestin, kun organisaation jäsen luo uuden lipun
- Käyttää vastaavan postilaatikon postin ohjaimia

### Postilaatikon asetukset

**Postilaatikon asetukset → OrgPortal** (postilaatikko kerrallaan):

| Vaihtoehto | Kuvaus |
|-----------|-------------|
| Näytä merkki lippusivulla | Ota käyttöön/poista käytöstä merkki tässä postilaatikossa |
| Näytä merkki Kanban-korteissa | Ota käyttöön/poista käytöstä merkki tässä postilaatikossa |
| Yrityksen lippujen tilasuodattimet | Valitse Kanban-sarakkeet, jotka näytetään valintaruutuina liput-sivulla; mukautettu tunniste jokaiselle suodattimelle |

---

### REST API *(valinnainen, vaatii API:n ja webhookeja)*

Todentaminen — `X-FreeScout-API-Key`-otsikko tai `api_key`-kyselyparametri.

> **Interaktiivinen dokumentaatio** (ReDoc) on saatavilla sivulla **Hallinta → API ja webhookit** (linkki "OrgPortal API-dokumentaatio") tai suoraan osoitteessa `/orgportal/admin/api-docs`.

| Menetelmä | Päätepiste | Kuvaus |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Luettele organisaatiot (sivutus, postilaatikon suodatin) |
| `POST` | `/api/organizations` | Luo organisaatio |
| `GET` | `/api/organizations/{id}` | Hae organisaatio jäsenineen |
| `PUT` | `/api/organizations/{id}` | Päivitä organisaatio |
| `DELETE` | `/api/organizations/{id}` | Poista organisaatio |
| `GET` | `/api/customers/{id}/organization` | Asiakkaan organisaatio |
| `PUT` | `/api/customers/{id}/organization` | Aseta/päivitä asiakkaan jäsenyys |
| `DELETE` | `/api/customers/{id}/organization` | Poista asiakas organisaatiosta |

#### Vastauksen koodit

| Koodi | Merkitys |
|------|-----------|
| `200` | Onnistui tai no-op (mitään ei muuttunut) |
| `201` | Resurssi luotu; `Resource-ID`-otsikko sisältää ID:n |
| `400` | Validointivirhe — tiedot osoitteessa `_embedded.errors` |
| `401` | Virheellinen tai puuttuva API-avain |
| `404` | Resurssia ei löydy |
| `409` | Ristiriita — asiakas kuuluu jo toiseen organisaatioon |

---

#### GET /api/organizations

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

#### POST /api/organizations

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|------|------|:--------:|-------------|
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

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|------|------|:--------:|-------------|
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
{"success": true, "message": "Organisaatio päivitetty."}
```

---

#### DELETE /api/organizations/{id}

**200 OK** *(kaikki jäsenet poistetaan kaskadissa)*
```json
{"success": true, "message": "Organisaatio poistettu."}
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

Määritä asiakas organisaatioon tai päivitä heidän roolia. **Yksi asiakas — yksi organisaatio**: jos asiakas on jo jäsen *toisessa* organisaatiossa, pyyntö hylätään `409 Ristiriita` -viestillä. Siirtämiseksi — poista ensin nykyinen jäsenyys `DELETE`-metodilla.

**Pyynnön runko**

| Kenttä | Tyyppi | Vaadittu | Kuvaus |
|------|------|:--------:|-------------|
| `organizationId` | kokonaisluku | ✅ | Organisaation ID |
| `role` | merkkijono | — | `"jäsen"` (oletus) tai `"johtaja"` |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager"}'
```

**201 Created** *(uusi jäsenyys)*
```json
{"success": true, "message": "Jäsenyys luotu."}
```

**200 OK** *(rooli päivitetty tai no-op)*
```json
{"success": true, "message": "Jäsenyys päivitetty."}
```

**409 Conflict** *(asiakas jo toisessa organisaatiossa)*
```json
{
  "message": "Asiakas kuuluu jo toiseen organisaatioon.",
  "errorCode": "CUSTOMER_ALREADY_BELONGS_TO_ANOTHER_ORGANIZATION.",
  "_embedded": {
    "errors": [
      {
        "path": "organizationId",
        "message": "Asiakas on jo organisaation #3 jäsen. Poista nykyinen jäsenyys ensin DELETE /api/customers/42/organization kautta.",
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
{"success": true, "message": "Jäsenyys poistettu."}
```

---

## Asennus

1. Kopioi `OrgPortal`-kansio FreeScoutin `Modules/`-kansioon
2. Hallintapaneelissa: **Hallinta → Moduulit → OrgPortal → Aktivoi**
3. Suorita migraatiot:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Tyhjennä välimuisti:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Päivitykset

OrgPortal tukee **automaattisia päivityksiä** FreeScout-ohjelmiston sisäänrakennetun moduulin päivitysmekanismin kautta.

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

Kun uusi versio on saatavilla, banderolli ilmestyy sivulle **Hallinta → Moduulit**. Napsauta **Päivitä nyt** — FreeScout lataa ja asentaa uusimman version automaattisesti.

Manuaalista tiedostojen kopiointia ei tarvita.

---

## Moduulin yhteensopivuus

| Moduuli | Tila |
|--------|--------|
| End-User Portal ≥ 1.0.85 | Valinnainen — portaalin ominaisuudet johtajille |
| API ja webhookit ≥ 1.0.80 | Valinnainen — REST API-päätepisteet |
| Kanban ≥ 1.0.23 | Valinnainen — merkki, suodatin, "Asema"-sarake yrityksen lipuissa |
| Mukautetut kentät | Yhteensopiva |
| Työnkulut | Yhteensopiva |
| Tunnisteet | Yhteensopiva |

---

## Konfiguraatio

### Yleinen (**Hallinta → OrgPortal-asetukset**)

| Vaihtoehto | Oletus |
|---------|---------|
| Näytä merkki lippusivulla | ✅ |
| Näytä merkki Kanban-korteissa | ✅ |

### Postilaatikko kerrallaan (**Postilaatikon asetukset → OrgPortal**)

Ohittaa globaalit arvot tietylle postilaatikolle.

| Vaihtoehto | Kuvaus |
|---------|-------------|
| Näytä merkki lippusivulla | Merkki keskustelun luettelossa ja lippusivulla |
| Näytä merkki Kanban-korteissa | Merkki Kanban-korteissa |
| Yrityksen lippujen tilasuodattimet | Kanban-sarakkeet valintaruutuina yrityksen liput-sivulla; jokaisella suodattimella on portaalin käyttäjille näkyvä mukautettu tunniste |

---

## Käännökset

Tuetut kielet: **Englanti** (`en`), **Ukraina** (`uk`), **Romania** (`ro`), **Georgia** (`ka`), **Saksa** (`de`), **Ranska** (`fr`), **Espanja** (`es`), **Italia** (`it`), **Tšekki** (`cs`), **Slovakia** (`sk`), **Puola** (`pl`), **Venäjä** (`ru`), **Alankomaat** (`nl`), **Norja** (`no`), **Tanska** (`da`), **Ruotsi** (`sv`), **Suomi** (`fi`), **Portugali BR** (`pt-BR`), **Portugali PT** (`pt-PT`), **Yksinkertainen kiina** (`zh-CN`).

Tiedostot: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### EUPSWLANG-integraatio

Moduuli toimii oikein [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) kanssa: portaalissa valittu kieli koskee myös OrgPortal-merkkijonoja.

Jotta kieli ilmestyy EUPSWLANG-luetteloon, vastaava `Modules/EndUserPortal/Resources/lang/{locale}.json`-tiedosto on oltava olemassa. **Romaanian** (`ro`) tiedostot sisältyvät pakettiin; **Georgian** (`ka`) tuetaan vain hallintaosiossa (ei järjestelmän tukea FreeScout core -ohjelmassa).

> **Tekninen yksityiskohta:** `ReapplyEupLocale`-middleware (rekisteröity viimeksi portaalin reititysryhmässä) palauttaa alueinstansen FreeScoutin `Localize`-middlewaren jälkeen, mikä muuten nollaisi portaalin kielivalinnan järjestelmän oletusarvoon.

---

## Lisenssi

[MIT](../LICENSE) — © 2026 ASTIN-UA
