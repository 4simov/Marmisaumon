<?php

namespace App;

class Router {
    protected $routes = [];
    public function __construct() {
        $this->routes = [];
    }

    /**
     * Permet d'ajouter une association entre une URL donnée et une fonction d'un contrôleur de l'application
     * @param $method => la méthode HTTP (GET, POST, PUT, DELETE)
     * @param $route => l'URL
     * @param $controller => la classe qui doit être utilisée
     * @param $action => la fonction à appeler dans la classe
     */
    public function addRoute($method, $route, $controller, $action) {
        $this->routes[$method][$route] = ['controller' => $controller, 'action' => $action];
    }

    public function dispatch($uri, $method) {
        // Gestion des requêtes OPTIONS pour CORS
        if ($method === 'OPTIONS') {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
            header("Access-Control-Allow-Headers: Content-Type, Authorization");
            echo "<script>console.log('test' );</script>";
            exit(0);
        }

        if (isset($this->routes[$method][$uri])) {
            $controller = $this->routes[$method][$uri]['controller'];
            $action = $this->routes[$method][$uri]['action'];

            $controller = new $controller();
            $requestBody = file_get_contents('php://input');

            if ($requestBody != null) {
                $controller->$action($requestBody);
            } else {
                $controller->$action();
            }
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    }
}