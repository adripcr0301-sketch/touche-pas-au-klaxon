<?php

/**
 * Classe abstraite Model — base de tous les modèles.
 *
 * Fournit les opérations CRUD génériques via PDO.
 * Chaque modèle enfant doit définir $table et $primaryKey.
 *
 * @package App\Core
 */

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model
{
    /** @var PDO Instance de connexion PDO */
    protected PDO $db;

    /** @var string Nom de la table en base de données */
    protected string $table = '';

    /** @var string Nom de la clé primaire */
    protected string $primaryKey = 'id';

    /**
     * Constructeur — injecte la connexion PDO via le singleton Database.
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Retourne toutes les lignes de la table.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt ? $stmt->fetchAll() : [];
    }

    /**
     * Retourne une ligne par son identifiant.
     *
     * @param int $id Identifiant de la ligne.
     * @return array<string, mixed>|false
     */
    public function findById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Supprime une ligne par son identifiant.
     *
     * @param int $id Identifiant de la ligne à supprimer.
     * @return bool Vrai si la suppression a réussi.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}
