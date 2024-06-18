<?php 
use App\Helpers\View;

$title = "Connexion";
$formAction = "/login";

if ($context === 'admin') {
    $title = "Connexion administrateur";
    $formAction = "/admin/login";
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
        <?php 
        if (isset($error)) {
            echo '<div class="alert alert-danger" role="alert">Erreur : ' . htmlspecialchars($error) . '</div>';
        }
        ?>
        <form action="<?= $formAction ?>" method="post">
            <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            </div>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
