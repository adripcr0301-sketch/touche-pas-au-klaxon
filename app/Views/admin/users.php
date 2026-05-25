<?php
/**
 * Vue admin/users — liste de tous les utilisateurs.
 *
 * Variables injectées : $users.
 */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Utilisateurs</h2>
    <a href="<?= BASE_URL ?>/admin" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>Tableau de bord
    </a>
</div>

<?php if (empty($users)) : ?>
    <div class="alert alert-info">Aucun utilisateur enregistré.</div>
<?php else : ?>

<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Rôle</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= (int) $user['id_user'] ?></td>
                <td><?= htmlspecialchars($user['nom']) ?></td>
                <td><?= htmlspecialchars($user['prenom']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['telephone'] ?? '—') ?></td>
                <td>
                    <?php if ($user['role'] === 'admin') : ?>
                        <span class="badge bg-danger">Admin</span>
                    <?php else : ?>
                        <span class="badge bg-secondary">Utilisateur</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<p class="text-muted small mt-2"><?= count($users) ?> utilisateur(s) au total.</p>

<?php endif; ?>
