# OrgPortal REST API

[← Volver al README](../../README.md)

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

*Opcional — requiere el módulo [API and Webhooks](https://freescout.net/module/api-webhooks/).*

Autenticación — encabezado `X-FreeScout-API-Key` o parámetro de consulta `api_key`.

> **Documentación interactiva** (ReDoc) está disponible en la página **Gestionar → API & Webhooks** (enlace "OrgPortal API Docs") o directamente en `/orgportal/admin/api-docs`.

## Puntos finales

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Listar organizaciones (paginación, filtro de buzón) |
| `POST` | `/api/organizations` | Crear una organización |
| `GET` | `/api/organizations/{id}` | Obtener organización con miembros y unidades |
| `PUT` | `/api/organizations/{id}` | Actualizar organización (nombre, color, buzón, isActive) |
| `DELETE` | `/api/organizations/{id}` | Eliminar organización |
| `GET` | `/api/organizations/{id}/members` | Listar miembros de la organización |
| `GET` | `/api/organizations/{id}/members/{memberId}` | Obtener un único miembro |
| `PUT` | `/api/organizations/{id}/members/{memberId}` | Actualizar miembro (rol, unidad, canManageOrg, isActive) |
| `DELETE` | `/api/organizations/{id}/members/{memberId}` | Eliminar miembro |
| `GET` | `/api/organizations/{id}/tags` | Listar vinculaciones de etiquetas (requiere módulo Tags) |
| `PUT` | `/api/organizations/{id}/tags` | Reemplazar todas las vinculaciones de etiquetas (requiere módulo Tags) |
| `GET` | `/api/organizations/{id}/units` | Listar unidades estructurales |
| `POST` | `/api/organizations/{id}/units` | Crear una unidad estructural |
| `PUT` | `/api/units/{unitId}` | Renombrar una unidad |
| `DELETE` | `/api/units/{unitId}` | Eliminar una unidad (miembros no asignados, gestores degradados) |
| `GET` | `/api/customers/{id}/organization` | Pertenencia del cliente a la organización |
| `PUT` | `/api/customers/{id}/organization` | Establecer/actualizar pertenencia del cliente |
| `DELETE` | `/api/customers/{id}/organization` | Eliminar cliente de la organización |

## Códigos de respuesta

| Code | Meaning |
|------|---------|
| `200` | Éxito |
| `201` | Recurso creado; encabezado `Resource-ID` contiene el ID |
| `400` | Error de validación — detalles en `_embedded.errors` |
| `401` | Clave API no válida o faltante |
| `404` | Recurso no encontrado |
| `409` | Conflicto — cliente ya tiene una pertenencia activa en otra organización |
| `422` | Violación de regla comercial — p. ej. eliminar una organización que aún tiene miembros o tickets |
| `503` | El módulo requerido (p. ej. Tags) no está activo |

---

## Organizaciones

### GET /api/organizations

**Parámetros de consulta**

| Parameter | Type | Default | Description |
|-----------|------|:-------:|-------------|
| `page` | integer | `1` | Número de página |
| `pageSize` | integer | `25` | Registros por página (máx 100) |
| `mailboxId` | integer | — | Filtro de buzón: devuelve organizaciones globales + las vinculadas a este buzón |

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

**Cuerpo de la solicitud**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Nombre de la organización (máx 255 caracteres, único) |
| `mailboxId` | integer\|null | — | ID de buzón o `null` / omitir para organización global |

```bash
curl -X POST "https://your-freescout.com/api/organizations" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Acme Corp", "mailboxId": 3}'
```

**201 Created** *(encabezado `Resource-ID: 1`)*
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

Devuelve la organización con **miembros** y **unidades** incrustados.

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

**Campos de miembro**

| Field | Type | Description |
|-------|------|-------------|
| `unitId` | integer\|null | Unidad estructural a la que pertenece el miembro, o `null` para toda la organización |
| `role` | string | `member` o `manager` |
| `canManageOrg` | boolean | Si este gestor puede promover a otros a gestor global desde el portal |
| `isActive` | boolean | Pertenencia activa; los miembros inactivos no reciben asignaciones o notificaciones de tickets |
| `notifyOnNewTicket` | boolean | Bandera de notificación de nuevo ticket por miembro |

---

### PUT /api/organizations/{id}

**Cuerpo de la solicitud**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Nuevo nombre de la organización (máx 255 caracteres, único) |
| `color` | string\|null | — | Color de distintivo como hex (`"#ff0000"`), `null` para restablecer al gris predeterminado; omitir para mantener actual |
| `mailboxId` | integer\|null | — | Nuevo buzón; `null` — hacer global; omitir — dejar sin cambios |
| `isActive` | boolean | — | `false` para desactivar la organización; omitir para mantener actual |

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

Bloqueado cuando la organización tiene miembros activos o tickets. Primero elimine todos los miembros y reasigne/elimine todos los tickets.

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

## Miembros de la organización

### GET /api/organizations/{id}/members

Devuelve una lista de todos los registros de miembros de la organización.

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

Devuelve un único registro de miembro.

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

Actualiza el rol del miembro, asignación de unidad, bandera canManageOrg o estado activo. Solo se actualizan los campos presentes en el cuerpo (actualización parcial).

**Cuerpo de la solicitud**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `role` | string | — | `"member"` o `"manager"` |
| `unitId` | integer\|null | — | Unidad estructural (debe pertenecer a esta organización), o `null` para anular asignación |
| `canManageOrg` | boolean | — | Otorgue derechos de gestor global en el portal |
| `isActive` | boolean | — | `false` para desactivar sin eliminar |

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

Eliminar un miembro de la organización.

**200 OK**
```json
{"success": true, "message": "Member removed."}
```

---

## Etiquetas de la organización

> Requiere el módulo [Tags](https://freescout.net/module/tags/) activo. Devuelve `503` si el módulo no está instalado.

### GET /api/organizations/{id}/tags

Devuelve todas las vinculaciones de etiquetas para la organización. Cada vinculación opcionalmente limita una etiqueta a una unidad específica.

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

**Reemplazo completo** — reemplaza todas las vinculaciones de etiquetas existentes para esta organización con la lista proporcionada. Envíe una matriz vacía `[]` para eliminar todas las vinculaciones.

**Cuerpo de la solicitud** — una matriz JSON de objetos de vinculación de etiquetas:

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `tagId` | integer | ✅ | ID de etiqueta de FreeScout |
| `unitId` | integer\|null | — | Limite la etiqueta a una unidad específica, u omita/`null` para toda la organización |

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

## Unidades estructurales

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

**Cuerpo de la solicitud**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Nombre de la unidad (único dentro de la organización) |

```bash
curl -X POST "https://your-freescout.com/api/organizations/1/units" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"name": "Sales department"}'
```

**201 Created** *(encabezado `Resource-ID: 2`)*
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

**Cuerpo de la solicitud**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Nuevo nombre de la unidad (único dentro de la organización) |

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

Elimina la unidad. Los gestores limitados a esta unidad se degradan a `member`; todos los miembros de la unidad no se asignan (su `unitId` se convierte en `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Pertenencia del cliente

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

Asigna un cliente a una organización o actualiza su pertenencia. **Una pertenencia activa por cliente**: si el cliente ya tiene una pertenencia *activa* en *otra* organización, la solicitud se rechaza con `409 Conflict`. Para transferir — primero desactive o elimine la pertenencia actual mediante `DELETE`.

**Cuerpo de la solicitud**

| Field | Type | Required | Description |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | ID de la organización |
| `role` | string | — | `"member"` (predeterminado) o `"manager"` |
| `unitId` | integer\|null | — | Unidad estructural (debe pertenecer a la organización destino), u `null` para toda la organización |
| `canManageOrg` | boolean | — | Otorgue a este gestor el derecho de promover a otros a gestor global (predeterminado `false`) |
| `isActive` | boolean | — | `false` para crear/actualizar como inactivo (predeterminado `true`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false, "isActive": true}'
```

**201 Created** *(nueva pertenencia)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(pertenencia actualizada)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(cliente ya activo en otra organización)*
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
