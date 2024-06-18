<?php

namespace App\Helpers;

use App\Controllers\AuthenticationController;
use App\Models\Permission;
use App\Models\Role;

class Authorization
{
    private static $rolePermissions = [
        Role::USER => [
            'permissions' => [
                Permission::VIEW_DASHBOARD,
            ],
            'inherits' => [],
        ],
        Role::MODERATOR => [
            'permissions' => [
                Permission::MANAGE_PROJECTS,
                Permission::MANAGE_HOMEPAGE,
            ],
            'inherits' => [Role::USER],
        ],
        Role::ADMIN => [
            'permissions' => [
                Permission::MANAGE_USERS,
                Permission::MANAGE_CONFIGURATION,
            ],
            'inherits' => [Role::MODERATOR],
        ],
    ];

    public static function getPermissionsForRole($role)
    {
        // Permissions spécifiques au rôle
        $permissions = self::$rolePermissions[$role]['permissions'];

        // Rôles dont il hérite
        $inheritedRoles = self::$rolePermissions[$role]['inherits'];

        // Récupérer les permissions des rôles hérités
        foreach ($inheritedRoles as $inheritedRole) {
            $permissions = array_merge($permissions, self::getPermissionsForRole($inheritedRole));
        }

        // Supprimer les doublons
        return array_unique($permissions);
    }


    public static function canAccess($role, $permission)
    {
        // Récupérer toutes les permissions du rôle, y compris celles héritées
        $permissions = self::getPermissionsForRole($role);

        return in_array($permission, $permissions);
    }


    public static function hasRequiredPermission($permission)
    {
        $user = AuthenticationController::getAuthenticatedUser();
        if ($user) {
            return self::canAccess($user->getRole(), $permission);
        }
        return false;
    }

    public static function requirePermission($permission, $redirectUrl = null)
    {
        if (self::hasRequiredPermission($permission)) {
            return true;
        }

        $redirectUrl = $redirectUrl ?? "/403";
        header('Location: ' . $redirectUrl);
        exit;
    }
}
