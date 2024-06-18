<?php

namespace App\Controllers;

use App\Models\Homepage;
use App\Models\Permission;
use App\Helpers\Authorization;
use App\Helpers\View;

class HomepageController
{
    public function index()
    {
        $content = Homepage::getContent();
        $title = $content['title'];
        $content = $content['content'];
        View::render('homepage/index.php', ['homepageTitle' => $title, 'homepageContent' => $content]);
    }

    public function adminIndex()
    {
        Authorization::requirePermission(Permission::MANAGE_HOMEPAGE, '/home');

        $content = Homepage::getContent();
        View::render('admin/homepage/index.php', ['content' => $content]);
    }

    public function edit()
    {
        Authorization::requirePermission(Permission::MANAGE_HOMEPAGE, '/home');

        $content = Homepage::getContent();
        View::render('admin/homepage/edit.php', ['content' => $content]);
    }

    public function update()
    {
        Authorization::requirePermission(Permission::MANAGE_HOMEPAGE, '/home');

        $data = [
            'title' => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? ''
        ];

        Homepage::updateContent($data);
        header('Location: /admin/homepage');
    }
}
