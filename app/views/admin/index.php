<?php
use App\Helpers\View;
use App\Helpers\Authorization;
use App\Models\Permission;

$title = "Panneau d'Administration";
$hasManageProjectsPermission = Authorization::hasRequiredPermission(Permission::MANAGE_PROJECTS);
$hasManageUserPermission = Authorization::hasRequiredPermission(Permission::MANAGE_USERS);
$hasManageHomepagePermission = Authorization::hasRequiredPermission(Permission::MANAGE_HOMEPAGE);
$hasManageConfigurationPermission = Authorization::hasRequiredPermission(Permission::MANAGE_CONFIGURATION);
$hasManageContactsPermission = Authorization::hasRequiredPermission(Permission::MANAGE_CONTACTS);

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
            <?php if ($hasManageProjectsPermission): ?>
                <div class="col-md-4 mb-4">
                    <a href="/admin/projects" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Gestion des projets</h5>
                                <p class="card-text">Ajouter, modifier ou supprimer des projets.</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($hasManageUserPermission): ?>
                <div class="col-md-4 mb-4">
                    <a href="/admin/users" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Gestion des utilisateurs</h5>
                                <p class="card-text">Ajouter, modifier ou supprimer des utilisateurs.</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($hasManageHomepagePermission): ?>
                <div class="col-md-4 mb-4">
                    <a href="/admin/homepage" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Gestion de la page d'accueil</h5>
                                <p class="card-text">Modifier le contenu de la page d'accueil.</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <?php if ($hasManageConfigurationPermission): ?>
                <div class="col-md-4 mb-4">
                    <a href="/admin/configuration" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Gestion de la configuration</h5>
                                <p class="card-text">Ajouter, modifier ou supprimer des configurations.</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
            <div class="col-md-4 mb-4">
                <a href="/admin/visits" class="text-decoration-none">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Visites</h5>
                            <p class="card-text">Voir les statistiques des visites.</p>
                        </div>
                    </div>
                </a>
            </div>
            <?php if ($hasManageContactsPermission): ?>
                <div class="col-md-4 mb-4">
                    <a href="/admin/contacts" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Gestion des moyens de contact</h5>
                                <p class="card-text">Ajouter, modifier ou supprimer des contacts.</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>

<?php View::render('layouts/layout.php', ['content' => $content, 'title' => $title]); ?>
