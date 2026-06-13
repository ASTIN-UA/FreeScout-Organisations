# OrgPortal REST API

[← Torna al README](../README.it.md)

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

*Opzionale — richiede il modulo [API e Webhook](https://freescout.net/module/api-webhooks/).*

Autenticazione — header `X-FreeScout-API-Key` o parametro di query `api_key`.

> **Documentazione interattiva** (ReDoc) è disponibile sulla pagina **Gestione → API & Webhook** (link "OrgPortal API Docs") o direttamente su `/orgportal/admin/api-docs`.

## Endpoint

| Metodo | Endpoint | Descrizione |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Elenca organizzazioni (paginazione, filtro cassetta postale) |
| `POST` | `/api/organizations` | Crea un'organizzazione |
| `GET` | `/api/organizations/{id}` | Ottieni organizzazione con membri e unità |
| `PUT` | `/api/organizations/{id}` | Aggiorna organizzazione |
| `DELETE` | `/api/organizations/{id}` | Elimina organizzazione |
| `GET` | `/api/organizations/{id}/units` | Elenca unità strutturali |
| `POST` | `/api/organizations/{id}/units` | Crea un'unità strutturale |
| `PUT` | `/api/units/{unitId}` | Rinomina un'unità |
| `DELETE` | `/api/units/{unitId}` | Elimina un'unità (membri non assegnati, gestori demossi) |
| `GET` | `/api/customers/{id}/organization` | Iscrizione all'organizzazione del cliente |
| `PUT` | `/api/customers/{id}/organization` | Imposta/aggiorna iscrizione cliente |
| `DELETE` | `/api/customers/{id}/organization` | Rimuovi cliente dall'organizzazione |

## Codici di risposta

| Codice | Significato |
|--------|------------|
| `200` | Successo o nessuna operazione (nulla è cambiato) |
| `201` | Risorsa creata; header `Resource-ID` contiene l'ID |
| `400` | Errore di validazione — dettagli in `_embedded.errors` |
| `401` | Chiave API non valida o mancante |
| `404` | Risorsa non trovata |
| `409` | Conflitto — il cliente ha già un'iscrizione attiva in un'altra organizzazione |

---

## Organizzazioni

### GET /api/organizations

**Parametri di query**

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

### POST /api/organizations

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

**201 Created** *(header `Resource-ID: 1`)*
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

Restituisce l'organizzazione con i suoi **membri** e **unità** incorporati.

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

**Campi membro**

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| `unitId` | integer\|null | Unità strutturale cui appartiene il membro, o `null` per l'intera organizzazione |
| `role` | string | `member` o `manager` |
| `canManageOrg` | boolean | Se questo gestore può promuovere altri a gestore globale dal portale |
| `isActive` | boolean | Iscrizione attiva; i membri inattivi non ricevono assegnazioni o notifiche di ticket |
| `notifyOnNewTicket` | boolean | Flag di notifica di nuovo ticket legacy per membro |

---

### PUT /api/organizations/{id}

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

Quando nulla cambia, il messaggio di risposta è `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(tutti i membri vengono eliminati a cascata)*
```json
{"success": true, "message": "Organization deleted."}
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
| `name` | string | ✅ | Nome dell'unità (univoco all'interno dell'organizzazione) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(header `Resource-ID: 2`)*
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
| `name` | string | ✅ | Nuovo nome dell'unità (univoco all'interno dell'organizzazione) |

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

Elimina l'unità. I gestori scoped a questa unità vengono demossi a `member`; tutti i membri dell'unità vengono non assegnati (il loro `unitId` diventa `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Iscrizione cliente

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

Assegna un cliente a un'organizzazione o aggiorna la sua iscrizione. **Un'iscrizione attiva per cliente**: se il cliente ha già un'iscrizione *attiva* in *un'altra* organizzazione, la richiesta viene rifiutata con `409 Conflict`. Per trasferire — prima disattiva o rimuovi l'iscrizione corrente tramite `DELETE`.

**Corpo della richiesta**

| Campo | Tipo | Richiesto | Descrizione |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | ID dell'organizzazione |
| `role` | string | — | `"member"` (predefinito) o `"manager"` |
| `unitId` | integer\|null | — | Unità strutturale (deve appartenere all'organizzazione di destinazione), o `null` per l'intera organizzazione |
| `canManageOrg` | boolean | — | Concedi a questo gestore il diritto di promuovere altri a gestore globale (predefinito `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(nuova iscrizione)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(iscrizione aggiornata)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(cliente già attivo in un'altra organizzazione)*
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
