# OrgPortal — B2B-organisaationhallintamoduuli FreeScoutille

[← Takaisin README:hen](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — FreeScout B2B -moduuli" width="140" align="right">

**OrgPortal** on FreeScout-moduuli, joka lisää täyden **B2B-organisaationhallinnan** tukipalveluusi: ryhmittele asiakkaat yrityksiin, määritä osastohierarkiat, anna yrityspäälliköille itsepalveluportaali ja automatisoi ilmoitukset — kaikki FreeScoutin sisällä, ilman ulkoisia työkaluja.

> Etsitkö tapaa hallita yritystilejä FreeScoutissa? Antaa yrityksille oma tukiportaali? Hallita, mitä tikettejä kukin B2B-yhteyshenkilö näkee roolinsa ja osastonsa perusteella? OrgPortal ratkaisee kaiken tämän.

**Toimii:** FreeScout 1.8.147+  
**Valinnaiset integraatiot:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

> [!IMPORTANT]
> **Asenna aina [uusimmasta julkaisusta](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest), ei repositorion lähdekoodista.**
> Lataa `OrgPortal.zip` Releases-sivulta — se sisältää oikean hakemistorakenteen, jota FreeScout vaatii.
> Lähdekoodin lataaminen (via "Code → Download ZIP" tai `git clone`) **ei toimi** ja rikkoo moduulirakenteen.
> Automaattiset päivitykset vaativat myös, että julkaisu-ZIP:iä on käytetty alkuasennuksessa.

---

🌐 **Saatavilla myös:**
[Українська](docs/README.uk.md) ·
[Deutsch](docs/README.de.md) ·
[Français](docs/README.fr.md) ·
[Español](docs/README.es.md) ·
[Italiano](docs/README.it.md) ·
[Polski](docs/README.pl.md) ·
[Čeština](docs/README.cs.md) ·
[Slovenčina](docs/README.sk.md) ·
[Nederlands](docs/README.nl.md) ·
[Norsk](docs/README.no.md) ·
[Dansk](docs/README.da.md) ·
[Svenska](docs/README.sv.md) ·
[Suomi](docs/README.fi.md) ·
[Português (BR)](docs/README.pt-BR.md) ·
[Português (PT)](docs/README.pt-PT.md) ·
[Română](docs/README.ro.md) ·
[中文 (简体)](docs/README.zh-CN.md)

---

## Sisällysluettelo

- [Mitä OrgPortal lisää FreeScoutiin](#mitä-orgportal-lisää-freescoutiin)
- [Organisaatiot](#organisaatiot)
- [Rakenteelliset yksiköt — Osastotason pääsynhallinta](#rakenteelliset-yksiköt--osastotason-pääsynhallinta)
- [Org Snapshot — Pysyvä tikettien attribuointi](#org-snapshot--pysyvä-tikettien-attribuointi)
- [Kanban-integraatio](#kanban-integraatio)
- [Pääsynhallinta ja käyttöoikeudet](#pääsynhallinta-ja-käyttöoikeudet)
- [Järjestelmäasetukset](#järjestelmäasetukset--manage--organizations--system-tab)
- [End-User Portal — Itsepalvelu yrityspäälliköille](#end-user-portal--itsepalvelu-yrityspäälliköille-valinnainen)
- [Reaaliaikaiset ilmoituskellot](#reaaliaikaiset-ilmoituskellot-valinnainen)
- [Ilmoitustilaukset](#ilmoitustilaukset-valinnainen)
- [Portaalin organisaatioasetukset](#portaalin-organisaatioasetukset)
- [Monikieliset ilmoitussähköpostimallit](#monikieliset-ilmoitussähköpostimallit-valinnainen)
- [REST API](#rest-api-valinnainen)
- [Asennus](#asennus)
- [Automaattiset päivitykset](#automaattiset-päivitykset)
- [Moduuliyhteensopivuus](#moduuliyhteensopivuus)
- [Konfigurointi](#konfigurointi)
- [Käännökset](#käännökset)
- [Kuvakaappaukset](#kuvakaappaukset)
- [Lisenssi](#lisenssi)

---

## Mitä OrgPortal lisää FreeScoutiin

FreeScout on rakennettu yksittäisten asiakkaiden ympärille — jokainen sähköposti on henkilöltä, eikä järjestelmässä ole sisäänrakennettua käsitettä yrityksestä, jolle henkilö työskentelee. Tämä toimii hyvin B2C-tukipalveluissa. B2B:lle se ei riitä.

OrgPortal täyttää tämän aukon:

- **Yritystilit** — ryhmittele asiakkaat organisaatioihin nimen, väritunnisteen, postilaatikon laajuuden ja aktiivisen/passiivisen tilan avulla
- **Osastohierarkiat** — jaa organisaatiot rakenteellisiin yksiköihin (osastot, toimipisteet, tiimit); jokainen jäsen kuuluu omaan yksikköönsä
- **Roolipohjainen pääsynhallinta** — `member` näkee vain omat tikettinsä; `unit_manager` näkee koko yksikön; `manager` näkee koko organisaation
- **Yrityksen itsepalveluportaali** — päälliköt näkevät kaikki yrityksen tiketit, voivat vastata, sulkea, siirtää tekijöitä ja hallita ilmoitusasetuksia ilman tukitiimin kontaktointia
- **Pysyvä tikettien attribuointi** — jokainen tiketti rekisteröidään organisaatiolle luontihetkellä; historiaraportointi säilyy vaikka asiakasluettelo muuttuu
- **Monikieliset ilmoitukset** — automaattiset sähköposti-ilmoitukset jokaisen päällikön omalla kielellä, lokalisoiduilla mallineilla ja sisäänrakennetulla WYSIWYG-editorilla
- **REST API** — synkronoi jäsenyydet CRM:stä, automatisoi käyttöönotto, hallitse tunnisteita ohjelmallisesti

---

## Organisaatiot

*Yksi paikka kaikelle yritystilin tiedoille.*

**Manage → Organizations** avaa välilehtipohjaisen käyttöliittymän, jossa on kolme osiota: Organizations, Templates ja System.

### Organisaatiolista

- **Luo, muokkaa, poista, aktivoi/deaktivoi** organisaatioita
- **Tilasuodatin** — vaihda Aktiivinen / Passiivinen / Kaikki radio-ryhmällä; suodattaa taulukon välittömästi asiakaspuolella
- **Reaaliaikahaku** — suodatus alkaa 2+ merkin jälkeen, ei sivun uudelleenlatausta
- **Värikoodatut tunnisteet** — interaktiivinen värinvalitsin 12 sävyllä ja reaaliaikainen tunniste-esikatselu; tunniste näkyy jokaisessa tiketissä ja Kanban-kortissa
- Tunnisteen tai tikettimäärän klikkaaminen avaa FreeScout-haun kyseiselle organisaatiolle suodatettuna
- **Postilaatikon sidonta** — organisaatiot voivat olla globaaleja (kaikki postilaatikot) tai rajattuja tiettyyn postilaatikkoon
- **Tunnisteet-sarake** — näyttää ✓/✗ onko FreeScout-tunnisteita sidottu organisaatioon (vaatii Tags-moduulin); tunnisteet määritetään muokkauslomakkeessa sirumaisella widgetillä ja automaattisella haulla
- **Tikettimäärä-sarake** — organisaation kokonaisviestit; klikattava linkki hakutuloksiin
- **Jäsenmäärä**-sarake
- **Aktivoi / deaktivoi** — jäädytä tili menettämättä historiaa; vaatii Org Snapshot -toiminnon käyttöön (painike on poistettu käytöstä työkaluvihjeellä jos ei ole)
- **Poista** — saatavilla vain kun organisaatiolla on 0 jäsentä ja 0 tikettejä (turvaesto)
- Kaikki poisto- ja deaktivointitoiminnot vaativat vahvistuksen

![Organisaatiolista — tilasuodatin, reaaliaikahaku, väritunnisteet, tunnisteet, tikettimäärät](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png)

### Organisaation muokkauslomake

- **Nimi** ja **postilaatikon sidonta**
- **Värinvalitsin** — 12 sävy reaaliaikaisella tunniste-esikatselulla
- **Tunnisteet** — sirupohjainen widget: hae olemassa olevia FreeScout-tunnisteita, klikkaa lisätäksesi, × poistaaksesi
- **Jäsentaulukko** — jäsenkohtaisesti: nimi, rooli, rakenteellinen yksikkö, `can_manage_org`-valintaruutu (myöntää ylläpitäjäoikeudet organisaatioihin ilman täysiä ylläpitäjäoikeuksia), aktiivinen/passiivinen-kytkin
- **Rakenteelliset yksiköt -paneeli** — luo ja nimeä uudelleen yksiköitä suoraan muokkauslomakkeesta; jäsenet liitetään yksiköihin samassa näkymässä
- **Jäsenen lisääminen** — täydentää automaattisesti asiakkaan olemassa olevat attribuoimattomat viestit

![Organisaation muokkaus — värinvalitsin, tunnistesirut, jäsentaulukko rooleilla ja yksiköillä](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

### Asiakasprofiilin integraatio

- **Organisaatiokenttä FreeScoutin asiakkaan muokkauslomakkeessa** — reaaliaikainen automaattinen haku organisaatioille; roolivalikko ilmestyy organisaation valinnan jälkeen; × -painike poistamiseen
- **"Näytä org-tiketit"** -pikaylinkki asiakkaanlomakkeessa
- **Org-tietojen lohko hallinta-tiketin sivupalkissa** — organisaation nimi (klikattava linkki org-muokkaussivulle), rakenteellinen yksikkö ja jäsenen rooli; vaihda näkyvyyttä postilaatikkokohtaisesti asetuksissa
- **Yksi aktiivinen jäsenyys asiakkaalle** — asiakasta ei voi lisätä toiseen organisaatioon aktiivisen jäsenyyden aikana; passiiviset/arkistoidut jäsenyydet ovat sallittuja

![Asiakkaan muokkaus — organisaatiokenttä automaattisella haulla ja roolivalitsimella](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png)

---

## Rakenteelliset yksiköt — Osastotason pääsynhallinta

*Tuki suurille yrityksille, joilla on monimutkaiset sisäiset hierarkiat.*

Organisaatiot voidaan jakaa rajattomaan määrään **rakenteellisia yksiköitä** (osastot, toimipisteet, aluetoimistot, projektitiimit):

- Luo, nimeä uudelleen ja poista yksiköitä hallinta-org-muokkauslomakkeesta tai suoraan portaalista (vain globaalit päälliköt)
- Liitä jäsenet yksiköihin — jokainen jäsen kuuluu yhteen yksikköön
- **Yksikön poistaminen** alentaa automaattisesti sen `unit_manager`-jäsenet `member`-tasolle

**Kolme roolisoa:**

| Rooli | Pääsyn laajuus |
|-------|----------------|
| `member` | Vain omat tiketit |
| `unit_manager` | Kaikki tiketit rakenteellisen yksikön sisällä |
| `manager` (globaali) | Kaikki tiketit koko organisaatiossa |

- Yksikköpäälliköillä on täydet portaalin ominaisuudet — vastaukset, liitteet, tekijän siirto, sulkeminen/avaaminen, ilmoitushallinta — rajattuna tiukasti omaan yksikköönsä
- Tikettien pääsy ja ilmoitusten toimitus noudattavat yksikkörajoja

![Organisaation muokkaus — jäsenet rooleilla ja yksiköillä, yksikönhallintapaneeli](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

---

## Org Snapshot — Pysyvä tikettien attribuointi

*Luotettava historiaraportointi vaikka asiakasluettelosi muuttuu.*

Kun tiketti luodaan, OrgPortal tallentaa organisaatiokontekstin pysyvänä tilannekuvana:

- `org_id`, `org_unit_id` ja `org_attributed_at` kirjoitetaan viestiin luontihetkellä
- **Muuttumaton** — jos asiakas myöhemmin poistuu organisaatiosta, hänen historialliset tikettinsä pysyvät kyseisen organisaation attribuoimina; raportointi ei koskaan katkea
- **Jäsenen lisääminen** käynnistää automaattisen täydennyksen asiakkaan olemassa oleville attribuoimattomille viesteille

### Attribuointilähde — kolme tilaa

Määritetään kohdassa **Manage → Organizations → System tab**:

| Tila | Toiminta |
|------|----------|
| `member` | Attribuoi tiketti sille organisaatiolle, jonka jäsen tiketin tekijä on |
| `tag` | Attribuoi ensin FreeScout-tunnisteen perusteella; siirry jäsenyyteen jos tunniste ei täsmää |
| `tag_only` | Attribuoi yksinomaan tunnisteen perusteella; jäsenyyttä ei käytetä |

`tag`- ja `tag_only`-tilat ovat poistettu käytöstä kun Tags-moduuli ei ole aktiivinen.

### Täydennystyökalut

- **Edistymispalkki** — näyttää X / Y attribuoitujen tikettien määrän (%) "valmis"-ilmaisimella
- **Ennakkotilastot** — ennen täydennyksen suorittamista eritellään kuinka monta tikettia attribuoidaan tunnisteen, jäsenyyden tai ei lainkaan perusteella
- **Suorita täydennys** -painike — käsittelee enintään 2000 tikettia klikkauksella; tulossummary (by_tag / by_member / unmatched) näytetään jälkeenpäin
- **Automaattinen cron** (`attribution_cron_enabled`) — ajastaa täydennyksen 5 minuutin välein, 1000 tikettia per ajo, ilman päällekkäisyyksiä
- **Nollaa attribuointi** — tyhjentää kaikki org-tilannekuvat (vaarallinen toiminto, vaatii vahvistuksen)
- Komentorivi: `php artisan orgportal:backfill-attribution`

![Järjestelmävälilehti — attribuointilähde, edistymispalkki, ennakkotilastot, täydennystyökalut](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png)

---

## Kanban-integraatio

*Pidä visuaalinen työnkulkusi linjassa B2B-tiliesi kanssa.*

- Organisaation tunnisteväri jokaisessa Kanban-kortissa
- **Organisaatiosuodatin** Kanban-suodatinpaneelissa — monivalintamodaali valintaruuduilla; suodatintila säilyy navigoinnin välillä
- **Monikieliset Kanban-tilasuodatintunnisteet** — anna jokaiselle Kanban-sarakkeelle mukautettu nimi portaalin kielellä; vaihda lokaalia postilaatikkokohtaisissa asetuksissa olevalla kielenvalitsimella; järjestä vetämällä
- Käännetyt tunnisteet näkyvät sekä portaalin suodatinpalkissa että yritystikettitaulukon **Tila**-sarakkeessa; varajärjestys: tallennettu lokaalikieli → tallennettu englanti → alkuperäinen sarakkeen nimi

![Kanban — organisaatiotunnisteet korteissa ja organisaatiosuodatinmodaali](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png)

---

## Pääsynhallinta ja käyttöoikeudet

*Delegoi organisaationhallinta ilman ylläpitäjäoikeuksien myöntämistä.*

- **"Salli organisaatioiden hallinta"** (`can_manage_org`) — kaksi tasoa:
  - **Käyttäjäoikeutena** agentin asetuksissa — sallii tukitiimin vetäjän hallita kaikkia organisaatioita ilman ylläpitäjäoikeuksia
  - **Jäsenkohtaisena merkintänä** organisaation muokkauslomakkeessa — sallii tietyn org-jäsenen hallita kyseistä yhtä organisaatiota hallintapaneelista
- **"Salli ilmoitusmallien hallinta"** — erillinen yksityiskohtainen käyttöoikeus mallien muokkaukseen
- Organisaatioiden poistaminen on yksinomaan ylläpitäjän oikeus
- Portaalin pääsy on tiukasti rajattu postilaatikkokohtaisesti: organisaation A päällikkö ei pääse organisaation B tietoihin

![Yksityiskohtaiset käyttöoikeudet — salli organisaatioiden ja ilmoitusmallien hallinta](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png)

---

## Järjestelmäasetukset — Manage → Organizations → System tab

*Vain ylläpitäjille tarkoitetut ohjaimet attribuointia, täydennystä ja portaalin kielenvalitsinta varten.*

**System**-välilehti näkyy vain FreeScoutin ylläpitäjille.

### Paneeli 1: Tikettien attribuointi

Katso [Org Snapshot](#org-snapshot--pysyvä-tikettien-attribuointi) yllä saadaksesi täyden kuvauksen attribuointitiloista, täydennystyökaluista ja automaattisesta cronista.

### Paneeli 2: Portaalin kielenvalitsin

- **Ota käyttöön/poista käytöstä** kielenvalitsin End-User Portal -navigaatiopalkissa
- **Valitse mitkä 19:stä lokaaleista** tarjotaan (valintaruutukehikko); kaikki ovat oletuksena käytössä
- Kun käytössä, päälliköt voivat vaihtaa portaalin kieltä; heidän valintansa tallennetaan ja käytetään ilmoitussähköposteissa
- Tämä on OrgPortalin sisäänrakennettu kielenvalitsin — se toimii riippumattomasti kolmannen osapuolen kielenvaihtomoduuleista; molemmat voivat olla aktiivisina samanaikaisesti

![Järjestelmävälilehti — portaalin kielenvalitsinpaneeli lokaaliruudukoilla](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png)

---

## End-User Portal — Itsepalvelu yrityspäälliköille *(valinnainen)*

*Anna B2B-asiakkaillesi portaali, jossa he hallitsevat yrityksensä tukisuhdetta — ilman että he joutuvat ottamaan tiimiisi yhteyttä jokaisen tilannetiedon takia.*

Vaatii [End-User Portal](https://freescout.net/module/end-user-portal/) -moduulin.

### Yritystikettien hallintapaneeli

Omistettu **Yritystiketit**-osio portaalin navigoinnissa täysiominaisella tikettitaulukolla:

| Sarake | Kuvaus |
|--------|--------|
| **#** | Tiketin tunniste |
| **Aihe** | Lyhennetty, työkaluvihje hoveroitaessa |
| **Vastuuhenkilö** | Määritetty tukiagent |
| **Tekijä** | Asiakas, joka avasi tiketin; klikkaa suodattaaksesi tekijän mukaan |
| **Tila** | Aktiivinen / Odottaa / Suljettu / Roskaposti kuvakkeilla |
| **Tila (Kanban)** | Kanban-sarakkeen nimi nykyisellä portaalin kielellä (vain kun Kanban-moduuli on aktiivinen) |
| **Päivitetty** | Viimeisimmän vastauksen päivämäärä ja aika |

**Kaksi riippumatonta lukutilaindikaattoria rivissä** — nämä seuraavat kahta eri henkilöä ja näytetään samanaikaisesti:

| Indikaattori | Kenen lukutila | Mitä tarkoittaa |
|-------------|----------------|-----------------|
| **Lihavoitu rivi** | Portaalia selaileva päällikkö | Päälliköllä on lukemattomia ilmoituksia tästä viestistä — jotain on tapahtunut mitä hän ei ole vielä nähnyt |
| **👁 Silmäkuvake** | Tiketin tekijä (asiakas, joka lähetti sen) | Tekijä ei ole vielä avannut agentin viimeisintä vastausta — hyödyllinen tietämään onko asiakas todella nähnyt vastauksen |

Nämä kaksi tilaa ovat täysin riippumattomia: rivi voi olla lihavoitu (päällikkö ei ole lukenut) samalla kun silmä puuttuu (tekijä on jo lukenut), tai päinvastoin. Päällikkö näkee molemmat samanaikaisesti, saaden täydellisen kuvan mitä tapahtuu tiketin molemmilla puolilla avaamatta sitä.

**Tekijäsuodatin** — tekijän nimen klikkaaminen aktivoi suodattimen; banneri ilmestyy taulukon yläosaan näyttäen aktiivisen tekijän nimen × -linkillä suodattimen tyhjentämiseksi.

Sekä pöytätietokoneelle tarkoitettu taulukko että reagoiva **mobiilikorttinäkymä** ovat mukana; ne vaihtuvat automaattisesti näytön leveyden mukaan.

Suodatinpalkin malline tukee **ohitusta** kohteella `enduserportal::partials.tickets_filters` — sijoita mukautettu näkymä siihen polkuun korvataksesi OrgPortalin oletussuodatinpalkin säilyttäen kaikki muut toiminnot.

![Yritystiketit — täysi taulukko lukuindikaattoreilla, tekijäsuodatinbannerilla, tilasuodattimilla](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png)

### Tikettitoiminnot portaalissa

Päälliköt voivat toimia suoraan — tukeen ei tarvitse ottaa yhteyttä:

- **Vastaa liitteillä** — vedä ja pudota, useita tiedostoja vastausta kohti; liitteiden nimet ja koot näkyvät jokaisessa viestissä
- **Sulje tiketti** — uusi vastaus avaa sen automaattisesti uudelleen; banneri informoi päällikköä tästä kun tiketti on suljettu
- **Vaihda tiketin tekijää** — siirrä tiketti toiselle organisaation jäsenelle
- **Suodata yksiköllä** — globaalit päälliköt suodattavat tikettilistan rakenteellisen yksikön mukaan
- **Suodata Kanban-tilalla** — postilaatikkokohtaisesti konfiguroitavissa, tunnisteet näytetään nykyisellä portaalin kielellä

![Portaalin tikettinäkymä — vastauslomake vedä-ja-pudota-liitteillä ja suljettu-tiketti-banneri](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png)

### Päällikön katselun seuranta

- **"Katsottu"**-merkintä ilmestyy agentin vastausten alle hallinta-tikettinäkymässä kun päällikkö avaa tiketin portaalissa
- Näyttää päällikön nimen, roolin (Organisaation päällikkö / Yksikköpäällikkö) ja kuluneen ajan
- Globaalin päällikön ja yksikköpäällikön katselut seurataan ja näytetään erikseen — sama UX kuin FreeScoutin natiivi "Asiakas katsoi"

![Päällikön katselun seuranta — 'katsottu'-merkintä ilmestyy agentin vastauksen alle hallinta-tikettinäkymässä](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png)

---

## Reaaliaikaiset ilmoituskellot *(valinnainen)*

*Pidä päälliköt ajan tasalla heti kun jotain tapahtuu heidän yritystiketeissään.*

Vaatii [End-User Portal](https://freescout.net/module/end-user-portal/) -moduulin.

- 🔔 Kellokuvake reaaliaikaisella lukemattomien määrä -merkinnällä EUP-navigaatiopalkissa — sijoittuu automaattisesti uudelleen mobiilissa (hampurilaisvalikon viereen)
- Ilmoitukset: **uusi tiketti**, **agentin vastaus**, **asiakkaan vastaus** — kaikille päällikkörooleille
- Pudotuspaneeli ilmoituksineen ryhmiteltynä päivämäärän mukaan: toimijan nimi, tapahtumatyyppi, tiketin numero, viestiesikatselu, aikaleima
- **Automaattinen merkitseminen luetuksi** kun päällikkö avaa tiketin
- Merkitse yksittäiset ilmoitukset luetuksi × -painikkeella; **Merkitse kaikki luetuksi** paneelin otsikossa
- Pollaa 15 sekunnin välein; päivittyy selaimen eteen/taakse-navigoinnissa (bfcache-tietoinen)

![Reaaliaikaiset ilmoituskellot — pudotuspaneeli ryhmitellyillä lukemattomilla ilmoituksilla](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png)

---

## Ilmoitustilaukset *(valinnainen)*

*Anna päälliköiden päättää mistä he kuulevat — ei enempää, ei vähempää.*

- **Visuaalinen tilausmatriisi** portaalin organisaatioasetusten "Notifications"-välilehdellä
- **Kolme tapahtumatyyppiä:** Uusi tiketti · Agentin vastaus · Asiakkaan vastaus
- **Kaksi laajuustasoa:** Koko organisaatio (globaalit päälliköt) · Yksittäiset rakenteelliset yksiköt
- Ilman yksikköä olevat jäsenet ryhmitellään erilliseen **"Ei yksikköä"** -laajennettavaan riviin
- **Jäsenkohtaiset ohitukset** — laajenna yksikön rivi paljastaaksesi yksittäiset jäsenet ja vaihda heidän tilauksiaan inline-toiminnolla; yksikköpäälliköt rajatulla roolilla merkitään vastaavasti
- **Kaskadoitu logiikka molempiin suuntiin:**
  - "Koko organisaatio" käyttöön ottaminen → ottaa kaikki yksiköt ja jäsenet käyttöön
  - Yksikön käyttöön ottaminen → ottaa kaikki sen jäsenet käyttöön
  - Jäsenen poistaminen käytöstä → täsmäyttää automaattisesti yksikön ja organisaation valintaruudut
- Globaalit päälliköt hallitsevat kaikkia jäseniä; yksikköpäälliköt hallitsevat vain omaa yksikköään
- Ilmoitukset käyttävät vastaavan postilaatikon sähköpostiajuria

![Ilmoitustilausmatriisi — yksikkö- ja jäsenkohtaiset kytkimet](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png)

---

## Portaalin organisaatioasetukset

*Päälliköt konfiguroivat organisaationsa rakenteen ilman ylläpitäjäoikeuksia.*

Portaalin navigoinnin **Organization Settings** -osiossa on kolme välilehteä:

### Notifications-välilehti

Yllä kuvattu tilausmatriisi.

### Units-välilehti *(vain globaalit päälliköt)*

- **Luo yksikkö** — inline-lomake nimikentällä
- **Nimeä yksikkö uudelleen** — inline-muokkaus suoraan taulukkorivissä
- **Poista yksikkö** — painike vahvistuksella; yksikköpäälliköt alennetaan automaattisesti jäseniksi
- Jäsenmäärä näytetään yksikköä kohti

### Members-välilehti

- Taulukko kaikista organisaation jäsenistä: nimi, rakenteellinen yksikkö, rooli, aktiivinen/passiivinen-tilamerkintä
- **"Globaali päällikkö"** -merkintä jäsenen nimen vieressä tarvittaessa
- **Näytä deaktivoituneet** -valintaruutu — näkyy vain kun passiivisia jäseniä on; piilotettu oletuksena
- **Globaalit päälliköt** voivat päivittää minkä tahansa jäsenen yksikköä ja roolia inline-lomakkeella (yksikkövalinta + roolivalinta + Käytä)
- **Globaalit päälliköt eivät voi ylentää jäsentä globaaliksi päälliköksi** portaalista — tämä vaatii ylläpitäjän oikeudet
- **Aktivoi / deaktivoi** -painike jäsentä kohti deaktivoinnin vahvistuksella

![Portaalin organisaatioasetukset — Units- ja Members-välilehdet](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png)

---

## Monikieliset ilmoitussähköpostimallit *(valinnainen)*

*Yrityksesi asiakkaat saavat tukisähköposteja omalla kielellään — automaattisesti, ilman manuaalista vaivaa.*

Määritetään kohdassa **Manage → Organizations → Templates tab** (näkyy käyttäjille, joilla on "hallitse malleja" -käyttöoikeus).

- **Lokaalikohdisteisia malleja** — erillinen aihe ja sisältö jokaiselle portaalin kielelle; vaihda lokaalivaroivalitsimella; arvot vaihdetaan muistissa ilman sivun uudelleenlatausta
- **Kokoontaitettavat paneelityyppeittäin** (Uusi tiketti / Agentin vastaus / Asiakkaan vastaus) — Summernote-editori alustetaan laiskasti kun paneeli avataan
- **Lataa oletus** -painike jokaisessa paneelissa — palauttaa sisäänrakennetun mallin nykyiselle lokaalille (käyttää englanninkielistä sisäänrakennettua jos lokaalikohtaista oletusta ei ole)
- **Summernote WYSIWYG -editori** rikasta HTML-sähköpostia varten
- **Makromuuttujan valitsin** — lisää paikkamerkkejä aiheeseen tai sisältöön yhdellä klikkauksella; kursorin sijainti säilyy aihe-kentässä
- **19 sisäänrakennettua oletusmallia** — käyttövalmis heti; ei konfigurointia tarvita

**Käytettävissä olevat makromuuttujat:**

| Muuttuja | Kuvaus |
|----------|--------|
| `{manager_name}` | Ilmoituksen vastaanottavan päällikön nimi |
| `{author_name}` | Asiakas, joka loi tiketin tai vastasi siihen |
| `{org_name}` | Organisaation nimi |
| `{unit_name}` | Rakenteellisen yksikön nimi |
| `{subject}` | Tiketin aihe |
| `{ticket_number}` | Tiketin tunniste |
| `{ticket_url}` | Suora linkki tikettiin portaalissa |
| `{ticket_text}` | Alkuperäisen viestin koko teksti (HTML) |
| `{reply_text}` | Viimeisimmän vastauksen koko teksti (HTML) |
| `{created_date}` | Tiketin luontipäivämäärä |
| `{created_time}` | Tiketin luontiaika |
| `{created_datetime}` | Tiketin luontipäivämäärä ja -aika |
| `{reply_date}` | Vastauksen päivämäärä |
| `{reply_time}` | Vastauksen aika |
| `{reply_datetime}` | Vastauksen päivämäärä ja aika |

**Varajärjestys:** tallennettu lokaalimalli → sisäänrakennettu lokaalimalli → tallennettu englanninkielinen malli → sisäänrakennettu englanninkielinen malli

Ilmoituskieli määräytyy jokaisen päällikön portaalin kielivalinnasta, tallennetaan automaattisesti kun he käyttävät kielenvalitsinta.

![Sähköpostimallit — lokaalikohdisteinen kokoontaittuva paneeli, Lataa oletus -painike, Summernote-editori](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png)

---

## REST API *(valinnainen)*

*Integroi OrgPortal CRM:ään, ERP:iin tai asiakkaan käyttöönottoprosessiin.*

Vaatii [API and Webhooks](https://freescout.net/module/api-webhooks/) -moduulin.

- Täysi CRUD organisaatioille, rakenteellisille yksiköille, asiakkaiden jäsenyyksille ja tunnisteille
- **Organisaatiokentät:** `name`, `color`, `mailboxId`, `isActive` — kaikki luettavissa ja päivitettävissä API:n kautta
- **Members-aliresurssi** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — päivitä rooli, yksikkö, `canManageOrg` ja jäsenkohtainen `isActive`-merkintä riippumattomasti koskematta muuhun jäsenyyteen
- **Tags-aliresurssi** — `GET/PUT /api/organizations/{id}/tags` — listaa tai korvaa tunnistesidokset kokonaan (vaatii Tags-moduulin; palauttaa `503` jos ei aktiivinen)
- Todennus `X-FreeScout-API-Key`-otsikon tai `api_key`-kyselyparametrin avulla
- Interaktiivinen **ReDoc-dokumentaatio** kohdassa **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Täysi API-viite → [docs/api/README.md](docs/api/README.md)**

![Interaktiivinen API-dokumentaatio — ReDoc kaikilla OrgPortal-päätepisteillä](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png)

---

## Asennus

> [!IMPORTANT]
> Lataa `OrgPortal.zip` **[Releases-sivulta](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)** — **älä** käytä "Code → Download ZIP" äläkä kloonaa repositoriota. Vain julkaisu-ZIP:illä on oikea rakenne FreeScoutille ja se tukee automaattisia päivityksiä.

1. Lataa `OrgPortal.zip` [uusimmasta julkaisusta](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)
2. Pura ja kopioi `OrgPortal`-kansio FreeScout-asennuksesi `Modules/`-hakemistoon
2. Siirry kohtaan **Manage → Modules → OrgPortal → Activate**
3. Suorita migraatiot:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Tyhjennä välimuisti:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Georgian kielen tuki** otetaan käyttöön automaattisesti ensimmäisellä käynnistyskerralla — ei tarvita manuaalista tiedostojen kopiointia.

---

## Automaattiset päivitykset

OrgPortal tukee **yhden klikkauksen päivityksiä** FreeScoutin sisäänrakennetun moduulin päivitysmekanismin kautta.

> **Vaatii FreeScout 1.8.170 tai uudemman.** Vanhemmissa versioissa päivitä manuaalisesti korvaamalla `OrgPortal`-kansio uusimmalla julkaisun ZIP-tiedostolla.

Kun uusi versio on saatavilla, banneri ilmestyy kohtaan **Manage → Modules**. Klikkaa **Update now** — FreeScout lataa ja asentaa uusimman version automaattisesti.

---

## Moduuliyhteensopivuus

| Moduuli | Tila | Huomiot |
|---------|------|---------|
| End-User Portal ≥ 1.0.85 | Valinnainen | Päällikköportaali, ilmoituskello, tilaukset |
| API and Webhooks ≥ 1.0.80 | Valinnainen | REST API -päätepisteet |
| Kanban ≥ 1.0.23 | Valinnainen | Tunnisteväri korteissa, org-suodatin, monikieliset Tila-sarakkeen tunnisteet |
| Custom Fields | ✅ Yhteensopiva | — |
| Workflows | ✅ Yhteensopiva | — |
| Tags | ✅ Yhteensopiva | Tunnistesirut org-muokkauslomakkeessa; tunnistesidokset API:n kautta (`/organizations/{id}/tags`); tunnistepohjainen tikettien attribuointi |

---

## Konfigurointi

### Globaalit asetukset — **Manage → Organizations → System tab**

| Vaihtoehto | Kuvaus |
|------------|--------|
| Näytä tunnisteväri tikettisivulla | Org-tunnisteväri viestilistauksessa ja tikettinäkymässä |
| Näytä tunnisteväri Kanban-korteissa | Org-tunnisteväri Kanban-taulun korteissa |
| Attribuointilähde | `member` / `tag` / `tag_only` — kuinka tiketit attribuoidaan organisaatioille |
| Automaattinen cron-täydennys | Suorita täydennys 5 minuutin välein automaattisesti |
| Tilannekuvan näkyvyys | Näytä/piilota attribuointitiedot tiketin sivupalkissa |
| Portaalin kielenvalitsin | Ota kielenvalitsin käyttöön EUP-navigaatiopalkissa; valitse mitkä 19 lokaalista tarjotaan |

### Postilaatikkokohtaiset asetukset — **Mailbox Settings → OrgPortal**

Ohittaa globaalit arvot tietyn postilaatikon osalta.

| Vaihtoehto | Kuvaus |
|------------|--------|
| Näytä tunnisteväri tikettisivulla | Ota käyttöön/poista käytöstä tunnisteväri tässä postilaatikossa |
| Näytä tunnisteväri Kanban-korteissa | Ota käyttöön/poista käytöstä tunnisteväri tässä postilaatikossa |
| Näytä organisaatiolohko asiakasprofiilissa | Vaihda org-tietolohkon näkyvyyttä tiketin sivupalkissa |
| Yritystikettien tilasuodattimet | Kartoita Kanban-sarakkeet nimettyihin suodattimiin portaalissa; kielikohtaiset tunnisteet lokaalikytkimellä; järjestä vetämällä |

![Postilaatikkokohtaiset asetukset — tunnistevärin näkyvyys ja Kanban-tilasuodattimet monikielisillä tunnisteilla](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png)

---

## Käännökset

OrgPortal on täysin lokalisoitu **19 kielelle**:

| Kieli | Koodi | Kieli | Koodi |
|-------|-------|-------|-------|
| Englanti | `en` | Hollanti | `nl` |
| Ukraina | `uk` | Norja | `no` |
| Saksa | `de` | Tanska | `da` |
| Ranska | `fr` | Ruotsi | `sv` |
| Espanja | `es` | Suomi | `fi` |
| Italia | `it` | Portugali (BR) | `pt-BR` |
| Tšekki | `cs` | Portugali (PT) | `pt-PT` |
| Slovakia | `sk` | Romania | `ro` |
| Puola | `pl` | Yksinkertaistettu kiina | `zh-CN` |
| Georgia | `ka` | | |

Käännöstiedostot: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Ilmoitussähköpostimalleilla on sisäänrakennetut oletukset kaikille 19 kielelle.

### Kielenvalitsimen integraatio

OrgPortal sisältää sisäänrakennetun portaalin kielenvalitsimen (ota käyttöön kohdassa **System tab → Portal Language Switcher**). Se integroituu myös [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) -moduuliin — molemmat voivat olla aktiivisina samanaikaisesti.

Päällikön valitsema kieli koskee kaikkia OrgPortalin käyttöliittymämerkkijonoja ja tallennetaan heidän ilmoituskielenään — sähköpostit lähetetään automaattisesti heidän valitsemallaan kielellä.

> **Tekninen huomio:** `OrgPortalSetLocale`-väliohjelmisto soveltaa portaalin lokaalia uudelleen FreeScoutin `Localize`-väliohjelmiston jälkeen estääkseen sen nollautumisen järjestelmäoletukseen jokaisessa pyynnössä.

---

## Kuvakaappaukset

| | |
|---|---|
| ![Organisaatiolista](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png) | ![Organisaation muokkaus](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png) |
| *Organisaatiolista — tilasuodatin, reaaliaikahaku, väritunnisteet* | *Organisaation muokkaus — värinvalitsin, tunnistesirut, jäsentaulukko* |
| ![Järjestelmävälilehti](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png) | ![Asiakkaan muokkaus](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png) |
| *Järjestelmävälilehti — attribuointitilat, täydennys, kielenvalitsin* | *Asiakkaan muokkaus — org-kenttä automaattisella haulla* |
| ![Yritystiketit portaali](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png) | ![Portaalin vastaus](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png) |
| *Yritystiketit — taulukko, tekijäsuodatin, lukuindikaattorit* | *Portaalin tiketti — vastaus liitteillä, suljettu-banneri* |
| ![Portaalin organisaatioasetukset](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png) | ![Ilmoituskello](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png) |
| *Portaalin org-asetukset — Units- ja Members-välilehdet* | *Reaaliaikaiset ilmoituskellot pudotuspaneelilla* |
| ![Tilausmatriisi](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png) | ![Sähköpostimallit](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png) |
| *Ilmoitustilausmatriisi — yksikkö- ja jäsenkohtainen* | *Sähköpostimallit — lokaalivalitsin, Lataa oletus, Summernote* |
| ![Kanban-integraatio](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png) | ![Postilaatikkoasetukset](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png) |
| *Kanban — org-tunnisteet ja org-suodatinmodaali* | *Postilaatikkoasetukset — Kanban-suodattimet monikielisillä tunnisteilla* |
| ![API-dokumentaatio](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png) | |
| *Interaktiivinen API-dokumentaatio — ReDoc* | |

---

## Lisenssi

[MIT](LICENSE) — © 2026 ASTIN-UA
