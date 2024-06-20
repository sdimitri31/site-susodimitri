<?php 
use App\Helpers\View;

$title = "Gestion des Projets";
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <a href="/admin/projects/create" class="btn btn-success mb-3">Ajouter un nouveau projet</a>
        <table class="table table-striped table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($project['id']); ?></td>
                        <td><?php echo htmlspecialchars($project['name']); ?></td>
                        <td><?php echo htmlspecialchars($project['description']); ?></td>
                        <td>
                            <img src="/uploads/projects/<?php echo $project['id'] . '/' . htmlspecialchars($project['image_name']); ?>" alt="<?php echo htmlspecialchars($project['name']); ?>" width="50">
                        </td>
                        <td><?php echo htmlspecialchars($project['position']); ?></td>
                        <td>
                            <a href="/projects/show/<?php echo $project['id']; ?>" class="btn btn-info btn-sm">Voir</a>
                            <a href="/admin/projects/edit/<?php echo $project['id']; ?>" class="btn btn-warning btn-sm">Éditer</a>
                            <a href="/admin/projects/destroy/<?php echo $project['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">Supprimer</a>
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
