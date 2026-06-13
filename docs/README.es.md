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

OrgPortal ofrece una API REST completa para gestionar organizaciones, unidades estructurales y membresías de clientes — autenticación mediante el encabezado `X-FreeScout-API-Key` o el parámetro de consulta `api_key`.

📖 **Referencia completa de la API → [docs/api/README.es.md](api/README.es.md)** (todos los endpoints, ejemplos de solicitud/respuesta, códigos de error)

La documentación interactiva ReDoc también está disponible en **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

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

## Actualizaciones

OrgPortal admite **actualizaciones automáticas** a través del mecanismo de actualización de módulos integrado de FreeScout.

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

Cuando hay una nueva versión disponible, aparece un banner en la página **Gestionar → Módulos**. Haga clic en **Actualizar ahora** — FreeScout descargará e instalará la última versión automáticamente.

No se requiere copia manual de archivos.

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

[MIT](../LICENSE) — © 2026 ASTIN-UA
