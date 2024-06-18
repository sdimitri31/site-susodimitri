<?php

namespace App\Core;

use App\Controllers\ConfigurationController;

class Router
{
    private $routes = [];

    public function add($method, $uri, $controller, $action, $params = [])
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action,
            'params' => $params,
        ];
    }

    public function dispatch($requestUri, $requestMethod)
    {
        ConfigurationController::checkMaintenanceMode();
        foreach ($this->routes as $route) {
            if ($route['uri'] === $requestUri && $route['method'] === $requestMethod) {
                $controllerName = $route['controller'];
                $controller = new $controllerName();
                call_user_func_array([$controller, $route['action']], $route['params']);
                return true;
            } elseif (preg_match($this->convertUriToRegex($route['uri']), $requestUri, $matches) && $route['method'] === $requestMethod) {
                $controllerName = $route['controller'];
                $controller = new $controllerName();
                call_user_func_array([$controller, $route['action']], array_merge(array_slice($matches, 1), $route['params']));
                return true;
            }
        }

        return false;
    }

    private function convertUriToRegex($uri)
    {
        $uri = preg_replace('/\//', '\/', $uri);
        $uri = preg_replace('/\{[a-zA-Z]+\}/', '([a-zA-Z0-9_-]+)', $uri);
        return '/^' . $uri . '$/';
    }
}
