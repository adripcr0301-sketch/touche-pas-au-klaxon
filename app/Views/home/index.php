<?php
/**
 * Vue home/index — liste des trajets.
 *
 * Visiteur    : tableau simple + message d'invitation à se connecter.
 * Connecté    : tableau + icônes d'action (détails, modifier, supprimer).
 */

$isLogged = isset($_SESSION['user']);
$userId   = $isLogged ? (int) $_SESSION['user']['id_user'] : 0;
$isAdmin  = $isLogged && $_SESSION['user']['role'] === 'admin';
?>

<?php if (!$isLogged) : ?>
    <p class="text-muted mb-3">
        <i class="bi bi-info-circle"></i>
        Pour obtenir plus d&rsquo;informations sur un trajet, veuillez vous connecter.
    </p>
<?php else : ?>
    <h2 class="mb-3">Trajets proposés</h2>
<?php endif; ?>

<?php if (empty($trajets)) : ?>
    <div class="alert alert-info">Aucun trajet disponible pour le moment.</div>
<?php else : ?>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>Départ</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Destination</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Places</th>
                <?php if ($isLogged) : ?>
                    <th class="text-center">Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($trajets as $trajet) :
            $dtDepart  = new DateTime($trajet['gdh_depart']);
            $dtArrivee = new DateTime($trajet['gdh_arrivee']);
            $isOwner   = ($trajet['id_user'] === $userId);
        ?>
            <tr>
                <td><?= htmlspecialchars($trajet['agence_depart'])  ?></td>
                <td><?= $dtDepart->format('d/m/Y')  ?></td>
                <td><?= $dtDepart->format('H:i')    ?></td>
                <td><?= htmlspecialchars($trajet['agence_arrivee']) ?></td>
                <td><?= $dtArrivee->format('d/m/Y') ?></td>
                <td><?= $dtArrivee->format('H:i')   ?></td>
                <td><?= (int) $trajet['places_dispo'] ?></td>

                <?php if ($isLogged) : ?>
                <td class="text-center text-nowrap">

                    <!-- Bouton détails (modal) — visible pour tous les connectés -->
                    <button
                        class="btn btn-outline-secondary btn-action"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-<?= (int) $trajet['id_trajet'] ?>"
                        title="Voir les détails"
                    >
                        <i class="bi bi-eye"></i>
                    </button>

                    <?php if ($isOwner || $isAdmin) : ?>
                        <!-- Modifier -->
                        <a
                            href="<?= BASE_URL ?>/trajets/<?= (int) $trajet['id_trajet'] ?>/modifier"
                            class="btn btn-outline-primary btn-action"
                            title="Modifier"
                        >
                            <i class="bi bi-pencil-square"></i>
                        </a>

                        <!-- Supprimer -->
                        <form
                            action="<?= BASE_URL ?>/trajets/<?= (int) $trajet['id_trajet'] ?>/supprimer"
                            method="POST"
                            class="d-inline"
                            onsubmit="return confirm('Supprimer ce trajet ?')"
                        >
                            <button type="submit" class="btn btn-outline-danger btn-action" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    <?php endif; ?>

                </td>
                <?php endif; ?>
            </tr>

            <?php if ($isLogged) : ?>
            <!-- Modale détails du trajet -->
            <div class="modal fade" id="modal-<?= (int) $trajet['id_trajet'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body p-4">
                            <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            <p><strong>Auteur :</strong>
                                <?= htmlspecialchars($trajet['user_prenom'] . ' ' . $trajet['user_nom']) ?>
                            </p>
                            <p><strong>Téléphone :</strong>
                                <?= htmlspecialchars($trajet['user_telephone']) ?>
                            </p>
                            <p><strong>Email :</strong>
                                <?= htmlspecialchars($trajet['user_email']) ?>
                            </p>
                            <p><strong>Nombre total de places :</strong>
                                <?= (int) $trajet['places_total'] ?>
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php endif; ?>
