<?php
/**
 * Vue admin/dashboard — tableau de bord administrateur.
 *
 * Variables injectées : $nbUsers, $nbAgences, $nbTrajets.
 */
?>

<h2 class="mb-4">Tableau de bord</h2>

<div class="row g-4 mb-4">

    <!-- Utilisateurs -->
    <div class="col-sm-4">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body py-4">
                <i class="bi bi-people fs-1 text-primary mb-2 d-block"></i>
                <h3 class="display-5 fw-bold"><?= (int) $nbUsers ?></h3>
                <p class="text-muted mb-0">Utilisateurs</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= BASE_URL ?>/admin/utilisateurs" class="btn btn-sm btn-outline-primary">
                    Gérer <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Agences -->
    <div class="col-sm-4">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body py-4">
                <i class="bi bi-building fs-1 text-success mb-2 d-block"></i>
                <h3 class="display-5 fw-bold"><?= (int) $nbAgences ?></h3>
                <p class="text-muted mb-0">Agences</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= BASE_URL ?>/admin/agences" class="btn btn-sm btn-outline-success">
                    Gérer <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Trajets -->
    <div class="col-sm-4">
        <div class="card text-center shadow-sm h-100">
            <div class="card-body py-4">
                <i class="bi bi-signpost-2 fs-1 text-warning mb-2 d-block"></i>
                <h3 class="display-5 fw-bold"><?= (int) $nbTrajets ?></h3>
                <p class="text-muted mb-0">Trajets</p>
            </div>
            <div class="card-footer bg-transparent">
                <a href="<?= BASE_URL ?>/admin/trajets" class="btn btn-sm btn-outline-warning">
                    Gérer <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

</div>

<div class="d-flex gap-2 mt-2">
    <a href="<?= BASE_URL ?>/trajets/creer" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i>Nouveau trajet
    </a>
    <a href="<?= BASE_URL ?>/admin/agences/creer" class="btn btn-outline-success">
        <i class="bi bi-plus-circle me-1"></i>Nouvelle agence
    </a>
</div>
