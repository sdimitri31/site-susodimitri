<?php

namespace App\Core;

use App\Controllers\ErrorController;
use App\Middleware\MaintenanceMiddleware;

class Router
{
    private $routes = [];
    private $middleware;

    public function __construct()
    {
        $this->middleware = new MaintenanceMiddleware();
    }

    /**
     * Adds a new route to the router.
     * 
     * @param string $method HTTP method
     * @param string $uri URI pattern
     * @param string $controller Controller class
     * @param string $action Controller action method
     * @param array $params Additional parameters
     */
    public function add($method, $uri, $controller, $action, $params = [])
    {
        $this->routes[$method][] = [
            'uri' => $uri,
            'controller' => $controller,
            'action' => $action,
            'params' => $params,
        ];
    }

    /**
     * Dispatches the request to the appropriate controller and action.
     * 
     * @param string $requestUri The URI of the incoming request
     * @param string $requestMethod The HTTP method of the incoming request
     * @return bool
     */
    public function dispatch($requestUri, $requestMethod)
    {
        // Check if site is in maintenance mode
        $this->middleware->handle($requestUri, $requestMethod);

        if (!isset($this->routes[$requestMethod])) {
            return $this->handleNotFound();
        }

        foreach ($this->routes[$requestMethod] as $route) {
            // Match route with dynamic parameters
            if ($this->matchRoute($route['uri'], $requestUri, $matches)) {
                // Remove the first element which is the full match
                array_shift($matches);
                // Merge route params with matched dynamic params
                $params = array_merge($matches, $route['params']);
                return $this->invokeAction($route['controller'], $route['action'], $params);
            }

            // Match route without dynamic parameters
            if ($route['uri'] === $requestUri) {
                return $this->invokeAction($route['controller'], $route['action'], $route['params']);
            }
        }

        return $this->handleNotFound();
    
    }

    /**
     * Converts a URI pattern into a regular expression.
     * 
     * @param string $uri URI pattern
     * @return string
     */
    private function convertUriToRegex($uri)
    {
        $uri = preg_replace('/\//', '\/', $uri);
        $uri = preg_replace('/\{[a-zA-Z]+\}/', '([a-zA-Z0-9_-]+)', $uri);
        return '/^' . $uri . '$/';
    }

    /**
     * Matches a request URI against a route URI pattern.
     * 
     * @param string $routeUri The route URI pattern
     * @param string $requestUri The request URI
     * @param array &$matches Matches found
     * @return bool
     */
    private function matchRoute($routeUri, $requestUri, &$matches)
    {
        return preg_match($this->convertUriToRegex($routeUri), $requestUri, $matches);
    }

    /**
     * Invokes the specified action on the given controller.
     * 
     * @param string $controllerName The controller class
     * @param string $action The action method
     * @param array $params Parameters to pass to the action method
     * @return bool
     */
    private function invokeAction($controllerName, $action, $params)
    {
        if (!class_exists($controllerName) || !method_exists($controllerName, $action)) {
            return $this->handleNotFound();
        }

        $controller = new $controllerName();
        call_user_func_array([$controller, $action], $params);

        return true;
    }

    /**
     * Handles not found routes.
     * 
     * @return bool
     */
    private function handleNotFound()
    {
        header('HTTP/1.0 404 Not Found');
        $controller = new ErrorController();
        $controller->notFound();
        return false;
    }
}
