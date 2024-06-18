<?php
use App\Helpers\View;
use App\Helpers\Authorization;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Configuration;

$title = "Inscription";
$hasManageUserPermission = false;
$formAction = "/register";
$submitText = "S'inscrire";

$config = new Configuration();
$isRegistrationOpen = $config->getValue('user_allow_registration') == '1';

if ($context === 'admin') {
    Authorization::requirePermission(Permission::MANAGE_USERS, '/home');
    $title = "Création d'un membre";
    $isRegistrationOpen = true;
    $formAction = "/admin/users/create";
    $hasManageUserPermission = true;
    $submitText = "Ajouter le membre";
}

?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <?php if (!$isRegistrationOpen): ?>
            <div class="alert alert-warning" role="alert">Les inscriptions sont actuellement fermées.</div>
        <?php else: ?>

            <?php
            if (isset($error)) {
                echo '<div class="alert alert-danger" role="alert">Erreur : ' . htmlspecialchars($error) . '</div>';
            }
            ?>
            <form action="<?= $formAction ?>" method="post">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>" />
                <div class="mb-3">
                    <label for="username" class="form-label">Nom d'utilisateur:</label>
                    <input type="text" class="form-control" id="username" name="username" required />
                </div>
                <?php if ($hasManageUserPermission): ?>
                    <div class="mb-3">
                        <label for="role" class="form-label">Rôle</label>
                        <select class="form-control" id="role" name="role" required>
                            <?php foreach (Role::getAllRoles() as $role): ?>
                                <option value="<?= htmlspecialchars($role); ?>"><?= htmlspecialchars($role); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe:</label>
                    <input type="password" class="form-control" id="password" name="password" required />
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-block"><?= $submitText ?></button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>