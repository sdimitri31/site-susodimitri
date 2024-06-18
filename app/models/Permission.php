<?php

namespace App\Models;

class Permission
{
    const MANAGE_USERS = 'manage_users';
    const MANAGE_HOMEPAGE = 'manage_homepage';
    const MANAGE_PROJECTS = 'manage_projects';
    const VIEW_DASHBOARD = 'view_dashboard';
    const MANAGE_CONFIGURATION = 'manage_configuration';

    public static function getAllPermissions()
    {
        return [
            self::MANAGE_USERS,
            self::MANAGE_HOMEPAGE,
            self::MANAGE_PROJECTS,
            self::VIEW_DASHBOARD,
            self::MANAGE_CONFIGURATION,
        ];
    }
}
