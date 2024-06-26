<?php 
use App\Helpers\View;

$title = "Création d'un projet";
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
        <form action="/admin/projects/store" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="destinationFolder" value="projects/">
            <input type="hidden" id="dataJson" name="dataJson">
            <div class="mb-3">
                <label for="name" class="form-label">Nom du Projet:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenu:</label>
                <div id="editor" class="form-control"></div>
                <input type="hidden" name="content" id="content">
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image du Projet:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="position" class="form-label">Position:</label>
                <input type="number" class="form-control" id="position" name="position" required>
            </div>
            <a href="/admin/projects" class="btn btn-primary mr-1">Retour</a>
            <button type="submit" class="btn btn-success">Créer</button>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title, 'requireQuill' => true]); ?>