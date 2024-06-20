<?php 
use App\Helpers\View;

$title = "Gestion de la page d'accueil";
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <a href="/admin/homepage/edit" class="btn btn-warning mb-3">Modifier le contenu</a>
        <h2><?php echo htmlspecialchars($homepage['title']); ?></h2>
        <div class="ql-editor">
            <?php echo $homepage['content']; ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col px-4 py-2">
        <a href="/admin" class="btn btn-primary">Retour</a>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
