<?php

/**
 * Classe de base pour tous les tests de l'application.
 *
 * Stratégie de nettoyage : chaque test s'exécute dans une transaction
 * qui est annulée dans tearDown() → la base klaxon_test reste toujours vide.
 *
 * Prérequis : exécuter database/create_test.sql une seule fois.
 *
 * @package Tests
 */

declare(strict_types=1);

namespace Tests;

use App\Core\Database;
use PDO;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

abstract class BaseTestCase extends TestCase
{
    /** @var PDO Connexion PDO partagée (même instance que Database::getInstance()) */
    protected PDO $pdo;

    /**
     * Réinitialise le singleton Database une fois par classe de test,
     * afin de forcer la reconnexion avec DB_NAME=klaxon_test (défini dans phpunit.xml).
     */
    public static function setUpBeforeClass(): void
    {
        // En PHP 8.1+, setAccessible() est inutile — accès direct aux props privées
        $ref = new ReflectionProperty(Database::class, 'instance');
        $ref->setValue(null, null); // remet le singleton à null

        // Pré-chauffe la connexion vers klaxon_test
        Database::getInstance();
    }

    /**
     * Récupère l'instance PDO et ouvre une transaction avant chaque test.
     * Les modèles instanciés dans les tests récupèrent la même connexion
     * (singleton), donc leurs requêtes s'exécutent dans cette transaction.
     */
    protected function setUp(): void
    {
        $this->pdo = Database::getInstance();
        $this->pdo->beginTransaction();
    }

    /**
     * Annule la transaction après chaque test → aucune donnée ne persiste.
     */
    protected function tearDown(): void
    {
        if ($this->pdo->inTransaction()) {
            $this->pdo->rollBack();
        }
    }
}
