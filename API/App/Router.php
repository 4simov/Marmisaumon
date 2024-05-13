<?php

namespace App;

class Router {
    protected $routes = [];
    public function __construct() {
        $this->routes = [];
    }

    /**
     * permet d'ajouter une association entre un url donné et une fonction d'un controlleur de l'application
     * @param $route => l'url
     * @param $controller => la classe qui doit être utilisée
     * @param $action la fonction à appelé dans la classe
     */
    public function addRoute($route, $controller, $action) {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch($uri) {
        if (array_key_exists($uri, $this->routes)) {
            $controller = $this->routes[$uri]['controller'];
            $action = $this->routes[$uri]['action'];

            $controller = new $controller();
            $requestBody = file_get_contents('php://input');

            if($requestBody != null) {
                $controller->$action($requestBody);
            }
            else {
                $controller->$action();
            }
        } else {
            include __DIR__ .'/Errors/404.php';
        }
    }
}