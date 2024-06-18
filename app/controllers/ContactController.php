<?php

namespace App\Controllers;

use App\Helpers\View;

class ContactController
{
    public function index()
    {
        View::render('contact/index.php');
    }
}
