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
        ob_start(); // Start output buffering
        $uriReplaceId = $this->replaceIdUrl($uri);
        if (array_key_exists($_SERVER['REQUEST_METHOD'] . $uriReplaceId, $this->routes)) {
            $controller = $this->routes[$_SERVER['REQUEST_METHOD'] . $uriReplaceId]['controller'];
            $action = $this->routes[$_SERVER['REQUEST_METHOD'] . $uriReplaceId]['action'];
            $rightEndpoint = $this->routes[$_SERVER['REQUEST_METHOD'] . $uriReplaceId]['right'];

            $params = $this->getParameters($uri);
            $requestBody = file_get_contents('php://input');
            $dataJson = json_decode($requestBody);
            $controller = new $controller($requestBody, $rightEndpoint, $params);

            if ($controller->isRigth()) {
                $controller->$action($dataJson, $params[0]);
            } else {
                echo json_encode(["error" => true, "message" => "Vous n'avez pas les droits nécessaires pour exécuter cette action."]);
            }
        } else {
            echo json_encode(["error" => true, "message" => "Vous êtes dans un endroit qui n'existe pas."]);
        }
        ob_end_flush(); // Send the output buffer and turn off output buffering
    }

    function replaceIdUrl($url) : string {       
        return preg_replace($this->motif, $this->remplacement, $url);
    }

    function getParameters($url) : Array{
        $params = null;
        if (preg_match_all($this->motif, $url, $params)) {
            $params = $params[1];
        }
        return $params;
    }
}
?>
