<?php
require __DIR__ . '/../vendor/autoload.php';

use App\Controllers\ConfigurationController;
use App\Controllers\RouterController;
use App\Controllers\VisitController;
use App\Core\Router;
use App\Helpers\Session;

Session::init();
ConfigurationController::initConfig();

$router = new Router();
RouterController::configureRoutes($router);

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

VisitController::logVisit($_SERVER['REMOTE_ADDR'], $requestUri, $requestMethod);

$router->dispatch($requestUri, $requestMethod);
