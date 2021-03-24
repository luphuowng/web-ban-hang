<?php

namespace Core;

use Exception;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function load($file)
    {
        $router = new static;

        require_once $file;

        return $router;
    }

    public function get($uri, $controller)
    {
        $this->routes['GET'][$uri] = $controller;
    }

    public function post($uri, $controller)
    {
        $this->routes['POST'][$uri] = $controller;
    }

    public function direct($uri, $method)
    {
        if (array_key_exists($uri, $this->routes[$method])) {
            try {
                return $this->callAction(
                    ...explode('@', $this->routes[$method][$uri])
                );
            } catch (Exception $e) {
                throw $e;
            }

        }
        try {
            return $this->callAction('PagesController', 'notFound');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function callAction($controller, $action)
    {
        $controller = "App\\Controllers\\{$controller}";
        $controller = new $controller;

        if (!method_exists($controller, $action)) {
            throw new Exception("{$controller} does not respond to the {$action} action");
        }

        return $controller->$action();
    }
}