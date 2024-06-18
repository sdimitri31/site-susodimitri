<?php 
use App\Helpers\View;

$title = "Administration des paramètres";
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1>Administration des paramètres</h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <a href="/admin/configuration/create" class="btn btn-success mb-3">Ajouter un paramètre</a>
        <table class="table table-striped table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Nom du paramètre</th>
                    <th>Valeur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($configs as $config): ?>
                <tr>
                    <form action="/admin/configuration/update" method="post">
                        <td><?= htmlspecialchars($config['setting_name']) ?></td>
                        <td>
                            <?php if ($config['setting_type'] == 'boolean'): ?>
                                <input type="hidden" name="setting_value" value="0">
                                <input type="checkbox" class="form-check-input" name="setting_value" value="1" <?= $config['setting_value'] == '1' ? 'checked' : '' ?>>
                            <?php else: ?>
                                <input type="text" name="setting_value" class="form-control" value="<?= htmlspecialchars($config['setting_value']) ?>">
                            <?php endif; ?>
                        </td>
                        <td>
                            <input type="hidden" name="setting_name" value="<?= htmlspecialchars($config['setting_name']) ?>">
                            <button type="submit" class="btn btn-warning">Mettre à jour</button>
                            <a href="/admin/configuration/destroy/<?php echo $config['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paramètre ?');">Supprimer</a>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col px-4 py-2">
        <a href="/admin" class="btn btn-primary">Retour</a>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>