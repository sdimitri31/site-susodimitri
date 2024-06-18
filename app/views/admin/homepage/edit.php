<?php 
use App\Helpers\View;

$title = "Éditer la Page d'Accueil";
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1>Éditer la page d'accueil</h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <?php 
        if (isset($error)) {
            echo '<div class="alert alert-danger" role="alert">Erreur : ' . htmlspecialchars($error) . '</div>';
        }
        ?>
        <form action="/admin/homepage/update" method="post">
            <input type="hidden" id="destinationFolder" value="homepage/">
            <div class="mb-3">
                <label for="title" class="form-label">Titre:</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($content['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Texte:</label>
                <div id="editor" class="form-control"><?php echo $content['content']; ?></div>
                <input type="hidden" name="content" id="content">
            </div>
            <a href="/admin/homepage" class="btn btn-primary mr-1">Retour</a>
            <button type="submit" class="btn btn-warning">Mettre à jour</button>
        </form>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title, 'requireQuill' => true]); ?>
