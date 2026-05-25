<?php

/**
 * Front Controller — point d'entrée unique de l'application.
 *
 * Toutes les requêtes HTTP passent par ce fichier.
 * Il charge l'autoloader Composer, les variables d'environnement,
 * démarre la session et initialise le routeur.
 */

declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

// --- Autoloader Composer ---
require ROOT_PATH . '/vendor/autoload.php';

// --- Variables d'environnement (.env) ---
$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();
$dotenv->required(['APP_URL', 'DB_HOST', 'DB_NAME', 'DB_USER']);

define('APP_NAME', $_ENV['APP_NAME'] ?? 'Touche Pas au Klaxon');
define('BASE_URL',  rtrim($_ENV['APP_URL'], '/'));

// --- Session ---
session_start();

// --- Routeur ---
use Buki\Router\Router;

$router = new Router([
    'paths' => [
        'controllers' => ROOT_PATH . '/app/Controllers',
    ],
    'namespaces' => [
        'controllers' => 'App\\Controllers',
    ],
]);

// ---- Routes publiques ----
$router->get('/',       'HomeController@index');
$router->get('/login',  'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// ---- Routes utilisateur connecté ----
$router->get('/trajets/creer',                   'TrajetController@create');
$router->post('/trajets/creer',                  'TrajetController@store');
$router->get('/trajets/(:num)/modifier',         'TrajetController@edit');
$router->post('/trajets/(:num)/modifier',        'TrajetController@update');
$router->post('/trajets/(:num)/supprimer',       'TrajetController@destroy');

// ---- Routes administrateur ----
$router->get('/admin',                           'AdminController@dashboard');
$router->get('/admin/utilisateurs',              'AdminController@users');
$router->get('/admin/agences',                   'AdminController@agences');
$router->get('/admin/agences/creer',             'AdminController@createAgence');
$router->post('/admin/agences/creer',            'AdminController@storeAgence');
$router->get('/admin/agences/(:num)/modifier',   'AdminController@editAgence');
$router->post('/admin/agences/(:num)/modifier',  'AdminController@updateAgence');
$router->post('/admin/agences/(:num)/supprimer', 'AdminController@destroyAgence');
$router->get('/admin/trajets',                   'AdminController@trajets');
$router->post('/admin/trajets/(:num)/supprimer', 'AdminController@destroyTrajet');

$router->run();
