<?php

/**
 * Contrôleur HomeController — page d'accueil.
 *
 * Affiche la liste des trajets disponibles.
 * Le contenu varie selon le rôle de l'utilisateur :
 * - Visiteur    : trajets avec places dispo, sans infos de contact
 * - Connecté    : tous les trajets non passés + actions (détails, modifier, supprimer)
 *
 * @package App\Controllers
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\TrajetModel;

class HomeController extends Controller
{
    private TrajetModel $trajetModel;

    public function __construct()
    {
        $this->trajetModel = new TrajetModel();
    }

    /**
     * Affiche la page d'accueil (GET /).
     */
    public function index(): void
    {
        // Visiteur : seulement les trajets avec places dispo
        // Connecté : tous les trajets non passés (y compris ses propres trajets complets)
        if ($this->isLogged()) {
            $trajets = $this->trajetModel->findAllActive();
        } else {
            $trajets = $this->trajetModel->findAvailable();
        }

        $this->render('home/index', [
            'trajets' => $trajets,
        ]);
    }
}
