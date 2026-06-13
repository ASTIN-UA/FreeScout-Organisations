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
    'save'                   => 'Salvar',
    'back'                   => 'Voltar',
    'edit'                   => 'Editar',
    'delete'                 => 'Excluir',
    'confirm_delete_org'     => 'Excluir esta organização?',

    // Admin — flash messages
    'org_created'            => 'Organização criada.',
    'org_updated'            => 'Organização atualizada.',
    'org_deleted'            => 'Organização excluída.',

    // Admin — badge color
    'badge_color'            => 'Cor do distintivo',
    'color_default'          => 'Padrão (cinza)',
    'preview'                => 'Visualizar',

    // Admin — members table
    'name'                   => 'Nome',
    'email'                  => 'Email',
    'members'                => 'Membros',
    'role'                   => 'Função',
    'member'                 => 'Membro',
    'manager'                => 'Gerenciador',
    'deleted_customer'       => 'Cliente excluído',
    'no_members'             => 'Nenhum membro ainda.',
    'remove'                 => 'Remover',
    'confirm_remove_member'  => 'Remover este membro?',

    // Admin — add member form
    'add_member'             => 'Adicionar Membro',
    'search_customer'        => 'Pesquisar cliente',
    'type_name_or_email'     => 'Digite o nome ou email…',

    // Admin — member flash messages
    'role_updated'           => 'Função atualizada.',
    'member_added'           => 'Membro adicionado.',
    'member_removed'         => 'Membro removido.',
    'already_member'         => 'Este cliente já é membro da organização.',
    'already_in_org'         => 'Este cliente já pertence a outra organização.',

    // Portal — company tickets
    'company_tickets'        => 'Tíquetes da Empresa',
    'my_tickets'             => 'Meus Tíquetes',
    'no_org_tickets'         => 'Nenhum tíquete encontrado para sua organização.',
    'unknown'                => 'Desconhecido',
    'from'                   => 'De',
    'subject'                => 'Assunto',
    'ticket_hash'            => 'Tíquete #',
    'updated'                => 'Atualizado',
    'no_subject'             => '(sem assunto)',
    'responsible'            => 'Responsável',
    'author'                 => 'Autor',
    'conv_status'            => 'Status',
    'kanban_state'           => 'Estado',
    'search_ticket'          => 'Pesquisar tíquete…',
    'filter_by_author'       => 'Mostrar tíquetes deste autor',
    'status_active'          => 'Ativo',
    'status_pending'         => 'Pendente',
    'status_closed'          => 'Fechado',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Equipe de Suporte',
    'customer'               => 'Cliente',
    'reply'                  => 'Responder',
    'write_reply'            => 'Escreva sua resposta…',
    'send_reply'             => 'Enviar Resposta',
    'reply_sent'             => 'Resposta enviada.',
    'change_author'          => 'Alterar Autor',
    'author_changed'         => 'Autor do tíquete atualizado.',

    // Portal — settings
    'org_notification_settings' => 'Configurações de Notificação da Organização',
    'organization'           => 'Organização',
    'notify_new_ticket_label'=> 'Receber notificação por email quando um membro da minha organização abre um novo tíquete',
    'settings_saved'         => 'Configurações salvas.',

    // EUP nav
    'org_settings_nav'       => 'Config. da Org',

    // Conversation badge & search
    'filter_by_org'          => 'Mostrar todos os tíquetes desta organização',
    'all_organizations'      => 'Todas as organizações',
    'remove_filter'          => 'Remover filtro',

    // Customer edit form
    'customer_organization'  => 'Organização',
    'no_organization'        => '— Nenhuma —',
    'customer_role'          => 'Função na organização',
    'view_org_tickets'       => 'Ver Tíquetes da Organização',

    // Module settings
    'settings'               => 'Configurações',
    'module_settings'        => 'OrgPortal Settings',
    'display_settings'       => 'Configurações de Exibição',
    'show_badge_conversation'=> 'Mostrar distintivo da organização na página do tíquete (perto das tags)',
    'show_badge_kanban'      => 'Mostrar distintivo da organização em cartões Kanban',

    // Kanban filter
    'kanban_filter_org'           => 'Organização',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Filtros de Status de Tíquetes da Empresa',
    'company_filters_hint'        => 'Selecione quais colunas Kanban aparecem como caixas de seleção na página de Tíquetes da Empresa. Você pode personalizar o rótulo mostrado aos usuários do portal.',
    'filter_column_id'            => 'ID da Coluna',
    'filter_label'                => 'Rótulo',
    'filter_add'                  => 'Adicionar filtro',
    'filter_board'                => 'Quadro',
    'company_filters_no_boards'   => 'Nenhum quadro Kanban encontrado. Crie um quadro primeiro.',

    // User permission
    'perm_manage_organizations' => 'Permitir gerenciamento de organizações',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API Docs',

    // Ticket actions
    'close_ticket'                  => 'Fechar Tíquete',
    'close_ticket_confirm'          => 'Tem certeza de que deseja fechar este tíquete?',
    'ticket_closed'                 => 'Tíquete foi fechado.',
    'ticket_closed_label'           => 'Fechado',
    'ticket_closed_reply_reopens'   => 'Este tíquete está fechado. Enviar uma resposta o reabrirá.',
    'attach_files'                  => 'Anexos',
    'attach_files_hint'             => 'Até :count arquivos, máx :max MB cada',
    'attach_add_more'               => 'Adicionar outro arquivo',
    'status_open'                   => 'Aberto',

    // Errors
    'access_denied'          => 'Acesso negado. É necessária a função de gerenciador.',

    // Email
    'email_hello'            => 'Olá',
    'email_new_ticket_intro' => 'Um novo tíquete de suporte foi enviado por um membro da sua organização:',
    'email_new_ticket_footer'=> 'Você recebeu este email porque ativou notificações de novos tíquetes para sua organização no Portal do Cliente.',
    'new_ticket_from'        => 'Novo tíquete de :name',
    'email_from'             => 'De',
    'email_subject'          => 'Assunto',
    'email_ticket_number'    => 'Tíquete #',
    'view_ticket'            => 'Ver Tíquete',

    // Org units & notifications
    'activate'               => 'Ativar',
    'add_unit'               => 'Adicionar unidade estrutural',
    'apply'                  => 'Aplicar',
    'can_manage_org'         => 'Gerencia toda a organização',
    'can_manage_org_hint'    => 'Permite que este gerenciador global promova outros membros a gerenciador global do portal.',
    'cannot_deactivate_self' => 'Você não pode desativar a si mesmo.',
    'cannot_grant_global'    => 'Você não tem permissão para atribuir gerenciadores globais.',
    'confirm_deactivate'     => 'Desativar este membro? Ele não receberá mais atribuições de tíquetes.',
    'confirm_delete_unit'    => 'Excluir esta unidade? Seus membros serão desatribuídos e gerenciadores de unidades rebaixados a membros.',
    'deactivate'             => 'Desativar',
    'email_reply_agent_intro'    => 'Uma resposta do agente foi adicionada a um tíquete em sua organização:',
    'email_reply_customer_intro' => 'Um cliente respondeu a um tíquete em sua organização:',
    'email_reply_subject'        => 'Re: :number — :subject',
    'global_grant_hint'      => 'Defina uma unidade para fazer um gerenciador de unidade. Promover a gerenciador global requer permissão de administrador.',
    'macro_author_name'      => 'Nome do autor do tíquete',
    'macro_created_date'     => 'Data de criação',
    'macro_created_datetime' => 'Data e hora de criação',
    'macro_created_time'     => 'Hora de criação',
    'macro_manager_name'     => 'Nome do destinatário',
    'macro_org_name'         => 'Nome da organização',
    'macro_reply_date'       => 'Data da resposta',
    'macro_reply_datetime'   => 'Data e hora da resposta',
    'macro_reply_time'       => 'Hora da resposta',
    'macro_subject'          => 'Assunto do tíquete',
    'macro_ticket_number'    => 'Número do tíquete',
    'macro_ticket_url'       => 'URL do tíquete',
    'macro_unit_name'        => 'Nome da unidade',
    'member_activated'       => 'Membro reativado.',
    'member_deactivated'     => 'Membro desativado.',
    'member_status'          => 'Status',
    'member_unit'            => 'Unidade estrutural',
    'member_updated'         => 'Membro atualizado.',
    'no_unit'                => 'Toda a organização',
    'no_units'               => 'Nenhuma unidade ainda.',
    'notif_event_new_ticket'     => 'Novo tíquete',
    'notif_event_reply_agent'    => 'Resposta do agente',
    'notif_event_reply_customer' => 'Resposta do cliente',
    'notif_hint'                 => 'Marque a caixa para receber notificações por email de tíquetes do escopo selecionado.',
    'notif_reply_triggers'       => 'Disparadores de notificação de resposta',
    'notif_scope'                => 'Escopo',
    'notif_scope_org'            => 'Toda a organização',
    'notif_trigger_agent'        => 'Notificar sobre respostas do agente',
    'notif_trigger_customer'     => 'Notificar sobre respostas do cliente',
    'notif_trigger_hint'         => 'Essas configurações se aplicam globalmente. Gerenciadores assinam escopos específicos na página de configurações do portal.',
    'org_settings_title'     => 'Configurações da Organização',
    'perm_manage_templates'     => 'Permitir gerenciamento de modelos de notificação',
    'rename'                 => 'Renomear',
    'role_global_manager'    => 'Gerenciador global',
    'role_manager_scoped'    => 'Gerenciador',
    'role_member'            => 'Membro',
    'role_unit_manager'      => 'Gerenciador de unidade',
    'select_member'          => 'Selecionar membro',
    'status_member_active'   => 'Ativo',
    'status_member_inactive' => 'Desativado',
    'tab_notifications'      => 'Notificações',
    'tab_units'              => 'Unidades Estruturais',
    'tpl_body'                   => 'Corpo da mensagem',
    'tpl_fallback_hint'          => '(deixe em branco para usar o modelo integrado)',
    'tpl_heading'                => 'Modelo de email',
    'tpl_insert_macro'           => 'Inserir variável…',
    'tpl_load_default'           => 'Carregar modelo padrão',
    'tpl_subject'                => 'Assunto',
    'tpl_subject_placeholder'    => 'Deixe em branco para usar o padrão',
    'tpl_tab_title'              => 'Modelos de Notificação',
    'unit_created'           => 'Unidade criada.',
    'unit_deleted'           => 'Unidade excluída.',
    'unit_exists'            => 'Uma unidade com este nome já existe.',
    'unit_name'              => 'Nome da unidade estrutural',
    'unit_name_placeholder'  => 'ex. Departamento de Vendas',
    'unit_updated'           => 'Unidade atualizada.',
];
