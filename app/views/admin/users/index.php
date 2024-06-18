<?php
use App\Helpers\View;

$title = "Gestion des Utilisateurs";
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1>Gestion des utilisateurs</h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <a href="/admin/users/create" class="btn btn-success mb-3">Ajouter un utilisateur</a>
        <table class="table table-striped table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Rôle</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']); ?></td>
                    <td><?= htmlspecialchars($user['username']); ?></td>
                    <td><?= htmlspecialchars($user['role']); ?></td>
                    <td><?= htmlspecialchars($user['created_at']); ?></td>
                    <td>
                        <a href="/admin/users/<?= $user['id']; ?>" class="btn btn-info btn-sm mr-1">Voir</a>
                        <a href="/admin/users/<?= $user['id']; ?>/edit" class="btn btn-warning btn-sm mr-1">Éditer</a>
                        <form action="/admin/users/<?= $user['id']; ?>/delete" method="post" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</button>
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
