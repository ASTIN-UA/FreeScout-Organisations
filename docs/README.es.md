# OrgPortal — Módulo de Gestión de Organizaciones B2B para FreeScout

[← Volver al README](../README.md)

<img src="Modules/OrgPortal/logo.png" alt="OrgPortal — módulo B2B de FreeScout" width="140" align="right">

**OrgPortal** es un módulo de FreeScout que añade **gestión completa de organizaciones B2B** a tu servicio de asistencia: agrupa clientes en empresas, define jerarquías de departamentos, da a los gerentes corporativos un portal de autoservicio y automatiza notificaciones — todo dentro de FreeScout, sin herramientas externas requeridas.

> ¿Buscas una forma de gestionar cuentas de empresas en FreeScout? ¿Quieres dar a tus clientes corporativos su propio portal de soporte? ¿Controlar qué tickets puede ver cada contacto B2B según su rol y departamento? OrgPortal resuelve todo eso.

**Compatible con:** FreeScout 1.8.147+  
**Integraciones opcionales:** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

> [!IMPORTANT]
> **Instala siempre desde la [última versión](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest), no desde el código fuente del repositorio.**
> Descarga `OrgPortal.zip` desde la página de Releases — contiene la estructura de directorios correcta que requiere FreeScout.
> Descargar el código fuente (mediante "Code → Download ZIP" o `git clone`) **no funcionará** y romperá la estructura del módulo.
> Las actualizaciones automáticas también requieren que el ZIP de la versión se haya utilizado para la instalación inicial.

---

🌐 **También disponible en:**
[Українська](docs/README.uk.md) ·
[Deutsch](docs/README.de.md) ·
[Français](docs/README.fr.md) ·
[Español](docs/README.es.md) ·
[Italiano](docs/README.it.md) ·
[Polski](docs/README.pl.md) ·
[Čeština](docs/README.cs.md) ·
[Slovenčina](docs/README.sk.md) ·
[Nederlands](docs/README.nl.md) ·
[Norsk](docs/README.no.md) ·
[Dansk](docs/README.da.md) ·
[Svenska](docs/README.sv.md) ·
[Suomi](docs/README.fi.md) ·
[Português (BR)](docs/README.pt-BR.md) ·
[Português (PT)](docs/README.pt-PT.md) ·
[Română](docs/README.ro.md) ·
[中文 (简体)](docs/README.zh-CN.md)

---

## Tabla de contenidos

