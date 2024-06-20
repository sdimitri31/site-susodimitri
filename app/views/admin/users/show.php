<?php
use App\Helpers\View;

$title = "Détails de l'utilisateur";
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <h5 class="card-title">Nom d'utilisateur: <?= htmlspecialchars($user->getUsername()); ?></h5>
        <p>Rôle: <?= htmlspecialchars($user->getRole()); ?></p>
        <p>Créé le: <?= htmlspecialchars($user->getCreatedAt()); ?></p>
        <p>Dernière connexion: <?= htmlspecialchars($user->getLastLoginAt() ?? 'Jamais'); ?></p>
        <a href="/admin/users" class="btn btn-primary">Retour</a>
        <a href="/admin/users/<?= $user->getId(); ?>/edit" class="btn btn-warning">Éditer</a>
        <form action="/admin/users/<?= $user->getId(); ?>/delete" method="post" style="display:inline;">
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
