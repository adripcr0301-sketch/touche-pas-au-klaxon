<?php

/**
 * Contrôleur TrajetController — création, modification, suppression de trajets.
 *
 * Toutes les actions nécessitent une connexion.
 * La modification et la suppression sont réservées à l'auteur du trajet
 * (ou à l'administrateur).
 *
 * @package App\Controllers
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\TrajetModel;
use App\Models\AgenceModel;

class TrajetController extends Controller
{
    private TrajetModel $trajetModel;
    private AgenceModel $agenceModel;

    public function __construct()
    {
        $this->trajetModel = new TrajetModel();
        $this->agenceModel = new AgenceModel();
    }

    /**
     * Affiche le formulaire de création d'un trajet (GET /trajets/creer).
     */
    public function create(): void
    {
        $this->requireAuth();

        $this->render('trajets/create', [
            'agences' => $this->agenceModel->findAll(),
            'user'    => $_SESSION['user'],
            'errors'  => [],
        ]);
    }

    /**
     * Enregistre un nouveau trajet (POST /trajets/creer).
     */
    public function store(): void
    {
        $this->requireAuth();

        $data   = $_POST;
        $errors = $this->validateTrajet($data);

        if (!empty($errors)) {
            $this->render('trajets/create', [
                'agences' => $this->agenceModel->findAll(),
                'user'    => $_SESSION['user'],
                'errors'  => $errors,
                'old'     => $data,
            ]);
            return;
        }

        $this->trajetModel->create([
            'gdh_depart'        => $data['gdh_depart'],
            'gdh_arrivee'       => $data['gdh_arrivee'],
            'places_total'      => (int) $data['places_total'],
            'id_agence_depart'  => (int) $data['id_agence_depart'],
            'id_agence_arrivee' => (int) $data['id_agence_arrivee'],
            'id_user'           => (int) $_SESSION['user']['id_user'],
        ]);

        $this->setFlash('Le trajet a été créé avec succès.');
        $this->redirect('/');
    }

    /**
     * Affiche le formulaire de modification d'un trajet (GET /trajets/:id/modifier).
     *
     * @param int $id Identifiant du trajet.
     */
    public function edit(int $id): void
    {
        $this->requireAuth();

        $trajet = $this->trajetModel->findById($id);

        if (!$trajet) {
            $this->setFlash('Trajet introuvable.', 'danger');
            $this->redirect('/');
        }

        // Seul l'auteur ou l'admin peut modifier
        if (!$this->isAdmin() && !$this->trajetModel->isOwner($id, (int) $_SESSION['user']['id_user'])) {
            $this->setFlash('Accès non autorisé.', 'danger');
            $this->redirect('/');
        }

        $this->render('trajets/edit', [
            'trajet'  => $trajet,
            'agences' => $this->agenceModel->findAll(),
            'errors'  => [],
        ]);
    }

    /**
     * Met à jour un trajet existant (POST /trajets/:id/modifier).
     *
     * @param int $id Identifiant du trajet.
     */
    public function update(int $id): void
    {
        $this->requireAuth();

        $trajet = $this->trajetModel->findById($id);

        if (!$trajet) {
            $this->setFlash('Trajet introuvable.', 'danger');
            $this->redirect('/');
        }

        if (!$this->isAdmin() && !$this->trajetModel->isOwner($id, (int) $_SESSION['user']['id_user'])) {
            $this->setFlash('Accès non autorisé.', 'danger');
            $this->redirect('/');
        }

        $data   = $_POST;
        $errors = $this->validateTrajet($data);

        if (!empty($errors)) {
            $this->render('trajets/edit', [
                'trajet'  => array_merge($trajet, $data),
                'agences' => $this->agenceModel->findAll(),
                'errors'  => $errors,
            ]);
            return;
        }

        // places_dispo ajusté si places_total diminue
        $placesDispo = min((int) $data['places_total'], (int) $trajet['places_dispo']);

        $this->trajetModel->update($id, [
            'gdh_depart'        => $data['gdh_depart'],
            'gdh_arrivee'       => $data['gdh_arrivee'],
            'places_total'      => (int) $data['places_total'],
            'places_dispo'      => $placesDispo,
            'id_agence_depart'  => (int) $data['id_agence_depart'],
            'id_agence_arrivee' => (int) $data['id_agence_arrivee'],
        ]);

        $this->setFlash('Le trajet a été modifié.');
        $this->redirect('/');
    }

    /**
     * Supprime un trajet (POST /trajets/:id/supprimer).
     *
     * @param int $id Identifiant du trajet.
     */
    public function destroy(int $id): void
    {
        $this->requireAuth();

        $trajet = $this->trajetModel->findById($id);

        if (!$trajet) {
            $this->setFlash('Trajet introuvable.', 'danger');
            $this->redirect('/');
        }

        if (!$this->isAdmin() && !$this->trajetModel->isOwner($id, (int) $_SESSION['user']['id_user'])) {
            $this->setFlash('Accès non autorisé.', 'danger');
            $this->redirect('/');
        }

        $this->trajetModel->delete($id);
        $this->setFlash('Le trajet a été supprimé.', 'success');
        $this->redirect('/');
    }

    /**
     * Valide les données d'un trajet.
     *
     * @param array<string, mixed> $data Données POST.
     * @return array<string> Liste des messages d'erreur.
     */
    private function validateTrajet(array $data): array
    {
        $errors = [];

        if (empty($data['id_agence_depart']) || empty($data['id_agence_arrivee'])) {
            $errors[] = 'Veuillez sélectionner les agences de départ et d\'arrivée.';
        } elseif ($data['id_agence_depart'] === $data['id_agence_arrivee']) {
            $errors[] = 'L\'agence de départ et d\'arrivée doivent être différentes.';
        }

        if (empty($data['gdh_depart']) || empty($data['gdh_arrivee'])) {
            $errors[] = 'Veuillez renseigner les dates de départ et d\'arrivée.';
        } elseif ($data['gdh_arrivee'] <= $data['gdh_depart']) {
            $errors[] = 'La date d\'arrivée doit être postérieure à la date de départ.';
        }

        if (empty($data['places_total']) || (int) $data['places_total'] < 1) {
            $errors[] = 'Le nombre de places doit être supérieur à 0.';
        }

        return $errors;
    }
}
