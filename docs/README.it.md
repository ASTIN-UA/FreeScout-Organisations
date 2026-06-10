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

Autenticazione — intestazione `X-FreeScout-API-Key` o parametro query `api_key`.

> **Documentazione interattiva** (ReDoc) è disponibile nella pagina **Gestione → API & Webhook** (link "OrgPortal API Docs") o direttamente in `/orgportal/admin/api-docs`.

| Metodo | Endpoint | Descrizione |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Elenca organizzazioni (paginazione, filtro cassetta postale) |
| `POST` | `/api/organizations` | Crea un'organizzazione |
| `GET` | `/api/organizations/{id}` | Ottieni organizzazione con membri |
| `PUT` | `/api/organizations/{id}` | Aggiorna organizzazione |
| `DELETE` | `/api/organizations/{id}` | Elimina organizzazione |
| `GET` | `/api/customers/{id}/organization` | Organizzazione del cliente |
| `PUT` | `/api/customers/{id}/organization` | Imposta/aggiorna iscrizione cliente |
| `DELETE` | `/api/customers/{id}/organization` | Rimuovi cliente dall'organizzazione |

#### Codici di risposta

| Codice | Significato |
|--------|------------|
| `200` | Successo o nessuna operazione (nulla è cambiato) |
| `201` | Risorsa creata; intestazione `Resource-ID` contiene l'ID |
| `400` | Errore di validazione — dettagli in `_embedded.errors` |
| `401` | Chiave API non valida o mancante |
| `404` | Risorsa non trovata |
| `409` | Conflitto — il cliente appartiene già a un'altra organizzazione |

---

#### GET /api/organizations

**Parametri query**

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|:----------:|-------------|
| `page` | integer | `1` | Numero di pagina |
| `pageSize` | integer | `25` | Record per pagina (max 100) |
| `mailboxId` | integer | — | Filtro cassetta postale: restituisce organizzazioni globali + quelle vincolate a questa cassetta postale |

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

**Corpo della richiesta**

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Nome dell'organizzazione (max 255 caratteri, univoco) |
| `mailboxId` | integer\|null | — | ID cassetta postale o `null` / omettere per organizzazione globale |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(intestazione `Resource-ID: 1`)*
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

**Corpo della richiesta**

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Nuovo nome dell'organizzazione (max 255 caratteri, univoco) |
| `mailboxId` | integer\|null | — | Nuova cassetta postale; `null` — rendi globale; omettere — lascia invariato |

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

---

#### DELETE /api/organizations/{id}

**200 OK** *(tutti i membri vengono eliminati a cascata)*
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

Assegna un cliente a un'organizzazione o aggiorna il suo ruolo. **Un cliente — un'organizzazione**: se il cliente è già membro di *un'altra* organizzazione, la richiesta viene rifiutata con `409 Conflict`. Per trasferire — prima rimuovi l'iscrizione corrente tramite `DELETE`.

**Corpo della richiesta**

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | ID dell'organizzazione |
| `role` | string | — | `"member"` (predefinito) o `"manager"` |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager"}'
```

**201 Created** *(nuova iscrizione)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(ruolo aggiornato o nessuna operazione)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(cliente già in un'altra organizzazione)*
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

Proprietaria — ASTIN UA.
