# OrgPortal — Modulo di Gestione Organizzazioni B2B per FreeScout

[← Torna al README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — modulo FreeScout B2B" width="140" align="right">

**OrgPortal** è un modulo FreeScout che aggiunge una completa **gestione delle organizzazioni B2B** al tuo helpdesk: raggruppa i clienti in aziende, definisci gerarchie di reparti, offri ai manager aziendali un portale self-service e automatizza le notifiche — tutto all'interno di FreeScout, senza strumenti esterni.

> Stai cercando un modo per gestire gli account aziendali in FreeScout? Per offrire ai clienti corporate il loro portale di supporto? Per controllare quali ticket può vedere ogni contatto B2B in base al suo ruolo e reparto? OrgPortal risolve tutto questo.

**Compatibile con:** FreeScout 1.8.147+  
**Integrazioni opzionali:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Disponibile anche in:**
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

## Cosa aggiunge OrgPortal a FreeScout

FreeScout è costruito attorno ai clienti individuali — ogni email proviene da una persona, e non esiste un concetto integrato di azienda a cui quella persona appartiene. Questo va bene per gli helpdesk B2C. Per il B2B, non è sufficiente.

OrgPortal colma questa lacuna:

- **Account aziendali** — raggruppa i clienti in organizzazioni con nome, badge colorato, ambito mailbox e stato attivo/inattivo
- **Gerarchie di reparti** — suddividi le organizzazioni in unità strutturali (reparti, filiali, team); ogni membro è vincolato alla propria unità
- **Accesso basato sui ruoli** — `member` vede solo i propri ticket; `unit_manager` vede l'intera unità; `manager` vede l'intera organizzazione
- **Portale self-service aziendale** — i manager visualizzano tutti i ticket aziendali, rispondono, chiudono, riassegnano autori e gestiscono le preferenze di notifica senza contattare il tuo team
- **Attribuzione permanente dei ticket** — ogni ticket viene associato alla propria organizzazione al momento della creazione; i report storici sopravvivono alle modifiche dell'elenco clienti
- **Notifiche multilingua** — avvisi email automatici nella lingua di ciascun manager, con modelli per lingua e un editor WYSIWYG integrato
- **REST API** — sincronizza le iscrizioni dal tuo CRM, automatizza l'onboarding, gestisci i tag programmaticamente

---

## Organizzazioni

*Un unico posto per tutto ciò che riguarda un account aziendale.*

**Gestione → Organizzazioni** apre un'interfaccia a schede con tre sezioni: Organizzazioni, Modelli e Sistema.

### Elenco organizzazioni

- **Crea, modifica, elimina, attiva/disattiva** organizzazioni
- **Filtro stato** — passa tra Attivo / Inattivo / Tutti con un gruppo di pulsanti radio; filtra la tabella lato client istantaneamente
- **Ricerca in tempo reale** — inizia a filtrare da 2+ caratteri, senza ricaricare la pagina
- **Badge colorati** — selettore colore interattivo con 12 campioni e anteprima dal vivo del badge accanto al selettore; il badge appare su ogni ticket e carta Kanban
- Cliccando il badge o il conteggio ticket si apre una ricerca FreeScout filtrata per quell'organizzazione
- **Associazione mailbox** — le organizzazioni possono essere globali (tutte le mailbox) o limitate a una mailbox specifica
- **Colonna Tag** — mostra ✓/✗ se sono associati tag FreeScout all'organizzazione (richiede il modulo Tags); i tag vengono assegnati nel modulo di modifica con un widget a chip e ricerca con completamento automatico
- **Colonna conteggio ticket** — totale conversazioni per organizzazione; link cliccabile ai risultati di ricerca completi
- Colonna **conteggio membri**
- **Attiva / disattiva** — sospendi un account senza perdere la cronologia; richiede che Org Snapshot sia abilitato (il pulsante è disabilitato con un tooltip quando non lo è)
- **Elimina** — disponibile solo quando l'organizzazione ha 0 membri e 0 ticket (protezione di sicurezza)
- Tutte le azioni di eliminazione e disattivazione richiedono conferma

![Elenco organizzazioni — filtro stato, ricerca in tempo reale, badge colorati, tag, conteggi ticket](docs/screenshots/org-list.png)

### Modulo di modifica organizzazione

- **Nome** e **associazione mailbox**
- **Selettore colore** — 12 campioni con anteprima dal vivo del badge
- **Tag** — widget a chip: digita per cercare i tag FreeScout esistenti, clicca per aggiungere, × per rimuovere
- **Tabella membri** — per membro: nome, ruolo, unità strutturale, checkbox `can_manage_org` (concede accesso amministrativo alle organizzazioni senza diritti di amministratore completi), toggle attivo/inattivo
- **Pannello unità strutturali** — crea e rinomina le unità direttamente nel modulo di modifica; i membri vengono assegnati alle unità nella stessa vista
- **Aggiunta di un membro** — esegue automaticamente il backfill delle conversazioni esistenti non attribuite per quel cliente

![Modifica organizzazione — selettore colore, chip tag, tabella membri con ruoli e unità](docs/screenshots/org-edit.png)

### Integrazione profilo cliente

- **Campo organizzazione nel modulo di modifica cliente FreeScout** — ricerca con completamento automatico dal vivo per le organizzazioni; il menu a tendina del ruolo appare dopo la selezione di un'org; pulsante × per rimuovere
- Link di scelta rapida **"Visualizza ticket org"** nel modulo cliente
- **Blocco info org nella barra laterale del ticket admin** — nome organizzazione (link cliccabile alla pagina di modifica org), unità strutturale e ruolo del membro; visibilità configurabile per mailbox nelle impostazioni
- **Un'iscrizione attiva per cliente** — un cliente non può essere aggiunto a una seconda organizzazione mentre ha un'iscrizione attiva; le iscrizioni inattive/archiviate sono consentite

![Modifica cliente — campo organizzazione con completamento automatico e selettore ruolo](docs/screenshots/customer-org-field.png)

---

## Unità Strutturali — Controllo degli Accessi a Livello di Reparto

*Supporta grandi aziende con gerarchie interne complesse.*

Le organizzazioni possono essere suddivise in **unità strutturali** illimitate (reparti, filiali, uffici regionali, team di progetto):

- Crea, rinomina ed elimina le unità nel modulo di modifica org admin, o direttamente dal portale (solo manager globali)
- Assegna i membri alle unità — ogni membro appartiene a un'unità
- **L'eliminazione di un'unità** declassa automaticamente i suoi membri `unit_manager` a `member`

**Tre livelli di ruolo:**

| Ruolo | Ambito di accesso |
|-------|-------------------|
| `member` | Solo i propri ticket |
| `unit_manager` | Tutti i ticket all'interno della propria unità strutturale |
| `manager` (globale) | Tutti i ticket dell'intera organizzazione |

- I manager di unità hanno tutte le funzionalità del portale — risposte, allegati, riassegnazione autore, chiudi/riapri, gestione notifiche — limitate strettamente alla propria unità
- L'accesso ai ticket e la consegna delle notifiche vengono applicati ai confini dell'unità

![Modifica organizzazione — membri con ruoli e unità, pannello gestione unità](docs/screenshots/org-edit.png)

---

## Org Snapshot — Attribuzione Permanente dei Ticket

*Report storici affidabili anche quando cambia il tuo elenco clienti.*

Quando viene creato un ticket, OrgPortal registra il contesto dell'organizzazione come snapshot permanente:

- `org_id`, `org_unit_id` e `org_attributed_at` vengono scritti nella conversazione al momento della creazione
- **Immutabile** — se un cliente lascia successivamente un'organizzazione, i suoi ticket storici rimangono attribuiti a quell'org; i report non si rompono mai
- **L'aggiunta di un membro** attiva il backfill automatico delle conversazioni esistenti non attribuite di quel cliente

### Fonte di attribuzione — tre modalità

Configurabile in **Gestione → Organizzazioni → scheda Sistema**:

| Modalità | Comportamento |
|----------|---------------|
| `member` | Attribuisce il ticket all'organizzazione di cui l'autore del ticket è membro |
| `tag` | Attribuisce prima tramite tag FreeScout associato a un'org; ricade sull'iscrizione se nessun tag corrisponde |
| `tag_only` | Attribuisce esclusivamente tramite tag; l'iscrizione non viene utilizzata |

Le modalità `tag` e `tag_only` sono disabilitate quando il modulo Tags è inattivo.

### Strumenti di backfill

- **Barra di avanzamento** — mostra X / Y ticket attribuiti (%) con un indicatore "completo" al termine
- **Statistiche pre-volo** — prima di eseguire il backfill, un riepilogo mostra quanti ticket verranno attribuiti tramite tag vs. iscrizione vs. non corrispondenti
- Pulsante **Esegui backfill** — elabora fino a 2000 ticket per clic; il riepilogo dei risultati (by_tag / by_member / unmatched) viene mostrato dopo
- **Auto-cron** (`attribution_cron_enabled`) — pianifica il backfill ogni 5 minuti, 1000 ticket per esecuzione, senza sovrapposizioni
- **Reimposta attribuzione** — cancella tutti gli snapshot org (azione pericolosa, richiede conferma)
- Riga di comando: `php artisan orgportal:backfill-attribution`

![Scheda Sistema — fonte di attribuzione, barra di avanzamento, statistiche pre-volo, controlli backfill](docs/screenshots/attribution-settings.png)

---

## Integrazione Kanban

*Mantieni il tuo flusso di lavoro visivo allineato con i tuoi account B2B.*

- Badge organizzazione su ogni carta Kanban con il colore assegnato all'account
- **Filtro organizzazione** nel pannello filtri Kanban — modal multi-selezione con checkbox; lo stato del filtro persiste tra le navigazioni
- **Etichette filtro stato Kanban multilingua** — assegna a ogni colonna Kanban un nome personalizzato per lingua del portale; cambia le lingue con il selettore lingua nelle impostazioni per mailbox; trascina per riordinare i filtri
- Le etichette tradotte appaiono sia nella barra dei filtri del portale che nella colonna **Stato** della tabella ticket aziendali; catena di fallback: lingua salvata → inglese salvato → nome colonna originale

![Kanban — badge organizzazione sulle carte e modal filtro org](docs/screenshots/kanban-org.png)

---

## Controllo Accessi e Permessi

*Delega la gestione delle organizzazioni senza concedere accesso amministrativo.*

- **"Consenti gestione organizzazioni"** (`can_manage_org`) — due livelli:
  - Come **permesso utente** nelle impostazioni agente — consente a un team lead del supporto di gestire tutte le organizzazioni senza diritti di amministratore
  - Come **flag per membro** nel modulo di modifica organizzazione — consente a un membro org specifico di gestire quella singola organizzazione dal pannello admin
- **"Consenti gestione modelli di notifica"** — permesso granulare separato per la modifica dei modelli
- L'eliminazione delle organizzazioni rimane esclusivamente riservata agli amministratori
- L'accesso al portale è strettamente limitato per mailbox: un manager dell'Organizzazione A non può accedere all'Organizzazione B

![Permessi granulari — consenti gestione organizzazioni e modelli di notifica](docs/screenshots/user-permissions.png)

---

## Impostazioni di Sistema — Gestione → Organizzazioni → scheda Sistema

*Controlli solo admin per attribuzione, backfill e il selettore lingua del portale.*

La scheda **Sistema** è visibile solo agli amministratori FreeScout.

### Pannello 1: Attribuzione Ticket

Vedi [Org Snapshot](#org-snapshot--attribuzione-permanente-dei-ticket) sopra per la descrizione completa delle modalità di attribuzione, degli strumenti di backfill e dell'auto-cron.

### Pannello 2: Selettore Lingua del Portale

- **Abilita/disabilita** il selettore lingua nella navbar dell'End-User Portal
- **Scegli quali dei 19 locale** offrire (griglia di checkbox); tutti sono abilitati di default
- Quando abilitato, i manager possono cambiare la lingua del portale; la loro scelta viene salvata e utilizzata per le email di notifica
- Questo è il selettore lingua integrato di OrgPortal — funziona indipendentemente da qualsiasi modulo di cambio lingua di terze parti; entrambi possono coesistere

![Scheda Sistema — pannello selettore lingua del portale con checkbox delle lingue](docs/screenshots/system-settings.png)

---

## End-User Portal — Self-Service per Manager Aziendali *(opzionale)*

*Offri ai tuoi clienti B2B un portale dove gestiscono il rapporto di supporto della loro azienda — senza contattare il tuo team per ogni aggiornamento di stato.*

Richiede il modulo [End-User Portal](https://freescout.net/module/end-user-portal/).

### Dashboard Ticket Aziendali

Una sezione dedicata **Ticket Aziendali** nella navigazione del portale con una tabella ticket completa:

| Colonna | Descrizione |
|---------|-------------|
| **#** | ID ticket |
| **Oggetto** | Troncato con tooltip al passaggio del mouse |
| **Responsabile** | Agente di supporto assegnato |
| **Autore** | Cliente che ha aperto il ticket; clicca per filtrare per questo autore |
| **Stato** | Attivo / In attesa / Chiuso / Spam con icone |
| **Stato Kanban** | Nome colonna Kanban nella lingua corrente del portale (solo quando il modulo Kanban è attivo) |
| **Aggiornato** | Data e ora dell'ultima risposta |

**Due indicatori di stato lettura indipendenti per riga** — questi tracciano due persone diverse e vengono mostrati simultaneamente:

| Indicatore | Stato lettura di chi | Cosa significa |
|------------|---------------------|----------------|
| **Riga in grassetto** | Il manager che visualizza il portale | Il manager ha notifiche non lette per questa conversazione — è successo qualcosa che non ha ancora visto |
| **Icona 👁 Occhio** | L'autore del ticket (il cliente che lo ha inviato) | L'autore non ha ancora aperto l'ultima risposta dell'agente — utile per sapere se il cliente ha effettivamente visto la risposta |

Questi due stati sono completamente indipendenti: una riga può essere in grassetto (manager non ha letto) mentre l'occhio è assente (autore ha già letto), o viceversa. Il manager vede entrambi contemporaneamente, ottenendo un quadro completo di cosa sta succedendo su entrambi i lati del ticket senza aprirlo.

**Filtro autore** — cliccando il nome di un autore si attiva un filtro; in cima alla tabella appare un banner che mostra il nome dell'autore attivo con un link × per cancellare il filtro.

Sono inclusi sia la tabella desktop che un **layout a schede mobile** responsive; si cambiano automaticamente in base alla larghezza dello schermo.

Il modello della barra dei filtri supporta l'**override** tramite `enduserportal::partials.tickets_filters` — posiziona una vista personalizzata in quel percorso per sostituire la barra dei filtri predefinita di OrgPortal mantenendo tutte le altre funzionalità.

![Ticket Aziendali — tabella completa con indicatori di lettura, banner filtro autore, filtri stato](docs/screenshots/portal-tickets.png)

### Azioni Ticket nel Portale

I manager possono agire direttamente — senza bisogno di contattare il supporto:

- **Rispondi con allegati** — drag & drop, più file per risposta; nomi degli allegati e dimensioni dei file mostrati su ogni thread
- **Chiudi ticket** — una nuova risposta lo riapre automaticamente; un banner informa il manager di questo quando il ticket è chiuso
- **Cambia autore del ticket** — riassegna un ticket a un altro membro dell'organizzazione
- **Filtra per unità** — i manager globali filtrano l'elenco ticket per unità strutturale
- **Filtra per stato Kanban** — configurabile per mailbox, etichette mostrate nella lingua corrente del portale

![Vista ticket portale — modulo risposta con allegati drag & drop e banner ticket chiuso](docs/screenshots/portal-reply.png)

### Tracciamento Visualizzazione Manager

- Una nota **"visualizzato"** appare sotto le risposte degli agenti nella vista ticket admin quando un manager apre il ticket nel portale
- Mostra nome del manager, ruolo (Manager organizzazione / Manager unità) e tempo trascorso
- Le visualizzazioni di manager globale e manager di unità vengono tracciate e mostrate indipendentemente — stessa UX del "Cliente ha visualizzato" nativo di FreeScout

![Tracciamento visualizzazione manager — nota 'visualizzato' appare sotto la risposta dell'agente nella vista ticket admin](docs/screenshots/manager-viewed.png)

---

## Campanello Notifiche in Tempo Reale *(opzionale)*

*Tieni i manager informati nel momento in cui qualcosa accade con i ticket della loro azienda.*

Richiede il modulo [End-User Portal](https://freescout.net/module/end-user-portal/).

- 🔔 Icona campanello con badge conteggio non letti dal vivo nella navbar EUP — si riposiziona automaticamente su mobile (accanto al menu hamburger)
- Notifiche per: **nuovo ticket**, **risposta agente**, **risposta cliente** — per tutti i ruoli manager
- Pannello a tendina con notifiche raggruppate per data: nome attore, tipo evento, numero ticket, anteprima messaggio, timestamp
- **Segna automaticamente come letto** quando il manager apre il ticket
- Segna le singole notifiche come lette tramite ×; **Segna tutte come lette** nell'intestazione del pannello
- Poll ogni 15 secondi; si aggiorna alla navigazione avanti/indietro del browser (compatibile con bfcache)

![Campanello notifiche in tempo reale — tendina con notifiche non lette raggruppate](docs/screenshots/portal-bell.png)

---

## Abbonamenti alle Notifiche *(opzionale)*

*Lascia che i manager decidano cosa ascoltare — niente di più, niente di meno.*

- **Matrice di abbonamento visiva** nella scheda "Notifiche" nelle Impostazioni Organizzazione del portale
- **Tre tipi di evento:** Nuovo ticket · Risposta agente · Risposta cliente
- **Due livelli di ambito:** Intera organizzazione (manager globali) · Unità strutturali individuali
- I membri senza un'unità sono raggruppati in una riga espandibile separata **"Nessuna unità"**
- **Override per membro** — espandi qualsiasi riga unità per rivelare i singoli membri e attivare/disattivare i loro abbonamenti inline; i manager di unità con ruolo limitato sono etichettati di conseguenza
- **Logica a cascata in entrambe le direzioni:**
  - Abilitare "Intera organizzazione" → abilita tutte le unità e tutti i membri
  - Abilitare un'unità → abilita tutti i suoi membri
  - Disabilitare un membro → riconcilia automaticamente le checkbox dell'unità e dell'organizzazione
- I manager globali gestiscono tutti i membri; i manager di unità gestiscono solo la propria unità
- Le notifiche utilizzano il driver mail della mailbox corrispondente

![Matrice di abbonamento alle notifiche — toggle per unità e per membro](docs/screenshots/portal-subscriptions.png)

---

## Impostazioni Organizzazione del Portale

*I manager configurano la struttura della propria organizzazione senza accesso amministrativo.*

**Impostazioni Organizzazione** nella navigazione del portale ha tre schede:

### Scheda Notifiche

La matrice di abbonamento descritta sopra.

### Scheda Unità *(solo manager globali)*

- **Crea unità** — modulo inline con campo nome
- **Rinomina unità** — modifica inline direttamente nella riga della tabella
- **Elimina unità** — pulsante con conferma; i manager di unità vengono automaticamente declassati a membro
- Conteggio membri mostrato per unità

### Scheda Membri

- Tabella di tutti i membri dell'organizzazione: nome, unità strutturale, ruolo, badge stato attivo/inattivo
- Etichetta **"Manager globale"** mostrata accanto al nome del membro dove applicabile
- Checkbox **Mostra disattivati** — appare solo quando esistono membri inattivi; nascosta di default
- I **manager globali** possono aggiornare l'unità e il ruolo di qualsiasi membro con un modulo inline (selezione unità + selezione ruolo + Applica)
- **I manager globali non possono promuovere un membro a manager globale** dal portale — questo richiede accesso amministrativo
- Pulsante **Attiva / disattiva** per membro con conferma per la disattivazione

![Impostazioni Organizzazione Portale — schede Unità e Membri](docs/screenshots/portal-settings.png)

---

## Modelli Email di Notifica Multilingua *(opzionale)*

*I tuoi clienti corporate ricevono email di supporto nella propria lingua — automaticamente, senza alcuno sforzo manuale.*

Configurabile in **Gestione → Organizzazioni → scheda Modelli** (visibile agli utenti con il permesso "gestisci modelli").

- **Modelli per lingua** — oggetto e corpo separati per ogni lingua del portale; passa tra di essi con il menu a tendina della lingua; i valori vengono scambiati in memoria senza ricaricare la pagina
- **Pannelli comprimibili** per tipo di evento (Nuovo ticket / Risposta agente / Risposta cliente) — l'editor Summernote si inizializza in modo pigro quando un pannello viene aperto
- Pulsante **Carica Predefinito** in ogni pannello — ripristina il modello integrato per la lingua correntemente selezionata (ricade sul predefinito inglese integrato se non esiste un predefinito specifico per la lingua)
- **Editor WYSIWYG Summernote** per la composizione di email HTML ricche
- **Selettore variabili macro** — inserisci segnaposto nell'oggetto o nel corpo con un clic; la posizione del cursore viene preservata nel campo oggetto
- **19 modelli predefiniti integrati** — pronti all'uso; nessuna configurazione necessaria

**Variabili macro disponibili:**

| Variabile | Descrizione |
|-----------|-------------|
| `{manager_name}` | Nome del manager che riceve la notifica |
| `{author_name}` | Cliente che ha creato o risposto al ticket |
| `{org_name}` | Nome organizzazione |
| `{unit_name}` | Nome unità strutturale |
| `{subject}` | Oggetto del ticket |
| `{ticket_number}` | ID ticket |
| `{ticket_url}` | Link diretto al ticket nel portale |
| `{ticket_text}` | Testo completo del messaggio iniziale (HTML) |
| `{reply_text}` | Testo completo dell'ultima risposta (HTML) |
| `{created_date}` | Data di creazione del ticket |
| `{created_time}` | Ora di creazione del ticket |
| `{created_datetime}` | Data e ora di creazione del ticket |
| `{reply_date}` | Data della risposta |
| `{reply_time}` | Ora della risposta |
| `{reply_datetime}` | Data e ora della risposta |

**Catena di fallback:** modello lingua salvato → modello lingua integrato → modello inglese salvato → modello inglese integrato

La lingua delle notifiche è determinata dalla selezione della lingua del portale di ciascun manager, salvata automaticamente quando usano il selettore lingua.

![Modelli email — pannelli comprimibili per lingua, pulsante Carica Predefinito, editor Summernote](docs/screenshots/admin-templates.png)

---

## REST API *(opzionale)*

*Integra OrgPortal nel tuo CRM, ERP o flusso di lavoro di onboarding clienti.*

Richiede il modulo [API and Webhooks](https://freescout.net/module/api-webhooks/).

- CRUD completo per organizzazioni, unità strutturali, iscrizioni clienti e tag
- **Campi organizzazione:** `name`, `color`, `mailboxId`, `isActive` — tutti leggibili e aggiornabili tramite API
- **Sub-resource membri** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — aggiorna ruolo, unità, `canManageOrg` e flag `isActive` per membro indipendentemente senza toccare il resto dell'iscrizione
- **Sub-resource tag** — `GET/PUT /api/organizations/{id}/tags` — elenca o sostituisce completamente le associazioni tag (richiede il modulo Tags; restituisce `503` se inattivo)
- Autenticazione tramite header `X-FreeScout-API-Key` o parametro query `api_key`
- **Documentazione ReDoc** interattiva in **Gestione → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Riferimento API completo → [docs/api/README.md](docs/api/README.md)**

![Documentazione API interattiva — ReDoc con tutti gli endpoint OrgPortal](docs/screenshots/api-docs.png)

---

## Installazione

1. Copia la cartella `OrgPortal` in `Modules/` della tua installazione FreeScout
2. Vai su **Gestione → Moduli → OrgPortal → Attiva**
3. Esegui le migrazioni:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Svuota la cache:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Il supporto della lingua georgiana** viene distribuito automaticamente al primo avvio — non è necessaria alcuna copia manuale di file.

---

## Aggiornamenti Automatici

OrgPortal supporta **aggiornamenti con un clic** tramite il meccanismo di aggiornamento moduli integrato di FreeScout.

> **Richiede FreeScout 1.8.170 o successivo.** Nelle versioni precedenti, aggiorna manualmente sostituendo la cartella `OrgPortal` con l'ultimo ZIP della release.

Quando è disponibile una nuova versione, appare un banner in **Gestione → Moduli**. Clicca **Aggiorna ora** — FreeScout scarica e installa automaticamente l'ultima versione.

---

## Compatibilità dei Moduli

| Modulo | Stato | Note |
|--------|-------|------|
| End-User Portal ≥ 1.0.85 | Opzionale | Portale manager, campanello notifiche, abbonamenti |
| API and Webhooks ≥ 1.0.80 | Opzionale | Endpoint REST API |
| Kanban ≥ 1.0.23 | Opzionale | Badge sulle carte, filtro org, etichette colonna Stato multilingua |
| Custom Fields | ✅ Compatibile | — |
| Workflows | ✅ Compatibile | — |
| Tags | ✅ Compatibile | Chip tag nel modulo modifica org; associazioni tag tramite API (`/organizations/{id}/tags`); attribuzione ticket basata su tag |

---

## Configurazione

### Impostazioni Globali — **Gestione → Organizzazioni → scheda Sistema**

| Opzione | Descrizione |
|---------|-------------|
| Mostra badge nella pagina ticket | Badge org nell'elenco conversazioni e nella vista ticket |
| Mostra badge nelle carte Kanban | Badge org nelle carte del Kanban board |
| Fonte attribuzione | `member` / `tag` / `tag_only` — come i ticket vengono attribuiti alle organizzazioni |
| Backfill auto-cron | Esegui il backfill ogni 5 minuti automaticamente |
| Visibilità snapshot | Mostra/nascondi i dati di attribuzione nella barra laterale del ticket |
| Selettore Lingua Portale | Abilita il selettore lingua nella navbar EUP; scegli quali dei 19 locale offrire |

### Impostazioni per Mailbox — **Impostazioni Mailbox → OrgPortal**

Sovrascrive i valori globali per la mailbox specifica.

| Opzione | Descrizione |
|---------|-------------|
| Mostra badge nella pagina ticket | Abilita/disabilita badge per questa mailbox |
| Mostra badge nelle carte Kanban | Abilita/disabilita badge per questa mailbox |
| Mostra blocco organizzazione nel profilo cliente | Toggle blocco info org nella barra laterale del ticket |
| Filtri stato ticket aziendali | Mappa le colonne Kanban a filtri nominati nel portale; etichette per lingua con selettore lingua; trascina per riordinare |

![Impostazioni per mailbox — visibilità badge e filtri stato Kanban con etichette multilingua](docs/screenshots/mailbox-settings.png)

---

## Traduzioni

OrgPortal è completamente localizzato in **19 lingue**:

| Lingua | Codice | Lingua | Codice |
|--------|--------|--------|--------|
| Inglese | `en` | Olandese | `nl` |
| Ucraino | `uk` | Norvegese | `no` |
| Tedesco | `de` | Danese | `da` |
| Francese | `fr` | Svedese | `sv` |
| Spagnolo | `es` | Finlandese | `fi` |
| Italiano | `it` | Portoghese (BR) | `pt-BR` |
| Ceco | `cs` | Portoghese (PT) | `pt-PT` |
| Slovacco | `sk` | Rumeno | `ro` |
| Polacco | `pl` | Cinese semplificato | `zh-CN` |
| Georgiano | `ka` | | |

File di traduzione: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

I modelli email di notifica hanno valori predefiniti integrati per tutte le 19 lingue.

### Integrazione Selettore Lingua

OrgPortal include un selettore lingua del portale integrato (abilita in **scheda Sistema → Selettore Lingua Portale**). Si integra anche con [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — entrambi possono essere attivi simultaneamente.

La lingua selezionata da un manager si applica a tutte le stringhe UI di OrgPortal e viene salvata come lingua delle notifiche — le email vengono inviate automaticamente nella lingua scelta.

> **Nota tecnica:** il middleware `OrgPortalSetLocale` riapplica la lingua del portale dopo il middleware `Localize` di FreeScout per evitare che venga reimpostata al valore predefinito di sistema ad ogni richiesta.

---

## Screenshot

| | |
|---|---|
| ![Elenco organizzazioni](docs/screenshots/org-list.png) | ![Modifica organizzazione](docs/screenshots/org-edit.png) |
| *Elenco organizzazioni — filtro stato, ricerca in tempo reale, badge colorati* | *Modifica organizzazione — selettore colore, chip tag, tabella membri* |
| ![Scheda Sistema](docs/screenshots/system-settings.png) | ![Modifica cliente](docs/screenshots/customer-org-field.png) |
| *Scheda Sistema — modalità attribuzione, backfill, selettore lingua* | *Modifica cliente — campo org con completamento automatico* |
| ![Portale Ticket Aziendali](docs/screenshots/portal-tickets.png) | ![Risposta portale](docs/screenshots/portal-reply.png) |
| *Ticket Aziendali — tabella, filtro autore, indicatori di lettura* | *Ticket portale — risposta con allegati, banner chiuso* |
| ![Impostazioni Organizzazione Portale](docs/screenshots/portal-settings.png) | ![Campanello notifiche](docs/screenshots/portal-bell.png) |
| *Impostazioni Org Portale — schede Unità e Membri* | *Campanello notifiche in tempo reale con tendina* |
| ![Matrice abbonamenti](docs/screenshots/portal-subscriptions.png) | ![Modelli email](docs/screenshots/admin-templates.png) |
| *Matrice abbonamenti notifiche — per unità, per membro* | *Modelli email — selettore lingua, Carica Predefinito, Summernote* |
| ![Integrazione Kanban](docs/screenshots/kanban-org.png) | ![Impostazioni mailbox](docs/screenshots/mailbox-settings.png) |
| *Kanban — badge org e modal filtro org* | *Impostazioni per mailbox — filtri Kanban con etichette multilingua* |
| ![Documentazione API](docs/screenshots/api-docs.png) | |
| *Documentazione API interattiva — ReDoc* | |

---

## Licenza

[MIT](LICENSE) — © 2026 ASTIN-UA
