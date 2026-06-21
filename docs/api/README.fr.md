# OrgPortal REST API

[← Retour au README](../../README.md)

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

*Facultatif — nécessite le module [API and Webhooks](https://freescout.net/module/api-webhooks/).*

Authentification — en-tête `X-FreeScout-API-Key` ou paramètre de requête `api_key`.

> **Documentation interactive** (ReDoc) est disponible sur la page **Gérer → API & Webhooks** (lien "OrgPortal API Docs") ou directement à `/orgportal/admin/api-docs`.

## Points de terminaison

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Lister les organisations (pagination, filtre de boîte postale) |
| `POST` | `/api/organizations` | Créer une organisation |
| `GET` | `/api/organizations/{id}` | Obtenir une organisation avec ses membres et unités |
| `PUT` | `/api/organizations/{id}` | Mettre à jour une organisation (nom, couleur, boîte postale, isActive) |
| `DELETE` | `/api/organizations/{id}` | Supprimer une organisation |
| `GET` | `/api/organizations/{id}/members` | Lister les membres de l'organisation |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Obtenir un seul membre |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Mettre à jour un membre (rôle, unité, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Supprimer un membre |
| `GET` | `/api/organizations/{id}/tags` | Lister les liaisons d'étiquettes (nécessite le module Tags) |
| `PUT` | `/api/organizations/{id}/tags` | Remplacer toutes les liaisons d'étiquettes (nécessite le module Tags) |
| `GET` | `/api/organizations/{id}/units` | Lister les unités structurelles |
| `POST` | `/api/organizations/{id}/units` | Créer une unité structurelle |
| `PUT` | `/api/units/{unitId}` | Renommer une unité |
| `DELETE` | `/api/units/{unitId}` | Supprimer une unité (membres non assignés, responsables rétrogradés) |
| `GET` | `/api/customers/{id}/organization` | Appartenance du client à l'organisation |
| `PUT` | `/api/customers/{id}/organization` | Définir/mettre à jour l'appartenance du client |
| `DELETE` | `/api/customers/{id}/organization` | Supprimer un client de l'organisation |

## Codes de réponse

| Code | Meaning |
|------|---------|
| `200` | Succès |
| `201` | Ressource créée ; l'en-tête `Resource-ID` contient l'ID |
| `400` | Erreur de validation — détails dans `_embedded.errors` |
| `401` | Clé API invalide ou manquante |
| `404` | Ressource non trouvée |
| `409` | Conflit — le client a déjà un adhésion active dans une autre organisation |
| `422` | Violation de règle métier — par ex. suppression d'une organisation qui a encore des membres ou des tickets |
| `503` | Le module requis (par ex. Tags) n'est pas actif |

---

## Organisations

### GET /api/organizations

**Paramètres de requête**

| Parameter | Type | Default | Description |
|-----------|------|:-------:|-------------|
| `page` | integer | `1` | Numéro de la page |
| `pageSize` | integer | `25` | Enregistrements par page (max 100) |
| `mailboxId` | integer | — | Filtre de boîte postale : renvoie les organisations globales + celles liées à cette boîte postale |

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

**Corps de la requête**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Nom de l'organisation (max 255 caractères, unique) |
| `mailboxId` | integer\|null | — | ID de boîte postale ou `null` / omettre pour une organisation globale |

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
  "color": null,
  "isActive": true,
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Retourne l'organisation avec ses **membres** et **unités** embarqués.

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

**Champs des membres**

| Field | Type | Description |
|-------|------|-------------|
| `unitId` | integer\|null | Unité structurelle à laquelle appartient le membre, ou `null` pour l'ensemble de l'organisation |
| `role` | string | `member` ou `manager` |
| `canManageOrg` | boolean | Si ce responsable peut promouvoir d'autres responsables globaux via le portail |
| `isActive` | boolean | Adhésion active ; les membres inactifs ne reçoivent pas d'assignations ou de notifications de tickets |
| `notifyOnNewTicket` | boolean | Indicateur de notification de nouveau ticket par membre |

---

### PUT /api/organizations/{id}

**Corps de la requête**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Nouveau nom de l'organisation (max 255 caractères, unique) |
| `color` | string\|null | — | Couleur du badge en hex (`"#ff0000"`), `null` pour réinitialiser au gris par défaut ; omettre pour conserver le courant |
| `mailboxId` | integer\|null | — | Nouvelle boîte postale ; `null` — rendre global ; omettre — laisser inchangé |
| `isActive` | boolean | — | `false` pour désactiver l'organisation ; omettre pour conserver le courant |

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

Bloqué lorsque l'organisation a des membres actifs ou des tickets. Supprimez d'abord tous les membres et réaffectez/supprimez tous les tickets.

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

## Membres de l'organisation

### GET /api/organizations/{id}/members

Retourne une liste de tous les enregistrements de membres de l'organisation.

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

Retourne un seul enregistrement de membre.

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

Mettez à jour le rôle d'un membre, l'assignation à une unité, l'indicateur canManageOrg ou le statut actif. Seuls les champs présents dans le corps sont mis à jour (mise à jour partielle).

**Corps de la requête**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `role` | string | — | `"member"` ou `"manager"` |
| `unitId` | integer\|null | — | Unité structurelle (doit appartenir à cette organisation), ou `null` pour annuler l'assignation |
| `canManageOrg` | boolean | — | Accordez des droits de gestionnaire global dans le portail |
| `isActive` | boolean | — | `false` pour désactiver sans supprimer |

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

Supprimer un membre de l'organisation.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## Étiquettes de l'organisation

> Nécessite le module [Tags](https://freescout.net/module/tags/) actif. Renvoie `503` si le module n'est pas installé.

### GET /api/organizations/{id}/tags

Retourne toutes les liaisons d'étiquettes pour l'organisation. Chaque liaison limite facultativement une étiquette à une unité spécifique.

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

**Remplacement complet** — remplace toutes les liaisons d'étiquettes existantes pour cette organisation par la liste fournie. Envoyez un tableau vide `[]` pour supprimer toutes les liaisons.

**Corps de la requête** — un tableau JSON d'objets de liaison d'étiquettes :

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `tagId` | integer | ✅ | ID d'étiquette FreeScout |
| `unitId` | integer\|null | — | Limitez l'étiquette à une unité spécifique, ou omettre/`null` pour l'organisation entière |

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

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
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

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
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

Supprime l'unité. Les responsables limités à cette unité sont rétrogradés à `member` ; tous les membres de l'unité ne sont pas assignés (leur `unitId` devient `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Appartenance du client

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

Assigne un client à une organisation ou met à jour son appartenance. **Une adhésion active par client** : si le client a déjà une adhésion *active* dans une *autre* organisation, la demande est rejetée avec `409 Conflict`. Pour transférer — déactivez ou supprimez d'abord l'adhésion actuelle via `DELETE`.

**Corps de la requête**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | ID de l'organisation |
| `role` | string | — | `"member"` (par défaut) ou `"manager"` |
| `unitId` | integer\|null | — | Unité structurelle (doit appartenir à l'organisation cible), ou `null` pour l'ensemble de l'organisation |
| `canManageOrg` | boolean | — | Accordez à ce responsable le droit de promouvoir d'autres responsables globaux (par défaut `false`) |
| `isActive` | boolean | — | `false` pour créer/mettre à jour comme inactif (par défaut `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(nouvelle adhésion)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(adhésion mise à jour)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(le client est déjà actif dans une autre organisation)*
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
