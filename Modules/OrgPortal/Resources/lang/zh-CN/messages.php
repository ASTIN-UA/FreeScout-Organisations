<?php

return [

    // Admin — organizations list
    'organizations'          => '组织',
    'new_organization'       => '新建组织',
    'no_organizations'       => '暂无组织。',
    'create_one'             => '创建一个',

    // Admin — mailbox scope
    'mailbox'                => '邮箱',
    'global_scope'           => '全局（所有邮箱）',
    'mailbox_scope_hint'     => '留空可在所有邮箱中显示此组织。',

    // Admin — create / edit form
    'organization_name'      => '组织名称',
    'create_organization'    => '创建组织',
    'org_details'            => '组织详情',
    'cancel'                 => '取消',
    'save'                   => '保存',
    'back'                   => '返回',
    'edit'                   => '编辑',
    'delete'                 => '删除',
    'confirm_delete_org'     => '确定删除此组织吗？',

    // Admin — flash messages
    'org_created'            => '组织已创建。',
    'org_updated'            => '组织已更新。',
    'org_deleted'            => '组织已删除。',

    // Admin — badge color
    'badge_color'            => '徽章颜色',
    'color_default'          => '默认（灰色）',
    'preview'                => '预览',

    // Admin — members table
    'name'                   => '名称',
    'email'                  => 'Email',
    'members'                => '成员',
    'role'                   => '角色',
    'member'                 => '成员',
    'manager'                => '管理员',
    'deleted_customer'       => '已删除客户',
    'no_members'             => '暂无成员。',
    'remove'                 => '移除',
    'confirm_remove_member'  => '确定移除此成员吗？',

    // Admin — add member form
    'add_member'             => '添加成员',
    'search_customer'        => '搜索客户',
    'type_name_or_email'     => '输入名称或邮箱…',

    // Admin — member flash messages
    'role_updated'           => '角色已更新。',
    'member_added'           => '成员已添加。',
    'member_removed'         => '成员已移除。',
    'already_member'         => '此客户已是组织成员。',
    'already_in_org'         => '此客户已属于另一个组织。',

    // Portal — company tickets
    'company_tickets'        => '公司工单',
    'my_tickets'             => '我的工单',
    'no_org_tickets'         => '未找到您的组织工单。',
    'unknown'                => '未知',
    'from'                   => '来自',
    'subject'                => '主题',
    'ticket_hash'            => '工单 #',
    'updated'                => '更新于',
    'no_subject'             => '（无主题）',
    'responsible'            => '负责人',
    'author'                 => '作者',
    'conv_status'            => '状态',
    'kanban_state'           => '阶段',
    'search_ticket'          => '搜索工单…',
    'filter_by_author'       => '显示此作者的工单',
    'status_active'          => '活跃',
    'status_pending'         => '待处理',
    'status_closed'          => '已关闭',
    'status_spam'            => '垃圾',

    // Portal — ticket view
    'support_team'           => '支持团队',
    'customer'               => '客户',
    'reply'                  => '回复',
    'write_reply'            => '输入您的回复…',
    'send_reply'             => '发送回复',
    'reply_sent'             => '回复已发送。',
    'change_author'          => '更改作者',
    'author_changed'         => '工单作者已更新。',

    // Portal — settings
    'org_notification_settings' => '组织通知设置',
    'organization'           => '组织',
    'notify_new_ticket_label'=> '当我的组织成员创建新工单时接收邮件通知',
    'settings_saved'         => '设置已保存。',

    // EUP nav
    'org_settings_nav'       => '组织设置',

    // Conversation badge & search
    'filter_by_org'          => '显示此组织的所有工单',
    'all_organizations'      => '所有组织',
    'remove_filter'          => '移除过滤器',

    // Customer edit form
    'customer_organization'  => '组织',
    'no_organization'        => '— 无 —',
    'customer_role'          => '在组织中的角色',
    'view_org_tickets'       => '查看组织工单',

    // Module settings
    'settings'               => '设置',
    'module_settings'        => 'OrgPortal Settings',
    'display_settings'       => '显示设置',
    'show_badge_conversation'=> '在工单页面显示组织徽章（靠近标签）',
    'show_badge_kanban'      => '在看板卡上显示组织徽章',

    // Kanban filter
    'kanban_filter_org'           => '组织',

    // Company tickets filters (settings)
    'company_filters_heading'     => '公司工单状态过滤器',
    'company_filters_hint'        => '选择在公司工单页面上显示为复选框的看板列。您可以自定义显示给门户用户的标签。',
    'filter_column_id'            => '列 ID',
    'filter_label'                => '标签',
    'filter_add'                  => '添加过滤器',
    'filter_board'                => '看板',
    'company_filters_no_boards'   => '未找到看板。请先创建一个看板。',

    // User permission
    'perm_manage_organizations' => '允许管理组织',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API Docs',

    // Ticket actions
    'close_ticket'                  => '关闭工单',
    'close_ticket_confirm'          => '确定要关闭此工单吗？',
    'ticket_closed'                 => '工单已关闭。',
    'ticket_closed_label'           => '已关闭',
    'ticket_closed_reply_reopens'   => '此工单已关闭。发送回复将重新打开它。',
    'attach_files'                  => '附件',
    'attach_files_hint'             => '最多 :count 个文件，每个最大 :max MB',
    'attach_add_more'               => '添加另一个文件',
    'status_open'                   => '打开',

    // Errors
    'access_denied'          => '访问被拒绝。需要管理员角色。',

    // Email
    'email_hello'            => '您好',
    'email_new_ticket_intro' => '您的组织成员提交了一个新的支持工单：',
    'email_new_ticket_footer'=> '您收到此邮件是因为您在客户门户中启用了组织的新工单通知。',
    'new_ticket_from'        => '来自 :name 的新工单',
    'email_from'             => '来自',
    'email_subject'          => '主题',
    'email_ticket_number'    => '工单 #',
    'view_ticket'            => '查看工单',

    // Org units & notifications
    'activate'               => '激活',
    'add_unit'               => '添加结构单位',
    'apply'                  => '应用',
    'can_manage_org'         => '管理整个组织',
    'can_manage_org_hint'    => '允许此全局管理员从门户提升其他成员为全局管理员。',
    'cannot_deactivate_self' => '您无法停用自己。',
    'cannot_grant_global'    => '您没有权限分配全局管理员。',
    'confirm_deactivate'     => '停用此成员？他们将不再收到工单分配。',
    'confirm_delete_unit'    => '删除此单位？其成员将被取消分配，单位管理员将被降级为成员。',
    'deactivate'             => '停用',
    'email_reply_agent_intro'    => '您的组织中一个工单添加了新的代理回复：',
    'email_reply_customer_intro' => '客户回复了您的组织中的一个工单：',
    'email_reply_subject'        => '回复：:number — :subject',
    'global_grant_hint'      => '设置单位以成为单位管理员。提升为全局管理员需要管理员权限。',
    'macro_author_name'      => '工单作者名称',
    'macro_created_date'     => '创建日期',
    'macro_created_datetime' => '创建日期和时间',
    'macro_created_time'     => '创建时间',
    'macro_manager_name'     => '收件人名称',
    'macro_org_name'         => '组织名称',
    'macro_reply_date'       => '回复日期',
    'macro_reply_datetime'   => '回复日期和时间',
    'macro_reply_text'       => '回复内容',
    'macro_ticket_text'      => '工单内容',
    'macro_reply_time'       => '回复时间',
    'macro_subject'          => '工单主题',
    'macro_ticket_number'    => '工单编号',
    'macro_ticket_url'       => '工单 URL',
    'macro_unit_name'        => '单位名称',
    'member_activated'       => '成员已重新激活。',
    'member_deactivated'     => '成员已停用。',
    'member_status'          => '状态',
    'member_unit'            => '结构单位',
    'member_updated'         => '成员已更新。',
    'no_unit'                => '整个组织',
    'no_units'               => '暂无单位。',
    'notif_event_new_ticket'     => '新工单',
    'notif_event_reply_agent'    => '代理回复',
    'notif_event_reply_customer' => '客户回复',
    'notif_hint'                 => '勾选此框以接收来自所选范围的工单的电子邮件通知。',
    'notif_reply_triggers'       => '回复通知触发器',
    'notif_scope'                => '范围',
    'notif_scope_org'            => '整个组织',
    'notif_trigger_agent'        => '代理回复时通知',
    'notif_trigger_customer'     => '客户回复时通知',
    'notif_trigger_hint'         => '这些设置全局适用。管理员在门户设置页面上订阅特定范围。',
    'org_settings_title'     => '组织设置',
    'perm_manage_templates'     => '允许管理通知模板',
    'rename'                 => '重命名',
    'role_global_manager'    => '全局管理员',
    'role_manager_scoped'    => '管理员',
    'role_member'            => '成员',
    'role_unit_manager'      => '单位管理员',
    'select_member'          => '选择成员',
    'status_member_active'   => '活跃',
    'status_member_inactive' => '已停用',
    'tab_notifications'      => '通知',
    'tab_units'              => '结构单位',
    'tpl_body'                   => '邮件正文',
    'tpl_fallback_hint'          => '（留空以使用内置模板）',
    'tpl_heading'                => '电子邮件模板',
    'tpl_insert_macro'           => '插入变量…',
    'tpl_load_default'           => '加载默认模板',
    'tpl_subject'                => '主题',
    'tpl_subject_placeholder'    => '留空以使用默认值',
    'tpl_tab_title'              => '通知模板',
    'unit_created'           => '单位已创建。',
    'unit_deleted'           => '单位已删除。',
    'unit_exists'            => '具有此名称的单位已存在。',
    'unit_name'              => '结构单位名称',
    'unit_name_placeholder'  => '例如 销售部门',
    'unit_updated'           => '单位已更新。',
];
