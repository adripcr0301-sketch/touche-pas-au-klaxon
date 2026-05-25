<?php
/**
 * Vue admin/trajets — liste de tous les trajets (vue admin).
 *
 * Variables injectées : $trajets.
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Tous les trajets</h2>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/trajets/creer" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Nouveau trajet
        </a>
        <a href="<?= BASE_URL ?>/admin" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Tableau de bord
        </a>
    </div>
</div>

<?php if (empty($trajets)) : ?>
    <div class="alert alert-info">Aucun trajet enregistré.</div>
<?php else : ?>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Départ</th>
                <th>Date départ</th>
                <th>Arrivée</th>
                <th>Date arrivée</th>
                <th>Places</th>
                <th>Proposé par</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($trajets as $trajet) :
            $dtDepart  = new DateTime($trajet['gdh_depart']);
            $dtArrivee = new DateTime($trajet['gdh_arrivee']);
        ?>
            <tr>
                <td><?= (int) $trajet['id_trajet'] ?></td>
                <td><?= htmlspecialchars($trajet['agence_depart'])  ?></td>
                <td><?= $dtDepart->format('d/m/Y H:i') ?></td>
                <td><?= htmlspecialchars($trajet['agence_arrivee']) ?></td>
                <td><?= $dtArrivee->format('d/m/Y H:i') ?></td>
                <td><?= (int) $trajet['places_dispo'] ?> / <?= (int) $trajet['places_total'] ?></td>
                <td><?= htmlspecialchars($trajet['user_prenom'] . ' ' . $trajet['user_nom']) ?></td>
                <td class="text-center text-nowrap">
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
                        action="<?= BASE_URL ?>/admin/trajets/<?= (int) $trajet['id_trajet'] ?>/supprimer"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Supprimer ce trajet ?')"
                    >
                        <button type="submit" class="btn btn-outline-danger btn-action" title="Supprimer">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<p class="text-muted small mt-2"><?= count($trajets) ?> trajet(s) au total.</p>

<?php endif; ?>