- [Qué añade OrgPortal a FreeScout](#qué-añade-orgportal-a-freescout)
- [Organizaciones](#organizaciones)
- [Unidades Estructurales — Control de Acceso a Nivel de Departamento](#unidades-estructurales--control-de-acceso-a-nivel-de-departamento)
- [Org Snapshot — Atribución Permanente de Tickets](#org-snapshot--atribución-permanente-de-tickets)
- [Integración Kanban](#integración-kanban)
- [Integración de Campos Personalizados](#integración-de-campos-personalizados)
- [Control de Acceso y Permisos](#control-de-acceso-y-permisos)
- [Configuración del Sistema](#configuración-del-sistema--manage--organizations--system-tab)
- [End-User Portal — Autoservicio para Gerentes Corporativos](#end-user-portal--autoservicio-para-gerentes-corporativos-opcional)
- [Campana de Notificación en Tiempo Real](#campana-de-notificación-en-tiempo-real-opcional)
- [Suscripciones de Notificación](#suscripciones-de-notificación-opcional)
- [Configuración de Organización del Portal](#configuración-de-organización-del-portal)
- [Plantillas de Correo de Notificación Multilingües](#plantillas-de-correo-de-notificación-multilingües-opcional)
- [API REST](#api-rest-opcional)
- [Instalación](#instalación)
- [Actualizaciones Automáticas](#actualizaciones-automáticas)
- [Compatibilidad de Módulos](#compatibilidad-de-módulos)
- [Configuración](#configuración)
- [Traducciones](#traducciones)
- [Capturas de pantalla](#capturas-de-pantalla)
- [Licencia](#licencia)

---

## Qué añade OrgPortal a FreeScout

FreeScout está construido alrededor de clientes individuales — cada correo electrónico proviene de una persona, y no hay concepto integrado de empresa en la que esa persona trabaja. Esto funciona bien para servicio al cliente B2C. Para B2B, se queda corto.

OrgPortal cierra esa brecha:

- **Cuentas de empresas** — agrupa clientes en organizaciones con nombre, insignia de color, alcance de buzón y estado activo/inactivo
- **Jerarquías de departamentos** — divide organizaciones en unidades estructurales (departamentos, sucursales, equipos); cada miembro está limitado a su unidad
- **Acceso basado en roles** — `member` ve solo sus propios tickets; `unit_manager` ve toda la unidad; `manager` ve toda la organización
- **Portal de autoservicio corporativo** — los gerentes ven todos los tickets de la empresa, responden, cierran, reasignan autores y gestionar preferencias de notificación sin contactar a tu equipo
- **Atribución permanente de tickets** — cada ticket se captura en su organización en el momento de la creación; los informes históricos persisten a cambios en la lista de clientes
- **Notificaciones multilingües** — alertas de correo automatizadas en el idioma de cada gerente, con plantillas por idioma y un editor WYSIWYG incorporado
- **API REST** — sincroniza membresías desde tu CRM, automatiza incorporación, gestiona etiquetas programáticamente

---

## Organizaciones

*Un único lugar para todo sobre una cuenta corporativa.*

**Manage → Organizations** abre una interfaz con pestañas y tres secciones: Organizations, Templates y System.

### Lista de organizaciones

- **Crear, editar, eliminar, activar/desactivar** organizaciones
- **Filtro de estado** — alterna entre Active / Inactive / All con un grupo de opciones; filtra la tabla instantáneamente del lado del cliente
- **Búsqueda en directo** — comienza a filtrar con 2+ caracteres, sin recarga de página
- **Insignias con código de color** — selector de color interactivo con 12 muestras y vista previa de insignia en directo junto al selector; la insignia aparece en cada ticket y tarjeta Kanban
- Hacer clic en la insignia o en el número de tickets abre una búsqueda de FreeScout filtrada a esa organización
- **Vinculación de buzones** — las organizaciones pueden ser globales (todos los buzones) o limitadas a un buzón específico
- **Columna de etiquetas** — muestra ✓/✗ si hay etiquetas de FreeScout vinculadas a la organización (se requiere módulo Tags); las etiquetas se asignan en el formulario de edición con un widget basado en fichas y búsqueda con autocompletado
- **Columna de número de tickets** — total de conversaciones por organización; enlace clickeable a resultados de búsqueda completos
- **Columna de número de miembros**
- **Activar / desactivar** — suspende una cuenta sin perder historial; requiere que Org Snapshot esté habilitado (el botón está deshabilitado con una información flotante cuando no lo está)
- **Eliminar** — disponible solo cuando la organización tiene 0 miembros y 0 tickets (protección de seguridad)
- Todas las acciones de eliminar y desactivar requieren confirmación

![Lista de organizaciones — filtro de estado, búsqueda en directo, insignias de color, etiquetas, números de tickets](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png)

### Formulario de edición de organización

- **Nombre** y **vinculación de buzón**
- **Selector de color** — 12 muestras con vista previa de insignia en directo
- **Etiquetas** — widget basado en fichas: escribe para buscar etiquetas existentes de FreeScout, haz clic para añadir, × para eliminar
- **Tabla de miembros** — por miembro: nombre, rol, unidad estructural, casilla de verificación `can_manage_org` (otorga acceso administrativo a organizaciones sin derechos administrativos completos), alternador activo/inactivo
- **Panel de unidades estructurales** — crea y renombra unidades directamente en el formulario de edición; los miembros se asignan a unidades en la misma vista
- **Añadir un miembro** — rellena automáticamente conversaciones existentes no atribuidas para ese cliente

![Edición de organización — selector de color, fichas de etiquetas, tabla de miembros con roles y unidades](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

### Integración de perfil de cliente

- **Campo de organización en el formulario de edición de cliente de FreeScout** — búsqueda con autocompletado en directo para organizaciones; el menú desplegable de rol aparece después de seleccionar una org; botón × para eliminar
- **Enlace de acceso directo "Ver tickets de org"** en el formulario de cliente
- **Bloque de información de org en la barra lateral del ticket del administrador** — nombre de la organización (enlace clickeable a la página de edición de org), unidad estructural y rol de miembro; alterna visibilidad por buzón en configuración
- **Un miembro activo por cliente** — un cliente no puede añadirse a una segunda organización mientras tenga una membresía activa; se permiten membresías inactivas/archivadas

![Customer edit](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png)

![Conversation — organization badge](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/conversation-org-badge.png)

---

## Unidades Estructurales — Control de Acceso a Nivel de Departamento

*Apoya a grandes empresas con jerarquías internas complejas.*

Las organizaciones pueden dividirse en **unidades estructurales** ilimitadas (departamentos, sucursales, oficinas regionales, equipos de proyecto):

- Crea, renombra y elimina unidades en el formulario de edición de org del administrador, o directamente desde el portal (solo gerentes globales)
- Asigna miembros a unidades — cada miembro pertenece a una unidad
- **Eliminar una unidad** degrada automáticamente sus miembros `unit_manager` a `member`

**Tres niveles de rol:**

| Rol | Alcance de acceso |
|------|-------------|
| `member` | Solo sus propios tickets |
| `unit_manager` | Todos los tickets dentro de su unidad estructural |
| `manager` (global) | Todos los tickets en toda la organización |

- Los gerentes de unidad tienen capacidades completas del portal — respuestas, adjuntos, reasignación de autor, cerrar/reabrir, gestión de notificaciones — limitadas estrictamente a su unidad
- El acceso a tickets y la entrega de notificaciones se aplican en los límites de unidades

![Edición de organización — miembros con roles y unidades, panel de gestión de unidades](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

---

## Org Snapshot — Atribución Permanente de Tickets

*Informes históricos confiables incluso mientras cambia tu cartera de clientes.*

Cuando se crea un ticket, OrgPortal registra el contexto de la organización como captura permanente:

- `org_id`, `org_unit_id` y `org_attributed_at` se escriben en la conversación en el momento de la creación
- **Inmutable** — si un cliente luego abandona una organización, sus tickets históricos siguen atribuidos a esa org; los informes nunca se rompen
- **Añadir un miembro** desencadena relleno automático de conversaciones existentes no atribuidas de ese cliente

### Fuente de atribución — tres modos

Configurado en **Manage → Organizations → System tab**:

| Modo | Comportamiento |
|------|----------|
| `member` | Atribuye el ticket a la organización de la que es miembro el autor del ticket |
| `tag` | Atribuye por etiqueta de FreeScout vinculada a una org primero; retrocede a membresía si no hay coincidencia de etiqueta |
| `tag_only` | Atribuye exclusivamente por etiqueta; la membresía no se usa |

Los modos `tag` y `tag_only` están deshabilitados cuando el módulo Tags está inactivo.

### Herramientas de relleno

- **Barra de progreso** — muestra X / Y tickets atribuidos (%) con un indicador "complete" cuando se hace
- **Estadísticas previas** — antes de ejecutar el relleno, un desglose muestra cuántos tickets se atribuirán por etiqueta vs. por membresía vs. no coincidentes
- **Botón Run backfill** — procesa hasta 2000 tickets por clic; el resumen de resultados (by_tag / by_member / unmatched) se muestra después
- **Auto-cron** (`attribution_cron_enabled`) — programa relleno cada 5 minutos, 1000 tickets por ejecución, sin solapamiento
- **Restablecer atribución** — borra todas las capturas de org (acción peligrosa, requiere confirmación)
- Línea de comandos: `php artisan orgportal:backfill-attribution`

![Pestaña System — fuente de atribución, barra de progreso, estadísticas previas, controles de relleno](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png)

---

## Integración Kanban

*Mantén tu flujo de trabajo visual alineado con tus cuentas B2B.*

- Insignia de organización en cada tarjeta Kanban con el color asignado de la cuenta
- **Filtro de organización** en el panel de filtros Kanban — modal multiselecta con casillas de verificación; el estado del filtro persiste en la navegación
- **Etiquetas de filtro de estado Kanban multilingües** — asigna un nombre personalizado a cada columna Kanban por idioma del portal; cambia idiomas con el selector de idioma en configuración por buzón; arrastra para reordenar filtros
- Las etiquetas traducidas aparecen tanto en la barra de filtros del portal como en la columna **State** de la tabla de tickets de la empresa; cadena de retroceso: idioma guardado → idioma inglés guardado → nombre de columna original

![Kanban — insignias de organización en tarjetas y modal de filtro de org](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png)

---

## Integración de Campos Personalizados

*Muestre los datos del módulo Campos Personalizados directamente en la página del ticket del portal.*

Requiere que el módulo [Custom Fields](https://freescout.net/module/custom-fields/) esté instalado y activo.

- Un panel por buzón en Configuración de Buzón → OrgPortal le permite elegir qué campos personalizados aparecen en la página del ticket del portal
- Arrastre los campos para reordenarlos; cada campo puede tener una etiqueta personalizada por idioma del portal, con reserva a la etiqueta en inglés guardada y luego al nombre original del campo
- En la página del ticket del portal, los campos habilitados se muestran en una cuadrícula responsiva de dos columnas entre el asunto del ticket y el hilo — solo se muestran los campos con un valor no vacío
- Totalmente opcional — el panel y el bloque de la página del ticket se ocultan automáticamente cuando el módulo Campos Personalizados no está instalado o no está activo

---

## Control de Acceso y Permisos

*Delega gestión de organizaciones sin otorgar acceso administrativo.*

- **"Permitir gestión de organizaciones"** (`can_manage_org`) — dos niveles:
  - Como **permiso de usuario** en configuración de agente — permite a un líder de equipo de soporte gestionar todas las organizaciones sin derechos administrativos
  - Como **indicador por miembro** en el formulario de edición de organización — permite a un miembro específico de org gestionar esa organización desde el panel administrativo
- **"Permitir gestión de plantillas de notificación"** — permiso granular separado para edición de plantillas
- La eliminación de organizaciones sigue siendo exclusivamente solo para administrador
- El acceso al portal está estrictamente limitado por buzón: un gerente de la Organización A no puede acceder a la Organización B

![Permisos granulares — permitir gestión de organizaciones y plantillas de notificación](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png)

---

## Configuración del Sistema — Manage → Organizations → System tab

*Controles solo para administrador para atribución, relleno e interruptor de idioma del portal.*

La pestaña **System** es visible solo para administradores de FreeScout.

### Panel 1: Atribución de Tickets

Consulta [Org Snapshot](#org-snapshot--atribución-permanente-de-tickets) arriba para la descripción completa de modos de atribución, herramientas de relleno y auto-cron.

### Panel 2: Interruptor de Idioma del Portal

- **Habilitar/deshabilitar** el interruptor de idioma en la barra de navegación de End-User Portal
- **Elige cuál de los 19 idiomas** ofrecer (cuadrícula de casillas de verificación); todos están habilitados por defecto
- Cuando está habilitado, los gerentes pueden cambiar el idioma del portal; su elección se guarda y se usa para correos de notificación
- Este es el interruptor de idioma integrado de OrgPortal — funciona independientemente de cualquier módulo de cambio de idioma de terceros; ambos pueden coexistir

---

## End-User Portal — Autoservicio para Gerentes Corporativos *(opcional)*

*Da a tus clientes B2B un portal donde gestionen su relación de soporte con la empresa — sin contactar a tu equipo para cada actualización de estado.*

Requiere el módulo [End-User Portal](https://freescout.net/module/end-user-portal/).

### Panel de Tickets de Empresa

Una sección dedicada de **Company Tickets** en la navegación del portal con una tabla de tickets con todas las características:

| Columna | Descripción |
|--------|-------------|
| **#** | ID de Ticket |
| **Subject** | Truncado con información flotante al pasar el ratón |
| **Responsible** | Agente de soporte asignado |
| **Author** | Cliente que abrió el ticket; haz clic para filtrar por este autor |
| **Status** | Active / Pending / Closed / Spam con iconos |
| **State** | Nombre de columna Kanban en el idioma actual del portal (solo cuando el módulo Kanban está activo) |
| **Updated** | Fecha y hora de la última respuesta |

**Dos indicadores de estado de lectura independientes por fila** — estos rastrean dos personas diferentes y se muestran simultáneamente:

| Indicador | Estado de lectura de | Qué significa |
|-----------|-------------------|---------------|
| **Fila en negrita** | El gerente que ve el portal | El gerente tiene notificaciones no leídas para esta conversación — algo pasó que no han visto aún |
| **👁 Icono de ojo** | El autor del ticket (el cliente que lo envió) | El autor no ha abierto la última respuesta del agente — útil para saber si un cliente realmente vio la respuesta |

Estos dos estados son completamente independientes: una fila puede estar en negrita (el gerente no ha leído) mientras el ojo está ausente (el autor ya leyó), o viceversa. El gerente ve ambos al mismo tiempo, dando una imagen completa de lo que está sucediendo en ambos lados del ticket sin abrirlo.

**Filtro de autor** — hacer clic en un nombre de autor activa un filtro; un banner aparece en la parte superior de la tabla mostrando el nombre del autor activo con un enlace × para limpiar el filtro.

Tanto la tabla de escritorio como un **diseño de tarjeta receptivo para móvil** se incluyen; cambian automáticamente según el ancho de pantalla.

La plantilla de barra de filtros soporta **anulación** vía `enduserportal::partials.tickets_filters` — coloca una vista personalizada en esa ruta para reemplazar la barra de filtros predeterminada de OrgPortal mientras mantienes toda la demás funcionalidad.

![Tickets de empresa — tabla completa con indicadores de lectura, banner de filtro de autor, filtros de estado](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png)

### Acciones de Ticket en el Portal

Los gerentes pueden tomar acción directamente — no hay necesidad de contactar a soporte:

- **Responder con adjuntos** — arrastra y suelta, múltiples archivos por respuesta; nombres de adjuntos y tamaños de archivo mostrados en cada hilo
- **Cerrar ticket** — una nueva respuesta lo reabre automáticamente; un banner informa al gerente de esto cuando el ticket está cerrado
- **Cambiar autor del ticket** — reasigna un ticket a otro miembro de la organización
- **Filtrar por unidad** — gerentes globales filtran la lista de tickets por unidad estructural
- **Filtrar por estado Kanban** — configurable por buzón, etiquetas mostradas en el idioma actual del portal

![Vista de ticket del portal — formulario de respuesta con adjuntos arrastra y suelta y banner de ticket cerrado](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png)

### Rastreo de Visualización de Gerente

- Una nota **"viewed"** aparece bajo respuestas del agente en la vista de ticket del administrador cuando un gerente abre el ticket en el portal
- Muestra nombre del gerente, rol (Gerente de organización / Gerente de unidad) y tiempo transcurrido
- Vistas de gerente global y gerente de unidad rastreadas y mostradas independientemente — misma UX que el "Cliente visto" nativo de FreeScout

![Rastreo de visualización de gerente — la nota 'viewed' aparece bajo la respuesta del agente en la vista de ticket del administrador](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png)

---

## Campana de Notificación en Tiempo Real *(opcional)*

*Mantén a los gerentes informados en el momento en que algo sucede con los tickets de su empresa.*

Requiere el módulo [End-User Portal](https://freescout.net/module/end-user-portal/).

- 🔔 Icono de campana con insignia de número no leído en directo en la barra de navegación de EUP — se reposiciona automáticamente en móvil (junto al menú de hamburguesa)
- Notificaciones para: **nuevo ticket**, **respuesta del agente**, **respuesta del cliente** — para todos los roles de gerente
- Panel desplegable con notificaciones agrupadas por fecha: nombre del actor, tipo de evento, número de ticket, vista previa de mensaje, marca de tiempo
- **Auto-marcar como leído** cuando el gerente abre el ticket
- Marcar notificaciones individuales como leídas vía ×; **Marcar todo como leído** en el encabezado del panel
- Sondea cada 15 segundos; se actualiza en la navegación hacia atrás/adelante del navegador (consciente de bfcache)

---

## Suscripciones de Notificación *(opcional)*

*Permite que los gerentes decidan qué escuchan — nada más, nada menos.*

- **Matriz de suscripción visual** en la pestaña "Notifications" en Configuración de Organización del portal
- **Tres tipos de eventos:** Nuevo ticket · Respuesta del agente · Respuesta del cliente
- **Dos niveles de alcance:** Organización completa (gerentes globales) · Unidades estructurales individuales
- Miembros sin unidad se agrupan en una fila expandible separada **"No unit"**
- **Anulaciones por miembro** — expande cualquier fila de unidad para revelar miembros individuales y alterna sus suscripciones en línea; gerentes de unidad con rol limitado están etiquetados en consecuencia
- **Lógica en cascada en ambas direcciones:**
  - Habilitar "Organización completa" → habilita todas las unidades y todos los miembros
  - Habilitar una unidad → habilita todos sus miembros
  - Deshabilitar un miembro → auto-reconcilia las casillas de verificación de unidad y organización
- Los gerentes globales gestionar todos los miembros; los gerentes de unidad gestionan solo su propia unidad
- Las notificaciones usan el controlador de correo del buzón correspondiente

![Matriz de suscripción de notificación — alternadores por unidad y por miembro](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png)

---

## Configuración de Organización del Portal

*Los gerentes configuran su estructura organizacional sin acceso administrativo.*

**Organization Settings** en la navegación del portal tiene tres pestañas:

### Pestaña Notifications

La matriz de suscripción descrita arriba.

### Pestaña Units *(solo gerentes globales)*

- **Crear unidad** — formulario en línea con campo de nombre
- **Renombrar unidad** — edición en línea directamente en la fila de la tabla
- **Eliminar unidad** — botón con confirmación; los gerentes de unidad se degradan automáticamente a miembro
- Número de miembros mostrado por unidad

### Pestaña Members

- Tabla de todos los miembros de la organización: nombre, unidad estructural, rol, insignia de estado activo/inactivo
- **Etiqueta "Gerente global"** mostrada junto al nombre del miembro donde sea aplicable
- **Mostrar desactivados** casilla de verificación — aparece solo cuando existen miembros inactivos; oculto por defecto
- **Gerentes globales** pueden actualizar la unidad y rol de cualquier miembro con un formulario en línea (seleccione unidad + seleccione rol + Apply)
- **Los gerentes globales no pueden promover a un miembro a gerente global** desde el portal — esto requiere acceso administrativo
- **Activar / desactivar** botón por miembro con confirmación para desactivación

![Configuración de Organización del Portal — pestañas Units y Members](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png)

---

## Plantillas de Correo de Notificación Multilingües *(opcional)*

*Tus clientes corporativos reciben correos de soporte en su propio idioma — automáticamente, sin esfuerzo manual.*

Configurado en **Manage → Organizations → Templates tab** (visible para usuarios con permiso de "manage templates").

- **Plantillas por idioma** — asunto y cuerpo separados para cada idioma del portal; cambia entre ellos con el menú desplegable de idioma; los valores se intercambian en memoria sin recarga de página
- **Paneles colapsables** por tipo de evento (Nuevo ticket / Respuesta del agente / Respuesta del cliente) — el editor Summernote se inicializa de manera perezosa cuando se abre un panel
- **Botón Load Default** en cada panel — restaura la plantilla integrada para el idioma actualmente seleccionado (retrocede al integrado en inglés si no existe un predeterminado específico de idioma)
- **Editor WYSIWYG Summernote** para composición de correo HTML enriquecido
- **Selector de variable macro** — inserta placeholders en asunto o cuerpo con un clic; la posición del cursor se preserva en el campo de asunto
- **19 plantillas integradas predeterminadas** — listas para usar del cuadro; no se requiere configuración

**Variables macro disponibles:**

| Variable | Descripción |
|----------|-------------|
| `{manager_name}` | Nombre del gerente que recibe la notificación |
| `{author_name}` | Cliente que creó o respondió al ticket |
| `{org_name}` | Nombre de la organización |
| `{unit_name}` | Nombre de la unidad estructural |
| `{subject}` | Asunto del ticket |
| `{ticket_number}` | ID del ticket |
| `{ticket_url}` | Enlace directo al ticket en el portal |
| `{ticket_text}` | Texto completo del mensaje inicial (HTML) |
| `{reply_text}` | Texto completo de la última respuesta (HTML) |
| `{created_date}` | Fecha de creación del ticket |
| `{created_time}` | Hora de creación del ticket |
| `{created_datetime}` | Fecha y hora de creación del ticket |
| `{reply_date}` | Fecha de respuesta |
| `{reply_time}` | Hora de respuesta |
| `{reply_datetime}` | Fecha y hora de respuesta |

**Cadena de retroceso:** plantilla de idioma guardada → plantilla de idioma integrada → plantilla en inglés guardada → plantilla integrada en inglés

El idioma de la notificación se determina por la selección de idioma del portal de cada gerente, guardado automáticamente cuando usan el interruptor de idioma.

![Plantillas de correo — paneles colapsables por idioma, botón Load Default, editor Summernote](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png)

---

## API REST *(opcional)*

*Integra OrgPortal en tu CRM, ERP o flujo de trabajo de incorporación de clientes.*

Requiere el módulo [API and Webhooks](https://freescout.net/module/api-webhooks/).

- CRUD completo para organizaciones, unidades estructurales, membresías de clientes y etiquetas
- **Campos de organización:** `name`, `color`, `mailboxId`, `isActive` — todos legibles y actualizables vía API
- **Sub-recurso Members** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — actualiza rol, unidad, `canManageOrg` e indicador `isActive` por miembro independientemente sin tocar el resto de la membresía
- **Sub-recurso Tags** — `GET/PUT /api/organizations/{id}/tags` — lista o reemplaza completamente vinculaciones de etiquetas (requiere módulo Tags; retorna `503` si está inactivo)
- Autenticación vía encabezado `X-FreeScout-API-Key` o parámetro de consulta `api_key`
- Documentación interactiva **ReDoc** en **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Referencia completa de API → [docs/api/README.md](docs/api/README.md)**

![Documentación API interactiva — ReDoc con todos los puntos finales de OrgPortal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png)

---

## Instalación

> [!IMPORTANT]
> Descarga `OrgPortal.zip` desde la **[página de Releases](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)** — **no** uses "Code → Download ZIP" ni clones el repositorio. Solo el ZIP de la versión tiene la estructura correcta para FreeScout y es compatible con las actualizaciones automáticas.

1. Descarga `OrgPortal.zip` desde la [última versión](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)
2. Extrae y copia la carpeta `OrgPortal` en `Modules/` de tu instalación de FreeScout
2. Ve a **Manage → Modules → OrgPortal → Activate**
3. Ejecuta migraciones:
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Borra caché:
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **El soporte del idioma georgiano** se implementa automáticamente en el primer inicio — no se requiere copia manual de archivos.

---

## Actualizaciones Automáticas

OrgPortal soporta **actualizaciones de un clic** vía el mecanismo integrado de actualización de módulos de FreeScout.

> **Requiere FreeScout 1.8.170 o posterior.** En versiones anteriores, actualiza manualmente reemplazando la carpeta `OrgPortal` con el ZIP de lanzamiento más reciente.

Cuando una nueva versión está disponible, un banner aparece en **Manage → Modules**. Haz clic en **Update now** — FreeScout descarga e instala la última versión automáticamente.

---

## Compatibilidad de Módulos

| Módulo | Estado | Notas |
|--------|--------|-------|
| End-User Portal ≥ 1.0.85 | Opcional | Portal del gerente, campana de notificación, suscripciones |
| API and Webhooks ≥ 1.0.80 | Opcional | Puntos finales de API REST |
| Kanban ≥ 1.0.23 | Opcional | Insignia en tarjetas, filtro de org, etiquetas de columna State multilingües |
| Custom Fields | ✅ Compatible | — |
| Workflows | ✅ Compatible | — |
| Tags | ✅ Compatible | Fichas de etiquetas en formulario de edición de org; vinculaciones de etiquetas vía API (`/organizations/{id}/tags`); atribución de tickets basada en etiquetas |

---

## Configuración

### Configuración Global — **Manage → Organizations → System tab**

| Opción | Descripción |
|--------|-------------|
| Show badge on ticket page | Insignia de org en lista de conversaciones y vista de ticket |
| Show badge on Kanban cards | Insignia de org en tarjetas del tablero Kanban |
| Attribution source | `member` / `tag` / `tag_only` — cómo se atribuyen los tickets a organizaciones |
| Auto-cron backfill | Ejecuta relleno cada 5 minutos automáticamente |
| Snapshot visibility | Mostrar/ocultar datos de atribución en barra lateral del ticket |
| Portal Language Switcher | Habilita interruptor de idioma en barra de navegación de EUP; elige cuál de 19 idiomas ofrecer |

### Configuración Por Buzón — **Mailbox Settings → OrgPortal**

Anula valores globales para el buzón específico.

| Opción | Descripción |
|--------|-------------|
| Show badge on ticket page | Habilitar/deshabilitar insignia para este buzón |
| Show badge on Kanban cards | Habilitar/deshabilitar insignia para este buzón |
| Show organization block in customer profile | Alterna bloque de información de org en la barra lateral del ticket |
| Company ticket status filters | Asigna columnas Kanban a filtros nombrados en el portal; etiquetas por idioma con selector de idioma; arrastra para reordenar |

![Configuración por buzón — visibilidad de insignia y filtros de estado Kanban con etiquetas multilingües](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png)

---

## Traducciones

OrgPortal está completamente localizado en **19 idiomas**:

| Idioma | Código | Idioma | Código |
|----------|------|----------|------|
| English | `en` | Dutch | `nl` |
| Ukrainian | `uk` | Norwegian | `no` |
| German | `de` | Danish | `da` |
| French | `fr` | Swedish | `sv` |
| Spanish | `es` | Finnish | `fi` |
| Italian | `it` | Portuguese (BR) | `pt-BR` |
| Czech | `cs` | Portuguese (PT) | `pt-PT` |
| Slovak | `sk` | Romanian | `ro` |
| Polish | `pl` | Chinese Simplified | `zh-CN` |
| Georgian | `ka` | | |

Archivos de traducción: `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Las plantillas de correo de notificación tienen predeterminados integrados para los 19 idiomas.

### Integración de Interruptor de Idioma

OrgPortal incluye un interruptor de idioma del portal integrado (habilita en **System tab → Portal Language Switcher**). También se integra con [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — ambos pueden estar activos simultáneamente.

El idioma que selecciona un gerente se aplica a todas las cadenas de UI de OrgPortal y se guarda como su idioma de notificación — los correos se envían en su idioma elegido automáticamente.

> **Nota técnica:** El middleware `OrgPortalSetLocale` re-aplica la configuración regional del portal después del middleware `Localize` de FreeScout para evitar que se restablezca al predeterminado del sistema en cada solicitud.

---

## Capturas de pantalla

| | |
|---|---|
| ![Lista de organizaciones](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png) | ![Edición de organización](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png) |
| *Lista de organizaciones — filtro de estado, búsqueda en directo, insignias de color* | *Edición de organización — selector de color, fichas de etiquetas, tabla de miembros* |
| ![Pestaña System](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/system-settings.png) | ![Edición de cliente](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png) |
| *Pestaña System — modos de atribución, relleno, interruptor de idioma* | *Edición de cliente — campo de org con autocompletado* |
| ![Portal de tickets de empresa](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png) | ![Respuesta del portal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png) |
| *Tickets de empresa — tabla, filtro de autor, indicadores de lectura* | *Portal de ticket — respuesta con adjuntos, banner de ticket cerrado* |
| ![Configuración de organización del portal](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings.png) | ![Campana de notificación](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-bell.png) |
| *Configuración de Org del Portal — pestañas Units y Members* | *Campana de notificación en tiempo real con desplegable* |
| ![Matriz de suscripción](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png) | ![Plantillas de correo](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png) |
| *Matriz de suscripción de notificación — por unidad, por miembro* | *Plantillas de correo — selector de idioma, Load Default, Summernote* |
| ![Integración Kanban](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png) | ![Configuración de buzón](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png) |
| *Kanban — insignias de org y modal de filtro de org* | *Configuración por buzón — filtros Kanban con etiquetas multilingües* |
| ![Documentación de API](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png) | |
| *Documentación de API interactiva — ReDoc* | |

---

## Licencia

[MIT](LICENSE) — © 2026 ASTIN-UA
