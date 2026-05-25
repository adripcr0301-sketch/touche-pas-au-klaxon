<?php
/**
 * Vue admin/agence-form — formulaire création / modification d'agence.
 *
 * Variables injectées :
 *   $agence  — array|null  (null = création, array = modification)
 *   $errors  — array       (liste de messages d'erreur)
 */
$isEdit  = !is_null($agence);
$action  = $isEdit
    ? BASE_URL . '/admin/agences/' . (int) $agence['id_agence'] . '/modifier'
    : BASE_URL . '/admin/agences/creer';
$title   = $isEdit ? 'Modifier l\'agence' : 'Nouvelle agence';
$btnLabel = $isEdit ? 'Enregistrer les modifications' : 'Créer l\'agence';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><?= $title ?></h2>
            <a href="<?= BASE_URL ?>/admin/agences" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
        </div>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $e) : ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= $action ?>" method="POST">

            <div class="mb-4">
                <label for="nom" class="form-label">Nom de l'agence <span class="text-danger">*</span></label>
                <input
                    type="text"
                    class="form-control"
                    id="nom"
                    name="nom"
                    required
                    autofocus
                    maxlength="100"
                    value="<?= htmlspecialchars($agence['nom'] ?? '') ?>"
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-1"></i><?= $btnLabel ?>
                </button>
                <a href="<?= BASE_URL ?>/admin/agences" class="btn btn-outline-secondary">Annuler</a>
            </div>

        </form>
    </div>
</div>
