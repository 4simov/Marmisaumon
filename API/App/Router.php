<?php

namespace App;

class Router {
    protected $routes = [];
    protected $remplacement = '/id/';
    protected $motif = "/\/(\d+)\/?/";

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
        $uriReplaceId = $this->replaceIdUrl($uri);
        if (array_key_exists($_SERVER['REQUEST_METHOD'] . $uriReplaceId, $this->routes)) {
            
            //on récupère l'intitulé de la classe que l'on veut utiliser
            $controller = $this->routes[$_SERVER['REQUEST_METHOD'] .$uriReplaceId]['controller'];
            //on récupère l'intitulé de la function de la classe
            $action = $this->routes[$_SERVER['REQUEST_METHOD'] .$uriReplaceId]['action'];
            //on récupère les droits nécessaires à la consommation de l'API
            $rightEndpoint = $this->routes[$_SERVER['REQUEST_METHOD'] .$uriReplaceId]['right'];

            
            //Stocke les id glissées dans l'url
            $params = $this->getParameters($uri);

            //on récupère le body de la requête du client
            $requestBody = file_get_contents('php://input');
            $dataJson = json_decode($requestBody);
            $controller = new $controller($requestBody, $rightEndpoint, $params);

            if($controller->isRigth()) {
                $reponse = $controller->$action($dataJson, $params[0]);//Exemple de résultat : UserController->getUserByEmail($dataJson)
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

    /**
     * Permet de remplacer les paramtres d'url de type "/12" ou "/42/" par une chaine de caractère => résou le problème d'identification des routes avec id
     */
    function replaceIdUrl($url) : string {       
        $replaceId = preg_replace($this->motif, $this->remplacement, $url);
        echo $replaceId;
        return $replaceId;
    }

    function getParameters($url) : Array{
         //Stocke les id glissées dnas l'urls-
         $params = null;
         if (preg_match_all($this->motif, $url, $params)) {
             // La correspondance est dans $correspondance[0]
             $params = $params[1];
         }
         var_dump($params);
         return $params;
    }
}