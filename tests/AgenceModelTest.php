<?php

/**
 * Tests unitaires pour AgenceModel.
 *
 * Couvre : findAll, findById, create, update, delete, isUsed.
 * Chaque test est isolé par une transaction annulée en tearDown().
 *
 * @package Tests
 */

declare(strict_types=1);

namespace Tests;

use App\Models\AgenceModel;

class AgenceModelTest extends BaseTestCase
{
    private AgenceModel $model;

    /** @var int Identifiant d'un utilisateur de test (nécessaire pour testIsUsedReturnsTrue). */
    private int $idUser;

    /**
     * Instancie le modèle et insère un utilisateur de test
     * (utilisé uniquement par le test isUsed + trajet).
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new AgenceModel();

        $this->pdo->prepare(
            "INSERT INTO users (nom, prenom, telephone, email, password, role)
             VALUES ('PHPUnit', 'Agence', '0000000001', 'phpunit.agence@test.fr', 'hash', 'user')"
        )->execute();
        $this->idUser = (int) $this->pdo->lastInsertId();
    }

    // -------------------------------------------------------------------------
    // findAll
    // -------------------------------------------------------------------------

    /** findAll() retourne toujours un tableau (vide ou non). */
    public function testFindAllReturnsArray(): void
    {
        $this->assertIsArray($this->model->findAll());
    }

    // -------------------------------------------------------------------------
    // create + findAll
    // -------------------------------------------------------------------------

    /** create() insère une agence et findAll() en tient compte. */
    public function testCreateInsertsAgence(): void
    {
        $countBefore = count($this->model->findAll());

        $result = $this->model->create('Bruxelles-Test');

        $this->assertTrue($result);
        $this->assertCount($countBefore + 1, $this->model->findAll());
    }

    // -------------------------------------------------------------------------
    // create + findById
    // -------------------------------------------------------------------------

    /** findById() retourne l'agence avec le bon nom après insertion. */
    public function testFindByIdReturnsInsertedAgence(): void
    {
        $this->model->create('Liège-Test');
        $id = (int) $this->pdo->lastInsertId();

        $agence = $this->model->findById($id);

        $this->assertIsArray($agence);
        $this->assertSame('Liège-Test', $agence['nom']);
    }

    // -------------------------------------------------------------------------
    // update
    // -------------------------------------------------------------------------

    /** update() modifie le nom et findById() retourne la nouvelle valeur. */
    public function testUpdateChangesName(): void
    {
        $this->model->create('Namur-Test');
        $id = (int) $this->pdo->lastInsertId();

        $result = $this->model->update($id, 'Namur-Modifié');

        $this->assertTrue($result);
        $agence = $this->model->findById($id);
        $this->assertSame('Namur-Modifié', $agence['nom']);
    }

    // -------------------------------------------------------------------------
    // isUsed — faux (pas de trajet associé)
    // -------------------------------------------------------------------------

    /** isUsed() retourne false quand l'agence n'est liée à aucun trajet. */
    public function testIsUsedReturnsFalseWhenAgenceHasNoTrajets(): void
    {
        $this->model->create('Charleroi-Test');
        $id = (int) $this->pdo->lastInsertId();

        $this->assertFalse($this->model->isUsed($id));
    }

    // -------------------------------------------------------------------------
    // isUsed — vrai (trajet associé)
    // -------------------------------------------------------------------------

    /** isUsed() retourne true quand l'agence est utilisée comme départ d'un trajet. */
    public function testIsUsedReturnsTrueWhenAgenceIsUsedInTrajet(): void
    {
        // Deux agences nécessaires (départ ≠ arrivée)
        $this->model->create('Gand-Départ');
        $idDepart = (int) $this->pdo->lastInsertId();

        $this->model->create('Anvers-Arrivée');
        $idArrivee = (int) $this->pdo->lastInsertId();

        // Insertion directe d'un trajet pour la fixture
        $this->pdo->prepare(
            "INSERT INTO trajets
                 (gdh_depart, gdh_arrivee, places_total, places_dispo,
                  id_agence_depart, id_agence_arrivee, id_user)
             VALUES
                 ('2030-07-01 09:00:00', '2030-07-01 11:00:00', 3, 3,
                  :dep, :arr, :usr)"
        )->execute([':dep' => $idDepart, ':arr' => $idArrivee, ':usr' => $this->idUser]);

        $this->assertTrue($this->model->isUsed($idDepart));
    }

    // -------------------------------------------------------------------------
    // delete
    // -------------------------------------------------------------------------

    /** delete() supprime l'agence et findById() retourne false ensuite. */
    public function testDeleteRemovesAgence(): void
    {
        $this->model->create('Mons-Test');
        $id = (int) $this->pdo->lastInsertId();

        $result = $this->model->delete($id);

        $this->assertTrue($result);
        $this->assertFalse($this->model->findById($id));
    }
}
