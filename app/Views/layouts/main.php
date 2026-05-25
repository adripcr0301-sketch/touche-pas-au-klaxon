<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(APP_NAME) ?></title>

    <!-- Bootstrap Icons (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- CSS compilé (Bootstrap + styles personnalisés via Sass) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
</head>
<body class="d-flex flex-column min-vh-100">

    <?php require ROOT_PATH . '/app/Views/partials/header.php'; ?>

    <main class="container flex-grow-1 mt-4 mb-4">

        <?php
        // Affiche et consomme le message flash s'il existe
        if (!empty($_SESSION['flash'])) :
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
        ?>
            <div class="flash-message alert alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($flash['message']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
            </div>
        <?php endif; ?>

        <?php require $__viewFile; ?>

    </main>

    <?php require ROOT_PATH . '/app/Views/partials/footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
