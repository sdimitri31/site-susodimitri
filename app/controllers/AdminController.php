<?php

namespace App\Controllers;

use App\Controllers\LoginAttemptController;
use App\Models\Configuration;
use App\Models\Permission;
use App\Models\User;
use App\Helpers\View;
use App\Helpers\Session;
use App\Helpers\Authorization;
use App\Helpers\Csrf;
use Exception;

class AdminController
{
    public function index()
    {
        Authorization::requirePermission(Permission::VIEW_DASHBOARD, '/login');
        View::render('admin/index.php');
    }
}
