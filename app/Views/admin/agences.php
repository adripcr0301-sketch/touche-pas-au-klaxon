<?php
/**
 * Vue admin/agences — liste des agences avec actions CRUD.
 *
 * Variables injectées : $agences.
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Agences</h2>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/admin/agences/creer" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Nouvelle agence
        </a>
        <a href="<?= BASE_URL ?>/admin" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Tableau de bord
        </a>
    </div>
</div>

<?php if (empty($agences)) : ?>
    <div class="alert alert-info">Aucune agence enregistrée.</div>
<?php else : ?>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom de l'agence</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agences as $agence) : ?>
            <tr>
                <td><?= (int) $agence['id_agence'] ?></td>
                <td><?= htmlspecialchars($agence['nom']) ?></td>
                <td class="text-center text-nowrap">
                    <!-- Modifier -->
                    <a
                        href="<?= BASE_URL ?>/admin/agences/<?= (int) $agence['id_agence'] ?>/modifier"
                        class="btn btn-outline-primary btn-action"
                        title="Modifier"
                    >
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <!-- Supprimer -->
                    <form
                        action="<?= BASE_URL ?>/admin/agences/<?= (int) $agence['id_agence'] ?>/supprimer"
                        method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Supprimer l\'agence « <?= htmlspecialchars(addslashes($agence['nom'])) ?> » ?')"
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

<p class="text-muted small mt-2"><?= count($agences) ?> agence(s) au total.</p>

<?php endif; ?>
