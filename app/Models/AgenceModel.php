<?php

/**
 * Modèle AgenceModel — accès aux données des agences (villes).
 *
 * Seul l'administrateur peut créer, modifier et supprimer des agences.
 *
 * @package App\Models
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class AgenceModel extends Model
{
    /** @var string Table en base de données */
    protected string $table = 'agences';

    /** @var string Clé primaire */
    protected string $primaryKey = 'id_agence';

    /**
     * Retourne toutes les agences triées par nom.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        $stmt = $this->db->query(
            'SELECT * FROM agences ORDER BY nom ASC'
        );
        return $stmt ? $stmt->fetchAll() : [];
    }

    /**
     * Crée une nouvelle agence.
     *
     * @param string $nom Nom de la ville.
     * @return bool Vrai si l'insertion a réussi.
     */
    public function create(string $nom): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO agences (nom) VALUES (:nom)'
        );
        return $stmt->execute([':nom' => $nom]);
    }

    /**
     * Met à jour le nom d'une agence.
     *
     * @param int    $id  Identifiant de l'agence.
     * @param string $nom Nouveau nom.
     * @return bool Vrai si la mise à jour a réussi.
     */
    public function update(int $id, string $nom): bool
    {
        $stmt = $this->db->prepare(
            'UPDATE agences SET nom = :nom WHERE id_agence = :id'
        );
        return $stmt->execute([
            ':nom' => $nom,
            ':id'  => $id,
        ]);
    }

    /**
     * Vérifie si une agence est utilisée par au moins un trajet.
     *
     * Empêche la suppression d'une agence référencée.
     *
     * @param int $id Identifiant de l'agence.
     * @return bool Vrai si l'agence est liée à un ou plusieurs trajets.
     */
    public function isUsed(int $id): bool
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM trajets
             WHERE id_agence_depart = :id OR id_agence_arrivee = :id'
        );
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() > 0;
    }
}
