<?php

/**
 * Classe abstraite Controller — base de tous les contrôleurs.
 *
 * Fournit les méthodes utilitaires partagées : rendu des vues,
 * redirection, messages flash et vérification des droits d'accès.
 *
 * @package App\Core
 */

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

abstract class Controller
{
    /**
     * Rend une vue dans le layout principal.
     *
     * @param string               $view Chemin relatif de la vue (ex: 'home/index').
     * @param array<string, mixed> $data Variables injectées dans la vue.
     */
    protected function render(string $view, array $data = []): void
    {
        $viewFile = ROOT_PATH . '/app/Views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("Vue introuvable : {$view}");
        }

        // Rend les clés du tableau accessibles comme variables dans la vue et le layout
        extract($data);

        require ROOT_PATH . '/app/Views/layouts/main.php';
    }

    /**
     * Redirige vers une URL relative à BASE_URL.
     *
     * @param string $url URL relative (ex: '/login', '/admin').
     */
    protected function redirect(string $url): void
    {
        header('Location: ' . BASE_URL . $url);
        exit;
    }

    /**
     * Stocke un message flash en session.
     *
     * @param string $message Message à afficher.
     * @param string $type    Type Bootstrap : 'success', 'danger', 'warning', 'info'.
     */
    protected function setFlash(string $message, string $type = 'success'): void
    {
        $_SESSION['flash'] = [
            'message' => $message,
            'type'    => $type,
        ];
    }

    /**
     * Indique si un utilisateur est connecté.
     */
    protected function isLogged(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Indique si l'utilisateur connecté est administrateur.
     */
    protected function isAdmin(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }

    /**
     * Exige une connexion, redirige vers /login sinon.
     */
    protected function requireAuth(): void
    {
        if (!$this->isLogged()) {
            $this->redirect('/login');
        }
    }

    /**
     * Exige le rôle administrateur, redirige vers / sinon.
     */
    protected function requireAdmin(): void
    {
        if (!$this->isAdmin()) {
            $this->redirect('/');
        }
    }

    /**
     * Récupère et supprime le message flash de la session.
     *
     * @return array<string, string>|null
     */
    protected function getFlash(): ?array
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}
