<?php

namespace App;

class Router {
    protected $routes = [];
    public function __construct() {
        $this->routes = [];
    }

    public function addRoute($route, $controller, $action) {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch($uri, $request = null) {
        if (array_key_exists($uri, $this->routes)) {
            $controller = $this->routes[$uri]['controller'];
            $action = $this->routes[$uri]['action'];

            $controller = new $controller();
            if($request != null) {
                $controller->$action($request);
                echo 'OPTION';
            }
            else {
                $controller->$action();
            }
        } else {
            include __DIR__ .'/Errors/404.php';
            //echo 'Error 404 : Tu es sur une page inexistante 0_0.';
            //throw new \Exception("No route found for URI: $uri");
        }
    }
}