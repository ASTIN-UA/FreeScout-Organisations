<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organizaciones',
    'new_organization'       => 'Nueva Organización',
    'no_organizations'       => 'Sin organizaciones aún.',
    'create_one'             => 'Crear una',

    // Admin — mailbox scope
    'mailbox'                => 'Buzón',
    'global_scope'           => 'Global (todos los buzones)',
    'mailbox_scope_hint'     => 'Dejar en blanco para hacer esta organización visible en todos los buzones.',

    // Admin — create / edit form
    'organization_name'      => 'Nombre de la Organización',
    'create_organization'    => 'Crear Organización',
    'org_details'            => 'Detalles de la Organización',
    'cancel'                 => 'Cancelar',
    'save'                   => 'Guardar',
    'back'                   => 'Atrás',
    'edit'                   => 'Editar',
    'delete'                 => 'Eliminar',
    'confirm_delete_org'     => '¿Eliminar esta organización?',

    // Admin — flash messages
    'org_created'            => 'Organización creada.',
    'org_updated'            => 'Organización actualizada.',
    'org_deleted'            => 'Organización eliminada.',

    // Admin — badge color
    'badge_color'            => 'Color de distintivo',
    'color_default'          => 'Predeterminado (gris)',
    'preview'                => 'Vista previa',

    // Admin — members table
    'name'                   => 'Nombre',
    'email'                  => 'Correo electrónico',
    'members'                => 'Miembros',
    'role'                   => 'Rol',
    'member'                 => 'Miembro',
    'manager'                => 'Gestor',
    'deleted_customer'       => 'Cliente eliminado',
    'no_members'             => 'Sin miembros aún.',
    'remove'                 => 'Eliminar',
    'confirm_remove_member'  => '¿Eliminar este miembro?',

    // Admin — add member form
    'add_member'             => 'Agregar Miembro',
    'search_customer'        => 'Buscar cliente',
    'type_name_or_email'     => 'Escriba nombre o correo electrónico…',

    // Admin — member flash messages
    'role_updated'           => 'Rol actualizado.',
    'member_added'           => 'Miembro agregado.',
    'member_removed'         => 'Miembro eliminado.',
    'already_member'         => 'Este cliente ya es miembro de la organización.',
    'already_in_org'         => 'Este cliente ya pertenece a otra organización.',

    // Portal — company tickets
    'company_tickets'        => 'Tickets de Empresa',
    'my_tickets'             => 'Mis Tickets',
    'no_org_tickets'         => 'No se encontraron tickets para su organización.',
    'unknown'                => 'Desconocido',
    'from'                   => 'De',
    'subject'                => 'Asunto',
    'ticket_hash'            => 'Ticket #',
    'updated'                => 'Actualizado',
    'no_subject'             => '(sin asunto)',
    'responsible'            => 'Responsable',
    'author'                 => 'Autor',
    'conv_status'            => 'Estado',
    'kanban_state'           => 'Fase',
    'search_ticket'          => 'Buscar ticket…',
    'filter_by_author'       => 'Mostrar tickets de este autor',
    'status_active'          => 'Activo',
    'status_pending'         => 'Pendiente',
    'status_closed'          => 'Cerrado',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Equipo de Soporte',
    'customer'               => 'Cliente',
    'reply'                  => 'Responder',
    'write_reply'            => 'Escriba su respuesta…',
    'send_reply'             => 'Enviar Respuesta',
    'reply_sent'             => 'Respuesta enviada.',
    'change_author'          => 'Cambiar Autor',
    'author_changed'         => 'Autor del ticket actualizado.',

    // Portal — settings
    'org_notification_settings' => 'Configuración de Notificaciones de Organización',
    'organization'           => 'Organización',
    'notify_new_ticket_label'=> 'Recibir notificación por correo electrónico cuando un miembro de mi organización abre un ticket nuevo',
    'settings_saved'         => 'Configuración guardada.',

    // EUP nav
    'org_settings_nav'       => 'Configuración de Org',

    // Conversation badge & search
    'filter_by_org'          => 'Mostrar todos los tickets de esta organización',
    'all_organizations'      => 'Todas las organizaciones',
    'remove_filter'          => 'Eliminar filtro',

    // Customer edit form
    'customer_organization'  => 'Organización',
    'no_organization'        => '— Ninguna —',
    'customer_role'          => 'Rol en la organización',
    'view_org_tickets'       => 'Ver Tickets de la Organización',

    // Module settings
    'settings'               => 'Configuración',
    'module_settings'        => 'Configuración de OrgPortal',
    'display_settings'       => 'Configuración de Pantalla',
    'show_badge_conversation'=> 'Mostrar distintivo de organización en la página del ticket (cerca de etiquetas)',
    'show_badge_kanban'      => 'Mostrar distintivo de organización en tarjetas Kanban',

    // Kanban filter
    'kanban_filter_org'           => 'Organización',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Filtros de Estado de Tickets de Empresa',
    'company_filters_hint'        => 'Seleccione qué columnas Kanban aparecen como casillas de verificación en la página de Tickets de Empresa. Puede personalizar la etiqueta que se muestra a los usuarios del portal.',
    'filter_column_id'            => 'ID de Columna',
    'filter_label'                => 'Etiqueta',
    'filter_add'                  => 'Agregar filtro',
    'filter_board'                => 'Tablero',
    'company_filters_no_boards'   => 'No se encontraron tableros Kanban. Cree un tablero primero.',

    // User permission
    'perm_manage_organizations' => 'Permitir gestionar organizaciones',

    // ApiWebhooks settings page
    'api_docs_link'          => 'Documentación de API de OrgPortal',

    // Ticket actions
    'close_ticket'                  => 'Cerrar Ticket',
    'close_ticket_confirm'          => '¿Está seguro de que desea cerrar este ticket?',
    'ticket_closed'                 => 'El ticket ha sido cerrado.',
    'ticket_closed_label'           => 'Cerrado',
    'ticket_closed_reply_reopens'   => 'Este ticket está cerrado. Enviar una respuesta lo reabrirá.',
    'attach_files'                  => 'Adjuntos',
    'attach_files_hint'             => 'Hasta :count archivos, máximo :max MB cada uno',
    'attach_add_more'               => 'Agregar otro archivo',
    'status_open'                   => 'Abierto',

    // Errors
    'access_denied'          => 'Acceso denegado. Se requiere rol de gestor.',

    // Email
    'email_hello'            => 'Hola',
    'email_new_ticket_intro' => 'Un nuevo ticket de soporte ha sido enviado por un miembro de su organización:',
    'email_new_ticket_footer'=> 'Recibió este correo electrónico porque habilitó notificaciones de tickets nuevos para su organización en el Portal del Cliente.',
    'new_ticket_from'        => 'Nuevo ticket de :name',
    'email_from'             => 'De',
    'email_subject'          => 'Asunto',
    'email_ticket_number'    => 'Ticket #',
    'view_ticket'            => 'Ver Ticket',

    // Org units & notifications
    'activate'               => 'Activar',
    'add_unit'               => 'Agregar unidad estructural',
    'apply'                  => 'Aplicar',
    'can_manage_org'         => 'Gestiona toda la organización',
    'can_manage_org_hint'    => 'Permite a este gestor global promover otros miembros a gestor global desde el portal.',
    'cannot_deactivate_self' => 'No puede desactivarse a sí mismo.',
    'cannot_grant_global'    => 'No tiene permiso para asignar gestores globales.',
    'confirm_deactivate'     => '¿Desactivar este miembro? Ya no recibirán asignaciones de tickets.',
    'confirm_delete_unit'    => '¿Eliminar esta unidad? Sus miembros serán desasignados y los gestores de unidades degradados a miembros.',
    'deactivate'             => 'Desactivar',
    'email_reply_agent_intro'    => 'Se agregó una nueva respuesta del agente a un ticket en su organización:',
    'email_reply_customer_intro' => 'Un cliente respondió a un ticket en su organización:',
    'email_reply_subject'        => 'Re: :number — :subject',
    'global_grant_hint'      => 'Establezca una unidad para hacer un gestor de unidad. Promocionar a gestor global requiere permiso de administrador.',
    'macro_author_name'      => 'Nombre del autor del ticket',
    'macro_created_date'     => 'Fecha de creación',
    'macro_created_datetime' => 'Fecha y hora de creación',
    'macro_created_time'     => 'Hora de creación',
    'macro_manager_name'     => 'Nombre del destinatario',
    'macro_org_name'         => 'Nombre de la organización',
    'macro_reply_date'       => 'Fecha de respuesta',
    'macro_reply_datetime'   => 'Fecha y hora de respuesta',
    'macro_reply_time'       => 'Hora de respuesta',
    'macro_subject'          => 'Asunto del ticket',
    'macro_ticket_number'    => 'Número del ticket',
    'macro_ticket_url'       => 'URL del ticket',
    'macro_unit_name'        => 'Nombre de la unidad',
    'member_activated'       => 'Miembro reactivado.',
    'member_deactivated'     => 'Miembro desactivado.',
    'member_status'          => 'Estado',
    'member_unit'            => 'Unidad estructural',
    'member_updated'         => 'Miembro actualizado.',
    'no_unit'                => 'Toda la organización',
    'no_units'               => 'Sin unidades aún.',
    'notif_event_new_ticket'     => 'Nuevo ticket',
    'notif_event_reply_agent'    => 'Respuesta del agente',
    'notif_event_reply_customer' => 'Respuesta del cliente',
    'notif_hint'                 => 'Marque la casilla para recibir notificaciones por correo electrónico de tickets del ámbito seleccionado.',
    'notif_reply_triggers'       => 'Disparadores de notificación de respuesta',
    'notif_scope'                => 'Ámbito',
    'notif_scope_org'            => 'Toda la organización',
    'notif_trigger_agent'        => 'Notificar en respuestas del agente',
    'notif_trigger_customer'     => 'Notificar en respuestas del cliente',
    'notif_trigger_hint'         => 'Estos ajustes se aplican globalmente. Los gestores se suscriben a ámbitos específicos en la página de configuración del portal.',
    'org_settings_title'     => 'Configuración de la Organización',
    'perm_manage_templates'     => 'Permitir gestionar plantillas de notificación',
    'rename'                 => 'Renombrar',
    'role_global_manager'    => 'Gestor global',
    'role_manager_scoped'    => 'Gestor',
    'role_member'            => 'Miembro',
    'role_unit_manager'      => 'Gestor de unidad',
    'select_member'          => 'Seleccionar miembro',
    'status_member_active'   => 'Activo',
    'status_member_inactive' => 'Desactivado',
    'tab_notifications'      => 'Notificaciones',
    'tab_units'              => 'Unidades Estructurales',
    'tpl_body'                   => 'Cuerpo del mensaje',
    'tpl_fallback_hint'          => '(dejar en blanco para usar la plantilla integrada)',
    'tpl_heading'                => 'Plantilla de correo electrónico',
    'tpl_insert_macro'           => 'Insertar variable…',
    'tpl_load_default'           => 'Cargar plantilla predeterminada',
    'tpl_subject'                => 'Asunto',
    'tpl_subject_placeholder'    => 'Dejar en blanco para usar el predeterminado',
    'tpl_tab_title'              => 'Plantillas de Notificación',
    'unit_created'           => 'Unidad creada.',
    'unit_deleted'           => 'Unidad eliminada.',
    'unit_exists'            => 'Una unidad con este nombre ya existe.',
    'unit_name'              => 'Nombre de la unidad estructural',
    'unit_name_placeholder'  => 'p. ej. Departamento de Ventas',
    'unit_updated'           => 'Unidad actualizada.',
];
