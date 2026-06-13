# OrgPortal — Portail d'organisation pour FreeScout

<img src="../Modules/OrgPortal/logo.png" alt="OrgPortal" width="140" align="right">

Un module FreeScout qui ajoute le concept d'**Organisations** (entreprises/équipes) aux clients, étend le portail utilisateur final pour les gestionnaires et affiche un badge d'organisation sur les tickets et les cartes Kanban.

**Version FreeScout minimale :** 1.8.147  
**Dépendances :** aucune requise  
**Optionnel :** [Portail Utilisateur Final](https://freescout.net/module/end-user-portal/), [API et Webhooks](https://freescout.net/module/api-webhooks/), [Kanban](https://freescout.net/module/kanban/)

🌐 **Langue:** [English](../README.md) · [Українська](README.uk.md) · [Deutsch](README.de.md) · [Français](README.fr.md) · [Español](README.es.md) · [Italiano](README.it.md) · [Polski](README.pl.md) · [Čeština](README.cs.md) · [Slovenčina](README.sk.md) · [Nederlands](README.nl.md) · [Norsk](README.no.md) · [Dansk](README.da.md) · [Svenska](README.sv.md) · [Suomi](README.fi.md) · [Português (BR)](README.pt-BR.md) · [Português (PT)](README.pt-PT.md) · [Română](README.ro.md) · [中文 (简体)](README.zh-CN.md)

---

## Fonctionnalités

### Gestion des organisations (administrateur)
- **Gérer → Organisations** — CRUD complet : créer, modifier, supprimer les organisations
- **Liaison de boîte aux lettres** — une organisation peut être **globale** (visible dans toutes les boîtes) ou **liée à une boîte aux lettres spécifique**; l'étiquette correspondante s'affiche dans la liste des organisations
- Assigner des clients aux organisations avec sélection de rôle : `membre` ou `gestionnaire`
- **Modifier le rôle du membre** directement dans le tableau (sans supprimer et rajouter)
- Autocomplétion de recherche client par nom ou e-mail; les clients appartenant déjà à une organisation sont exclus des résultats
- L'e-mail du membre s'affiche sous le nom dans le tableau des membres
- Un client — une organisation (appliqué au niveau de la base de données et de l'API)
- **Couleur du badge** — palette visuelle avec 12 couleurs dans le formulaire d'édition de l'organisation; le gris par défaut

### Permissions utilisateur
- Nouvelle permission **"Autoriser la gestion des organisations"** — les non-administrateurs disposant de cette permission accèdent à la liste, créer et modifier les pages d'organisation
- La suppression d'organisations reste exclusive aux administrateurs

### Fiche client
- Champ **Organisation** dans le formulaire d'édition du client — sélectionner organisation et rôle
- Bouton **Tickets de l'organisation** — ouvre une recherche pour tous les tickets de l'organisation

### Badge d'organisation sur les tickets
- Affiché sous l'objet sur la page du ticket et avant le nom dans la liste de conversation
- Cliquable — ouvre une recherche pour tous les tickets de cette organisation
- La couleur du badge est déterminée par le paramètre d'organisation (gris par défaut)
- Activer/désactiver **par boîte aux lettres** via **Paramètres de la boîte aux lettres → OrgPortal**; la valeur globale est utilisée comme secours

### Badge d'organisation sur les cartes Kanban
- Affiché après le compteur de messages sur chaque carte
- Cliquable — mène à la recherche d'organisation
- La couleur correspond au paramètre d'organisation
- Filtre **Organisation** intégré au menu déroulant de filtre Kanban standard : modal avec cases à cocher, similaire au filtre d'étiquettes; l'état est conservé entre les navigations
- Activer/désactiver **par boîte aux lettres** via **Paramètres de la boîte aux lettres → OrgPortal**

### Filtre de recherche d'organisation
- Étend la recherche FreeScout standard avec un filtre **Organisation**
- Affiche tous les tickets des clients appartenant à l'organisation sélectionnée

### Portail utilisateur final — accès gestionnaire *(optionnel)*

Un gestionnaire d'organisation obtient un accès étendu via EUP :

- Élément **Tickets de l'entreprise** dans la navigation du portail
- Tableau des tickets de l'entreprise avec colonnes :
  - **#** et **Objet** avec troncature par ellipse et infobulle au survol
  - **Responsable** — agent assigné
  - **Auteur** — le client qui a ouvert le ticket; cliquer filtre les tickets par auteur dans l'organisation
  - **Statut** — Actif / En attente / Fermé / Spam avec icônes
  - **État** — nom de la colonne Kanban (avec étiquette personnalisée si configurée); affiché uniquement si le module Kanban est actif
  - **Mis à jour** — date et heure de la dernière réponse
- Recherche par objet du ticket
- Filtres par statuts Kanban (configurables via **Paramètres de la boîte aux lettres → OrgPortal**)
- Répondre au ticket avec support des **pièces jointes** (glisser-déposer, plusieurs fichiers)
- **Fermer le ticket** — le gestionnaire peut fermer un ticket; une nouvelle réponse le rouvre automatiquement
- Modifier l'auteur du ticket — réassigner un ticket à un autre membre de l'organisation
- Page **Paramètres org** pour configurer les notifications par e-mail
- L'accès aux tickets est **strictement limité à la boîte aux lettres actuelle** (organisation copiée à une autre boîte — portail 403)

### Notifications par e-mail *(optionnel)*
- Les gestionnaires avec l'option activée reçoivent un e-mail lorsqu'un nouveau ticket est créé par un membre de l'organisation
- Utilise le pilote de courrier de la boîte aux lettres correspondante

### Paramètres de la boîte aux lettres

**Paramètres de la boîte aux lettres → OrgPortal** (par boîte) :

| Option | Description |
|--------|------------|
| Afficher le badge sur la page du ticket | Activer/désactiver le badge dans cette boîte |
| Afficher le badge sur les cartes Kanban | Activer/désactiver le badge dans cette boîte |
| Filtres de statut des tickets de l'entreprise | Sélectionner les colonnes Kanban affichées comme cases à cocher sur la page des tickets; étiquette personnalisée pour chaque filtre |

---

### REST API *(optionnel, nécessite API et Webhooks)*

OrgPortal expose une API REST complète pour gérer les organisations, les unités structurelles et les adhésions des clients — authentification via l'en-tête `X-FreeScout-API-Key` ou le paramètre de requête `api_key`.

📖 **Référence complète de l'API → [docs/api/README.fr.md](api/README.fr.md)** (tous les points de terminaison, exemples de requêtes/réponses, codes d'erreur)

Une documentation interactive ReDoc est également disponible dans **Manage → API & Webhooks → OrgPortal API Docs** (`/orgportal/admin/api-docs`).

---

## Installation

1. Copiez le dossier `OrgPortal` dans `Modules/` de votre FreeScout
2. Dans le panneau d'administration : **Gérer → Modules → OrgPortal → Activer**
3. Exécutez les migrations :
   ```bash
   php artisan module:migrate OrgPortal
   ```
4. Effacez le cache :
   ```bash
   php artisan cache:clear && php artisan config:clear
   ```

---

## Mises à jour

OrgPortal prend en charge les **mises à jour automatiques** via le mécanisme de mise à jour des modules intégré de FreeScout.

> **Requires FreeScout 1.8.170 or later.** On older versions the update banner will not appear — update the module manually by replacing the `OrgPortal` folder with the latest release ZIP.

Lorsqu'une nouvelle version est disponible, une bannière apparaît sur la page **Gérer → Modules**. Cliquez sur **Mettre à jour maintenant** — FreeScout téléchargera et installera la dernière version automatiquement.

Aucune copie de fichier manuelle requise.

---

## Compatibilité des modules

| Module | Statut |
|--------|--------|
| Portail Utilisateur Final ≥ 1.0.85 | Optionnel — fonctionnalités du portail pour les gestionnaires |
| API et Webhooks ≥ 1.0.80 | Optionnel — points de terminaison de l'API REST |
| Kanban ≥ 1.0.23 | Optionnel — badge, filtre, colonne "État" dans les tickets de l'entreprise |
| Champs personnalisés | Compatible |
| Workflows | Compatible |
| Tags | Compatible |

---

## Configuration

### Global (**Gérer → Paramètres OrgPortal**)

| Option | Par défaut |
|--------|-----------|
| Afficher le badge sur la page du ticket | ✅ |
| Afficher le badge sur les cartes Kanban | ✅ |

### Par boîte aux lettres (**Paramètres de la boîte aux lettres → OrgPortal**)

Remplace les valeurs globales pour la boîte aux lettres spécifique.

| Option | Description |
|--------|------------|
| Afficher le badge sur la page du ticket | Badge dans la liste de conversation et sur la page du ticket |
| Afficher le badge sur les cartes Kanban | Badge sur les cartes Kanban |
| Filtres de statut des tickets de l'entreprise | Colonnes Kanban comme cases à cocher sur la page des tickets de l'entreprise; chaque filtre a une étiquette personnalisée visible pour les utilisateurs du portail |

---

## Traductions

Langues supportées : **English** (`en`), **Українська** (`uk`), **Română** (`ro`), **Georgian** (`ka`), **Deutsch** (`de`), **Français** (`fr`), **Español** (`es`), **Italiano** (`it`), **Čeština** (`cs`), **Slovenčina** (`sk`), **Polski** (`pl`), **Русский** (`ru`), **Nederlands** (`nl`), **Norsk** (`no`), **Dansk** (`da`), **Svenska** (`sv`), **Suomi** (`fi`), **Português BR** (`pt-BR`), **Português PT** (`pt-PT`), **中文 (简体)** (`zh-CN`).

Fichiers : `Modules/OrgPortal/Resources/lang/{locale}/messages.php`

### Intégration EUPSWLANG

Le module fonctionne correctement avec [EUP Switch Language](https://freescout.net/module/eup-sw-lang/) : la langue sélectionnée dans le portail s'applique également aux chaînes OrgPortal.

Pour qu'une langue apparaisse dans la liste EUPSWLANG, le fichier correspondant `Modules/EndUserPortal/Resources/lang/{locale}.json` doit exister. Les fichiers pour **Română** (`ro`) sont inclus dans le paquet; **Georgian** (`ka`) n'est supporté que dans la section d'administration (pas de support système dans le noyau FreeScout).

> **Détail technique :** le middleware `ReapplyEupLocale` (enregistré en dernier dans le groupe d'itinéraires du portail) restaure les paramètres régionaux après le middleware `Localize` de FreeScout, qui réinitialiserait autrement la sélection de langue du portail à la valeur par défaut du système.

---

## Licence

[MIT](../LICENSE) — © 2026 ASTIN-UA
