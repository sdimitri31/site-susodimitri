<?php 
use App\Helpers\View;

$title = "Projets";
?>

<?php ob_start(); ?>

<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1>Mes Projets</h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <div class="row">
            <?php foreach ($projects as $project): ?>
                <div class="col-md-4 mb-4">
                    <a href="/projects/show/<?php echo $project['id']; ?>" class="text-decoration-none">
                        <div class="card h-100">
                            <img src="/uploads/projects/<?php echo $project['id'] .'/'.htmlspecialchars($project['image_name']); ?>" class="card-img-top" alt="Image de <?php echo htmlspecialchars($project['name']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($project['name']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($project['description']); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
