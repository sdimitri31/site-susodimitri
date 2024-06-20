<?php

use App\Helpers\View;

$title = "Gestion des contacts";
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <a href="/admin/contacts/create" class="btn btn-success mb-3">Ajouter un contact</a>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Text</th>
                    <th>Link</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= htmlspecialchars($contact['title']) ?></td>
                        <td><?= htmlspecialchars($contact['text']) ?></td>
                        <td><?= htmlspecialchars($contact['link']) ?></td>
                        <td>
                            <a href="/admin/contacts/edit/<?= $contact['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="/admin/contacts/delete/<?= $contact['id'] ?>" method="POST" style="display:inline;">
                                <input type="hidden" name="csrfToken" value="<?= \App\Helpers\Csrf::generateToken() ?>" />
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
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
