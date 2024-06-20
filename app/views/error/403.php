<?php 
use App\Helpers\View;

$title = "Erreur 403";
?>

<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <p>Vous n'avez pas la permission d'accéder à cette page.</p>
    </div>
</div>
<div class="row">
    <div class="col px-4 py-2">
        <a href="/home" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
