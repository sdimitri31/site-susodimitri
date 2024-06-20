<?php
use App\Helpers\View;

$title = "Édition du contact : " . $contact['title'];
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <form action="/admin/contacts/update/<?= $contact['id'] ?>" method="POST">
            <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>" />
            <div class="mb-3">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($contact['title']) ?>" required />
            </div>
            <div class="mb-3">
                <label for="text">Text</label>
                <input type="text" class="form-control" id="text" name="text" value="<?= htmlspecialchars($contact['text']) ?>" required />
            </div>
            <div class="mb-3">
                <label for="link">Link</label>
                <input type="url" class="form-control" id="link" name="link" value="<?= htmlspecialchars($contact['link']) ?>" />
            </div>
            <a href="/admin/contacts" class="btn btn-primary mr-1">Retour</a>
            <button type="submit" class="btn btn-warning">Mettre à jour</button>
        </form>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
