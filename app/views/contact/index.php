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
                <a href="mailto:suso.dimitri@gmail.com" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Email</h5>
                            <p class="m-0">suso.dimitri@gmail.com</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="https://github.com/sdimitri31" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">GitHub</h5>
                            <p class="m-0">@sdimitri31</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mb-4">
                <a href="https://www.instagram.com/sdimitri31/" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Instagram</h5>
                            <p class="m-0">@sdimitri31</p>
                        </div>
                    </div>
                </a>
            </div>            
            <div class="col-md-4 mb-4">
                <a href="https://www.linkedin.com/in/dimitri-suso-797b17314/" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Linkedin</h5>
                            <p class="m-0">Dimitri Suso</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>