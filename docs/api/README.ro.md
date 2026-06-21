# OrgPortal REST API

[← Înapoi la README](../../README.md)

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

*Opțional — necesită modulul [API și Webhooks](https://freescout.net/module/api-webhooks/).*

Autentificare — antetul `X-FreeScout-API-Key` sau parametrul de interogare `api_key`.

> **Documentație interactivă** (ReDoc) este disponibilă pe pagina **Gestionare → API & Webhooks** (link "OrgPortal API Docs") sau direct la `/orgportal/admin/api-docs`.

## Puncte finale

| Metodă | Punct final | Descriere |
|--------|----------|-----------|
| `GET` | `/api/organizations` | Listează organizațiile (paginare, filtru căsută poștală) |
| `POST` | `/api/organizations` | Creează o organizație |
| `GET` | `/api/organizations/{id}` | Obține organizația cu membri și unități |
| `PUT` | `/api/organizations/{id}` | Actualizează organizația (nume, culoare, căsută poștală, isActive) |
| `DELETE` | `/api/organizations/{id}` | Șterge organizația |
| `GET` | `/api/organizations/{id}/members` | Listează membri organizației |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Obține un singur membru |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Actualizează membru (rol, unitate, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Elimină un membru |
| `GET` | `/api/organizations/{id}/tags` | Listează legăturile de etichete (necesită modulul Tags) |
| `PUT` | `/api/organizations/{id}/tags` | Înlocuiește toate legăturile de etichete (necesită modulul Tags) |
| `GET` | `/api/organizations/{id}/units` | Listează unitățile structurale |
| `POST` | `/api/organizations/{id}/units` | Creează o unitate structurală |
| `PUT` | `/api/units/{unitId}` | Redenumește o unitate |
| `DELETE` | `/api/units/{unitId}` | Șterge o unitate (membri desatribuiți, manageri demotați) |
| `GET` | `/api/customers/{id}/organization` | Asocierea organizației clientului |
| `PUT` | `/api/customers/{id}/organization` | Setează/actualizează asocierea clientului |
| `DELETE` | `/api/customers/{id}/organization` | Elimină clientul din organizație |

## Coduri de răspuns

| Cod | Semnificație |
|------|---------|
| `200` | Succes |
| `201` | Resursă creată; antetul `Resource-ID` conține ID-ul |
| `400` | Eroare de validare — detalii în `_embedded.errors` |
| `401` | Cheie API nevalidă sau lipsă |
| `404` | Resursa nu a fost găsită |
| `409` | Conflict — clientul are deja o asociere activă în altă organizație |
| `422` | Încălcare de regulă de afaceri — ex. ștergerea unei organizații care are încă membri sau ticheți |
| `503` | Modulul necesar (ex. Tags) nu este activ |

---

## Organizații

### GET /api/organizations

**Parametri de interogare**

| Parametru | Tip | Implicit | Descriere |
|-----------|------|:-------:|-----------|
| `page` | întreg | `1` | Numărul paginii |
| `pageSize` | întreg | `25` | Înregistrări pe pagină (maximum 100) |
| `mailboxId` | întreg | — | Filtru căsută poștală: returnează organizațiile globale + cele legate de această căsută |

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

**Corp de cerere**

| Câmp | Tip | Necesar | Descriere |
|-------|------|:--------:|-----------|
| `name` | șir | ✅ | Nume organizație (maximum 255 caractere, unic) |
| `mailboxId` | întreg\|null | — | ID căsută poștală sau `null` / omiteți pentru organizație globală |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(antet `Resource-ID: 1`)*
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

Returnează organizația cu **membrii** și **unitățile** sale încorporate.

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

**Câmpurile membrului**

| Câmp | Tip | Descriere |
|-------|------|-----------|
| `unitId` | întreg\|null | Unitatea structurală căreia îi aparține membrul, sau `null` pentru întreaga organizație |
| `role` | șir | `member` sau `manager` |
| `canManageOrg` | boolean | Dacă acest manager poate promova alții la manager global din portal |
| `isActive` | boolean | Asociere activă; membrii inactivi nu primesc atribuiri de tichet sau notificări |
| `notifyOnNewTicket` | boolean | Steag de notificare per membru pentru tichet nou |

---

### PUT /api/organizations/{id}

**Corp de cerere**

| Câmp | Tip | Necesar | Descriere |
|-------|------|:--------:|-----------|
| `name` | șir | ✅ | Nume nou organizație (maximum 255 caractere, unic) |
| `color` | șir\|null | — | Culoare insignă ca hexazecimal (`"#ff0000"`), `null` pentru resetare la gri implicit; omiteți pentru a menține actual |
| `mailboxId` | întreg\|null | — | Căsută poștală nouă; `null` — faceți global; omiteți — lăsați neschimbat |
| `isActive` | boolean | — | `false` pentru dezactivare organizație; omiteți pentru a menține actual |

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

Blocat atunci când organizația are membri activi sau ticheți. Eliminați mai întâi toți membrii și reatribuiți/ștergeți toți ticheții.

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

## Membri organizației

### GET /api/organizations/{id}/members

Returnează o listă a tuturor înregistrărilor de membri ale organizației.

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

Returnează o singură înregistrare de membru.

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

Actualizează rolul, atribuția unității, steagul canManageOrg sau starea activă a unui membru. Doar câmpurile prezente în corp sunt actualizate (actualizare parțială).

**Corp de cerere**

| Câmp | Tip | Necesar | Descriere |
|-------|------|:--------:|-----------|
| `role` | șir | — | `"member"` sau `"manager"` |
| `unitId` | întreg\|null | — | Unitate structurală (trebuie să aparțină acestei organizații), sau `null` pentru a elimina |
| `canManageOrg` | boolean | — | Acordă drepturi de manager global în portal |
| `isActive` | boolean | — | `false` pentru dezactivare fără ștergere |

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

Elimină un membru din organizație.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## Etichete de organizație

> Necesită ca modulul [Tags](https://freescout.net/module/tags/) să fie activ. Returnează `503` dacă modulul nu este instalat.

### GET /api/organizations/{id}/tags

Returnează toate legăturile de etichete pentru organizație. Fiecare legătură poate opțional să limiteze o etichetă la o unitate specifică.

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

**Înlocuire completă** — înlocuiește toate legăturile de etichete existente pentru această organizație cu lista furnizată. Trimiteți o matrice goală `[]` pentru a elimina toate legăturile.

**Corp de cerere** — o matrice JSON de obiecte de legare a etichetelor:

| Câmp | Tip | Necesar | Descriere |
|-------|------|:--------:|-----------|
| `tagId` | întreg | ✅ | ID etichetă FreeScout |
| `unitId` | întreg\|null | — | Limitează eticheta la o unitate specifică, sau omiteți/`null` pentru tot organizația |

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

## Unități structurale

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

**Corp de cerere**

| Câmp | Tip | Necesar | Descriere |
|-------|------|:--------:|-----------|
| `name` | șir | ✅ | Nume unitate (unic în cadrul organizației) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(antet `Resource-ID: 2`)*
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

**Corp de cerere**

| Câmp | Tip | Necesar | Descriere |
|-------|------|:--------:|-----------|
| `name` | șir | ✅ | Nume nou unitate (unic în cadrul organizației) |

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

Șterge unitatea. Managerii cu domeniu în această unitate sunt demotați la `member`; toți membrii unității sunt desatribuiți (`unitId` lor devine `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Asocierea clientului

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

Atribuie un client unei organizații sau actualizează asocierea acestuia. **O asociere activă per client**: dacă clientul are deja o asociere *activă* în *altă* organizație, cererea este respingă cu `409 Conflict`. Pentru transfer — mai întâi dezactivați sau eliminați asocierea curentă via `DELETE`.

**Corp de cerere**

| Câmp | Tip | Necesar | Descriere |
|-------|------|:--------:|-----------|
| `organizationId` | întreg | ✅ | ID organizație |
| `role` | șir | — | `"member"` (implicit) sau `"manager"` |
| `unitId` | întreg\|null | — | Unitate structurală (trebuie să aparțină organizației țintă), sau `null` pentru întreaga organizație |
| `canManageOrg` | boolean | — | Acordă acestui manager dreptul de a promova alții la manager global (implicit `false`) |
| `isActive` | boolean | — | `false` pentru a crea/actualiza ca inactiv (implicit `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(asociere nouă)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(asociere actualizată)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(client deja activ în altă organizație)*
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
