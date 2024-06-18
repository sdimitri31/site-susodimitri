<?php 
use App\Helpers\View;

$title = "Contact";

?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1>Contact</h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Nom</h5>
                        <p class="m-0">Suso Dimitri</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Email</h5>
                        <a href="mailto:suso.dimitri@gmail.com">suso.dimitri@gmail.com</a>
                    </div>
                </div>
            </div>          
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>