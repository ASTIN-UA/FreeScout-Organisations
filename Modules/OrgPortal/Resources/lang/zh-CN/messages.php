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
];
