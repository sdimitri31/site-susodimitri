<?php

namespace App\Helpers;

class View
{
    public static function render($view, $data = [])
    {
        extract($data);
        $viewPath = realpath(__DIR__ . '/../Views/' . $view);
        if ($viewPath) {
            include $viewPath;
        } else {
            die("Vue non trouvée : " . $view);
        }
    }
}
