<?php 
use App\Helpers\View;

$title = "Accueil";
?>

<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?php echo htmlspecialchars($homepageTitle); ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <div class="ql-container ql-snow mb-3" style="border-width: 0;">
            <div class="ql-editor">
                <?php echo $homepageContent; ?>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
