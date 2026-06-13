<?php

return [

    // Admin — organizations list
    'organizations'          => 'Organisaatiot',
    'new_organization'       => 'Uusi organisaatio',
    'no_organizations'       => 'Ei organisaatioita vielä.',
    'create_one'             => 'Luo yksi',

    // Admin — mailbox scope
    'mailbox'                => 'Postilaatikko',
    'global_scope'           => 'Yleinen (kaikki postilaatikot)',
    'mailbox_scope_hint'     => 'Jätä tyhjäksi tehdäksesi tämän organisaation näkyväksi kaikissa postilaatikoissa.',

    // Admin — create / edit form
    'organization_name'      => 'Organisaation nimi',
    'create_organization'    => 'Luo organisaatio',
    'org_details'            => 'Organisaation tiedot',
    'cancel'                 => 'Peruuta',
    'save'                   => 'Tallenna',
    'back'                   => 'Takaisin',
    'edit'                   => 'Muokkaa',
    'delete'                 => 'Poista',
    'confirm_delete_org'     => 'Poista tämä organisaatio?',

    // Admin — flash messages
    'org_created'            => 'Organisaatio luotu.',
    'org_updated'            => 'Organisaatio päivitetty.',
    'org_deleted'            => 'Organisaatio poistettu.',

    // Admin — badge color
    'badge_color'            => 'Merkin väri',
    'color_default'          => 'Oletus (harmaa)',
    'preview'                => 'Esikatselu',

    // Admin — members table
    'name'                   => 'Nimi',
    'email'                  => 'Email',
    'members'                => 'Jäsenet',
    'role'                   => 'Rooli',
    'member'                 => 'Jäsen',
    'manager'                => 'Johtaja',
    'deleted_customer'       => 'Poistettu asiakas',
    'no_members'             => 'Ei jäseniä vielä.',
    'remove'                 => 'Poista',
    'confirm_remove_member'  => 'Poista tämä jäsen?',

    // Admin — add member form
    'add_member'             => 'Lisää jäsen',
    'search_customer'        => 'Hae asiakasta',
    'type_name_or_email'     => 'Kirjoita nimi tai sähköposti…',

    // Admin — member flash messages
    'role_updated'           => 'Rooli päivitetty.',
    'member_added'           => 'Jäsen lisätty.',
    'member_removed'         => 'Jäsen poistettu.',
    'already_member'         => 'Tämä asiakas on jo organisaation jäsen.',
    'already_in_org'         => 'Tämä asiakas kuuluu jo toiseen organisaatioon.',

    // Portal — company tickets
    'company_tickets'        => 'Yrityksen liput',
    'my_tickets'             => 'Omat liput',
    'no_org_tickets'         => 'Organisaatioosi ei löytynyt lippuja.',
    'unknown'                => 'Tuntematon',
    'from'                   => 'Lähettäjä',
    'subject'                => 'Aihe',
    'ticket_hash'            => 'Lippu #',
    'updated'                => 'Päivitetty',
    'no_subject'             => '(ei aiheita)',
    'responsible'            => 'Vastuuhenkilö',
    'author'                 => 'Kirjoittaja',
    'conv_status'            => 'Tila',
    'kanban_state'           => 'Asema',
    'search_ticket'          => 'Hae lippua…',
    'filter_by_author'       => 'Näytä tämän kirjoittajan liput',
    'status_active'          => 'Aktiivinen',
    'status_pending'         => 'Odottava',
    'status_closed'          => 'Suljettu',
    'status_spam'            => 'Roskaposti',

    // Portal — ticket view
    'support_team'           => 'Tukitiimi',
    'customer'               => 'Asiakas',
    'reply'                  => 'Vastaa',
    'write_reply'            => 'Kirjoita vastauksesi…',
    'send_reply'             => 'Lähetä vastaus',
    'reply_sent'             => 'Vastaus lähetetty.',
    'change_author'          => 'Muuta kirjoittajaa',
    'author_changed'         => 'Lipun tekijä päivitetty.',

    // Portal — settings
    'org_notification_settings' => 'Organisaation ilmoitusasetukset',
    'organization'           => 'Organisaatio',
    'notify_new_ticket_label'=> 'Vastaanota sähköposti-ilmoitus kun organisaationi jäsen avaa uuden lipun',
    'settings_saved'         => 'Asetukset tallennettu.',

    // EUP nav
    'org_settings_nav'       => 'Organisaation asetukset',

    // Conversation badge & search
    'filter_by_org'          => 'Näytä kaikki liput tästä organisaatiosta',
    'all_organizations'      => 'Kaikki organisaatiot',
    'remove_filter'          => 'Poista suodatin',

    // Customer edit form
    'customer_organization'  => 'Organisaatio',
    'no_organization'        => '— Ei mitään —',
    'customer_role'          => 'Rooli organisaatiossa',
    'view_org_tickets'       => 'Näytä organisaation liput',

    // Module settings
    'settings'               => 'Asetukset',
    'module_settings'        => 'OrgPortal-asetukset',
    'display_settings'       => 'Näyttöasetukset',
    'show_badge_conversation'=> 'Näytä organisaation merkki lippusivulla (tagien vieressä)',
    'show_badge_kanban'      => 'Näytä organisaation merkki Kanban-korteissa',

    // Kanban filter
    'kanban_filter_org'           => 'Organisaatio',

    // Company tickets filters (settings)
    'company_filters_heading'     => 'Yrityksen lippujen tilasuodattimet',
    'company_filters_hint'        => 'Valitse mitkä Kanban-sarakkeet näytetään valintaruutuina Yrityksen liput -sivulla. Voit mukauttaa portaalin käyttäjille näytettävää etikettia.',
    'filter_column_id'            => 'Sarakkeen tunnus',
    'filter_label'                => 'Etiketti',
    'filter_add'                  => 'Lisää suodatin',
    'filter_board'                => 'Lauta',
    'company_filters_no_boards'   => 'Kanban-lautoja ei löytynyt. Luo lauta ensin.',

    // User permission
    'perm_manage_organizations' => 'Salli organisaatioiden hallinta',

    // ApiWebhooks settings page
    'api_docs_link'          => 'OrgPortal API -dokumentaatio',

    // Ticket actions
    'close_ticket'                  => 'Sulje lippu',
    'close_ticket_confirm'          => 'Oletko varma, että haluat sulkea tämän lipun?',
    'ticket_closed'                 => 'Lippu on suljettu.',
    'ticket_closed_label'           => 'Suljettu',
    'ticket_closed_reply_reopens'   => 'Tämä lippu on suljettu. Vastauksen lähettäminen avaa sen uudelleen.',
    'attach_files'                  => 'Liitteet',
    'attach_files_hint'             => 'Enintään :count tiedostoa, maksimi :max MB kukin',
    'attach_add_more'               => 'Lisää toinen tiedosto',
    'status_open'                   => 'Avoin',

    // Errors
    'access_denied'          => 'Pääsy kielletty. Johtajan rooli vaaditaan.',

    // Email
    'email_hello'            => 'Hei',
    'email_new_ticket_intro' => 'Uuden tukitiketin on lähettänyt organisaatioosi jäsen:',
    'email_new_ticket_footer'=> 'Sait tämän sähköpostin, koska olet ottanut käyttöön uusien lippujen ilmoitukset organisaatiolle asiakaportaalissa.',
    'new_ticket_from'        => 'Uusi lippu käyttäjältä :name',
    'email_from'             => 'Lähettäjä',
    'email_subject'          => 'Aihe',
    'email_ticket_number'    => 'Lippu #',
    'view_ticket'            => 'Näytä lippu',

    // Org units & notifications
    'activate'               => 'Aktivoi',
    'add_unit'               => 'Lisää organisaatioyksiköt',
    'apply'                  => 'Käytä',
    'can_manage_org'         => 'Johtaa koko organisaatiota',
    'can_manage_org_hint'    => 'Antaa tälle yleiselle johtajalle mahdollisuuden ylentää muita jäseniä yleisiksi johtajiksi portaalista.',
    'cannot_deactivate_self' => 'Et voi poistaa itseäsi käytöstä.',
    'cannot_grant_global'    => 'Sinulla ei ole oikeutta määrittää globaaleja johtajia.',
    'confirm_deactivate'     => 'Poistetaanko tämä jäsen käytöstä? He eivät enää saa lipputehtäviä.',
    'confirm_delete_unit'    => 'Poistetaanko tämä yksikkö? Sen jäsenet poistetaan tehtävistään ja yksikönjohtajat alentuvat jäseniksi.',
    'deactivate'             => 'Poista käytöstä',
    'email_reply_agent_intro'    => 'Uusi agentin vastaus lisättiin lippuun organisaatiossasi:',
    'email_reply_customer_intro' => 'Asiakas vastasi lippuun organisaatiossasi:',
    'email_reply_subject'        => 'Vastaus: :number — :subject',
    'global_grant_hint'      => 'Aseta yksikkö tehdäksesi yksikönjohtajasta. Ylentäminen yleiseksi johtajaksi vaatii järjestelmän ylläpitäjän oikeuksia.',
    'macro_author_name'      => 'Lipun tekijän nimi',
    'macro_created_date'     => 'Luomispäivä',
    'macro_created_datetime' => 'Luomispäivä ja -aika',
    'macro_created_time'     => 'Luomisaika',
    'macro_manager_name'     => 'Vastaanottajan nimi',
    'macro_org_name'         => 'Organisaation nimi',
    'macro_reply_date'       => 'Vastauksen päivä',
    'macro_reply_datetime'   => 'Vastauksen päivä ja -aika',
    'macro_reply_time'       => 'Vastauksen aika',
    'macro_subject'          => 'Lipun aihe',
    'macro_ticket_number'    => 'Lipun numero',
    'macro_ticket_url'       => 'Lipun URL',
    'macro_unit_name'        => 'Yksikön nimi',
    'member_activated'       => 'Jäsen aktivoitu uudelleen.',
    'member_deactivated'     => 'Jäsen poistettu käytöstä.',
    'member_status'          => 'Tila',
    'member_unit'            => 'Organisaatioyksiköt',
    'member_updated'         => 'Jäsen päivitetty.',
    'no_unit'                => 'Koko organisaatio',
    'no_units'               => 'Yksikköjä ei ole vielä.',
    'notif_event_new_ticket'     => 'Uusi lippu',
    'notif_event_reply_agent'    => 'Agentin vastaus',
    'notif_event_reply_customer' => 'Asiakkaan vastaus',
    'notif_hint'                 => 'Merkitse valintaruutu saadaksesi sähköposti-ilmoitukset lippuista valitusta laajuudesta.',
    'notif_reply_triggers'       => 'Vastausilmoitusten laukaisijat',
    'notif_scope'                => 'Laajuus',
    'notif_scope_org'            => 'Koko organisaatio',
    'notif_trigger_agent'        => 'Ilmoita agentin vastauksista',
    'notif_trigger_customer'     => 'Ilmoita asiakkaan vastauksista',
    'notif_trigger_hint'         => 'Nämä asetukset pätevät kaikkiin alueisiin. Johtajat tilaavat tiettyjä alueita portaalin asetussivulla.',
    'org_settings_title'     => 'Organisaation asetukset',
    'perm_manage_templates'     => 'Salli ilmoitusmallineiden hallinta',
    'rename'                 => 'Nimeä uudelleen',
    'role_global_manager'    => 'Yleinen johtaja',
    'role_manager_scoped'    => 'Johtaja',
    'role_member'            => 'Jäsen',
    'role_unit_manager'      => 'Yksikönjohtaja',
    'select_member'          => 'Valitse jäsen',
    'status_member_active'   => 'Aktiivinen',
    'status_member_inactive' => 'Poistettu käytöstä',
    'tab_notifications'      => 'Ilmoitukset',
    'tab_units'              => 'Organisaatioyksiköt',
    'tpl_body'                   => 'Viestin runko',
    'tpl_fallback_hint'          => '(jätä tyhjäksi käyttääksesi sisäänrakennettua mallia)',
    'tpl_heading'                => 'Sähköpostimalli',
    'tpl_insert_macro'           => 'Lisää muuttuja…',
    'tpl_load_default'           => 'Lataa oletusmallia',
    'tpl_subject'                => 'Aihe',
    'tpl_subject_placeholder'    => 'Jätä tyhjäksi käyttääksesi oletusta',
    'tpl_tab_title'              => 'Ilmoitusmallineet',
    'unit_created'           => 'Yksikkö luotu.',
    'unit_deleted'           => 'Yksikkö poistettu.',
    'unit_exists'            => 'Yksikkö tällä nimellä on jo olemassa.',
    'unit_name'              => 'Organisaatioyksiköt nimi',
    'unit_name_placeholder'  => 'esim. Myyntiosasto',
    'unit_updated'           => 'Yksikkö päivitetty.',
];
