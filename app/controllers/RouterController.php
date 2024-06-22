<?php

namespace App\Controllers;

use App\Core\Router;
use App\Controllers\HomepageController;
use App\Controllers\ProjectController;
use App\Controllers\ContactController;
use App\Controllers\AuthenticationController;
use App\Controllers\AdminController;
use App\Controllers\UserController;
use App\Controllers\VisitController;
use App\Controllers\ConfigurationController;
use App\Controllers\ErrorController;
use App\Helpers\Upload;

class RouterController
{
    public static function configureRoutes(Router $router)
    {
        // Home routes
        $router->add('GET', '/', HomepageController::class, 'index', ['user']);
        $router->add('GET', '/index.php', HomepageController::class, 'index', ['user']);
        $router->add('GET', '/index', HomepageController::class, 'index', ['user']);
        $router->add('GET', '/home', HomepageController::class, 'index', ['user']);

        // Project routes
        $router->add('GET', '/projects', ProjectController::class, 'index', ['user']);
        $router->add('GET', '/projets', ProjectController::class, 'index', ['user']);
        $router->add('GET', '/projects/show/{id}', ProjectController::class, 'show');
        $router->add('GET', '/projets/show/{id}', ProjectController::class, 'show');

        // Contact routes
        $router->add('GET', '/contact', ContactController::class, 'index', ['user']);

        // Image routes
        $router->add('POST', '/upload_image', Upload::class, 'quillImageUpload');
        $router->add('POST', '/delete_image', Upload::class, 'quillDeleteUpload');

        // Auth routes
        $router->add('GET', '/login', AuthenticationController::class, 'showLoginForm', ['user']);
        $router->add('POST', '/login', AuthenticationController::class, 'login', ['user']);
        $router->add('GET', '/logout', AuthenticationController::class, 'logout');

        // User routes
        $router->add('GET', '/register', UserController::class, 'create', ['user']);
        $router->add('POST', '/register', UserController::class, 'store', ['user']);

        // Admin routes
        $router->add('GET', '/admin', AdminController::class, 'index');
        $router->add('GET', '/admin/login', AuthenticationController::class, 'showLoginForm', ['admin']);
        $router->add('POST', '/admin/login', AuthenticationController::class, 'login', ['admin']);

        // Admin Configuration routes
        $router->add('GET', '/admin/configuration', ConfigurationController::class, 'index');
        $router->add('POST', '/admin/configuration/update', ConfigurationController::class, 'update');
        $router->add('GET', '/admin/configuration/create', ConfigurationController::class, 'create');
        $router->add('POST', '/admin/configuration/create', ConfigurationController::class, 'store');
        $router->add('GET', '/admin/configuration/destroy/{id}', ConfigurationController::class, 'destroy');

        // Admin Project routes
        $router->add('GET', '/admin/projects', ProjectController::class, 'index', ['admin']);
        $router->add('GET', '/admin/projects/create', ProjectController::class, 'create');
        $router->add('GET', '/admin/projects/destroy/{id}', ProjectController::class, 'destroy');
        $router->add('GET', '/admin/projects/edit/{id}', ProjectController::class, 'edit');
        $router->add('POST', '/admin/projects/store', ProjectController::class, 'store');
        $router->add('POST', '/admin/projects/update/{id}', ProjectController::class, 'update');

        // Admin Home routes
        $router->add('GET', '/admin/homepage', HomepageController::class, 'index', ['admin']);
        $router->add('GET', '/admin/homepage/edit', HomepageController::class, 'edit');
        $router->add('POST', '/admin/homepage/update', HomepageController::class, 'update');

        // Admin User routes
        $router->add('GET', '/admin/users', UserController::class, 'index');
        $router->add('GET', '/admin/users/create', UserController::class, 'create', ['admin']);
        $router->add('POST', '/admin/users/create', UserController::class, 'store', ['admin']);
        $router->add('GET', '/admin/users/{id}', UserController::class, 'show');
        $router->add('GET', '/admin/users/{id}/edit', UserController::class, 'edit');
        $router->add('POST', '/admin/users/{id}/update', UserController::class, 'update');
        $router->add('POST', '/admin/users/{id}/delete', UserController::class, 'destroy');

        // Admin Visit routes
        $router->add('GET', '/admin/visits', VisitController::class, 'index');

        // Admin Contact routes
        $router->add('GET', '/admin/contacts', ContactController::class, 'index', ['admin']);
        $router->add('GET', '/admin/contacts/create', ContactController::class, 'create');
        $router->add('GET', '/admin/contacts/destroy/{id}', ContactController::class, 'destroy');
        $router->add('GET', '/admin/contacts/edit/{id}', ContactController::class, 'edit');
        $router->add('POST', '/admin/contacts/store', ContactController::class, 'store');
        $router->add('POST', '/admin/contacts/update/{id}', ContactController::class, 'update');

        // Error routes
        $router->add('GET', '/403', ErrorController::class, 'forbidden');
        $router->add('GET', '/404', ErrorController::class, 'notFound');
        $router->add('GET', '/maintenance', ErrorController::class, 'maintenance');
    }
}