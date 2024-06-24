<?php

namespace App\Controllers;

use App\Helpers\AlertMessage;
use App\Helpers\Session;
use App\Models\Homepage;
use App\Models\Permission;
use App\Helpers\Authorization;
use App\Helpers\View;
use Exception;

class HomepageController
{
    public function index($context = 'user')
    {
        $homepage = Homepage::getHomepage();
        if (is_null($homepage)) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Erreur lors de la recherche de la page d\'accueil.');
        } elseif (empty($homepage)) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Aucune page d\'accueil trouvée.');
        }

        if ($context == 'user') {
            View::render('homepage/index.php', ['homepage' => $homepage]);
        } else if ($context == 'admin') {
            Authorization::requirePermission(Permission::MANAGE_HOMEPAGE, '/home');
            View::render('admin/homepage/index.php', ['homepage' => $homepage]);
        }
    }

    public function edit()
    {
        Authorization::requirePermission(Permission::MANAGE_HOMEPAGE, '/home');
        $content = Homepage::getHomepage();
        if (is_null($content)) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Erreur lors de la recherche de la page d\'accueil.');
            self::index('admin');
            exit();
        } elseif (empty($content)) {
            AlertMessage::setAlert(AlertMessage::ERROR, 'Page d\'accueil non trouvée.');
            self::index('admin');
            exit();
        }
        View::render('admin/homepage/edit.php', ['content' => $content]);
    }

    public function update()
    {
        Authorization::requirePermission(Permission::MANAGE_HOMEPAGE, '/home');

        $data = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? ''
        ];
        try {
            Homepage::updateContent($data);
            AlertMessage::setAlert(AlertMessage::SUCCESS, 'Page d\'accueil éditée avec succès !');
            self::index('admin');
        }catch (Exception $e) {
            AlertMessage::setAlert(AlertMessage::ERROR, $e->getMessage());
            self::edit();
        }
    }
}
