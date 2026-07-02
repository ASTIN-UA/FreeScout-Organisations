# OrgPortal — Module de Gestion B2B des Organisations pour FreeScout

[← Retour au README](../README.md)

<img src="https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/refs/heads/main/Modules/OrgPortal/logo.png" alt="OrgPortal — Module B2B FreeScout" width="140" align="right">

**OrgPortal** est un module FreeScout qui ajoute une **gestion complète des organisations B2B** à votre helpdesk : regroupez les clients en entreprises, définissez des hiérarchies de départements, offrez aux gestionnaires d'entreprise un portail en libre-service et automatisez les notifications — tout cela dans FreeScout, sans outil externe.

> Vous cherchez un moyen de gérer les comptes d'entreprise dans FreeScout ? Offrir à vos clients B2B leur propre portail d'assistance ? Contrôler les tickets que chaque contact B2B peut voir selon son rôle et son département ? OrgPortal résout tout cela.

**Compatible avec :** FreeScout 1.8.147+  
**Intégrations optionnelles :** [End-User Portal](https://freescout.net/module/end-user-portal/), [API and Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

> [!IMPORTANT]
> **Installez toujours depuis la [dernière version](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest), pas depuis le code source du dépôt.**
> Téléchargez `OrgPortal.zip` depuis la page Releases — il contient la structure de répertoires correcte requise par FreeScout.
> Le téléchargement du code source (via "Code → Download ZIP" ou `git clone`) ne **fonctionnera pas** et cassera la structure du module.
> Les mises à jour automatiques nécessitent également que le ZIP de la version ait été utilisé pour l'installation initiale.

---

🌐 **Aussi disponible en :**
[Українська](docs/README.uk.md) ·
[Deutsch](docs/README.de.md) ·
[Français](docs/README.fr.md) ·
[Español](docs/README.es.md) ·
[Italiano](docs/README.it.md) ·
[Polski](docs/README.pl.md) ·
[Čeština](docs/README.cs.md) ·
[Slovenčina](docs/README.sk.md) ·
[Nederlands](docs/README.nl.md) ·
[Norsk](docs/README.no.md) ·
[Dansk](docs/README.da.md) ·
[Svenska](docs/README.sv.md) ·
[Suomi](docs/README.fi.md) ·
[Português (BR)](docs/README.pt-BR.md) ·
[Português (PT)](docs/README.pt-PT.md) ·
[Română](docs/README.ro.md) ·
[中文 (简体)](docs/README.zh-CN.md)

---

## Table des matières

- [Ce qu'OrgPortal ajoute à FreeScout](#ce-quorgportal-ajoute-à-freescout)
- [Organisations](#organisations)
- [Unités Structurelles — Contrôle d'Accès au Niveau du Département](#unités-structurelles--contrôle-daccès-au-niveau-du-département)
- [Instantané d'Organisation — Attribution Permanente des Tickets](#instantané-dorganisation--attribution-permanente-des-tickets)
- [Intégration Kanban](#intégration-kanban)
- [Intégration des Champs Personnalisés](#intégration-des-champs-personnalisés)
- [Contrôle d'Accès et Permissions](#contrôle-daccès-et-permissions)
- [Paramètres Système](#paramètres-système--gérer--organisations--onglet-système)
- [Portail Utilisateur Final — Auto-Service pour Gestionnaires d'Entreprise](#portail-utilisateur-final--auto-service-pour-gestionnaires-dentreprise-optionnel)
- [Cloche de Notification en Temps Réel](#cloche-de-notification-en-temps-réel-optionnel)
- [Abonnements aux Notifications](#abonnements-aux-notifications-optionnel)
- [Paramètres d'Organisation du Portail](#paramètres-dorganisation-du-portail)
- [Modèles Email de Notification Multilingues](#modèles-email-de-notification-multilingues-optionnel)
- [API REST](#api-rest-optionnel)
- [Installation](#installation)
- [Mises à Jour Automatiques](#mises-à-jour-automatiques)
- [Compatibilité des Modules](#compatibilité-des-modules)
- [Configuration](#configuration)
- [Traductions](#traductions)
- [Screenshots](#screenshots)
- [Licence](#licence)

---

## Ce qu'OrgPortal ajoute à FreeScout

FreeScout est construit autour de clients individuels — chaque email provient d'une personne, et il n'y a pas de concept intégré d'une entreprise pour laquelle cette personne travaille. Cela fonctionne bien pour les helpdesks B2C. Pour le B2B, c'est insuffisant.

OrgPortal comble cette lacune :

- **Comptes d'entreprise** — regroupez les clients en organisations avec un nom, un badge de couleur, une portée de boîte aux lettres et un statut actif/inactif
- **Hiérarchies de départements** — divisez les organisations en unités structurelles (départements, succursales, équipes) ; chaque membre est limité à son unité
- **Accès basé sur les rôles** — `member` ne voit que ses propres tickets ; `unit_manager` voit toute l'unité ; `manager` voit toute l'organisation
- **Portail d'auto-service d'entreprise** — les gestionnaires visualisent tous les tickets de l'entreprise, répondent, ferment, réaffectent les auteurs et gèrent les préférences de notification sans contacter votre équipe
- **Attribution permanente des tickets** — chaque ticket est enregistré dans son organisation à sa création ; les rapports historiques survivent aux changements de composition des clients
- **Notifications multilingues** — alertes email automatisées dans la langue de chaque gestionnaire, avec des modèles par locale et un éditeur WYSIWYG intégré
- **API REST** — synchronisez les appartenances à partir de votre CRM, automatisez l'intégration, gérez les tags par programme

---

## Organisations

*Un seul endroit pour tout ce qui concerne un compte d'entreprise.*

**Gérer → Organisations** ouvre une interface à onglets avec trois sections : Organisations, Modèles et Système.

### Liste des organisations

- **Créer, modifier, supprimer, activer/désactiver** les organisations
- **Filtre de statut** — basculez entre Actif / Inactif / Tous avec un groupe de boutons radio ; filtre le tableau côté client instantanément
- **Recherche en direct** — commence à filtrer à partir de 2+ caractères, sans rechargement de page
- **Badges codés par couleur** — sélecteur de couleur interactif avec 12 nuances et un aperçu du badge en direct à côté du sélecteur ; le badge apparaît sur chaque ticket et carte Kanban
- Cliquer sur le badge ou le nombre de tickets ouvre une recherche FreeScout filtrée par cette organisation
- **Liaison de boîte aux lettres** — les organisations peuvent être globales (toutes les boîtes aux lettres) ou limitées à une boîte aux lettres spécifique
- **Colonne Tags** — affiche ✓/✗ si des tags FreeScout sont liés à l'organisation (module Tags requis) ; les tags sont assignés dans le formulaire de modification avec un widget basé sur des puces et une recherche d'autocomplétion
- **Colonne du nombre de tickets** — total des conversations par organisation ; lien cliquable vers les résultats de recherche complets
- **Colonne du nombre de membres**
- **Activer / Désactiver** — suspendre un compte sans perdre l'historique ; nécessite que l'instantané d'organisation soit activé (le bouton est désactivé avec une info-bulle quand ce n'est pas le cas)
- **Supprimer** — disponible uniquement quand l'organisation a 0 membre et 0 ticket (garde de sécurité)
- Toutes les actions de suppression et désactivation nécessitent une confirmation

![Liste des organisations — filtre de statut, recherche en direct, badges de couleur, tags, nombre de tickets](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png)

### Formulaire de modification d'organisation

- **Nom** et **liaison de boîte aux lettres**
- **Sélecteur de couleur** — 12 nuances avec aperçu du badge en direct
- **Tags** — widget basé sur des puces : tapez pour chercher les tags FreeScout existants, cliquez pour ajouter, × pour supprimer
- **Tableau des membres** — par membre : nom, rôle, unité structurelle, case à cocher `can_manage_org` (accorde l'accès administrateur aux organisations sans droits administrateur complets), bascule actif/inactif
- **Panneau des unités structurelles** — créez et renommez les unités directement dans le formulaire de modification ; les membres sont assignés aux unités dans la même vue
- **Ajouter un membre** — remplit automatiquement les conversations existantes non attribuées pour ce client

![Modification d'organisation — sélecteur de couleur, puces de tags, tableau des membres avec rôles et unités](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

### Intégration du profil client

- **Champ d'organisation dans le formulaire de modification client FreeScout** — recherche d'autocomplétion en direct pour les organisations ; le sélecteur de rôle apparaît après la sélection d'une organisation ; bouton × pour supprimer
- **Raccourci « Voir les tickets de l'organisation »** dans le formulaire client
- **Bloc d'info d'organisation dans la barre latérale des tickets administrateur** — nom de l'organisation (lien cliquable vers la page de modification de l'organisation), unité structurelle et rôle de membre ; bascule la visibilité par boîte aux lettres dans les paramètres
- **Une adhésion active par client** — un client ne peut pas être ajouté à une deuxième organisation s'il a une adhésion active ; les adhésions inactives/archivées sont autorisées

![Customer edit](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png)

![Conversation — organization badge](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/conversation-org-badge.png)

---

## Unités Structurelles — Contrôle d'Accès au Niveau du Département

*Supportez les grandes entreprises avec des hiérarchies internes complexes.*

Les organisations peuvent être divisées en **unités structurelles** illimitées (départements, succursales, bureaux régionaux, équipes de projet) :

- Créez, renommez et supprimez les unités dans le formulaire de modification d'organisation administrateur, ou directement depuis le portail (gestionnaires globaux uniquement)
- Assignez les membres aux unités — chaque membre appartient à une unité
- **Supprimer une unité** rétrograde automatiquement ses membres `unit_manager` en `member`

**Trois niveaux de rôles :**

| Rôle | Portée d'accès |
|------|----------------|
| `member` | Ses propres tickets uniquement |
| `unit_manager` | Tous les tickets de son unité structurelle |
| `manager` (global) | Tous les tickets de toute l'organisation |

- Les gestionnaires d'unité ont les capacités complètes du portail — réponses, pièces jointes, réaffectation d'auteur, fermeture/réouverture, gestion des notifications — limités strictement à leur unité
- L'accès aux tickets et la livraison des notifications sont appliqués aux limites des unités

![Modification d'organisation — membres avec rôles et unités, panneau de gestion des unités](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png)

---

## Instantané d'Organisation — Attribution Permanente des Tickets

*Rapports historiques fiables même si votre composition de clients change.*

Quand un ticket est créé, OrgPortal enregistre le contexte d'organisation comme un instantané permanent :

- `org_id`, `org_unit_id` et `org_attributed_at` sont écrits dans la conversation au moment de la création
- **Immuable** — si un client quitte plus tard une organisation, ses tickets historiques restent attribués à cette organisation ; les rapports ne se cassent jamais
- **Ajouter un membre** déclenche le remplissage automatique des conversations existantes non attribuées de ce client

### Source d'attribution — trois modes

Configuré dans **Gérer → Organisations → Onglet Système** :

| Mode | Comportement |
|------|-------------|
| `member` | Attribuer le ticket à l'organisation dont l'auteur du ticket est membre |
| `tag` | Attribuer d'abord par le tag FreeScout lié à une organisation ; revenir à l'adhésion si aucun tag ne correspond |
| `tag_only` | Attribuer exclusivement par tag ; l'adhésion n'est pas utilisée |

Les modes `tag` et `tag_only` sont désactivés quand le module Tags est inactif.

### Outils de remplissage

- **Barre de progression** — affiche X / Y tickets attribués (%) avec un indicateur « terminé » quand c'est fini
- **Statistiques de préflight** — avant d'exécuter le remplissage, une ventilation montre combien de tickets seront attribués par tag vs. par adhésion vs. non appairés
- **Bouton Exécuter le remplissage** — traite jusqu'à 2000 tickets par clic ; le résumé du résultat (by_tag / by_member / unmatched) est montré après
- **Auto-cron** (`attribution_cron_enabled`) — programme le remplissage toutes les 5 minutes, 1000 tickets par exécution, sans chevauchement
- **Réinitialiser l'attribution** — efface tous les instantanés d'organisation (action dangereuse, nécessite une confirmation)
- Ligne de commande : `php artisan orgportal:backfill-attribution`

![Onglet Système — source d'attribution, barre de progression, statistiques de préflight, contrôles de remplissage](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png)

---

## Intégration Kanban

*Gardez votre flux de travail visuel aligné avec vos comptes B2B.*

- Badge d'organisation sur chaque carte Kanban avec la couleur assignée du compte
- **Filtre d'organisation** dans le panneau de filtrage Kanban — modal multi-sélection avec cases à cocher ; l'état du filtre persiste lors de la navigation
- **Étiquettes de filtre de statut Kanban multilingues** — donnez à chaque colonne Kanban un nom personnalisé par langue de portail ; basculez les locales avec le sélecteur de langue dans les paramètres par boîte aux lettres ; glissez-déposez pour réorganiser les filtres
- Les étiquettes traduites apparaissent dans le panneau de filtres du portail et la colonne **État** du tableau des tickets d'entreprise ; chaîne de secours : locale enregistrée → anglais enregistré → nom de colonne original

![Kanban — badges d'organisation sur les cartes et modal de filtre d'organisation](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png)

---

## Intégration des Champs Personnalisés

*Affichez les données du module Champs Personnalisés directement sur la page du ticket dans le portail.*

Nécessite que le module [Custom Fields](https://freescout.net/module/custom-fields/) soit installé et actif.

- Un panneau par boîte mail dans Paramètres de la Boîte Mail → OrgPortal vous permet de choisir quels champs personnalisés apparaissent sur la page du ticket dans le portail
- Faites glisser les champs pour les réorganiser ; chaque champ peut avoir un libellé personnalisé par langue du portail, avec repli sur le libellé anglais enregistré puis sur le nom d'origine du champ
- Sur la page du ticket dans le portail, les champs activés s'affichent dans une grille responsive à deux colonnes entre l'objet du ticket et le fil de discussion — seuls les champs avec une valeur non vide sont affichés
- Entièrement optionnel — le panneau et le bloc de la page du ticket sont automatiquement masqués lorsque le module Champs Personnalisés n'est pas installé ou actif

---

## Contrôle d'Accès et Permissions

*Déléguez la gestion des organisations sans accorder l'accès administrateur.*

- **« Permettre la gestion des organisations »** (`can_manage_org`) — deux niveaux :
  - Comme **permission utilisateur** dans les paramètres de l'agent — permet à un responsable d'équipe d'assistance de gérer toutes les organisations sans droits administrateur
  - Comme **drapeau par membre** dans le formulaire de modification d'organisation — permet à un membre d'organisation spécifique de gérer cette organisation à partir du panneau administrateur
- **« Permettre la gestion des modèles de notification »** — permission granulaire séparée pour la modification de modèles
- La suppression des organisations reste exclusivement réservée à l'administrateur
- L'accès au portail est strictement limité par boîte aux lettres : un gestionnaire de l'organisation A ne peut pas accéder à l'organisation B

![Permissions granulaires — permettre la gestion des organisations et des modèles de notification](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png)

---

## Paramètres Système — Gérer → Organisations → Onglet Système

*Contrôles réservés à l'administrateur pour l'attribution, le remplissage et le commutateur de langue du portail.*

L'onglet **Système** n'est visible que pour les administrateurs FreeScout.

### Panneau 1 : Attribution de Tickets

Voir [Instantané d'Organisation](#instantané-dorganisation--attribution-permanente-des-tickets) ci-dessus pour la description complète des modes d'attribution, des outils de remplissage et de l'auto-cron.

### Panneau 2 : Commutateur de Langue du Portail

- **Activer/Désactiver** le commutateur de langue dans la barre de navigation du portail utilisateur final
- **Choisir lequel des 19 locales** offrir (grille de cases à cocher) ; tous sont activés par défaut
- Quand activé, les gestionnaires peuvent basculer la langue du portail ; leur choix est enregistré et utilisé pour les emails de notification
- C'est le commutateur de langue intégré d'OrgPortal — il fonctionne indépendamment de tout module de commutation de langue tiers ; les deux peuvent coexister

---

## Portail Utilisateur Final — Auto-Service pour Gestionnaires d'Entreprise *(optionnel)*

*Offrez à vos clients B2B un portail où ils gèrent la relation de support de leur entreprise — sans contacter votre équipe pour chaque mise à jour de statut.*

Nécessite le module [End-User Portal](https://freescout.net/module/end-user-portal/).

### Tableau de Bord des Tickets d'Entreprise

Une section dédiée **Tickets d'Entreprise** dans la navigation du portail avec un tableau de tickets complet :

| Colonne | Description |
|---------|-------------|
| **#** | ID du ticket |
| **Sujet** | Tronqué avec info-bulle au survol |
| **Responsable** | Agent d'assistance assigné |
| **Auteur** | Client qui a ouvert le ticket ; cliquez pour filtrer par cet auteur |
| **Statut** | Actif / En attente / Fermé / Spam avec icônes |
| **État** | Nom de la colonne Kanban dans la langue du portail actuelle (uniquement quand le module Kanban est actif) |
| **Mise à jour** | Date et heure de la dernière réponse |

**Deux indicateurs de statut de lecture indépendants par ligne** — ceux-ci suivent deux personnes différentes et sont affichés simultanément :

| Indicateur | Statut de lecture de | Ce que cela signifie |
|-----------|---------------------|-------------|
| **Ligne en gras** | Le gestionnaire qui consulte le portail | Le gestionnaire a des notifications non lues pour cette conversation — quelque chose s'est passé qu'il n'a pas encore vu |
| **👁 Icône d'oeil** | L'auteur du ticket (le client qui l'a soumis) | L'auteur n'a pas encore ouvert la dernière réponse de l'agent — utile pour savoir si un client a réellement vu la réponse |

Ces deux états sont complètement indépendants : une ligne peut être en gras (le gestionnaire n'a pas lu) pendant que l'oeil est absent (l'auteur a déjà lu), ou vice versa. Le gestionnaire voit les deux en même temps, donnant une image complète de ce qui se passe des deux côtés du ticket sans l'ouvrir.

**Filtre d'auteur** — cliquer sur un nom d'auteur active un filtre ; une bannière apparaît en haut du tableau montrant le nom de l'auteur actif avec un lien × pour effacer le filtre.

Un tableau **responsive** et une **mise en page de carte mobile** sont inclus ; ils se basculentautomatiquement selon la largeur de l'écran.

Le modèle de la barre de filtrage supporte un **remplacement** via `enduserportal::partials.tickets_filters` — placez une vue personnalisée à ce chemin pour remplacer la barre de filtrage par défaut d'OrgPortal tout en conservant toutes les autres fonctionnalités.

![Tickets d'Entreprise — tableau complet avec indicateurs de lecture, bannière de filtre d'auteur, filtres de statut](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png)

### Actions sur les Tickets dans le Portail

Les gestionnaires peuvent agir directement — pas besoin de contacter le support :

- **Répondre avec pièces jointes** — glissez-déposez, plusieurs fichiers par réponse ; noms et tailles des pièces jointes affichés sur chaque fil
- **Fermer le ticket** — une nouvelle réponse le réouvre automatiquement ; une bannière informe le gestionnaire de cela quand le ticket est fermé
- **Changer l'auteur du ticket** — réaffectez un ticket à un autre membre de l'organisation
- **Filtrer par unité** — les gestionnaires globaux filtrent la liste des tickets par unité structurelle
- **Filtrer par statut Kanban** — configurable par boîte aux lettres, étiquettes affichées dans la langue du portail actuelle

![Vue du ticket du portail — formulaire de réponse avec glissez-déposez de pièces jointes et bannière de ticket fermé](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png)

### Suivi des Visualisations du Gestionnaire

- Une note **« consultée »** apparaît sous les réponses de l'agent dans la vue du ticket administrateur quand un gestionnaire ouvre le ticket dans le portail
- Affiche le nom du gestionnaire, le rôle (Gestionnaire d'organisation / Gestionnaire d'unité) et le temps écoulé
- Les vues du gestionnaire global et du gestionnaire d'unité sont suivies et affichées indépendamment — même UX que le « Client a consulté » natif de FreeScout

![Suivi des visualisations du gestionnaire — la note « consultée » apparaît sous la réponse de l'agent dans la vue du ticket administrateur](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png)

---

## Cloche de Notification en Temps Réel *(optionnel)*

*Gardez les gestionnaires informés du moment où quelque chose se produit avec les tickets de leur entreprise.*

Nécessite le module [End-User Portal](https://freescout.net/module/end-user-portal/).

- Icône de cloche avec badge de compte non lu en direct dans la barre de navigation du portail utilisateur final — se repositionne automatiquement sur mobile (à côté du menu hamburger)
- Notifications pour : **nouveau ticket**, **réponse de l'agent**, **réponse du client** — pour tous les rôles de gestionnaire
- Panneau déroulant avec notifications regroupées par date : nom de l'acteur, type d'événement, numéro de ticket, aperçu du message, horodatage
- **Marquer automatiquement comme lu** quand le gestionnaire ouvre le ticket
- Marquer les notifications individuelles comme lues via × ; **Marquer tout comme lu** dans l'en-tête du panneau
- Sonde toutes les 15 secondes ; s'actualise lors de la navigation arrière/avant du navigateur (compatible bfcache)

---

## Abonnements aux Notifications *(optionnel)*

*Permettez aux gestionnaires de décider de ce qu'ils entendent — ni plus ni moins.*

- **Matrice d'abonnement visuelle** sur l'onglet « Notifications » dans les paramètres d'organisation du portail
- **Trois types d'événements :** Nouveau ticket · Réponse de l'agent · Réponse du client
- **Deux niveaux de portée :** Toute l'organisation (gestionnaires globaux) · Unités structurelles individuelles
- Les membres sans unité sont regroupés dans une ligne **« Sans unité »** expansible séparée
- **Remplacements par membre** — développez n'importe quelle ligne d'unité pour révéler les membres individuels et basculez leurs abonnements en ligne ; les gestionnaires d'unité avec rôle limité sont étiquetés en conséquence
- **Logique en cascade dans les deux sens :**
  - Activer « Toute l'organisation » → active toutes les unités et tous les membres
  - Activer une unité → active tous ses membres
  - Désactiver un membre → réaffilie automatiquement les cases à cocher d'unité et d'organisation
- Les gestionnaires globaux gèrent tous les membres ; les gestionnaires d'unité gèrent uniquement leur propre unité
- Les notifications utilisent le gestionnaire de courrier de la boîte aux lettres correspondante

![Matrice d'abonnement aux notifications — bascules par unité et par membre](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png)

---

## Paramètres d'Organisation du Portail

*Les gestionnaires configurent la structure de leur organisation sans accès administrateur.*

**Paramètres d'Organisation** dans la navigation du portail a trois onglets :

### Onglet Notifications

La matrice d'abonnement décrite ci-dessus.

### Onglet Unités *(gestionnaires globaux uniquement)*

- **Créer une unité** — formulaire en ligne avec champ de nom
- **Renommer une unité** — modification en ligne directement dans la ligne du tableau
- **Supprimer une unité** — bouton avec confirmation ; les gestionnaires d'unité sont automatiquement rétrogradés en membre
- Nombre de membres affiché par unité

### Onglet Membres

- Tableau de tous les membres de l'organisation : nom, unité structurelle, rôle, badge de statut actif/inactif
- **Étiquette « Gestionnaire global »** affichée à côté du nom du membre le cas échéant
- **Case à cocher « Afficher les désactivés »** — apparaît uniquement quand des membres inactifs existent ; cachée par défaut
- **Les gestionnaires globaux** peuvent mettre à jour l'unité et le rôle de n'importe quel membre avec un formulaire en ligne (sélection d'unité + sélection de rôle + Appliquer)
- **Les gestionnaires globaux ne peuvent pas promouvoir un membre en gestionnaire global** depuis le portail — cela nécessite l'accès administrateur
- **Bouton Activer / Désactiver** par membre avec confirmation pour la désactivation

![Portal Settings — Units tab](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings-units.png)

![Portal Settings — Members tab](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings-members.png)

---

## Modèles Email de Notification Multilingues *(optionnel)*

*Vos clients d'entreprise reçoivent les emails de support dans leur propre langue — automatiquement, sans effort manuel.*

Configuré dans **Gérer → Organisations → Onglet Modèles** (visible pour les utilisateurs avec la permission « gérer les modèles »).

- **Modèles par locale** — sujet et corps séparés pour chaque langue de portail ; basculez entre eux avec le sélecteur de locale ; les valeurs sont échangées en mémoire sans rechargement de page
- **Panneaux réductibles** par type d'événement (Nouveau ticket / Réponse de l'agent / Réponse du client) — l'éditeur Summernote s'initialise paresseusement quand un panneau est ouvert
- **Bouton Charger par défaut** dans chaque panneau — restaure le modèle intégré pour la locale actuellement sélectionnée (revient au défaut intégré anglais s'il n'existe pas de défaut spécifique à la locale)
- **Éditeur WYSIWYG Summernote** pour la composition riche d'email HTML
- **Sélecteur de variable de macro** — insérez des placeholders dans le sujet ou le corps avec un clic ; la position du curseur est préservée dans le champ sujet
- **19 modèles par défaut intégrés** — prêts à utiliser out of the box ; aucune configuration nécessaire

**Variables de macro disponibles :**

| Variable | Description |
|----------|-------------|
| `{manager_name}` | Nom du gestionnaire qui reçoit la notification |
| `{author_name}` | Client qui a créé ou répondu au ticket |
| `{org_name}` | Nom de l'organisation |
| `{unit_name}` | Nom de l'unité structurelle |
| `{subject}` | Sujet du ticket |
| `{ticket_number}` | ID du ticket |
| `{ticket_url}` | Lien direct vers le ticket dans le portail |
| `{ticket_text}` | Texte intégral du message initial (HTML) |
| `{reply_text}` | Texte intégral de la dernière réponse (HTML) |
| `{created_date}` | Date de création du ticket |
| `{created_time}` | Heure de création du ticket |
| `{created_datetime}` | Date et heure de création du ticket |
| `{reply_date}` | Date de réponse |
| `{reply_time}` | Heure de réponse |
| `{reply_datetime}` | Date et heure de réponse |

**Chaîne de secours :** modèle de locale enregistrée → modèle de locale intégré → modèle anglais enregistré → modèle anglais intégré

La langue de notification est déterminée par la sélection de langue du portail de chaque gestionnaire, enregistrée automatiquement quand il utilise le commutateur de langue.

![Modèles email — panneaux réductibles par locale, bouton Charger par défaut, éditeur Summernote](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png)

---

## API REST *(optionnel)*

*Intégrez OrgPortal dans votre CRM, ERP ou flux de travail d'intégration client.*

Nécessite le module [API and Webhooks](https://freescout.net/module/api-webhooks/).

- CRUD complet pour les organisations, les unités structurelles, les adhésions de clients et les tags
- **Champs d'organisation :** `name`, `color`, `mailboxId`, `isActive` — tous lisibles et mettable à jour via l'API
- **Sous-ressource Members** — `GET/PUT/DELETE /api/organizations/{id}/members/{memberId}` — mettez à jour le rôle, l'unité, `canManageOrg` et le drapeau `isActive` par membre indépendamment sans toucher au reste de l'adhésion
- **Sous-ressource Tags** — `GET/PUT /api/organizations/{id}/tags` — listez ou remplacez complètement les liaisons de tags (nécessite le module Tags ; retourne `503` s'il est inactif)
- Authentification via l'en-tête `X-FreeScout-API-Key` ou le paramètre de requête `api_key`
- Documentation **ReDoc interactive** à **Gérer → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`)

📖 **Full API reference → [docs/api/README.md](docs/api/README.md)**

![API documentation](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png)

![API Docs link](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs-link.png)

---

## Installation

> [!IMPORTANT]
> Téléchargez `OrgPortal.zip` depuis la **[page Releases](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)** — n'utilisez **pas** "Code → Download ZIP" et ne clonez pas le dépôt. Seul le ZIP de la version a la structure correcte pour FreeScout et supporte les mises à jour automatiques.

1. Téléchargez `OrgPortal.zip` depuis la [dernière version](https://github.com/ASTIN-UA/FreeScout-Organisations/releases/latest)
2. Extrayez et copiez le dossier `OrgPortal` dans `Modules/` de votre installation FreeScout
2. Allez à **Gérer → Modules → OrgPortal → Activer**
3. Exécutez les migrations :
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Effacez le cache :
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

> **Le support de la langue géorgienne** est déployé automatiquement au premier démarrage — aucune copie de fichier manuelle nécessaire.

---

## Mises à Jour Automatiques

OrgPortal supporte les **mises à jour en un clic** via le mécanisme de mise à jour de module intégré de FreeScout.

> **Nécessite FreeScout 1.8.170 ou plus tard.** Sur les versions plus anciennes, mettez à jour manuellement en remplaçant le dossier `OrgPortal` par le ZIP de la dernière version.

Quand une nouvelle version est disponible, une bannière apparaît sur **Gérer → Modules**. Cliquez sur **Mettre à jour maintenant** — FreeScout télécharge et installe la dernière version automatiquement.

---

## Compatibilité des Modules

| Module | Statut | Notes |
|--------|--------|-------|
| End-User Portal ≥ 1.0.85 | Optionnel | Portail gestionnaire, cloche de notification, abonnements |
| API and Webhooks ≥ 1.0.80 | Optionnel | Points de terminaison de l'API REST |
| Kanban ≥ 1.0.23 | Optionnel | Badge sur les cartes, filtre d'organisation, étiquettes de colonne État multilingues |
| Custom Fields | ✅ Compatible | — |
| Workflows | ✅ Compatible | — |
| Tags | ✅ Compatible | Puces de tags sur le formulaire de modification d'organisation ; liaisons de tags via API (`/organizations/{id}/tags`) ; attribution de tickets basée sur les tags |

---

## Configuration

### Paramètres Globaux — **Gérer → Organisations → Onglet Système**

| Option | Description |
|--------|-------------|
| Afficher le badge sur la page du ticket | Badge d'organisation dans la liste de conversation et la vue du ticket |
| Afficher le badge sur les cartes Kanban | Badge d'organisation sur les cartes du tableau Kanban |
| Source d'attribution | `member` / `tag` / `tag_only` — comment les tickets sont attribués aux organisations |
| Remplissage auto-cron | Exécuter le remplissage toutes les 5 minutes automatiquement |
| Visibilité de l'instantané | Afficher/masquer les données d'attribution dans la barre latérale du ticket |
| Commutateur de Langue du Portail | Activer le commutateur de langue dans la barre de navigation du portail utilisateur final ; choisir parmi les 19 locales |

### Paramètres par Boîte aux Lettres — **Paramètres de la Boîte aux Lettres → OrgPortal**

Remplace les valeurs globales pour la boîte aux lettres spécifique.

| Option | Description |
|--------|-------------|
| Afficher le badge sur la page du ticket | Activer/désactiver le badge pour cette boîte aux lettres |
| Afficher le badge sur les cartes Kanban | Activer/désactiver le badge pour cette boîte aux lettres |
| Afficher le bloc d'organisation dans le profil client | Basculer le bloc d'info d'organisation dans la barre latérale du ticket |
| Filtres de statut des tickets d'entreprise | Mappez les colonnes Kanban aux filtres nommés dans le portail ; étiquettes par langue avec commutateur de locale ; glissez-déposez pour réorganiser |

![Paramètres par boîte aux lettres — visibilité du badge et filtres de statut Kanban avec étiquettes multilingues](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png)

---

## Traductions

OrgPortal est entièrement localisé dans **19 langues** :

| Langue | Code | Langue | Code |
|--------|------|--------|------|
| English | `en` | Dutch | `nl` |
| Ukrainian | `uk` | Norwegian | `no` |
| German | `de` | Danish | `da` |
| French | `fr` | Swedish | `sv` |
| Spanish | `es` | Finnish | `fi` |
| Italian | `it` | Portuguese (BR) | `pt-BR` |
| Czech | `cs` | Portuguese (PT) | `pt-PT` |
| Slovak | `sk` | Romanian | `ro` |
| Polish | `pl` | Chinese Simplified | `zh-CN` |
| Georgian | `ka` | | |

Fichiers de traduction : `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

Les modèles email de notification ont des défauts intégrés pour les 19 langues.

### Intégration du Commutateur de Langue

OrgPortal inclut un commutateur de langue de portail intégré (activez dans **Onglet Système → Commutateur de Langue du Portail**). Il s'intègre aussi avec [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) — les deux peuvent être actifs simultanément.

La langue qu'un gestionnaire sélectionne s'applique à toutes les chaînes d'interface d'OrgPortal et est enregistrée comme sa langue de notification — les emails sont envoyés dans sa langue choisie automatiquement.

> **Note technique :** Le middleware `OrgPortalSetLocale` réapplique la locale du portail après le middleware `Localize` de FreeScout pour éviter qu'elle ne soit réinitialisée au défaut du système à chaque requête.

---

## Screenshots

| | |
|---|---|
| ![Organizations list](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-list.png) | ![Organization edit](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/org-edit.png) |
| *Organizations list* | *Organization edit* |
| ![Attribution settings](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/attribution-settings.png) | ![Customer edit](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/customer-org-field.png) |
| *Attribution settings* | *Customer edit* |
| ![Conversation — organization badge](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/conversation-org-badge.png) | ![Kanban integration](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/kanban-org.png) |
| *Conversation — organization badge* | *Kanban integration* |
| ![Company Tickets](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-tickets.png) | ![Portal ticket](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-reply.png) |
| *Company Tickets* | *Portal ticket* |
| ![Portal Settings — Units tab](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings-units.png) | ![Portal Settings — Members tab](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-settings-members.png) |
| *Portal Settings — Units tab* | *Portal Settings — Members tab* |
| ![Notification subscriptions](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/portal-subscriptions.png) | ![Email templates](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/admin-templates.png) |
| *Notification subscriptions* | *Email templates* |
| ![Mailbox settings](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/mailbox-settings.png) | ![User permissions](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/user-permissions.png) |
| *Mailbox settings* | *User permissions* |
| ![API documentation](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/api-docs.png) | ![Manager viewed](https://raw.githubusercontent.com/ASTIN-UA/FreeScout-Organisations/main/docs/screenshots/manager-viewed.png) |
| *API documentation* | *Manager viewed* |

---

## Licence

[MIT](LICENSE) — © 2026 ASTIN-UA
