<?php

/**
 * Tests unitaires pour TrajetModel.
 *
 * Couvre : create, findById (avec jointures), update, isOwner, delete.
 * Chaque test est isolé par une transaction annulée en tearDown().
 *
 * @package Tests
 */

declare(strict_types=1);

namespace Tests;

use App\Models\TrajetModel;

class TrajetModelTest extends BaseTestCase
{
    private TrajetModel $model;

    /** @var int Agence de départ insérée pour les fixtures */
    private int $idAgenceDepart;

    /** @var int Agence d'arrivée insérée pour les fixtures */
    private int $idAgenceArrivee;

    /** @var int Utilisateur inséré pour les fixtures */
    private int $idUser;

    /**
     * Instancie le modèle et insère les fixtures communes à tous les tests :
     * deux agences + un utilisateur.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new TrajetModel();
        $this->insertFixtures();
    }

    /**
     * Insère les données de test nécessaires aux opérations sur les trajets.
     * Ces données sont annulées en tearDown() avec le reste de la transaction.
     */
    private function insertFixtures(): void
    {
        $this->pdo->exec("INSERT INTO agences (nom) VALUES ('Bruxelles-Test')");
        $this->idAgenceDepart = (int) $this->pdo->lastInsertId();

        $this->pdo->exec("INSERT INTO agences (nom) VALUES ('Liège-Test')");
        $this->idAgenceArrivee = (int) $this->pdo->lastInsertId();

        $this->pdo->prepare(
            "INSERT INTO users (nom, prenom, telephone, email, password, role)
             VALUES ('Dupont', 'Jean', '0400000000', 'jean.test@klaxon.fr', 'hash', 'user')"
        )->execute();
        $this->idUser = (int) $this->pdo->lastInsertId();
    }

    /**
     * Retourne un tableau de données valides pour créer un trajet de test.
     *
     * @return array<string, mixed>
     */
    private function trajetData(): array
    {
        return [
            'gdh_depart'        => '2030-06-01 08:00:00',
            'gdh_arrivee'       => '2030-06-01 10:00:00',
            'places_total'      => 4,
            'id_agence_depart'  => $this->idAgenceDepart,
            'id_agence_arrivee' => $this->idAgenceArrivee,
            'id_user'           => $this->idUser,
        ];
    }

    // -------------------------------------------------------------------------
    // create
    // -------------------------------------------------------------------------

    /** create() retourne true quand les données sont valides. */
    public function testCreateInsertsTrajet(): void
    {
        $result = $this->model->create($this->trajetData());

        $this->assertTrue($result);
    }

    /** Après create(), places_dispo doit être égal à places_total. */
    public function testCreateSetsPlacesDispoEqualToPlacesTotal(): void
    {
        $this->model->create($this->trajetData());
        $id = (int) $this->pdo->lastInsertId();

        $trajet = $this->model->findById($id);

        $this->assertSame((int) $trajet['places_total'], (int) $trajet['places_dispo']);
    }

    // -------------------------------------------------------------------------
    // findById (avec jointures)
    // -------------------------------------------------------------------------

    /** findById() retourne les données jointes (noms d'agences et d'utilisateur). */
    public function testFindByIdReturnsJoinedData(): void
    {
        $this->model->create($this->trajetData());
        $id = (int) $this->pdo->lastInsertId();

        $trajet = $this->model->findById($id);

        $this->assertIsArray($trajet);
        $this->assertSame('Bruxelles-Test', $trajet['agence_depart']);
        $this->assertSame('Liège-Test',     $trajet['agence_arrivee']);
        $this->assertSame('Dupont',         $trajet['user_nom']);
        $this->assertSame('Jean',           $trajet['user_prenom']);
    }

    /** findById() retourne false pour un identifiant inexistant. */
    public function testFindByIdReturnsFalseForUnknownId(): void
    {
        $this->assertFalse($this->model->findById(999999));
    }

    // -------------------------------------------------------------------------
    // update
    // -------------------------------------------------------------------------

    /** update() modifie places_total et la nouvelle valeur est lue par findById(). */
    public function testUpdateModifiesPlacesTotal(): void
    {
        $this->model->create($this->trajetData());
        $id = (int) $this->pdo->lastInsertId();

        $updated = [
            'gdh_depart'        => '2030-06-01 08:00:00',
            'gdh_arrivee'       => '2030-06-01 10:00:00',
            'places_total'      => 6,
            'places_dispo'      => 6,
            'id_agence_depart'  => $this->idAgenceDepart,
            'id_agence_arrivee' => $this->idAgenceArrivee,
        ];

        $result = $this->model->update($id, $updated);

        $this->assertTrue($result);
        $trajet = $this->model->findById($id);
        $this->assertSame(6, (int) $trajet['places_total']);
    }

    // -------------------------------------------------------------------------
    // isOwner
    // -------------------------------------------------------------------------

    /** isOwner() retourne true pour l'auteur du trajet. */
    public function testIsOwnerReturnsTrueForAuthor(): void
    {
        $this->model->create($this->trajetData());
        $id = (int) $this->pdo->lastInsertId();

        $this->assertTrue($this->model->isOwner($id, $this->idUser));
    }

    /** isOwner() retourne false pour un utilisateur qui n'est pas l'auteur. */
    public function testIsOwnerReturnsFalseForOtherUser(): void
    {
        $this->model->create($this->trajetData());
        $id = (int) $this->pdo->lastInsertId();

        $this->assertFalse($this->model->isOwner($id, $this->idUser + 9999));
    }

    // -------------------------------------------------------------------------
    // delete
    // -------------------------------------------------------------------------

    /** delete() supprime le trajet et findById() retourne false ensuite. */
    public function testDeleteRemovesTrajet(): void
    {
        $this->model->create($this->trajetData());
        $id = (int) $this->pdo->lastInsertId();

        $result = $this->model->delete($id);

        $this->assertTrue($result);
        $this->assertFalse($this->model->findById($id));
    }
}
