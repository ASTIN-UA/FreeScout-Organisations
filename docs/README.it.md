# OrgPortal — Portale Organizzazioni per FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Un modulo FreeScout che aggiunge il concetto di **Organizzazioni** (aziende/team) ai clienti, estende il Portale Utenti Finali per i manager e visualizza un distintivo di organizzazione su ticket e schede Kanban.

**Versione minima FreeScout:** 1.8.147  
**Dipendenze:** nessuna richiesta  
**Opzionale:** [Portale Utenti Finali](https://freescout.net/module/end-user-portal/), [API e Webhook](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Lingua:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Caratteristiche

### Gestione organizzazioni (admin)
- **Gestione → Organizzazioni** — CRUD completo: crea, modifica, elimina organizzazioni
- **Vincolamento cassetta postale** — un'organizzazione può essere **globale** (visibile in tutte le cassette postali) o **vincolata a una cassetta postale specifica**; l'etichetta corrispondente viene visualizzata nell'elenco delle organizzazioni
- Assegna clienti alle organizzazioni con selezione ruolo: `membro` o `gestore`
- **Modifica ruolo membro** direttamente nella tabella (senza rimuovere e aggiungere di nuovo)
- Ricerca con autocompletamento di clienti per nome o email; i clienti già in qualsiasi organizzazione sono esclusi dai risultati
- L'email del membro viene visualizzata sotto il nome nella tabella dei membri
- Un cliente — un'organizzazione (applicato a livello di DB e API)
- **Colore distintivo** — tavolozza visiva con 12 colori nel modulo modifica organizzazione; il predefinito è grigio

### Permessi utente
- Nuovo permesso **"Consenti gestione organizzazioni"** — gli utenti non-admin con questo permesso ottengono accesso alle pagine di elenco, creazione e modifica delle organizzazioni
- Eliminare organizzazioni rimane esclusivo degli admin

### Scheda cliente
- Campo **Organizzazione** nel modulo modifica cliente — seleziona organizzazione e ruolo
- Pulsante **Ticket Organizzazione** — apre una ricerca di tutti i ticket dell'organizzazione

### Distintivo organizzazione su ticket
- Visualizzato sotto l'oggetto sulla pagina del ticket e prima del nome nell'elenco conversazioni
- Cliccabile — apre una ricerca di tutti i ticket di questa organizzazione
- Il colore del distintivo è determinato dall'impostazione dell'organizzazione (predefinito grigio)
- Attiva/disattiva **per cassetta postale** tramite **Impostazioni Cassetta Postale → OrgPortal**; il valore globale viene utilizzato come fallback

### Distintivo organizzazione su schede Kanban
- Visualizzato dopo il contatore messaggi su ogni scheda
- Cliccabile — conduce alla ricerca dell'organizzazione
- Il colore corrisponde all'impostazione dell'organizzazione
- Filtro **Organizzazione** integrato nel menu a discesa dei filtri Kanban standard: modale con caselle di controllo, simile al filtro dei tag; lo stato viene preservato tra le navigazioni
- Attiva/disattiva **per cassetta postale** tramite **Impostazioni Cassetta Postale → OrgPortal**

### Filtro ricerca organizzazione
- Estende la ricerca FreeScout con un filtro **Organizzazione**
- Mostra tutti i ticket dei clienti che appartengono all'organizzazione selezionata

### Portale Utenti Finali — accesso manager *(opzionale)*

Un manager di organizzazione ottiene accesso esteso attraverso EUP:

- Elemento **Ticket Aziendali** nella navigazione del portale
- Tabella ticket aziendali con colonne:
  - **#** e **Oggetto** con truncamento ellissi e tooltip al passaggio del mouse
  - **Responsabile** — agente assegnato
  - **Autore** — il cliente che ha aperto il ticket; fare clic filtra i ticket per autore all'interno dell'organizzazione
  - **Stato** — Attivo / In sospeso / Chiuso / Spam con icone
  - **Fase** — nome della colonna Kanban (con etichetta personalizzata se configurata); mostrato solo se il modulo Kanban è attivo
  - **Aggiornato** — data e ora dell'ultima risposta
- Ricerca per oggetto del ticket
- Filtri per stati Kanban (configurabili tramite **Impostazioni Cassetta Postale → OrgPortal**)
- Risposta a ticket con supporto **allegati** (trascinamento, più file)
- **Chiudi ticket** — il manager può chiudere un ticket; una nuova risposta lo riapre automaticamente
- Modifica autore del ticket — riassegna un ticket a un altro membro dell'organizzazione
- Pagina **Impostazioni Org** per configurare le notifiche email
- L'accesso ai ticket è **strettamente limitato alla cassetta postale corrente** (organizzazione copiata in un'altra cassetta postale — portale 403)

