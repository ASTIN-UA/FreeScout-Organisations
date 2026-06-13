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

### REST API *(valinnainen, vaatii API:n ja webhookit)*

OrgPortal tarjoaa täydellisen REST API:n organisaatioiden, rakenneyksiköiden ja asiakasjäsenyyksien hallintaan — todennus `X-FreeScout-API-Key`-otsakkeella tai `api_key`-kyselyparametrilla.

📖 **Täydellinen API-viite → [docs/api/README.fi.md](api/README.fi.md)** (kaikki päätepisteet, pyyntö-/vastausesimerkit, virhekoodit)

Interaktiivinen ReDoc-dokumentaatio on saatavilla myös kohdassa **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

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
