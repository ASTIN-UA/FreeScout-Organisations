<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organizations',
    'new_organization'       => 'New Organization',
    'no_organizations'       => 'No organizations yet.',
    'create_one'             => 'Create one',

    // Admin — mailbox scope
    'mailbox'                => 'Mailbox',
    'global_scope'           => 'Global (all mailboxes)',
    'mailbox_scope_hint'     => 'Leave blank to make this organization visible in all mailboxes.',

    // Admin — create / edit form
    'organization_name'      => 'Organization Name',
    'create_organization'    => 'Create Organization',
    'org_details'            => 'Organization Details',
    'cancel'                 => 'Cancel',
    'save'                   => 'Save',
    'back'                   => 'Back',
    'edit'                   => 'Edit',
    'delete'                 => 'Delete',
    'confirm_delete_org'     => 'Delete this organization?',

    // Admin — flash messages
    'org_created'            => 'Organization created.',
    'org_updated'            => 'Organization updated.',
    'org_deleted'            => 'Organization deleted.',

    // Admin — badge color
    'badge_color'            => 'Badge color',
    'color_default'          => 'Default (gray)',
    'preview'                => 'Preview',

    // Admin — members table
    'name'                   => 'Name',
    'email'                  => 'Email',
    'members'                => 'Members',
    'role'                   => 'Role',
    'member'                 => 'Member',
    'manager'                => 'Manager',
    'deleted_customer'       => 'Deleted customer',
    'no_members'             => 'No members yet.',
    'select_member'          => 'Select member',
    'remove'                 => 'Remove',
    'confirm_remove_member'  => 'Remove this member?',

    // Admin — add member form
    'add_member'             => 'Add Member',
    'search_customer'        => 'Search customer',
    'type_name_or_email'     => 'Type name or email…',

    // Admin — member flash messages
    'role_updated'           => 'Role updated.',
    'member_added'           => 'Member added.',
    'member_removed'         => 'Member removed.',
    'already_member'         => 'This customer is already a member of the organization.',
    'already_in_org'         => 'This customer already belongs to another organization.',

    // Portal — company tickets
    'company_tickets'        => 'Company Tickets',
    'my_tickets'             => 'My Tickets',
    'no_org_tickets'         => 'No tickets found for your organization.',
    'unknown'                => 'Unknown',
    'from'                   => 'From',
    'subject'                => 'Subject',
    'ticket_hash'            => 'Ticket #',
    'updated'                => 'Updated',
    'no_subject'             => '(no subject)',
    'responsible'            => 'Responsible',
    'author'                 => 'Author',
    'conv_status'            => 'Status',
    'kanban_state'           => 'State',
    'search_ticket'          => 'Search ticket…',
    'filter_by_author'       => 'Show tickets by this author',
    'status_active'          => 'Active',
    'status_pending'         => 'Pending',
    'status_closed'          => 'Closed',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Support Team',
    'customer'               => 'Customer',
    'reply'                  => 'Reply',
    'write_reply'            => 'Write your reply…',
    'send_reply'             => 'Send Reply',
    'reply_sent'             => 'Reply sent.',
    'change_author'          => 'Change Author',
    'author_changed'         => 'Ticket author updated.',

    // Portal — settings
    'org_notification_settings' => 'Organization Notification Settings',
    'org_settings_title'     => 'Organization Settings',
    'organization'           => 'Organization',
    'notify_new_ticket_label'=> 'Receive email notification when a member of my organization opens a new ticket',
    'settings_saved'         => 'Settings saved.',

    // Portal — settings tabs
    'tab_notifications'      => 'Notifications',
    'tab_units'              => 'Structural Units',

    // Portal — structural units
    'unit_name'              => 'Structural unit name',
    'unit_name_placeholder'  => 'e.g. Sales department',
    'add_unit'               => 'Add structural unit',
    'rename'                 => 'Rename',
    'no_units'               => 'No units yet.',
    'unit_created'           => 'Unit created.',
    'unit_updated'           => 'Unit updated.',
    'unit_deleted'           => 'Unit deleted.',
    'unit_exists'            => 'A unit with this name already exists.',
    'confirm_delete_unit'    => 'Delete this unit? Its members will be unassigned and unit managers demoted to members.',

    // Portal — member management
    'member_unit'            => 'Structural unit',
    'no_unit'                => 'Entire organization',
    'apply'                  => 'Apply',
    'role_member'            => 'Member',
    'role_manager_scoped'    => 'Manager',
    'role_unit_manager'      => 'Unit manager',
    'role_global_manager'    => 'Global manager',
    'global_grant_hint'      => 'Set a unit to make a unit manager. Promoting to global manager requires admin permission.',
    'member_updated'         => 'Member updated.',
    'cannot_grant_global'    => 'You are not allowed to assign global managers.',
    'can_manage_org'         => 'Manages entire organization',
    'can_manage_org_hint'    => 'Allows this global manager to promote other members to global manager from the portal.',
    'member_status'          => 'Status',
    'status_member_active'   => 'Active',
    'status_member_inactive' => 'Deactivated',
    'deactivate'             => 'Deactivate',
    'activate'               => 'Activate',
    'member_deactivated'     => 'Member deactivated.',
    'member_activated'       => 'Member reactivated.',
    'confirm_deactivate'     => 'Deactivate this member? They will no longer receive ticket assignments.',
    'cannot_deactivate_self' => 'You cannot deactivate yourself.',

    // Notification subscriptions (portal)
    'notif_scope'                => 'Scope',
    'notif_scope_org'            => 'Entire organization',
    'notif_scope_no_unit'        => 'No unit',
    'notif_event_new_ticket'     => 'New ticket',
    'notif_event_reply_agent'    => 'Agent reply',
    'notif_event_reply_customer' => 'Customer reply',
    'notif_hint'                 => 'Check the box to receive email notifications for tickets from the selected scope.',

    // Notification template settings (admin)
    'notif_reply_triggers'       => 'Reply notification triggers',
    'notif_trigger_agent'        => 'Notify on agent replies',
    'notif_trigger_customer'     => 'Notify on customer replies',
    'notif_trigger_hint'         => 'These settings apply globally. Managers subscribe to specific scopes on the portal settings page.',
    'tpl_tab_title'              => 'Notification Templates',
    'tpl_heading'                => 'Email template',
    'tpl_fallback_hint'          => '(leave empty to use the built-in template)',
    'tpl_subject'                => 'Subject',
    'tpl_subject_placeholder'    => 'Leave empty to use default',
    'tpl_body'                   => 'Message body',
    'tpl_insert_macro'           => 'Insert variable…',
    'tpl_load_default'           => 'Load default template',

    // Macros
    'macro_manager_name'     => 'Recipient name',
    'macro_author_name'      => 'Ticket author name',
    'macro_org_name'         => 'Organization name',
    'macro_unit_name'        => 'Unit name',
    'macro_subject'          => 'Ticket subject',
    'macro_ticket_number'    => 'Ticket number',
    'macro_ticket_url'       => 'Ticket URL',
    'macro_created_date'     => 'Creation date',
    'macro_created_time'     => 'Creation time',
    'macro_created_datetime' => 'Creation date & time',
    'macro_reply_date'       => 'Reply date',
    'macro_reply_time'       => 'Reply time',
    'macro_reply_datetime'   => 'Reply date & time',
    'macro_reply_text'       => 'Reply text',
    'macro_ticket_text'      => 'Ticket text',

    // Notification email fallback strings
    'email_reply_agent_intro'    => 'A new agent reply was added to a ticket in your organization:',
    'email_reply_customer_intro' => 'A customer replied to a ticket in your organization:',
    'email_reply_subject'        => 'Re: :number — :subject',

    // EUP nav
    'org_settings_nav'       => 'Organization Settings',

    // Conversation badge & search
    'filter_by_org'          => 'Show all tickets from this organization',
    'all_organizations'      => 'All organizations',
    'remove_filter'          => 'Remove filter',

    // Customer edit form
    'customer_organization'  => 'Organization',
    'no_organization'        => '— None —',
    'customer_role'          => 'Role in organization',
    'view_org_tickets'       => 'View Organization Tickets',

    // Module settings
    'settings'               => 'Settings',
    'module_settings'        => 'OrgPortal Settings',
    'display_settings'       => 'Display Settings',
    'show_badge_conversation'=> 'Show organization badge on ticket page (near tags)',
    'show_badge_kanban'      => 'Show organization badge on Kanban cards',

    // Kanban filter
    'kanban_filter_org'           => 'Organization',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Company Tickets Status Filters',
    'company_filters_hint'        => 'Select which Kanban columns appear as filter checkboxes on the Company Tickets page. You can customise the label shown to portal users.',
    'filter_column_id'            => 'Column ID',
    'filter_label'                => 'Label',
    'filter_add'                  => 'Add filter',
    'filter_board'                => 'Board',
    'company_filters_no_boards'   => 'No Kanban boards found. Create a board first.',

    // User permission
    'perm_manage_organizations' => 'Allow managing organizations',
    'perm_manage_templates'     => 'Allow managing notification templates',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API Docs',

    // Ticket actions
    'close_ticket'                  => 'Close Ticket',
    'close_ticket_confirm'          => 'Are you sure you want to close this ticket?',
    'ticket_closed'                 => 'Ticket has been closed.',
    'ticket_closed_label'           => 'Closed',
    'ticket_closed_reply_reopens'   => 'This ticket is closed. Sending a reply will reopen it.',
    'attach_files'                  => 'Attachments',
    'attach_files_hint'             => 'Up to :count files, max :max MB each',
    'attach_add_more'               => 'Add another file',
    'status_open'                   => 'Open',

    // Errors
    'access_denied'          => 'Access denied. Manager role required.',

    // Email
    'email_hello'            => 'Hello',
    'email_new_ticket_intro' => 'A new support ticket has been submitted by a member of your organization:',
    'email_new_ticket_footer'=> 'You received this email because you enabled new-ticket notifications for your organization in the Customer Portal.',
    'new_ticket_from'        => 'New ticket from :name',
    'email_from'             => 'From',
    'email_subject'          => 'Subject',
    'email_ticket_number'    => 'Ticket #',
    'view_ticket'            => 'View Ticket',

    'author_not_read'        => 'Author has not read this reply yet',

    // Manager viewed (admin thread meta)
    'manager_org_label'    => 'Organization manager',
    'manager_viewed_when'  => 'viewed :when',

    // Portal in-app notifications
    'notifications'          => 'Notifications',
    'no_notifications'       => 'No new notifications',
    'notif_new_ticket'       => 'created a ticket',
    'notif_new_reply'        => 'replied to conversation',
    'notif_customer_reply'   => 'replied to conversation',
    'notif_mark_all_read'    => 'Mark all as read',
    'notif_today'            => 'Today',
    'notif_yesterday'        => 'Yesterday',
];
