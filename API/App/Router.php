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

    public function dispatch($uri, $response) {
        if (array_key_exists($uri, $this->routes)) {
            $controller = $this->routes[$uri]['controller'];
            $action = $this->routes[$uri]['action'];

            
            $requestBody = file_get_contents('php://input');
            $dataJSON = json_decode($requestBody);
            // Output data from JSON data
            if(is_object($dataJSON)){
                print_r( $dataJSON->mail);
            }else{
                print("The given variable is not an object");
            }

            $controller = new $controller($requestBody);

            if($controller->getToken() == true) {
                if($requestBody != null) {
                    $reponse = $controller->$action($dataJSON);
                }
                else {
                    $reponse = $controller->$action();
                }
            } else {
                include __DIR__ .'/Errors/404.php';
                $reponse;
            }

            return $response;
        }
    }
}