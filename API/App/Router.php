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
    public function addRoute($route, $controller, $action, $roleMinimun = 0) {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action, 'right' => $roleMinimun];
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
            //on récupère les droits nécessaires à la consommation de l'API
            $rightEndpoint = $this->routes[$_SERVER['REQUEST_METHOD'] .$uri]['right'];

            //on récupère le body de la requête du client
            $requestBody = file_get_contents('php://input');
            $dataJSON = json_decode($requestBody);
            $controller = new $controller($requestBody, $rightEndpoint);


            if($controller->isRigth()) {
                $reponse = $controller->$action($dataJSON);//Exemple de résultat : UserController->getUserByEmail($dataJSON)
            }
            else {
                echo "Vous n'avez pas les droits nécessaires pour exécuter cette actions.";
            }
        }
        //Aucune route correspondant à l'url n'a été trouvé
        else {
            echo "Vous êtes dans un endroit qui n'existe pas";
            //include __DIR__ .'/Errors/404.php';
        }
    }
}