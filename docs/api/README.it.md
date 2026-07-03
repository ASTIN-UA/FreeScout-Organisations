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

*Facoltativo — richiede il modulo [API and Webhooks](https://freescout.net/module/api-webhooks/).*

Autenticazione — intestazione `X-FreeScout-API-Key` o parametro di query `api_key`.

> **Documentazione interattiva** (ReDoc) è disponibile nella pagina **Gestisci → API e Webhook** (collegamento "OrgPortal API Docs") oppure direttamente su `/orgportal/admin/api-docs`.

## Endpoint

| Metodo | Endpoint | Descrizione |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Elenca le organizzazioni (impaginazione, filtro cassetta postale) |
| `POST` | `/api/organizations` | Crea un'organizzazione |
| `GET` | `/api/organizations/{id}` | Ottiene l'organizzazione con i membri e le unità |
| `PUT` | `/api/organizations/{id}` | Aggiorna l'organizzazione (nome, colore, cassetta postale, isActive) |
| `DELETE` | `/api/organizations/{id}` | Elimina l'organizzazione |
| `GET` | `/api/organizations/{id}/members` | Elenca i membri dell'organizzazione |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Ottiene un singolo membro |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Aggiorna il membro (ruolo, unità, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Rimuove un membro |
| `GET` | `/api/organizations/{id}/tags` | Elenca i binding dei tag (richiede il modulo Tags) |
| `PUT` | `/api/organizations/{id}/tags` | Sostituisce tutti i binding dei tag (richiede il modulo Tags) |
| `GET` | `/api/organizations/{id}/units` | Elenca le unità strutturali |
| `POST` | `/api/organizations/{id}/units` | Crea un'unità strutturale |
| `PUT` | `/api/units/{unitId}` | Rinomina un'unità |
| `DELETE` | `/api/units/{unitId}` | Elimina un'unità (i membri vengono non assegnati, i gestori dell'unità vengono degradati) |
| `GET` | `/api/customers/{id}/organization` | Appartenenza all'organizzazione del cliente |
| `PUT` | `/api/customers/{id}/organization` | Imposta/aggiorna l'appartenenza del cliente |
| `DELETE` | `/api/customers/{id}/organization` | Rimuove il cliente dall'organizzazione |

## Codici di risposta

| Codice | Significato |
|--------|-------------|
| `200` | Successo |
| `201` | Risorsa creata; l'intestazione `Resource-ID` contiene l'ID |
| `400` | Errore di convalida — i dettagli sono in `_embedded.errors` |
| `401` | Chiave API non valida o mancante |
| `404` | Risorsa non trovata |
| `409` | Conflitto — il cliente ha già un'appartenenza attiva in un'altra organizzazione |
| `422` | Violazione di una regola commerciale — ad es. eliminazione di un'organizzazione che ha ancora membri o ticket |
| `503` | Il modulo richiesto (ad es. Tags) non è attivo |

---

## Organizzazioni

### GET /api/organizations

**Parametri di query**

| Parametro | Tipo | Predefinito | Descrizione |
|-----------|------|:-------:|-------------|
| `page` | numero intero | `1` | Numero di pagina |
| `pageSize` | numero intero | `25` | Record per pagina (max 100) |
| `mailboxId` | numero intero | — | Filtro cassetta postale: restituisce le organizzazioni globali + quelle associate a questa cassetta postale |

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

**Corpo della richiesta**

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `name` | stringa | ✅ | Nome dell'organizzazione (max 255 caratteri, univoco) |
| `mailboxId` | numero intero\|null | — | ID della cassetta postale o `null` / ometti per un'organizzazione globale |

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
  "color": null,
  "isActive": true,
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Restituisce l'organizzazione con i suoi **membri** e **unità** incorporati.

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

**Campi membro**

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `unitId` | numero intero\|null | Unità strutturale a cui appartiene il membro, oppure `null` per l'intera organizzazione |
| `role` | stringa | `"member"` o `"manager"`. Un **gestore di unità** è `role: "manager"` con `unitId` non-null; un **gestore globale** è `role: "manager"` con `unitId: null`. La stringa `"unit_manager"` non esiste nell'API — passarla restituisce 400. |
| `canManageOrg` | booleano | Se questo gestore può promuovere altri a gestore globale dal portale |
| `isActive` | booleano | Appartenenza attiva; i membri inattivi non ricevono assegnazioni di ticket o notifiche |
| `notifyOnNewTicket` | booleano | Flag di notifica per nuovo ticket per membro |

---

### PUT /api/organizations/{id}

**Corpo della richiesta**

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `name` | stringa | ✅ | Nuovo nome dell'organizzazione (max 255 caratteri, univoco) |
| `color` | stringa\|null | — | Colore del badge come esadecimale (`"#ff0000"`), `null` per ripristinare il grigio predefinito; ometti per mantenere quello attuale |
| `mailboxId` | numero intero\|null | — | Nuova cassetta postale; `null` — rendi globale; ometti — lascia invariato |
| `isActive` | booleano | — | `false` per disattivare l'organizzazione; ometti per mantenere quello attuale |

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

