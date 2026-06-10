<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organisations',
    'new_organization'       => 'Nouvelle organisation',
    'no_organizations'       => 'Aucune organisation pour le moment.',
    'create_one'             => 'En créer une',

    // Admin — mailbox scope
    'mailbox'                => 'Boîte aux lettres',
    'global_scope'           => 'Global (toutes les boîtes)',
    'mailbox_scope_hint'     => 'Laissez vide pour rendre cette organisation visible dans toutes les boîtes aux lettres.',

    // Admin — create / edit form
    'organization_name'      => 'Nom de l\'organisation',
    'create_organization'    => 'Créer une organisation',
    'org_details'            => 'Détails de l\'organisation',
    'cancel'                 => 'Annuler',
    'save'                   => 'Enregistrer',
    'back'                   => 'Retour',
    'edit'                   => 'Modifier',
    'delete'                 => 'Supprimer',
    'confirm_delete_org'     => 'Supprimer cette organisation ?',

    // Admin — flash messages
    'org_created'            => 'Organisation créée.',
    'org_updated'            => 'Organisation mise à jour.',
    'org_deleted'            => 'Organisation supprimée.',

    // Admin — badge color
    'badge_color'            => 'Couleur du badge',
    'color_default'          => 'Par défaut (gris)',
    'preview'                => 'Aperçu',

    // Admin — members table
    'name'                   => 'Nom',
    'email'                  => 'E-mail',
    'members'                => 'Membres',
    'role'                   => 'Rôle',
    'member'                 => 'Membre',
    'manager'                => 'Gestionnaire',
    'deleted_customer'       => 'Client supprimé',
    'no_members'             => 'Aucun membre pour le moment.',
    'remove'                 => 'Retirer',
    'confirm_remove_member'  => 'Retirer ce membre ?',

    // Admin — add member form
    'add_member'             => 'Ajouter un membre',
    'search_customer'        => 'Rechercher un client',
    'type_name_or_email'     => 'Saisissez le nom ou l\'e-mail…',

    // Admin — member flash messages
    'role_updated'           => 'Rôle mis à jour.',
    'member_added'           => 'Membre ajouté.',
    'member_removed'         => 'Membre retiré.',
    'already_member'         => 'Ce client est déjà membre de l\'organisation.',
    'already_in_org'         => 'Ce client appartient déjà à une autre organisation.',

    // Portal — company tickets
    'company_tickets'        => 'Tickets de l\'entreprise',
    'my_tickets'             => 'Mes tickets',
    'no_org_tickets'         => 'Aucun ticket trouvé pour votre organisation.',
    'unknown'                => 'Inconnu',
    'from'                   => 'De',
    'subject'                => 'Objet',
    'ticket_hash'            => 'Ticket #',
    'updated'                => 'Mis à jour',
    'no_subject'             => '(pas d\'objet)',
    'responsible'            => 'Responsable',
    'author'                 => 'Auteur',
    'conv_status'            => 'Statut',
    'kanban_state'           => 'Statut',
    'search_ticket'          => 'Rechercher un ticket…',
    'filter_by_author'       => 'Afficher les tickets de cet auteur',
    'status_active'          => 'Actif',
    'status_pending'         => 'En attente',
    'status_closed'          => 'Fermé',
    'status_spam'            => 'Spam',

    // Portal — ticket view
    'support_team'           => 'Équipe d\'assistance',
    'customer'               => 'Client',
    'reply'                  => 'Répondre',
    'write_reply'            => 'Écrivez votre réponse…',
    'send_reply'             => 'Envoyer la réponse',
    'reply_sent'             => 'Réponse envoyée.',
    'change_author'          => 'Changer l\'auteur',
    'author_changed'         => 'Auteur du ticket mis à jour.',

    // Portal — settings
    'org_notification_settings' => 'Paramètres de notification de l\'organisation',
    'organization'           => 'Organisation',
    'notify_new_ticket_label'=> 'Recevoir une notification par e-mail quand un membre de mon organisation ouvre un nouveau ticket',
    'settings_saved'         => 'Paramètres enregistrés.',

    // EUP nav
    'org_settings_nav'       => 'Paramètres org',

    // Conversation badge & search
    'filter_by_org'          => 'Afficher tous les tickets de cette organisation',
    'all_organizations'      => 'Toutes les organisations',
    'remove_filter'          => 'Supprimer le filtre',

    // Customer edit form
    'customer_organization'  => 'Organisation',
    'no_organization'        => '— Aucune —',
    'customer_role'          => 'Rôle dans l\'organisation',
    'view_org_tickets'       => 'Afficher les tickets de l\'organisation',

    // Module settings
    'settings'               => 'Paramètres',
    'module_settings'        => 'Paramètres OrgPortal',
    'display_settings'       => 'Paramètres d\'affichage',
    'show_badge_conversation'=> 'Afficher le badge de l\'organisation sur la page du ticket (près des étiquettes)',
    'show_badge_kanban'      => 'Afficher le badge de l\'organisation sur les cartes Kanban',

    // Kanban filter
    'kanban_filter_org'           => 'Organisation',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Filtres de statut des tickets de l\'entreprise',
    'company_filters_hint'        => 'Sélectionnez les colonnes Kanban qui apparaissent comme cases à cocher de filtre sur la page Tickets de l\'entreprise. Vous pouvez personnaliser l\'étiquette affichée aux utilisateurs du portail.',
    'filter_column_id'            => 'ID de colonne',
    'filter_label'                => 'Étiquette',
    'filter_add'                  => 'Ajouter un filtre',
    'filter_board'                => 'Tableau',
    'company_filters_no_boards'   => 'Aucun tableau Kanban trouvé. Créez d\'abord un tableau.',

    // User permission
    'perm_manage_organizations' => 'Autoriser la gestion des organisations',

    // ApiWebhooks settings page
    'api_docs_link'          => 'Documentation de l\'API OrgPortal',

    // Ticket actions
    'close_ticket'                  => 'Fermer le ticket',
    'close_ticket_confirm'          => 'Êtes-vous sûr de vouloir fermer ce ticket ?',
    'ticket_closed'                 => 'Le ticket a été fermé.',
    'ticket_closed_label'           => 'Fermé',
    'ticket_closed_reply_reopens'   => 'Ce ticket est fermé. L\'envoi d\'une réponse le rouvrira.',
    'attach_files'                  => 'Pièces jointes',
    'attach_files_hint'             => 'Jusqu\'à :count fichiers, max :max Mo chacun',
    'attach_add_more'               => 'Ajouter un autre fichier',
    'status_open'                   => 'Ouvert',

    // Errors
    'access_denied'          => 'Accès refusé. Rôle de gestionnaire requis.',

    // Email
    'email_hello'            => 'Bonjour',
    'email_new_ticket_intro' => 'Un nouveau ticket d\'assistance a été soumis par un membre de votre organisation :',
    'email_new_ticket_footer'=> 'Vous recevez cet e-mail parce que vous avez activé les notifications pour les nouveaux tickets de votre organisation dans le portail client.',
    'new_ticket_from'        => 'Nouveau ticket de :name',
    'email_from'             => 'De',
    'email_subject'          => 'Objet',
    'email_ticket_number'    => 'Ticket #',
    'view_ticket'            => 'Afficher le ticket',
];
