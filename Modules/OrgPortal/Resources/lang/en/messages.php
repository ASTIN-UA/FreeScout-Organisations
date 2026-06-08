<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organizations',
    'new_organization'       => 'New Organization',
    'no_organizations'       => 'No organizations yet.',
    'create_one'             => 'Create one',

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

    // Admin — members table
    'name'                   => 'Name',
    'email'                  => 'Email',
    'members'                => 'Members',
    'role'                   => 'Role',
    'member'                 => 'Member',
    'manager'                => 'Manager',
    'deleted_customer'       => 'Deleted customer',
    'no_members'             => 'No members yet.',
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
    'status_active'          => 'Active',
    'status_pending'         => 'Pending',
    'status_closed'          => 'Closed',

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
    'organization'           => 'Organization',
    'notify_new_ticket_label'=> 'Receive email notification when a member of my organization opens a new ticket',
    'settings_saved'         => 'Settings saved.',

    // EUP nav
    'org_settings_nav'       => 'Org Settings',

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
    'kanban_filter_org'      => 'Organization',

    // User permission
    'perm_manage_organizations' => 'Allow managing organizations',

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
];
