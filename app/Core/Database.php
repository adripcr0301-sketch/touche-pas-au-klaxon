<?php

/**
 * Classe Database — connexion PDO en singleton.
 *
 * Fournit une instance PDO unique partagée par toute l'application.
 * Les paramètres de connexion sont lus depuis les variables d'environnement.
 *
 * @package App\Core
 */

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    /** @var PDO|null Instance PDO partagée */
    private static ?PDO $instance = null;

    /**
     * Retourne l'instance PDO (crée la connexion si besoin).
     *
     * @throws RuntimeException Si la connexion échoue.
     */
    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $_ENV['DB_HOST'],
                $_ENV['DB_PORT'] ?? '3306',
                $_ENV['DB_NAME']
            );

            try {
                self::$instance = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'] ?? '', [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                throw new RuntimeException('Connexion à la base de données impossible : ' . $e->getMessage());
            }
        }

        return self::$instance;
    }

    /** Interdit l'instanciation directe. */
    private function __construct() {}

    /** Interdit le clonage. */
    private function __clone() {}
}
