<nav class="navbar navbar-dark mx-3 mt-3 px-3 rounded">
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') : ?>

        <!-- ========== HEADER ADMIN ========== -->
        <a class="navbar-brand fst-italic fw-bold" href="<?= BASE_URL ?>/admin">
            <?= htmlspecialchars(APP_NAME) ?>
        </a>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= BASE_URL ?>/admin/utilisateurs" class="btn btn-secondary btn-sm">Utilisateurs</a>
            <a href="<?= BASE_URL ?>/admin/agences"      class="btn btn-secondary btn-sm">Agences</a>
            <a href="<?= BASE_URL ?>/admin/trajets"      class="btn btn-secondary btn-sm">Trajets</a>
            <span class="text-white ms-2">
                Bonjour <?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?>
            </span>
            <a href="<?= BASE_URL ?>/logout" class="btn btn-dark btn-sm ms-1">Déconnexion</a>
        </div>

    <?php elseif (isset($_SESSION['user'])) : ?>

        <!-- ========== HEADER USER CONNECTÉ ========== -->
        <a class="navbar-brand fst-italic fw-bold" href="<?= BASE_URL ?>/">
            <?= htmlspecialchars(APP_NAME) ?>
        </a>
        <div class="d-flex align-items-center gap-2">
            <a href="<?= BASE_URL ?>/trajets/creer" class="btn btn-dark btn-sm">Créer un trajet</a>
            <span class="text-white">
                Bonjour <?= htmlspecialchars($_SESSION['user']['prenom'] . ' ' . $_SESSION['user']['nom']) ?>
            </span>
            <a href="<?= BASE_URL ?>/logout" class="btn btn-dark btn-sm">Déconnexion</a>
        </div>

    <?php else : ?>

        <!-- ========== HEADER VISITEUR ========== -->
        <a class="navbar-brand fst-italic fw-bold" href="<?= BASE_URL ?>/">
            <?= htmlspecialchars(APP_NAME) ?>
        </a>
        <a href="<?= BASE_URL ?>/login" class="btn btn-dark btn-sm">Connexion</a>

    <?php endif; ?>
</nav>
