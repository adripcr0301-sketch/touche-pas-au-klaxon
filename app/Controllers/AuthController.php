<?php

/**
 * Contrôleur AuthController — gestion de l'authentification.
 *
 * Gère l'affichage et le traitement du formulaire de connexion,
 * ainsi que la déconnexion de l'utilisateur.
 *
 * @package App\Controllers
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;

class AuthController extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Affiche le formulaire de connexion (GET /login).
     *
     * Redirige vers l'accueil si l'utilisateur est déjà connecté.
     */
    public function loginForm(): void
    {
        if ($this->isLogged()) {
            $this->redirect('/');
        }

        $this->render('auth/login', ['error' => null]);
    }

    /**
     * Traite la soumission du formulaire de connexion (POST /login).
     *
     * Vérifie les identifiants, crée la session et redirige.
     */
    public function login(): void
    {
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validation basique des champs
        if (empty($email) || empty($password)) {
            $this->render('auth/login', ['error' => 'Veuillez remplir tous les champs.']);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        // Vérification email + mot de passe
        if (!$user || !password_verify($password, $user['password'])) {
            $this->render('auth/login', ['error' => 'Email ou mot de passe incorrect.']);
            return;
        }

        // Régénère l'ID de session pour prévenir la fixation de session
        session_regenerate_id(true);

        // Stocke les données utiles en session (sans le mot de passe)
        $_SESSION['user'] = [
            'id_user'   => $user['id_user'],
            'nom'       => $user['nom'],
            'prenom'    => $user['prenom'],
            'email'     => $user['email'],
            'telephone' => $user['telephone'],
            'role'      => $user['role'],
        ];

        // Redirection selon le rôle
        if ($user['role'] === 'admin') {
            $this->redirect('/admin');
        } else {
            $this->redirect('/');
        }
    }

    /**
     * Déconnecte l'utilisateur (GET /logout).
     *
     * Détruit la session et redirige vers l'accueil.
     */
    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/');
    }
}
