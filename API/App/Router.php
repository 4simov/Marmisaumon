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

    /**
     * Traduit l'url de façon à utiliser le bon endpoint à travers les controllers
     */
    public function dispatch($uri) {
        if (array_key_exists($_SERVER['REQUEST_METHOD'] . $uri, $this->routes)) {
            //on récupère l'intitulé de la classe que l'on veut utiliser
            $controller = $this->routes[$_SERVER['REQUEST_METHOD'] .$uri]['controller'];
            //on récupère l'intitulé de la function de la classe
            $action = $this->routes[$_SERVER['REQUEST_METHOD'] .$uri]['action'];

            //on récupère le body de la requête du client
            $requestBody = file_get_contents('php://input');
            $dataJSON = json_decode($requestBody);

            $controller = new $controller($requestBody);

            if($controller->getToken() == true) {
                //on vérifie si la requête n'a pas de données dans le body pour le rediriger en paramètre de la fonction utilisé par la classe
                if($requestBody != null) {
                    $reponse = $controller->$action($dataJSON);//Exemple de résultat : UserController->getUserByEmail($dataJSON)
                }
                else {
                    $reponse = $controller->$action();//Exemple de résultat : UserController->getAllUsers()
                }
            }
        }
        //Aucune route correspondant à l'url n'a été trouvé
        else {
            include __DIR__ .'/Errors/404.php';
        }
    }
}