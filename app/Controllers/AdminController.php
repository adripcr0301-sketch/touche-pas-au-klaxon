<?php

/**
 * Contrôleur AdminController — tableau de bord administrateur.
 *
 * Toutes les actions requièrent le rôle 'admin'.
 * Gestion des utilisateurs, agences et trajets.
 *
 * @package App\Controllers
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Models\AgenceModel;
use App\Models\TrajetModel;

class AdminController extends Controller
{
    private UserModel   $userModel;
    private AgenceModel $agenceModel;
    private TrajetModel $trajetModel;

    public function __construct()
    {
        $this->userModel   = new UserModel();
        $this->agenceModel = new AgenceModel();
        $this->trajetModel = new TrajetModel();
    }

    /** Tableau de bord (GET /admin). */
    public function dashboard(): void
    {
        $this->requireAdmin();
        $this->render('admin/dashboard', [
            'nbUsers'   => count($this->userModel->findAll()),
            'nbAgences' => count($this->agenceModel->findAll()),
            'nbTrajets' => count($this->trajetModel->findAll()),
        ]);
    }

    /** Liste des utilisateurs (GET /admin/utilisateurs). */
    public function users(): void
    {
        $this->requireAdmin();
        $this->render('admin/users', [
            'users' => $this->userModel->findAll(),
        ]);
    }

    /** Liste des agences (GET /admin/agences). */
    public function agences(): void
    {
        $this->requireAdmin();
        $this->render('admin/agences', [
            'agences' => $this->agenceModel->findAll(),
        ]);
    }

    /** Formulaire création agence (GET /admin/agences/creer). */
    public function createAgence(): void
    {
        $this->requireAdmin();
        $this->render('admin/agence-form', ['agence' => null, 'errors' => []]);
    }

    /** Enregistre une nouvelle agence (POST /admin/agences/creer). */
    public function storeAgence(): void
    {
        $this->requireAdmin();
        $nom    = trim($_POST['nom'] ?? '');
        $errors = [];

        if ($nom === '') {
            $errors[] = 'Le nom de l\'agence est obligatoire.';
        }

        if (!empty($errors)) {
            $this->render('admin/agence-form', ['agence' => null, 'errors' => $errors]);
            return;
        }

        $this->agenceModel->create($nom);
        $this->setFlash('L\'agence a été créée.');
        $this->redirect('/admin/agences');
    }

    /** Formulaire modification agence (GET /admin/agences/:id/modifier). */
    public function editAgence(int $id): void
    {
        $this->requireAdmin();
        $agence = $this->agenceModel->findById($id);

        if (!$agence) {
            $this->setFlash('Agence introuvable.', 'danger');
            $this->redirect('/admin/agences');
        }

        $this->render('admin/agence-form', ['agence' => $agence, 'errors' => []]);
    }

    /** Met à jour une agence (POST /admin/agences/:id/modifier). */
    public function updateAgence(int $id): void
    {
        $this->requireAdmin();
        $nom    = trim($_POST['nom'] ?? '');
        $errors = [];

        if ($nom === '') {
            $errors[] = 'Le nom de l\'agence est obligatoire.';
        }

        if (!empty($errors)) {
            $agence = $this->agenceModel->findById($id);
            $this->render('admin/agence-form', ['agence' => $agence, 'errors' => $errors]);
            return;
        }

        $this->agenceModel->update($id, $nom);
        $this->setFlash('L\'agence a été modifiée.');
        $this->redirect('/admin/agences');
    }

    /** Supprime une agence (POST /admin/agences/:id/supprimer). */
    public function destroyAgence(int $id): void
    {
        $this->requireAdmin();

        if ($this->agenceModel->isUsed($id)) {
            $this->setFlash('Impossible de supprimer : cette agence est utilisée par un ou plusieurs trajets.', 'danger');
            $this->redirect('/admin/agences');
        }

        $this->agenceModel->delete($id);
        $this->setFlash('L\'agence a été supprimée.');
        $this->redirect('/admin/agences');
    }

    /** Liste de tous les trajets (GET /admin/trajets). */
    public function trajets(): void
    {
        $this->requireAdmin();
        $this->render('admin/trajets', [
            'trajets' => $this->trajetModel->findAll(),
        ]);
    }

    /** Supprime un trajet (POST /admin/trajets/:id/supprimer). */
    public function destroyTrajet(int $id): void
    {
        $this->requireAdmin();
        $this->trajetModel->delete($id);
        $this->setFlash('Le trajet a été supprimé.');
        $this->redirect('/admin/trajets');
    }
}
