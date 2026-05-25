<?php
/**
 * Vue trajets/create — formulaire de création d'un trajet.
 *
 * Variables injectées : $agences, $user, $errors, $old (optionnel).
 */
$old = $old ?? [];
?>

<div class="row justify-content-center">
    <div class="col-md-7 col-lg-6">

        <h2 class="mb-4">Proposer un trajet</h2>

        <?php if (!empty($errors)) : ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $e) : ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>/trajets/creer" method="POST">

            <!-- Agence de départ -->
            <div class="mb-3">
                <label for="id_agence_depart" class="form-label">Agence de départ <span class="text-danger">*</span></label>
                <select name="id_agence_depart" id="id_agence_depart" class="form-select" required>
                    <option value="">— Sélectionner —</option>
                    <?php foreach ($agences as $agence) : ?>
                        <option
                            value="<?= (int) $agence['id_agence'] ?>"
                            <?= isset($old['id_agence_depart']) && (int) $old['id_agence_depart'] === (int) $agence['id_agence'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($agence['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date / heure de départ -->
            <div class="mb-3">
                <label for="gdh_depart" class="form-label">Date et heure de départ <span class="text-danger">*</span></label>
                <input
                    type="datetime-local"
                    class="form-control"
                    id="gdh_depart"
                    name="gdh_depart"
                    required
                    value="<?= htmlspecialchars($old['gdh_depart'] ?? '') ?>"
                >
            </div>

            <!-- Agence d'arrivée -->
            <div class="mb-3">
                <label for="id_agence_arrivee" class="form-label">Agence d'arrivée <span class="text-danger">*</span></label>
                <select name="id_agence_arrivee" id="id_agence_arrivee" class="form-select" required>
                    <option value="">— Sélectionner —</option>
                    <?php foreach ($agences as $agence) : ?>
                        <option
                            value="<?= (int) $agence['id_agence'] ?>"
                            <?= isset($old['id_agence_arrivee']) && (int) $old['id_agence_arrivee'] === (int) $agence['id_agence'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($agence['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Date / heure d'arrivée -->
            <div class="mb-3">
                <label for="gdh_arrivee" class="form-label">Date et heure d'arrivée <span class="text-danger">*</span></label>
                <input
                    type="datetime-local"
                    class="form-control"
                    id="gdh_arrivee"
                    name="gdh_arrivee"
                    required
                    value="<?= htmlspecialchars($old['gdh_arrivee'] ?? '') ?>"
                >
            </div>

            <!-- Nombre de places -->
            <div class="mb-4">
                <label for="places_total" class="form-label">Nombre de places <span class="text-danger">*</span></label>
                <input
                    type="number"
                    class="form-control"
                    id="places_total"
                    name="places_total"
                    min="1"
                    required
                    value="<?= htmlspecialchars($old['places_total'] ?? '') ?>"
                >
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Créer le trajet
                </button>
                <a href="<?= BASE_URL ?>/" class="btn btn-outline-secondary">Annuler</a>
            </div>

        </form>
    </div>
</div>
