<?php 
use App\Helpers\View;

$title = "Création d'un paramètre";
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <?php if (isset($message['message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message['message']) ?></div>
        <?php endif; ?>
        <?php if (isset($message['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($message['error']) ?></div>
        <?php endif; ?>
        <div class="row">
            <form action="/admin/configuration/create" method="post" class="mt-3">
                <div class="mb-3">
                    <label for="setting_name" class="form-label">Nom du paramètre</label>
                    <input type="text" class="form-control" id="setting_name" name="setting_name" required>
                </div>
                <div class="mb-3">
                    <label for="setting_type" class="form-label">Type de paramètre</label>
                    <select id="setting_type" name="setting_type" class="form-select">
                        <option value="text">Texte</option>
                        <option value="number">Nombre</option>
                        <option value="boolean">Booléen</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="setting_value" class="form-label">Valeur</label>
                    <input type="text" class="form-control" id="setting_value" name="setting_value" required>
                </div>
                <a href="/admin/configuration" class="btn btn-primary">Retour</a>
                <button type="submit" class="btn btn-success">Ajouter</button>
            </form>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>