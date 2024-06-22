<?php

namespace App\Middleware;

use App\Controllers\ConfigurationController;
use App\Controllers\ErrorController;
use App\Helpers\Session;
use App\Models\Role;

class MaintenanceMiddleware
{
    public function handle($requestUri, $requestMethod)
    {
        // Check if maintenance mode is active
        if (ConfigurationController::isMaintenanceMode()) {
            if (!$this->isAdmin()) {
                // Allow access to specific routes even in maintenance mode
                if ($requestUri !== '/admin/login') {
                    header('HTTP/1.1 503 Service Unavailable');
                    header('Retry-After: 3600');
                    $controller = new ErrorController();
                    $controller->maintenance();
                    exit();
                }
            }
        }
    }

    private function isAdmin()
    {
        return Session::get('user_role') === ROLE::ADMIN;
    }
}
