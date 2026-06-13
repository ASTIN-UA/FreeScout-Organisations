# API REST OrgPortal

[← Retour au README](../README.fr.md)

🌐 **Langue:**
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

*Optionnel — nécessite le module [API et Webhooks](https://freescout.net/module/api-webhooks/).*

Authentification — en-tête `X-FreeScout-API-Key` ou paramètre de requête `api_key`.

> **Documentation interactive** (ReDoc) est disponible sur la page **Gérer → API & Webhooks** (lien "Documentation de l'API OrgPortal") ou directement à `/orgportal/admin/api-docs`.

## Points de terminaison

| Méthode | Point de terminaison | Description |
|---------|-----------------|------------|
| `GET` | `/api/organizations` | Lister les organisations (pagination, filtre boîte aux lettres) |
| `POST` | `/api/organizations` | Créer une organisation |
| `GET` | `/api/organizations/{id}` | Obtenir l'organisation avec les membres et unités |
| `PUT` | `/api/organizations/{id}` | Mettre à jour l'organisation |
| `DELETE` | `/api/organizations/{id}` | Supprimer l'organisation |
| `GET` | `/api/organizations/{id}/units` | Lister les unités structurelles |
| `POST` | `/api/organizations/{id}/units` | Créer une unité structurelle |
| `PUT` | `/api/units/{unitId}` | Renommer une unité |
| `DELETE` | `/api/units/{unitId}` | Supprimer une unité (membres non assignés, gestionnaires rétrogradés) |
| `GET` | `/api/customers/{id}/organization` | Adhésion du client à l'organisation |
| `PUT` | `/api/customers/{id}/organization` | Définir/mettre à jour l'adhésion du client |
| `DELETE` | `/api/customers/{id}/organization` | Supprimer le client de l'organisation |

## Codes de réponse

| Code | Signification |
|------|-----------|
| `200` | Succès ou pas d'opération (rien n'a changé) |
| `201` | Ressource créée; l'en-tête `Resource-ID` contient l'ID |
| `400` | Erreur de validation — détails dans `_embedded.errors` |
| `401` | Clé API invalide ou manquante |
| `404` | Ressource non trouvée |
| `409` | Conflit — le client dispose déjà d'une adhésion active dans une autre organisation |

---

## Organisations

### GET /api/organizations

**Paramètres de requête**

| Paramètre | Type | Par défaut | Description |
|-----------|------|:--------:|------------|
| `page` | integer | `1` | Numéro de page |
| `pageSize` | integer | `25` | Enregistrements par page (max 100) |
| `mailboxId` | integer | — | Filtre de boîte aux lettres : retourne les organisations globales + celles liées à cette boîte |

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

**Corps de la requête**

| Champ | Type | Requis | Description |
|-------|------|:--------:|------------|
| `name` | string | ✅ | Nom de l'organisation (max 255 caractères, unique) |
| `mailboxId` | integer\|null | — | ID de la boîte aux lettres ou `null` / omis pour organisation globale |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(en-tête `Resource-ID: 1`)*
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

Retourne l'organisation avec ses **membres** et **unités** imbriqués.

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

**Champs des membres**

| Champ | Type | Description |
|-------|------|------------|
| `unitId` | integer\|null | Unité structurelle à laquelle le membre appartient, ou `null` pour toute l'organisation |
| `role` | string | `member` ou `manager` |
| `canManageOrg` | boolean | Indique si ce gestionnaire peut promouvoir d'autres personnes gestionnaire global à partir du portail |
| `isActive` | boolean | Adhésion active; les membres inactifs ne reçoivent ni assignations ni notifications de tickets |
| `notifyOnNewTicket` | boolean | Paramètre de notification par ticket hérité pour chaque membre |

---

### PUT /api/organizations/{id}

**Corps de la requête**

| Champ | Type | Requis | Description |
|-------|------|:--------:|------------|
| `name` | string | ✅ | Nouveau nom de l'organisation (max 255 caractères, unique) |
| `mailboxId` | integer\|null | — | Nouvelle boîte aux lettres; `null` — rendre global; omis — laisser inchangé |

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

En cas d'absence de modification, le message de réponse est `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(tous les membres sont supprimés en cascade)*
```json
{"success": true, "message": "Organization deleted."}
```

---

## Unités structurelles

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

**Corps de la requête**

| Champ | Type | Requis | Description |
|-------|------|:--------:|------------|
| `name` | string | ✅ | Nom de l'unité (unique au sein de l'organisation) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(en-tête `Resource-ID: 2`)*
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

**Corps de la requête**

| Champ | Type | Requis | Description |
|-------|------|:--------:|------------|
| `name` | string | ✅ | Nouveau nom de l'unité (unique au sein de l'organisation) |

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

Supprime l'unité. Les gestionnaires limités à cette unité sont rétrogradés à `member`; tous les membres de l'unité sont non assignés (leur `unitId` devient `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Adhésion client

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

Assigne un client à une organisation ou met à jour son adhésion. **Une adhésion active par client** : si le client dispose déjà d'une adhésion *active* dans *une autre* organisation, la requête est rejetée avec `409 Conflict`. Pour transférer — d'abord désactiver ou supprimer l'adhésion actuelle via `DELETE`.

**Corps de la requête**

| Champ | Type | Requis | Description |
|-------|------|:--------:|------------|
| `organizationId` | integer | ✅ | ID de l'organisation |
| `role` | string | — | `"member"` (par défaut) ou `"manager"` |
| `unitId` | integer\|null | — | Unité structurelle (doit appartenir à l'organisation cible), ou `null` pour toute l'organisation |
| `canManageOrg` | boolean | — | Accorder à ce gestionnaire le droit de promouvoir d'autres personnes gestionnaire global (par défaut `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(nouvelle adhésion)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(adhésion mise à jour)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(client déjà actif dans une autre organisation)*
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
