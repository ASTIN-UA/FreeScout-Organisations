<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organizações',
    'new_organization'       => 'Nova Organização',
    'no_organizations'       => 'Nenhuma organização ainda.',
    'create_one'             => 'Criar uma',

    // Admin — mailbox scope
    'mailbox'                => 'Caixa de correio',
    'global_scope'           => 'Global (todas as caixas)',
    'mailbox_scope_hint'     => 'Deixe em branco para tornar esta organização visível em todas as caixas de correio.',

    // Admin — create / edit form
    'organization_name'      => 'Nome da Organização',
    'create_organization'    => 'Criar Organização',
    'org_details'            => 'Detalhes da Organização',
    'cancel'                 => 'Cancelar',
    'save'                   => 'Guardar',
    'back'                   => 'Voltar',
    'edit'                   => 'Editar',
    'delete'                 => 'Eliminar',
    'confirm_delete_org'     => 'Eliminar esta organização?',

    // Admin — flash messages
    'org_created'            => 'Organização criada.',
    'org_updated'            => 'Organização actualizada.',
    'org_deleted'            => 'Organização eliminada.',

    // Admin — badge color
    'badge_color'            => 'Cor do distintivo',
    'color_default'          => 'Predefinido (cinzento)',
    'preview'                => 'Pré-visualização',

    // Admin — members table
    'name'                   => 'Nome',
    'email'                  => 'Email',
    'members'                => 'Membros',
    'role'                   => 'Função',
    'member'                 => 'Membro',
    'manager'                => 'Gestor',
    'deleted_customer'       => 'Cliente eliminado',
    'no_members'             => 'Nenhum membro ainda.',
    'remove'                 => 'Remover',
    'confirm_remove_member'  => 'Remover este membro?',

    // Admin — add member form
    'add_member'             => 'Adicionar Membro',
    'search_customer'        => 'Procurar cliente',
    'type_name_or_email'     => 'Digite o nome ou email…',

    // Admin — member flash messages
    'role_updated'           => 'Função actualizada.',
    'member_added'           => 'Membro adicionado.',
    'member_removed'         => 'Membro removido.',
    'already_member'         => 'Este cliente já é membro da organização.',
    'already_in_org'         => 'Este cliente já pertence a outra organização.',

    // Portal — company tickets
    'company_tickets'        => 'Bilhetes da Empresa',
    'my_tickets'             => 'Os Meus Bilhetes',
    'no_org_tickets'         => 'Nenhum bilhete encontrado para a sua organização.',
    'unknown'                => 'Desconhecido',
    'from'                   => 'De',
    'subject'                => 'Assunto',
    'ticket_hash'            => 'Bilhete #',
    'updated'                => 'Actualizado',
    'no_subject'             => '(sem assunto)',
    'responsible'            => 'Responsável',
    'author'                 => 'Autor',
    'conv_status'            => 'Estado',
    'kanban_state'           => 'Posição',
    'search_ticket'          => 'Procurar bilhete…',
    'filter_by_author'       => 'Mostrar bilhetes deste autor',
    'status_active'          => 'Activo',
    'status_pending'         => 'Pendente',
    'status_closed'          => 'Fechado',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Equipa de Suporte',
    'customer'               => 'Cliente',
    'reply'                  => 'Responder',
    'write_reply'            => 'Escreva a sua resposta…',
    'send_reply'             => 'Enviar Resposta',
    'reply_sent'             => 'Resposta enviada.',
    'change_author'          => 'Alterar Autor',
    'author_changed'         => 'Autor do bilhete actualizado.',

    // Portal — settings
    'org_notification_settings' => 'Configurações de Notificação da Organização',
    'organization'           => 'Organização',
    'notify_new_ticket_label'=> 'Receber notificação por email quando um membro da minha organização abre um novo bilhete',
    'settings_saved'         => 'Configurações guardadas.',

    // EUP nav
    'org_settings_nav'       => 'Config. da Org',

    // Conversation badge & search
    'filter_by_org'          => 'Mostrar todos os bilhetes desta organização',
    'all_organizations'      => 'Todas as organizações',
    'remove_filter'          => 'Remover filtro',

    // Customer edit form
    'customer_organization'  => 'Organização',
    'no_organization'        => '— Nenhuma —',
    'customer_role'          => 'Função na organização',
    'view_org_tickets'       => 'Ver Bilhetes da Organização',

    // Module settings
    'settings'               => 'Configurações',
    'module_settings'        => 'OrgPortal Settings',
    'display_settings'       => 'Definições de Apresentação',
    'show_badge_conversation'=> 'Mostrar distintivo da organização na página do bilhete (perto das etiquetas)',
    'show_badge_kanban'      => 'Mostrar distintivo da organização em cartões Kanban',

    // Kanban filter
    'kanban_filter_org'           => 'Organização',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Filtros de Estado de Bilhetes da Empresa',
    'company_filters_hint'        => 'Seleccione quais colunas Kanban aparecem como caixas de selecção na página de Bilhetes da Empresa. Pode personalizar o rótulo mostrado aos utilizadores do portal.',
    'filter_column_id'            => 'ID da Coluna',
    'filter_label'                => 'Rótulo',
    'filter_add'                  => 'Adicionar filtro',
    'filter_board'                => 'Quadro',
    'company_filters_no_boards'   => 'Nenhum quadro Kanban encontrado. Crie um quadro primeiro.',

    // User permission
    'perm_manage_organizations' => 'Permitir gestão de organizações',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API Docs',

    // Ticket actions
    'close_ticket'                  => 'Fechar Bilhete',
    'close_ticket_confirm'          => 'Tem certeza de que quer fechar este bilhete?',
    'ticket_closed'                 => 'Bilhete foi fechado.',
    'ticket_closed_label'           => 'Fechado',
    'ticket_closed_reply_reopens'   => 'Este bilhete está fechado. Enviar uma resposta reabrirá.',
    'attach_files'                  => 'Anexos',
    'attach_files_hint'             => 'Até :count ficheiros, máx :max MB cada',
    'attach_add_more'               => 'Adicionar outro ficheiro',
    'status_open'                   => 'Aberto',

    // Errors
    'access_denied'          => 'Acesso negado. É necessária a função de gestor.',

    // Email
    'email_hello'            => 'Olá',
    'email_new_ticket_intro' => 'Um novo bilhete de suporte foi enviado por um membro da sua organização:',
    'email_new_ticket_footer'=> 'Recebeu este email porque activou notificações de novos bilhetes para a sua organização no Portal do Cliente.',
    'new_ticket_from'        => 'Novo bilhete de :name',
    'email_from'             => 'De',
    'email_subject'          => 'Assunto',
    'email_ticket_number'    => 'Bilhete #',
    'view_ticket'            => 'Ver Bilhete',
];
