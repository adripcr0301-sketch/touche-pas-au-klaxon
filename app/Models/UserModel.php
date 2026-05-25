<?php

/**
 * Modèle UserModel — accès aux données des utilisateurs.
 *
 * Les données employés sont extraites du système RH et ne peuvent
 * pas être créées, modifiées ou supprimées via l'application.
 * Ce modèle est donc en lecture seule, sauf pour la mise à jour
 * du mot de passe lors du premier paramétrage.
 *
 * @package App\Models
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class UserModel extends Model
{
    /** @var string Table en base de données */
    protected string $table = 'users';

    /** @var string Clé primaire */
    protected string $primaryKey = 'id_user';

    /**
     * Retourne un utilisateur par son adresse email.
     *
     * Utilisé lors de l'authentification.
     *
     * @param string $email Adresse email de l'utilisateur.
     * @return array<string, mixed>|false Données utilisateur ou false si inexistant.
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM users WHERE email = :email LIMIT 1'
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Retourne tous les utilisateurs triés par nom puis prénom.
     *
     * @return array<int, array<string, mixed>>
     */
    public function findAll(): array
    {
        $stmt = $this->db->query(
            'SELECT id_user, nom, prenom, telephone, email, role
             FROM users
             ORDER BY nom ASC, prenom ASC'
        );
        return $stmt ? $stmt->fetchAll() : [];
    }
}
