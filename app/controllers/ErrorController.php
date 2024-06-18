<?php

namespace App\Controllers;

use App\Helpers\View;

class ErrorController
{
    public function notFound()
    {
        View::render('error/404.php');
    }

    public function forbidden()
    {
        View::render('error/403.php');
    }

    public function maintenance()
    {
        View::render('error/maintenance.php');
    }
}
