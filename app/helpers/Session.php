<?php

namespace App\Helpers;

class Session
{
    public static function init()
    {
        include realpath(__DIR__ . '/../../config/config.php');

        if ($sessionSettings !== null) {
            session_set_cookie_params($sessionSettings);
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function destroy()
    {
        session_destroy();
        $_SESSION = [];
    }
}