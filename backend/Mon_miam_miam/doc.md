# Documentation du Projet Mon Miam Miam

## Vue d'ensemble

**Mon Miam Miam** est une application web de gestion de restaurant développée avec Laravel 12. Cette plateforme permet la gestion complète d'un restaurant avec des fonctionnalités avancées incluant un système de commandes, de fidélité, de mini-jeux et de gestion multi-rôles.

## Informations du Projet

- **Framework** : Laravel 12
- **Base de données** : SQLite
- **Frontend** : Blade Templates + TailwindCSS + Alpine.js
- **Authentification** : Laravel Breeze
- **Documentation API** : Swagger (L5-Swagger)
- **Tests** : PHPUnit

## Architecture du Projet

### Structure des Dossiers

```
Mon_miam_miam/
├── app/
│   ├── Http/Controllers/     # Contrôleurs de l'application
│   ├── Models/              # Modèles Eloquent
│   ├── Services/            # Services métier
│   ├── Notifications/       # Notifications système
│   └── Providers/           # Service Providers
├── database/
│   ├── migrations/          # Migrations de base de données
│   ├── seeders/            # Seeders pour données de test
│   └── factories/          # Factories pour tests
├── resources/
│   ├── views/              # Templates Blade
│   ├── css/                # Styles CSS
│   └── js/                 # Scripts JavaScript
├── routes/                 # Définition des routes
├── public/                 # Fichiers publics
└── tests/                  # Tests automatisés
```

## Fonctionnalités Principales

### 1. Système d'Authentification et Rôles

L'application gère plusieurs types d'utilisateurs avec des permissions spécifiques :

- **Student** : Clients étudiants
- **Employee** : Employés du restaurant
- **Manager** : Gérants
- **Admin** : Administrateurs système

### 2. Gestion des Plats et Menu

- Création, modification et suppression de plats
- Catégorisation (plat, boisson)
- Gestion de la disponibilité
- Upload d'images
- Système de prix

### 3. Système de Commandes

- Panier de commandes
- Suivi en temps réel des commandes
- Statuts : en_attente, en_preparation, prete, en_livraison, livree, annulee
- Historique des commandes
- Système de notifications

### 4. Programme de Fidélité

- Système de points de fidélité
- Parrainage avec codes de référence
- Historique des transactions
- Récompenses et niveaux de fidélité

### 5. Mini-Jeux et Événements

- Roue de la fortune
- Quiz cuisine
- Système d'événements
- Participation et récompenses

### 6. Gestion Administrative

- Dashboard avec statistiques
- Gestion des employés
- Gestion des réclamations
- Système de promotions
- Rapports et exports

## Modèles de Données

### User (Utilisateur)
```php
- id, name, email, password
- role (student, employee, manager, admin)
- referral_code, referred_by
- total_points
- is_suspended
```

### Plat (Plat)
```php
- id, name, description, price
- category (plat, boisson)
- image_url, is_available
- total_points
```

### Commande (Commande)
```php
- id, numero_commande, user_id
- statut, montant_total
- items (JSON), adresse_livraison
- telephone_contact, notes
- preparee_a, livree_a
```

### Autres Modèles
- **Cart/CartItem** : Panier de commandes
- **LoyaltyPoint** : Points de fidélité
- **Referral** : Système de parrainage
- **Event/EventParticipant** : Événements
- **Reclamation** : Réclamations clients
- **Promotion** : Promotions et offres
- **Reward** : Récompenses
- **Game** : Mini-jeux

## Routes et Contrôleurs

### Routes Publiques
- `/` : Page d'accueil (WelcomeController)

### Routes Authentifiées
- `/dashboard` : Dashboard général
- `/menu` : Affichage du menu
- `/panier` : Gestion du panier
- `/historique` : Historique des commandes
- `/mes-points` : Points de fidélité
- `/minijeux` : Mini-jeux

### Routes par Rôle

#### Student
- `/student/dashboard` : Dashboard étudiant
- `/student/menu` : Menu étudiant

#### Employee
- `/employee/dashboard` : Dashboard employé
- `/employee/commandes` : Gestion des commandes
- `/employee/menu` : Gestion du menu
- `/employee/reclamations` : Gestion des réclamations

#### Admin
- `/admin/dashboard` : Dashboard administrateur
- `/admin/employees` : Gestion des employés
- `/admin/promotions` : Gestion des promotions
- `/admin/statistiques` : Statistiques