Bloccato quando l'organizzazione ha membri attivi o ticket. Rimuovere prima tutti i membri e riassegnare/eliminare tutti i ticket.

**200 OK**
```json
{"success": true, "message": "Organization deleted."}
```

**422 Unprocessable Entity** *(organization has members)*
```json
{"message": "Cannot delete an organization that has members. Remove all members first.", "_embedded": {"errors": [{"members_count": 3}]}}
```

**422 Unprocessable Entity** *(organization has tickets)*
```json
{"message": "Cannot delete an organization that has tickets. Reassign or delete all tickets first.", "_embedded": {"errors": [{"conversations_count": 12}]}}
```

---

## Membri dell'organizzazione

### GET /api/organizations/{id}/members

Restituisce un elenco di tutti i record di membro per l'organizzazione.

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

Restituisce un singolo record di membro.

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

Aggiorna il ruolo, l'assegnazione dell'unità, il flag canManageOrg o lo stato attivo di un membro. Solo i campi presenti nel corpo vengono aggiornati (aggiornamento parziale).

**Corpo della richiesta**

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `role` | stringa | — | `"member"` o `"manager"`. Per creare un **gestore di unità**: `role: "manager"` + `unitId: <id>`. Per creare un **gestore globale**: `role: "manager"` + `unitId: null`. |
| `unitId` | numero intero\|null | — | Unità strutturale (deve appartenere a questa organizzazione), o `null` per non assegnare |
| `canManageOrg` | booleano | — | Concedi diritti di gestore globale nel portale |
| `isActive` | booleano | — | `false` per disattivare senza rimuovere |

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

Rimuove un membro dall'organizzazione. Bloccato se il membro ha ticket in questa organizzazione — usa invece `PUT` con `isActive: false` per disattivarlo e conservare la cronologia dei ticket.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

**422 Unprocessable Entity** *(member has tickets)*
```json
{"message": "Cannot remove this member: they have tickets in this organization. Deactivate them instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```

---

## Tag dell'organizzazione

> Richiede che il modulo [Tags](https://freescout.net/module/tags/) sia attivo. Restituisce `503` se il modulo non è installato.

### GET /api/organizations/{id}/tags

Restituisce tutti i binding dei tag per l'organizzazione. Ogni binding ha un'estensione facoltativa di un tag a un'unità specifica.

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

**Sostituzione completa** — sostituisce tutti i binding di tag esistenti per questa organizzazione con l'elenco fornito. Invia un array vuoto `[]` per rimuovere tutti i binding.

**Corpo della richiesta** — un array JSON di oggetti di binding dei tag:

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `tagId` | numero intero | ✅ | ID tag FreeScout |
| `unitId` | numero intero\|null | — | Limita il tag a un'unità specifica, oppure ometti/`null` per l'organizzazione intera |

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

## Unità strutturali

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

**Corpo della richiesta**

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `name` | stringa | ✅ | Nome dell'unità (univoco all'interno dell'organizzazione) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(intestazione `Resource-ID: 2`)*
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

**Corpo della richiesta**

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `name` | stringa | ✅ | Nuovo nome dell'unità (univoco all'interno dell'organizzazione) |

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

Elimina l'unità. I gestori limitati a questa unità vengono degradati a `member`; tutti i membri dell'unità vengono non assegnati (il loro `unitId` diventa `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Appartenenza del cliente

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

Assegna un cliente a un'organizzazione o aggiorna la sua appartenenza. **Un'appartenenza attiva per cliente**: se il cliente ha già un'appartenenza *attiva* in un'*altra* organizzazione, la richiesta viene rifiutata con `409 Conflict`. Per trasferire — disattiva o rimuovi prima l'appartenenza attuale tramite `DELETE`.

**Corpo della richiesta**

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `organizationId` | numero intero | ✅ | ID dell'organizzazione |
| `role` | stringa | — | `"member"` (predefinito) o `"manager"` |
| `unitId` | numero intero\|null | — | Unità strutturale (deve appartenere all'organizzazione di destinazione), o `null` per l'intera organizzazione |
| `canManageOrg` | booleano | — | Concedi a questo gestore il diritto di promuovere altri a gestore globale (predefinito `false`) |
| `isActive` | booleano | — | `false` per creare/aggiornare come inattivo (predefinito `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(nuova appartenenza)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(appartenenza aggiornata)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(il cliente è già attivo in un'altra organizzazione)*
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

Rimuove solo l'appartenenza **attiva** del cliente. Le appartenenze storiche (disattivate) in altre organizzazioni vengono conservate intatte. Bloccato se il cliente ha ticket in questa organizzazione — usa invece `PUT` con `isActive: false` per disattivare e conservare la cronologia dei ticket.

**200 OK**
```json
{"success": true, "message": "Membership removed."}
```

**422 Unprocessable Entity** *(customer has tickets)*
```json
{"message": "Cannot remove this membership: the customer has tickets in this organization. Deactivate instead (isActive: false) to preserve their ticket history.", "_embedded": {"errors": [{"tickets_count": 5}]}}
```
