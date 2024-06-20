<?php 

use App\Helpers\View;
use App\Controllers\AuthenticationController;
use App\Helpers\AlertMessage;


$title = isset($title) ? $title : null ;
$loggedUser = AuthenticationController::getAuthenticatedUser();

?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php View::render('layouts/head.php', ['title' => $title]); ?>
    </head>
    <body class="d-flex flex-column min-vh-100" id="body">
        
        <?php View::render('layouts/menu.php', ['loggedUser' => $loggedUser]); ?>

        <div class="container flex-grow-1">
            <?= AlertMessage::displayMessages() ?>
            <?php echo $content; ?>
        </div>

        <footer class="bg-dark-subtle pt-3 mt-auto" id="footer">
            <div class="container">
                <p class="text-muted">&copy; 2024 Suso Dimitri. Tous droits réservés.</p>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <!-- Custom JS -->
        <script src="/js/theme-toggle.js"></script>

        <?php if (isset($requireQuill) && $requireQuill === true): ?>
            <!-- Quill JS -->
            <?php View::render('quill/image-properties.php'); ?>
            <?php View::render('quill/link-properties.php'); ?>
            <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
            <script src="/js/LinkBlot.js"></script>
            <script src="/js/LinkManager.js"></script>
            <script src="/js/ImageManager.js"></script>
            <script src="/js/Quill.js"></script>
        <?php endif; ?>

    </body>
</html>