#### Manager
- `/gerant/dashboard` : Dashboard gérant

## Technologies Utilisées

### Backend
- **Laravel 12** : Framework PHP
- **Eloquent ORM** : Gestion de base de données
- **Laravel Breeze** : Authentification
- **L5-Swagger** : Documentation API
- **Simple QR Code** : Génération de QR codes

### Frontend
- **Blade Templates** : Moteur de templates
- **TailwindCSS** : Framework CSS
- **Alpine.js** : Framework JavaScript léger
- **Laravel Echo** : WebSockets en temps réel
- **Pusher** : Service de broadcasting

### Base de Données
- **SQLite** : Base de données principale
- **Migrations** : Versioning de la base de données
- **Seeders** : Données de test

### Outils de Développement
- **Vite** : Build tool moderne
- **PHPUnit** : Tests unitaires
- **Laravel Pint** : Code style fixer
- **Laravel Pail** : Monitoring des logs

## Installation et Configuration

### Prérequis
- PHP 8.2+
- Composer
- Node.js et npm
- SQLite

### Installation
```bash
# Cloner le projet
git clone [url-du-projet]

# Installer les dépendances PHP
composer install

# Installer les dépendances Node.js
npm install

# Configuration de l'environnement
cp .env.example .env
php artisan key:generate

# Migration et seeding
php artisan migrate --force
php artisan db:seed

# Build des assets
npm run build
```

### Scripts Disponibles

```bash
# Développement complet
composer run dev

# Tests
composer run test

# Setup complet
composer run setup
```

## Fonctionnalités Avancées

### 1. Système de Notifications
- Notifications en temps réel pour les changements de statut
- Notifications par email
- Système de broadcasting avec Pusher

### 2. Gestion des Réclamations
- Création de réclamations par les clients
- Traitement par les employés
- Suivi des statuts

### 3. Système de Statistiques
- Dashboard avec métriques en temps réel
- Graphiques de performance
- Exports de données

### 4. Mini-Jeux Intégrés
- Roue de la fortune avec récompenses
- Quiz cuisine éducatif
- Système d'événements temporaires

### 5. Gestion Multi-Restaurant
- Support pour plusieurs restaurants
- Paramètres configurables par restaurant
- Gestion centralisée

## Sécurité

### Authentification
- Système d'authentification Laravel Breeze
- Vérification d'email
- Réinitialisation de mot de passe
- Middleware de rôles

### Autorisation
- Contrôle d'accès basé sur les rôles
- Middleware de protection des routes
- Validation des permissions

### Protection des Données
- Validation des entrées utilisateur
- Protection CSRF
- Chiffrement des mots de passe
- Sanitisation des données

## Tests

### Structure des Tests
- **Feature Tests** : Tests d'intégration
- **Unit Tests** : Tests unitaires
- **Auth Tests** : Tests d'authentification

### Exécution des Tests
```bash
# Tous les tests
php artisan test

# Tests spécifiques
php artisan test --filter=AuthTest
```

## Déploiement

### Environnement de Production
1. Configuration des variables d'environnement
2. Optimisation des assets
3. Configuration de la base de données
4. Mise en place des services de broadcasting

### Monitoring
- Logs Laravel avec Pail
- Monitoring des performances
- Alertes système

## API Documentation

L'application inclut une documentation API automatique générée avec Swagger accessible via `/api/documentation`.

## Contribution

### Standards de Code
- PSR-12 pour le PHP
- Laravel Pint pour le formatage
- Tests obligatoires pour les nouvelles fonctionnalités

### Workflow
1. Fork du projet
2. Création d'une branche feature
3. Développement avec tests
4. Pull request avec description détaillée

## Support et Maintenance

### Logs et Debugging
- Logs centralisés dans `storage/logs/`
- Mode debug configurable
- Monitoring en temps réel avec Pail

### Sauvegarde
- Sauvegarde régulière de la base de données
- Versioning des migrations
- Backup des fichiers uploadés

## Roadmap et Évolutions

### Fonctionnalités Futures
- Application mobile
- Intégration paiement en ligne
- Système de livraison géolocalisée
- Analytics avancées
- Intégration réseaux sociaux

### Optimisations
- Cache Redis
- CDN pour les assets
- Optimisation des requêtes
- Mise en cache des données fréquentes

---

*Documentation générée automatiquement - Dernière mise à jour : $(date)*