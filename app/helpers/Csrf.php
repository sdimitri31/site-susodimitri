<?php

namespace App\Helpers;

use App\Helpers\Session;

class Csrf
{
    public static function generateToken()
    {
        Session::set('csrf_token', bin2hex(random_bytes(32)));
        return Session::get('csrf_token');
    }

    public static function verifyToken($token)
    {
        return ((Session::get('csrf_token') !== null) && Session::get('csrf_token') === $token);
    }
}
