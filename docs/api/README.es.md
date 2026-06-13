# API REST de OrgPortal

[← Volver al README](../README.es.md)

🌐 **Idioma:**
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

*Opcional — requiere el módulo [API y Webhooks](https://freescout.net/module/api-webhooks/).*

Autenticación — encabezado `X-FreeScout-API-Key` o parámetro de consulta `api_key`.

> **Documentación interactiva** (ReDoc) está disponible en la página **Gestionar → API & Webhooks** (enlace "OrgPortal API Docs") o directamente en `/orgportal/admin/api-docs`.

## Endpoints

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/organizations` | Listar organizaciones (paginación, filtro de buzón) |
| `POST` | `/api/organizations` | Crear una organización |
| `GET` | `/api/organizations/{id}` | Obtener organización con miembros y unidades |
| `PUT` | `/api/organizations/{id}` | Actualizar organización |
| `DELETE` | `/api/organizations/{id}` | Eliminar organización |
| `GET` | `/api/organizations/{id}/units` | Listar unidades estructurales |
| `POST` | `/api/organizations/{id}/units` | Crear una unidad estructural |
| `PUT` | `/api/units/{unitId}` | Renombrar una unidad |
| `DELETE` | `/api/units/{unitId}` | Eliminar una unidad (miembros desasignados, gestores degradados) |
| `GET` | `/api/customers/{id}/organization` | Membresía de la organización del cliente |
| `PUT` | `/api/customers/{id}/organization` | Establecer/actualizar membresía del cliente |
| `DELETE` | `/api/customers/{id}/organization` | Eliminar cliente de la organización |

## Códigos de respuesta

| Código | Significado |
|--------|------------|
| `200` | Éxito o sin operación (nada cambió) |
| `201` | Recurso creado; el encabezado `Resource-ID` contiene el ID |
| `400` | Error de validación — detalles en `_embedded.errors` |
| `401` | Clave API inválida o faltante |
| `404` | Recurso no encontrado |
| `409` | Conflicto — el cliente ya tiene una membresía activa en otra organización |

---

## Organizaciones

### GET /api/organizations

**Parámetros de consulta**

| Parámetro | Tipo | Predeterminado | Descripción |
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

| Campo | Tipo | Requerido | Descripción |
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
  "mailboxId": 3,
  "createdAt": "2026-06-01T10:00:00+00:00",
  "updatedAt": "2026-06-01T10:00:00+00:00"
}
```

---

### GET /api/organizations/{id}

Devuelve la organización con sus **miembros** y **unidades** incrustados.

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

**Campos de miembro**

| Campo | Tipo | Descripción |
|-------|------|-------------|
| `unitId` | integer\|null | Unidad estructural a la que pertenece el miembro, o `null` para toda la organización |
| `role` | string | `member` o `manager` |
| `canManageOrg` | boolean | Si este gestor puede promover a otros a gestor global desde el portal |
| `isActive` | boolean | Membresía activa; los miembros inactivos no reciben asignaciones de tickets ni notificaciones |
| `notifyOnNewTicket` | boolean | Bandera heredada de notificación de nuevo ticket por miembro |

---

### PUT /api/organizations/{id}

**Cuerpo de la solicitud**

| Campo | Tipo | Requerido | Descripción |
|-------|------|:--------:|-------------|
| `name` | string | ✅ | Nuevo nombre de la organización (máx 255 caracteres, único) |
| `mailboxId` | integer\|null | — | Nuevo buzón; `null` — hacer global; omitir — dejar sin cambios |

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

Cuando nada cambia, el mensaje de respuesta es `No changes — organization already has this name and mailbox.`

---

### DELETE /api/organizations/{id}

**200 OK** *(todos los miembros se eliminan en cascada)*
```json
{"success": true, "message": "Organization deleted."}
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

| Campo | Tipo | Requerido | Descripción |
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

| Campo | Tipo | Requerido | Descripción |
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

Elimina la unidad. Los gestores limitados a esta unidad se degradan a `member`; todos los miembros de la unidad se desasignan (su `unitId` se convierte en `null`).

**200 OK**
```json
{"success": true, "message": "Unit deleted."}
```

---

## Membresía del cliente

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

Asigna un cliente a una organización o actualiza su membresía. **Una membresía activa por cliente**: si el cliente ya tiene una membresía *activa* en *otra* organización, la solicitud se rechaza con `409 Conflict`. Para transferir — primero desactive o elimine la membresía actual mediante `DELETE`.

**Cuerpo de la solicitud**

| Campo | Tipo | Requerido | Descripción |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | ID de la organización |
| `role` | string | — | `"member"` (predeterminado) o `"manager"` |
| `unitId` | integer\|null | — | Unidad estructural (debe pertenecer a la organización de destino), o `null` para toda la organización |
| `canManageOrg` | boolean | — | Otorgue a este gestor el derecho de promover a otros a gestor global (predeterminado `false`) |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager", "unitId": 2, "canManageOrg": false}'
```

**201 Created** *(membresía nueva)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(membresía actualizada)*
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
