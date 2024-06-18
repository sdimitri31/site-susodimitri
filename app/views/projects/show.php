<?php 
use App\Helpers\View;

$title = "Projet : " . $project['name'];
?>

<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?php echo htmlspecialchars($project['name']); ?></h1>
        <p class="m-0"><?php echo nl2br(htmlspecialchars($project['description'])); ?></p>
    </div>
    <div class="col-sm-2 px-4 text-md-right">
        <img class="img-fluid py-2" src="/uploads/projects/<?php echo $project['id'] . '/' . htmlspecialchars($project['image_name']); ?>" alt="<?php echo htmlspecialchars($project['name']); ?>">
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <div class="ql-container ql-snow" style="border-width: 0;">
            <div class="ql-editor">
                <?php echo $project['content'];?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col px-4 py-2">
        <a href="/projects" class="btn btn-primary">Retour</a>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
