<?php

namespace App\Models;

class Role
{
    const ADMIN = 'admin';
    const USER = 'user';
    const MODERATOR = 'moderator';

    public static function getAllRoles()
    {
        return [
            self::ADMIN,
            self::USER,
            self::MODERATOR,
        ];
    }
}
