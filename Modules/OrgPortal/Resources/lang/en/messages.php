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

    // Admin — system tab (Phase 7 attribution)
    'system_tab_title'          => 'System',
    'system_attribution_heading'=> 'Ticket Attribution',
    'system_attribution_more'   => 'details',
    'system_attribution_desc'   => 'By default the portal decides which tickets a manager can see by looking at the current member list of the organisation — if a customer is in the org, their tickets are visible. This works well until customers move between organisations or leave: a customer transferred to another org would suddenly lose all their old tickets, and a customer who left would pull their tickets out of the portal entirely. Ticket Attribution solves this by storing a snapshot of the organisation and unit directly on each ticket at the moment it is created. The ticket stays visible in the original org even after the customer moves. Example: John submitted 10 tickets while in "Acme Corp". He was later moved to "Beta LLC". With attribution enabled, all 10 tickets remain visible in "Acme Corp" portal — and future tickets appear in "Beta LLC". Without attribution, all 10 tickets would disappear from "Acme Corp" the moment John is moved. The background job runs every 5 minutes and processes up to 1 000 tickets per pass to attribute the existing history.',
    'system_tickets_attributed' => 'tickets attributed',
    'system_tickets_pending'    => ':count tickets still pending attribution',
    'system_backfill_complete'  => 'All tickets attributed — ready to enable snapshot visibility.',
    'system_run_backfill'       => 'Run backfill now',
    'system_cron_hint'          => 'Processes up to 2 000 tickets immediately (cron runs every 5 min automatically).',
    'system_backfill_done'        => 'Backfill complete: :count tickets processed.',
    // Preflight stats
    'system_preflight_heading'          => 'What will happen when you run the backfill:',
    'system_preflight_pending'          => 'Tickets awaiting attribution',
    'system_preflight_orgs_with_tags'   => ':n of :total organizations have tag bindings configured',
    'system_preflight_orgs_no_tags'     => ':n organizations have no tag bindings',
    'system_preflight_will_tag'         => 'will be attributed via tag',
    'system_preflight_will_member'      => 'will fall back to membership / remain unmatched',

    // Backfill result summary
    'system_backfill_summary_heading'   => 'Backfill batch complete:',
    'system_backfill_summary_processed' => ':n tickets processed in this pass.',
    'system_backfill_summary_by_tag'    => 'attributed via tag binding',
    'system_backfill_summary_by_member' => 'attributed via active membership',
    'system_backfill_summary_unmatched' => 'no match found (org_id left empty)',

    'system_save_settings'        => 'Save settings',
    'system_reset_attribution'    => 'Reset all attribution',
    'system_reset_confirm'        => 'This will clear org_id, org_unit_id and org_attributed_at on ALL tickets and restart attribution from scratch. Are you sure?',
    'system_reset_done'           => 'Attribution reset. All tickets will be re-attributed on the next backfill run.',
    'system_snapshot_warning'   => 'There are still unattributed tickets. We recommend enabling snapshot visibility only after the counter reaches 0 — otherwise those tickets may temporarily disappear from the portal.',
    'system_snapshot_label'     => 'Enable snapshot-based ticket visibility',
    'system_snapshot_hint'      => 'When enabled, the portal shows tickets by stored org_id snapshot instead of the live member list. A safe fallback for un-attributed tickets remains active at all times.',

    // Admin — system tab: attribution source
    'system_attr_source_heading'  => 'Attribution Source',
    'system_attr_source_desc'     => 'Controls how tickets are linked to an organization. When the Tags module is inactive, only member-based attribution is available.',
    'system_attr_member'          => 'By membership',
    'system_attr_member_hint'     => 'Default. Tickets are attributed to the organization the customer belongs to at the time of creation.',
    'system_attr_tag'             => 'By tag, fall back to membership',
    'system_attr_tag_hint'        => 'If a ticket has a tag bound to an organization, that binding wins. Otherwise membership is used. Requires the Tags module.',
    'system_attr_tag_only'        => 'By tag only',
    'system_attr_tag_only_hint'   => 'Tickets are attributed only via tag bindings. Tickets without a matching tag get no attribution. Requires the Tags module.',
    'system_attr_tags_inactive'   => 'Tag-based options are disabled because the Tags module is not active.',

    // Admin — org edit: tag bindings
    'org_tags_heading'  => 'Tag Bindings',
    'org_tags_hint'     => 'Select which tags identify tickets belonging to this organization. Optionally assign a structural unit per tag.',
    'org_tags_none'               => 'No tags found. Create tags in Manage → Tags first.',
    'org_tags_unit_any'           => '— any unit —',
    'org_tags_saved'              => 'Tag bindings saved.',
    'org_tags_search_placeholder' => 'Search tag…',

    // Admin — system tab: language switcher
    'system_lang_heading'       => 'Portal Language Switcher',
    'system_lang_desc'          => 'Adds a language switcher dropdown to the customer portal navbar. The chosen language is saved to the customer\'s profile and used when sending email notifications. Has no effect when the EupSwLang module is active (use EupSwLang settings instead).',
    'system_lang_enable'        => 'Enable language switcher on portal',
    'system_lang_enable_hint'   => 'Shows a globe icon in the portal navbar that lets customers switch the portal language.',
    'system_lang_locales'       => 'Available languages',
    'system_lang_locales_hint'  => 'Only the checked languages appear in the switcher. Leave all checked to show every available language.',

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
