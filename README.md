# 🚗 Touche Pas au Klaxon

Application de covoiturage intranet développée en **PHP 8 avec une architecture MVC maison**.  
Projet CEF — Développement Web & Mobile.

---

## Fonctionnalités

| Rôle | Accès |
|------|-------|
| **Visiteur** | Consulte les trajets disponibles (places > 0, date future) |
| **Utilisateur connecté** | Propose, modifie et supprime ses propres trajets ; voit les coordonnées des conducteurs |
| **Administrateur** | Gère les agences (CRUD), supervise tous les trajets et tous les utilisateurs |

---

## Stack technique

| Couche | Technologie |
|--------|-------------|
| Langage | PHP 8.1+ |
| Routeur | [izniburak/router](https://github.com/izniburak/php-router) v3.1 |
| Variables d'env | [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) v5.6 |
| Base de données | MySQL 8 / MariaDB 10.6 — PDO |
| Frontend | Bootstrap 5.3 + Bootstrap Icons 1.11 |
| CSS | Sass (dart-sass) compilé via npm |
| Tests | PHPUnit 10.5 |
| Analyse statique | PHPStan 1.11 (niveau 6) |
| Serveur dev | WAMP (Apache 2.4 + mod_rewrite) |

---

## Prérequis

- **PHP >= 8.1** avec les extensions `pdo_mysql`, `mbstring`
- **MySQL 8** ou **MariaDB 10.6+**
- **Apache 2.4** avec `mod_rewrite` activé
- **Composer** ([getcomposer.org](https://getcomposer.org))
- **Node.js >= 18** + npm (pour recompiler le CSS Sass)

> **WAMP / XAMPP** — tout est inclus. Assurez-vous que `mod_rewrite` est activé dans Apache.

---

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/adripcr0301-sketch/touche-pas-au-klaxon.git
cd touche-pas-au-klaxon
```

---

### 2. Dépendances PHP

```bash
composer install
```

---

### 3. Variables d'environnement

```bash
cp .env.example .env
```

Ouvrir `.env` et adapter si nécessaire :

```dotenv
APP_NAME="Touche Pas au Klaxon"
APP_URL=http://localhost/klaxon   # URL de base — doit correspondre à l'alias Apache
APP_ENV=development

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=klaxon
DB_USER=root
DB_PASS=                          # Laisser vide pour WAMP sans mot de passe
```

---

### 4. Configurer Apache

L'application doit être accessible sous un **alias**, pas directement dans `htdocs`.

#### Option A — WAMP (fichier alias dédié)

Créer `C:\wamp64\alias\klaxon.conf` :

```apache
Alias /klaxon "C:/chemin/vers/touche-pas-au-klaxon/public"

<Directory "C:/chemin/vers/touche-pas-au-klaxon/public">
    AllowOverride All
    Require all granted
</Directory>
```

Redémarrer WAMP, puis vérifier que `http://localhost/klaxon/` répond.

#### Option B — XAMPP / `htdocs`

Placer le projet dans `htdocs/klaxon/` ou ajouter un alias dans `httpd-vhosts.conf`.  
S'assurer que `AllowOverride All` est actif sur le répertoire.

> Le fichier `public/.htaccess` gère la réécriture vers `index.php` avec `RewriteBase /klaxon`.  
> Si votre alias est différent, adapter `RewriteBase` **et** `APP_URL` en conséquence.

---

### 5. Base de données

Dans **phpMyAdmin** (onglet Import) ou en ligne de commande :

```bash
mysql -u root < database/create.sql
mysql -u root < database/seed.sql
```

Cela crée la base `klaxon` avec 12 agences, 21 utilisateurs et 12 trajets de test.

---

### 6. Assets CSS (Sass)

Le fichier `public/assets/css/main.css` est déjà compilé et versionné.  
Pour le recompiler après modification des sources Sass :

```bash
npm install          # une seule fois
npm run sass:build   # compilation prod (minifiée)
npm run sass:dev     # watch en développement
```

---

### 7. Accéder à l'application

```
http://localhost/klaxon/
```

---

## Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|--------------|
| Administrateur | `admin@klaxon.fr` | `Admin1234!` |
| Utilisateur | `alexandre.martin@email.fr` | `User1234!` |
| Utilisateur | `sophie.dubois@email.fr` | `User1234!` |

> Tous les utilisateurs du seed partagent le mot de passe `User1234!`.

---

## Tests unitaires (PHPUnit)

### Créer la base de test (une seule fois)

```bash
mysql -u root < database/create_test.sql
```

Ou importer `database/create_test.sql` dans phpMyAdmin.  
La base `klaxon_test` est créée vide (les tests s'auto-nettoient par rollback de transaction).

### Lancer les tests

```bash
./vendor/bin/phpunit --testdox
```

Résultat attendu :

```
Agence Model (Tests\AgenceModel)
 ✔ Find all returns array
 ✔ Create inserts agence
 ✔ Find by id returns inserted agence
 ✔ Update changes name
 ✔ Is used returns false when agence has no trajets
 ✔ Is used returns true when agence is used in trajet
 ✔ Delete removes agence

Trajet Model (Tests\TrajetModel)
 ✔ Create inserts trajet
 ✔ Create sets places dispo equal to places total
 ✔ Find by id returns joined data
 ✔ Find by id returns false for unknown id
 ✔ Update modifies places total
 ✔ Is owner returns true for author
 ✔ Is owner returns false for other user
 ✔ Delete removes trajet

OK (15 tests, 25 assertions)
```

> Les tests utilisent des **transactions annulées** après chaque cas :  
> la base `klaxon_test` reste toujours vide entre les runs.

---

## Analyse statique (PHPStan)

```bash
./vendor/bin/phpstan analyse
```

Configuré au niveau 6 sur le dossier `app/`.

---

## Structure du projet

```
touche-pas-au-klaxon/
├── app/
│   ├── Controllers/
│   │   ├── AdminController.php   # Tableau de bord + CRUD agences + gestion trajets
│   │   ├── AuthController.php    # Connexion / déconnexion
│   │   ├── HomeController.php    # Page d'accueil (liste des trajets)
│   │   └── TrajetController.php  # CRUD trajets utilisateur
│   ├── Core/
│   │   ├── Controller.php        # Contrôleur abstrait (render, redirect, flash, auth)
│   │   ├── Database.php          # Singleton PDO
│   │   └── Model.php             # Modèle abstrait (findAll, findById, delete)
│   ├── Models/
│   │   ├── AgenceModel.php       # findAll, create, update, delete, isUsed
│   │   ├── TrajetModel.php       # findAll, findAvailable, findAllActive, create, update, isOwner
│   │   └── UserModel.php         # findAll, findByEmail
│   └── Views/
│       ├── layouts/main.php      # Layout HTML Bootstrap 5
│       ├── partials/             # Header (3 états) + footer
│       ├── auth/login.php        # Formulaire de connexion
│       ├── home/index.php        # Liste des trajets + modales Bootstrap
│       ├── trajets/              # Formulaires création / modification
│       └── admin/                # Dashboard + users + agences CRUD + trajets
├── database/
│   ├── create.sql                # Crée la BDD klaxon + tables
│   ├── seed.sql                  # Données de test (agences, users, trajets)
│   └── create_test.sql           # Crée la BDD klaxon_test (tests PHPUnit)
├── docs/
│   ├── mcd.md                    # Modèle Conceptuel des Données
│   └── mld.md                    # Modèle Logique des Données
├── public/                       # Document root Apache
│   ├── index.php                 # Front controller (routes)
│   ├── .htaccess                 # mod_rewrite → index.php
│   └── assets/css/main.css       # CSS compilé (Bootstrap + custom)
├── resources/scss/main.scss      # Sources Sass (variables Bootstrap + styles)
├── tests/
│   ├── BaseTestCase.php          # Classe de base : transaction rollback + reset PDO
│   ├── AgenceModelTest.php       # 7 tests AgenceModel
│   └── TrajetModelTest.php       # 8 tests TrajetModel
├── .env.example                  # Modèle de configuration
├── .gitignore
├── composer.json                 # Dépendances PHP
├── package.json                  # Scripts npm (Sass)
├── phpstan.neon                  # Configuration PHPStan
└── phpunit.xml                   # Configuration PHPUnit
```

---

## Sécurité

- Mots de passe hachés avec **bcrypt** (`password_hash` / `password_verify`)
- `session_regenerate_id(true)` à la connexion (protection fixation de session)
- Toutes les sorties HTML protégées par `htmlspecialchars()`
- Requêtes paramétrées PDO (aucune concaténation SQL)
- Accès admin protégé par `requireAdmin()` sur chaque action
- Suppression d'agence bloquée si référencée par un trajet (`isUsed()`)

---

