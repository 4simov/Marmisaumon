<?php

namespace App;

class Router {
    protected $routes = [];
    protected $remplacement = '/id/';
    protected $motif = "/\/(\d+)\/?/";

    public function __construct() {
        $this->routes = [];
    }

    public function addRoute($route, $controller, $action, $roleMinimun = 0) {
        $this->routes[$route] = ['controller' => $controller, 'action' => $action, 'right' => $roleMinimun];
    }

    public function dispatch($uri) {
        ob_start();//Met en tampon l'exacution API

        $uriReplaceId = $this->replaceIdUrl($uri);
        if (array_key_exists($_SERVER['REQUEST_METHOD'] . $uriReplaceId, $this->routes)) {
            $controller = $this->routes[$_SERVER['REQUEST_METHOD'] . $uriReplaceId]['controller'];
            $action = $this->routes[$_SERVER['REQUEST_METHOD'] . $uriReplaceId]['action'];
            $rightEndpoint = $this->routes[$_SERVER['REQUEST_METHOD'] . $uriReplaceId]['right'];

            $params = $this->getParameters($uri);
            $requestBody = file_get_contents('php://input');
            $dataJson = json_decode($requestBody);
            $controller = new $controller($requestBody, $rightEndpoint, $params);

            if($controller->isRigth()) {
                $reponse = $controller->$action($dataJson, $params[0]);//Exemple de résultat : UserController->getUserByEmail($dataJson)
            }
            else {
            echo json_encode(["error" => true, "message" => "Vous êtes dans un endroit qui n'existe pas."]);
            }
        }
        //Aucune route correspondant à l'url n'a été trouvé
        else {
            echo "Vous êtes dans un endroit qui n'existe pas";
            //include __DIR__ .'/Errors/404.php';
        }
        ob_end_flush();//Renvoie l'exécution API au client
    }

    function replaceIdUrl($url) : string {       
        $replaceId = preg_replace($this->motif, $this->remplacement, $url);
        return $replaceId;
    }

    function getParameters($url) : Array{
         //Stocke les id glissées dnas l'url-
         $params = null;
         if (preg_match_all($this->motif, $url, $params)) {
             $params = $params[1];
         }
         return $params;
    }
}
?>
