<?php

namespace App\Helpers;

class AlertMessage
{
    public static function displayMessages()
    {
        $message = Session::get('message');
        $error = Session::get('error');

        if (!empty($message)) {
            echo '<div class="alert alert-success mt-4">' . htmlspecialchars($message) . '</div>';
            Session::set('message', null);
        }
        if (!empty($error)) {
            echo '<div class="alert alert-danger mt-4 ">' . htmlspecialchars($error) . '</div>';
            Session::set('error', null);
        }
    }
}
