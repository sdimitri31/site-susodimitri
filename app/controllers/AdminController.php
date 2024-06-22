<?php

namespace App\Controllers;

use App\Helpers\Authorization;
use App\Helpers\View;
use App\Models\Permission;

class AdminController
{
    public function index()
    {
        Authorization::requirePermission(Permission::VIEW_DASHBOARD, '/login');
        View::render('admin/index.php');
    }
}