### Notifiche email *(opzionale)*
- I manager con l'opzione abilitata ricevono un'email quando viene creato un nuovo ticket da qualsiasi membro dell'organizzazione
- Utilizza il driver di posta della cassetta postale corrispondente

### Impostazioni cassetta postale

**Impostazioni Cassetta Postale → OrgPortal** (per cassetta postale):

| Opzione | Descrizione |
|---------|-------------|
| Mostra distintivo sulla pagina ticket | Attiva/disattiva distintivo all'interno di questa cassetta postale |
| Mostra distintivo su schede Kanban | Attiva/disattiva distintivo all'interno di questa cassetta postale |
| Filtri stato ticket aziendali | Seleziona colonne Kanban visualizzate come caselle di controllo sulla pagina dei ticket; etichetta personalizzata per ogni filtro |

---

### REST API *(opzionale, richiede API e Webhook)*

OrgPortal espone un'API REST completa per gestire organizzazioni, unità strutturali e appartenenze dei clienti — autenticazione tramite l'header `X-FreeScout-API-Key` o il parametro di query `api_key`.

📖 **Riferimento API completo → [docs/api/README.it.md](api/README.it.md)** (tutti gli endpoint, esempi di richiesta/risposta, codici di errore)

La documentazione interattiva ReDoc è disponibile anche in **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

---

## Installazione

1. Copia la cartella `OrgPortal` in `Modules/` del tuo FreeScout
2. Nel pannello di amministrazione: **Gestione → Moduli → OrgPortal → Attiva**
3. Esegui le migrazioni:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Cancella la cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Aggiornamenti

OrgPortal supporta **gli aggiornamenti automatici** tramite il meccanismo di aggiornamento dei moduli integrato di FreeScout.

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

Quando è disponibile una nuova versione, un banner apparirà nella pagina **Gestione → Moduli**. Fai clic su **Aggiorna ora** — FreeScout scaricherà e installerà l'ultima versione automaticamente.

Nessuna copia manuale di file richiesta.

---

## Compatibilità moduli

| Modulo | Stato |
|--------|--------|
| Portale Utenti Finali ≥ 1.0.85 | Opzionale — funzioni del portale per manager |
| API e Webhook ≥ 1.0.80 | Opzionale — endpoint API REST |
| Kanban ≥ 1.0.23 | Opzionale — distintivo, filtro, colonna "Fase" nei ticket aziendali |
| Campi Personalizzati | Compatibile |
| Flussi di Lavoro | Compatibile |
| Tag | Compatibile |

---

## Configurazione

### Globale (**Gestione → Impostazioni OrgPortal**)

| Opzione | Predefinito |
|---------|------------|
| Mostra distintivo sulla pagina ticket | ✅ |
| Mostra distintivo su schede Kanban | ✅ |

### Per cassetta postale (**Impostazioni Cassetta Postale → OrgPortal**)

Sovrascrive i valori globali per la cassetta postale specifica.

| Opzione | Descrizione |
|---------|-------------|
| Mostra distintivo sulla pagina ticket | Distintivo nell'elenco conversazioni e sulla pagina ticket |
| Mostra distintivo su schede Kanban | Distintivo su schede Kanban |
| Filtri stato ticket aziendali | Colonne Kanban come caselle di controllo sulla pagina dei ticket aziendali; etichetta personalizzata visibile agli utenti del portale |

---

## Traduzioni

Lingue supportate: **English** (`en`), **Українська** (`uk`), **Română** (`ro`), **Georgian** (`ka`), **Deutsch** (`de`), **Français** (`fr`), **Español** (`es`), **Italiano** (`it`), **Čeština** (`cs`), **Slovenčina** (`sk`), **Polski** (`pl`), **Nederlands** (`nl`), **Norsk** (`no`), **Dansk** (`da`), **Svenska** (`sv`), **Suomi** (`fi`), **Português BR** (`pt-BR`), **Português PT** (`pt-PT`), **中文 (简体)** (`zh-CN`).

File: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### Integrazione EUPSWLANG

Il modulo funziona correttamente con [Cambio Lingua EUP](https://freescout.net/module/eup-sw-lang/): la lingua selezionata nel portale si applica anche alle stringhe di OrgPortal.

Affinché una lingua appaia nell'elenco EUPSWLANG, il file corrispondente `Modules/EndUserPortal/Resources/lang/{locale}.json` deve esistere. I file per **Română** (`ro`) sono inclusi nel pacchetto; **Georgian** (`ka`) è supportato solo nella sezione admin (nessun supporto di sistema nel core FreeScout).

> **Dettaglio tecnico:** il middleware `ReapplyEupLocale` (registrato per ultimo nel gruppo di route del portale) ripristina la locale dopo il middleware `Localize` di FreeScout, che altrimenti ripristinerebbe la lingua del portale al valore predefinito del sistema.

---

## Licenza

[MIT](../LICENSE) — © 2026 ASTIN-UA
