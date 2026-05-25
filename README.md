# Touche Pas au Klaxon

Application de covoiturage intranet développée en PHP avec une architecture MVC.

## Prérequis

- PHP >= 8.1
- MySQL >= 8.0 ou MariaDB >= 10.6
- Composer
- Serveur Apache avec mod_rewrite activé (ex: XAMPP)
- Node.js + npm (pour compiler les assets Sass)

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/adripcr0301-sketch/touche-pas-au-klaxon.git
cd touche-pas-au-klaxon
```

### 2. Installer les dépendances PHP

```bash
composer install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
```

Éditer `.env` avec vos paramètres de connexion BDD.

### 4. Créer la base de données

```sql
-- Dans votre client MySQL (phpMyAdmin, DBeaver, CLI…)
source database/create.sql
source database/seed.sql
```

### 5. Compiler les assets CSS

```bash
npm install
npm run build
```

### 6. Lancer l'application

Placer le dossier du projet dans le répertoire web de votre serveur Apache (ex: `htdocs/klaxon/`) et accéder à :

```
http://localhost/klaxon/public
```

## Comptes de test

| Rôle          | Email                      | Mot de passe |
|---------------|----------------------------|--------------|
| Administrateur| admin@klaxon.fr            | Admin1234!   |
| Utilisateur   | alexandre.martin@email.fr  | User1234!    |

## Structure du projet

```
├── app/
│   ├── Controllers/    Contrôleurs MVC
│   ├── Core/           Classes de base (Database, Model, Controller)
│   ├── Models/         Modèles d'accès aux données
│   └── Views/          Vues PHP (templates)
│       ├── layouts/    Layout principal
│       ├── partials/   Header, footer
│       ├── home/       Page d'accueil
│       ├── auth/       Connexion
│       ├── trajets/    CRUD trajets
│       └── admin/      Tableau de bord admin
├── database/
│   ├── create.sql      Script de création de la BDD
│   └── seed.sql        Jeu d'essais
├── docs/
│   ├── mcd.md          Modèle Conceptuel des Données
│   └── mld.md          Modèle Logique des Données
├── public/             Document root Apache
│   ├── index.php       Front controller
│   └── assets/         CSS compilé, JS
├── resources/
│   └── scss/           Sources Sass
├── tests/              Tests unitaires PHPUnit
├── .env.example        Exemple de configuration
├── composer.json       Dépendances PHP
├── phpstan.neon        Configuration PHPStan
└── phpunit.xml         Configuration PHPUnit
```

## Qualité du code

```bash
# Analyse statique
./vendor/bin/phpstan analyse

# Tests unitaires
./vendor/bin/phpunit
```
