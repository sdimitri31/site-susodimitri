<?php
use App\Helpers\View;
use App\Models\Contact;

$title = "Contact";
$contacts = Contact::getAllContacts();
?>
<?php ob_start(); ?>
<div class="shadow row mt-4 bg-body-tertiary">
    <div class="col px-4 py-2">
        <h1><?= $title ?></h1>
    </div>
</div>
<div class="row">
    <div class="col mt-4 px-4 py-2">
        <div class="row">
            <?php foreach ($contacts as $contact): ?>
                <div class="col-md-4 mb-4">
                    <?php if (!empty($contact['link'])): ?>
                        <a href="<?= htmlspecialchars($contact['link']) ?>" target="_blank" class="text-decoration-none">
                    <?php endif; ?>
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?= htmlspecialchars($contact['title']) ?></h5>
                                    <p class="m-0"><?= htmlspecialchars($contact['text']) ?></p>
                                </div>
                            </div>
                    <?php if (!empty($contact['link'])): ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
