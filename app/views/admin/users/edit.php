<?php
use App\Helpers\View;
use App\Models\Role;

$title = "Édition de l'utilisateur : " . $user->getUsername();
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <form action="/admin/users/<?= $user->getId(); ?>/update" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user->getUsername()); ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rôle</label>
                <select class="form-control" id="role" name="role" required>
                    <?php foreach (Role::getAllRoles() as $role): ?>
                    <option value="<?= htmlspecialchars($role); ?>" <?= $user->getRole() === $role ? 'selected' : ''; ?>><?= htmlspecialchars($role); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Nouveau mot de passe (laisser vide pour conserver le mot de passe actuel)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <a href="/admin/users" class="btn btn-primary mr-1">Retour</a>
            <button type="submit" class="btn btn-warning">Mettre à jour</button>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
