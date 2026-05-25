<?php

/**
 * Modèle TrajetModel — accès aux données des trajets.
 *
 * Gère la consultation, la création, la modification et la suppression
 * des trajets avec les jointures nécessaires vers agences et users.
 *
 * @package App\Models
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class TrajetModel extends Model
{
    /** @var string Table en base de données */
    protected string $table = 'trajets';

    /** @var string Clé primaire */
    protected string $primaryKey = 'id_trajet';

    /**
     * Requête SELECT de base avec toutes les jointures.
     * Factorisée pour éviter la duplication.
     */
    private const SELECT_FULL = '
        SELECT
            t.id_trajet,
            t.gdh_depart,
            t.gdh_arrivee,
            t.places_total,
            t.places_dispo,
            t.id_agence_depart,
            t.id_agence_arrivee,
            t.id_user,
            ad.nom  AS agence_depart,
            aa.nom  AS agence_arrivee,
            u.nom   AS user_nom,
            u.prenom AS user_prenom,
            u.telephone AS user_telephone,
            u.email AS user_email
        FROM trajets t
        INNER JOIN agences ad ON ad.id_agence = t.id_agence_depart
        INNER JOIN agences aa ON aa.id_agence = t.id_agence_arrivee
        INNER JOIN users   u  ON u.id_user    = t.id_user
    ';

    /**
     * Retourne les trajets disponibles pour la page d'accueil (visiteur).
     *
     * Critères :
     * - places_dispo > 0
     * - gdh_depart > maintenant (trajets non passés)
     * Triés par date de départ croissante.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAvailable(): array
    {
        $stmt = $this->db->prepare(
            self::SELECT_FULL .
            'WHERE t.places_dispo > 0
               AND t.gdh_depart > NOW()
             ORDER BY t.gdh_depart ASC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retourne tous les trajets avec détails (page utilisateur connecté).
     *
     * Même résultat que findAvailable() mais sans le filtre places_dispo,
     * pour que l'auteur puisse voir ses propres trajets complets.
     * Les trajets passés restent filtrés.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAllActive(): array
    {
        $stmt = $this->db->prepare(
            self::SELECT_FULL .
            'WHERE t.gdh_depart > NOW()
             ORDER BY t.gdh_depart ASC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retourne tous les trajets sans filtre (tableau de bord admin).
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        $stmt = $this->db->prepare(
            self::SELECT_FULL .
            'ORDER BY t.gdh_depart DESC'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Retourne un trajet par son identifiant avec toutes les données jointes.
     *
     * @param int $id Identifiant du trajet.
     * @return array<string, mixed>|false
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            self::SELECT_FULL .
            'WHERE t.id_trajet = :id'
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Retourne les trajets proposés par un utilisateur donné.
     *
     * @param int $userId Identifiant de l'utilisateur.
     * @return array<int, array<string, mixed>>
     */
    public function findByUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            self::SELECT_FULL .
            'WHERE t.id_user = :userId
             ORDER BY t.gdh_depart ASC'
        );
        $stmt->execute([':userId' => $userId]);
        return $stmt->fetchAll();
    }

    /**
     * Crée un nouveau trajet.
     *
     * @param array<string, mixed> $data Données du trajet.
     *   - gdh_depart        (string DATETIME)
     *   - gdh_arrivee       (string DATETIME)
     *   - places_total      (int)
     *   - places_dispo      (int) — identique à places_total à la création
     *   - id_agence_depart  (int)
     *   - id_agence_arrivee (int)
     *   - id_user           (int)
     * @return bool Vrai si l'insertion a réussi.
     */
    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('
            INSERT INTO trajets
                (gdh_depart, gdh_arrivee, places_total, places_dispo,
                 id_agence_depart, id_agence_arrivee, id_user)
            VALUES
                (:gdh_depart, :gdh_arrivee, :places_total, :places_dispo,
                 :id_agence_depart, :id_agence_arrivee, :id_user)
        ');
        return $stmt->execute([
            ':gdh_depart'        => $data['gdh_depart'],
            ':gdh_arrivee'       => $data['gdh_arrivee'],
            ':places_total'      => $data['places_total'],
            ':places_dispo'      => $data['places_total'], // à la création = total
            ':id_agence_depart'  => $data['id_agence_depart'],
            ':id_agence_arrivee' => $data['id_agence_arrivee'],
            ':id_user'           => $data['id_user'],
        ]);
    }

    /**
     * Met à jour un trajet existant.
     *
     * Seul l'auteur du trajet peut le modifier (vérifié dans le contrôleur).
     *
     * @param int                  $id   Identifiant du trajet.
     * @param array<string, mixed> $data Nouvelles données.
     * @return bool Vrai si la mise à jour a réussi.
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('
            UPDATE trajets SET
                gdh_depart        = :gdh_depart,
                gdh_arrivee       = :gdh_arrivee,
                places_total      = :places_total,
                places_dispo      = :places_dispo,
                id_agence_depart  = :id_agence_depart,
                id_agence_arrivee = :id_agence_arrivee
            WHERE id_trajet = :id
        ');
        return $stmt->execute([
            ':gdh_depart'        => $data['gdh_depart'],
            ':gdh_arrivee'       => $data['gdh_arrivee'],
            ':places_total'      => $data['places_total'],
            ':places_dispo'      => $data['places_dispo'],
            ':id_agence_depart'  => $data['id_agence_depart'],
            ':id_agence_arrivee' => $data['id_agence_arrivee'],
            ':id'                => $id,
        ]);
    }

    /**
     * Vérifie si un utilisateur est l'auteur d'un trajet.
     *
     * Utilisé comme garde de sécurité avant modification ou suppression.
     *
     * @param int $trajetId Identifiant du trajet.
     * @param int $userId   Identifiant de l'utilisateur.
     * @return bool Vrai si l'utilisateur est bien l'auteur.
     */
    public function isOwner(int $trajetId, int $userId): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM trajets
             WHERE id_trajet = :trajetId AND id_user = :userId'
        );
        $stmt->execute([
            ':trajetId' => $trajetId,
            ':userId'   => $userId,
        ]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
