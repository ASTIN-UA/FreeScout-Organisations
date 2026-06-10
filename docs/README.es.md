# OrgPortal — Portal de Organizaciones para FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Un módulo de FreeScout que añade el concepto de **Organizaciones** (empresas/equipos) a los clientes, extiende el Portal del Usuario Final para administradores y muestra un distintivo de organización en tickets y tarjetas Kanban.

**Versión mínima de FreeScout:** 1.8.147  
**Dependencias:** ninguna requerida  
**Opcional:** [Portal del Usuario Final](https://freescout.net/module/end-user-portal/), [API y Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

---

🌐 **Idioma:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Características

### Gestión de organizaciones (admin)
- **Gestionar → Organizaciones** — CRUD completo: crear, editar, eliminar organizaciones
- **Vinculación de buzón** — una organización puede ser **global** (visible en todos los buzones) o **vinculada a un buzón específico**; la etiqueta correspondiente se muestra en la lista de organizaciones
- Asignar clientes a organizaciones con selección de rol: `member` o `manager`
- **Cambiar rol de miembro** directamente en la tabla (sin eliminar y volver a añadir)
- Búsqueda automática de clientes por nombre o correo electrónico; los clientes que ya pertenecen a cualquier organización se excluyen de los resultados
- El correo electrónico del miembro se muestra debajo del nombre en la tabla de miembros
- Un cliente — una organización (aplicado a nivel de BD y API)
- **Color de distintivo** — paleta visual con 12 colores en el formulario de edición de organización; el predeterminado es gris

### Permisos de usuario
- Nuevo permiso **"Permitir gestionar organizaciones"** — los usuarios sin derechos de administrador con este permiso obtienen acceso a las páginas de lista, creación y edición de organizaciones
- Eliminar organizaciones sigue siendo exclusivo de los administradores

### Tarjeta del cliente
- Campo **Organización** en el formulario de edición de cliente — seleccione organización y rol
- Botón **Tickets de Organización** — abre una búsqueda de todos los tickets de la organización

### Distintivo de organización en tickets
- Se muestra debajo del asunto en la página del ticket y antes del nombre en la lista de conversaciones
- Se puede hacer clic — abre una búsqueda de todos los tickets de esta organización
- El color del distintivo está determinado por la configuración de la organización (predeterminado gris)
- Activar/desactivar **por buzón** mediante **Configuración del Buzón → OrgPortal**; se usa el valor global como respaldo

### Distintivo de organización en tarjetas Kanban
- Se muestra después del contador de mensajes en cada tarjeta
- Se puede hacer clic — conduce a la búsqueda de organización
- El color coincide con la configuración de la organización
- Filtro **Organización** integrado en el menú desplegable de filtros de Kanban estándar: modal con casillas de verificación, similar al filtro de etiquetas; el estado se conserva entre navegaciones
- Activar/desactivar **por buzón** mediante **Configuración del Buzón → OrgPortal**

### Filtro de búsqueda de organización
- Extiende la búsqueda de FreeScout con un filtro de **Organización**
- Muestra todos los tickets de clientes que pertenecen a la organización seleccionada

### Portal del Usuario Final — acceso gestor *(opcional)*

Un gestor de organización obtiene acceso extendido a través de EUP:

- Elemento **Tickets de Empresa** en la navegación del portal
- Tabla de tickets de empresa con columnas:
  - **#** y **Asunto** con truncamiento de elipsis y tooltip al pasar el ratón
  - **Responsable** — agente asignado
  - **Autor** — el cliente que abrió el ticket; hacer clic filtra tickets por autor dentro de la organización
  - **Estado** — Activo / Pendiente / Cerrado / Spam con iconos
  - **Fase** — nombre de la columna Kanban (con etiqueta personalizada si se configura); se muestra solo si el módulo Kanban está activo
  - **Actualizado** — fecha y hora de la última respuesta
- Buscar por asunto del ticket
- Filtros por estados de Kanban (configurables mediante **Configuración del Buzón → OrgPortal**)
- Responder a ticket con soporte de **adjuntos** (arrastrar y soltar, múltiples archivos)
- **Cerrar ticket** — el gestor puede cerrar un ticket; una nueva respuesta lo reabre automáticamente
- Cambiar autor del ticket — reasignar un ticket a otro miembro de la organización
- Página **Configuración de Org** para configurar notificaciones por correo electrónico
- El acceso a tickets está **estrictamente limitado al buzón actual** (organización copiada a otro buzón — portal 403)

### Notificaciones por correo electrónico *(opcional)*
- Los gestores con la opción habilitada reciben un correo electrónico cuando se crea un nuevo ticket de cualquier miembro de la organización
- Utiliza el controlador de correo del buzón correspondiente

### Configuración de buzón

**Configuración del Buzón → OrgPortal** (por buzón):

| Opción | Descripción |
|--------|-------------|
| Mostrar distintivo en la página del ticket | Activar/desactivar distintivo dentro de este buzón |
| Mostrar distintivo en tarjetas Kanban | Activar/desactivar distintivo dentro de este buzón |
| Filtros de estado de tickets de empresa | Seleccione columnas Kanban mostradas como casillas de verificación en la página de tickets; etiqueta personalizada para cada filtro |

---

### REST API *(opcional, requiere API y Webhooks)*

Autenticación — encabezado `X-FreeScout-API-Key` o parámetro de consulta `api_key`.

> **Documentación interactiva** (ReDoc) está disponible en la página **Gestionar → API & Webhooks** (enlace "OrgPortal API Docs") o directamente en `/orgportal/admin/api-docs`.

| Método | Punto final | Descripción |
|--------|-------------|-------------|
| `GET` | `/api/organizations` | Listar organizaciones (paginación, filtro de buzón) |
| `POST` | `/api/organizations` | Crear una organización |
| `GET` | `/api/organizations/{id}` | Obtener organización con miembros |
| `PUT` | `/api/organizations/{id}` | Actualizar organización |
| `DELETE` | `/api/organizations/{id}` | Eliminar organización |
| `GET` | `/api/customers/{id}/organization` | Organización del cliente |
| `PUT` | `/api/customers/{id}/organization` | Establecer/actualizar membresía del cliente |
| `DELETE` | `/api/customers/{id}/organization` | Eliminar cliente de la organización |

#### Códigos de respuesta

| Código | Significado |
|--------|------------|
| `200` | Éxito o sin operación (nada cambió) |
| `201` | Recurso creado; encabezado `Resource-ID` contiene el ID |
| `400` | Error de validación — detalles en `_embedded.errors` |
| `401` | Clave API inválida o faltante |
| `404` | Recurso no encontrado |
| `409` | Conflicto — el cliente ya pertenece a otra organización |

---

#### GET /api/organizations

**Parámetros de consulta**

| Parámetro | Tipo | Por defecto | Descripción |
|-----------|------|:----------:|-------------|
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

#### POST /api/organizations

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

---

#### DELETE /api/organizations/{id}

**200 OK** *(todos los miembros se eliminan en cascada)*
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

Asigna un cliente a una organización o actualiza su rol. **Un cliente — una organización**: si el cliente ya es miembro de *otra* organización, la solicitud se rechaza con `409 Conflict`. Para transferir — primero elimine la membresía actual mediante `DELETE`.

**Cuerpo de la solicitud**

| Campo | Tipo | Requerido | Descripción |
|-------|------|:--------:|-------------|
| `organizationId` | integer | ✅ | ID de la organización |
| `role` | string | — | `"member"` (predeterminado) o `"manager"` |

```bash
curl -X PUT "https://your-freescout.com/api/customers/42/organization" \
  -H "X-FreeScout-API-Key: YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"organizationId": 1, "role": "manager"}'
```

**201 Created** *(membresía nueva)*
```json
{"success": true, "message": "Membership created."}
```

**200 OK** *(rol actualizado o sin operación)*
```json
{"success": true, "message": "Membership updated."}
```

**409 Conflict** *(cliente ya en otra organización)*
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

## Instalación

1. Copie la carpeta `OrgPortal` en `Modules/` de su FreeScout
2. En el panel de administración: **Gestionar → Módulos → OrgPortal → Activar**
3. Ejecute las migraciones:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Limpie el caché:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Compatibilidad de módulos

| Módulo | Estado |
|--------|--------|
| Portal del Usuario Final ≥ 1.0.85 | Opcional — características del portal para gestores |
| API y Webhooks ≥ 1.0.80 | Opcional — puntos finales de API REST |
| Kanban ≥ 1.0.23 | Opcional — distintivo, filtro, columna "Fase" en tickets de empresa |
| Campos Personalizados | Compatible |
| Flujos de Trabajo | Compatible |
| Etiquetas | Compatible |

---

## Configuración

### Global (**Gestionar → Configuración de OrgPortal**)

| Opción | Por defecto |
|--------|------------|
| Mostrar distintivo en la página del ticket | ✅ |
| Mostrar distintivo en tarjetas Kanban | ✅ |

### Por buzón (**Configuración del Buzón → OrgPortal**)

Anula los valores globales para el buzón específico.

| Opción | Descripción |
|--------|-------------|
| Mostrar distintivo en la página del ticket | Distintivo en lista de conversaciones y en página de ticket |
| Mostrar distintivo en tarjetas Kanban | Distintivo en tarjetas Kanban |
| Filtros de estado de tickets de empresa | Columnas Kanban como casillas de verificación en la página de tickets de empresa; etiqueta personalizada visible para usuarios del portal |

---

## Traducciones

Idiomas admitidos: **English** (`en`), **Українська** (`uk`), **Română** (`ro`), **Georgian** (`ka`), **Deutsch** (`de`), **Français** (`fr`), **Español** (`es`), **Italiano** (`it`), **Čeština** (`cs`), **Slovenčina** (`sk`), **Polski** (`pl`), **Nederlands** (`nl`), **Norsk** (`no`), **Dansk** (`da`), **Svenska** (`sv`), **Suomi** (`fi`), **Português BR** (`pt-BR`), **Português PT** (`pt-PT`), **中文 (简体)** (`zh-CN`).

Archivos: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### Integración EUPSWLANG

El módulo funciona correctamente con [Cambio de Idioma EUP](https://freescout.net/module/eup-sw-lang/): el idioma seleccionado en el portal también se aplica a las cadenas de OrgPortal.

Para que un idioma aparezca en la lista EUPSWLANG, el archivo correspondiente `Modules/EndUserPortal/Resources/lang/{locale}.json` debe existir. Los archivos para **Română** (`ro`) se incluyen en el paquete; **Georgian** (`ka`) solo se admite en la sección de administración (sin soporte del sistema en el núcleo de FreeScout).

> **Detalle técnico:** middleware `ReapplyEupLocale` (registrado último en el grupo de rutas del portal) restaura la configuración regional después del middleware `Localize` de FreeScout, que de otro modo restablecería el idioma del portal al valor predeterminado del sistema.

---

## Licencia

Propietaria — ASTIN UA.
