<?php

namespace Controllers;
use System;
use System\DatabaseConnector;
use GuzzleHttp\Psr7\Response;
use MiddlewareHome;

/**
 * Classe mère dont hériteront tous les controlleurs de l'application
 */
class Controller {
    protected $db;
    protected $isValideToken = true;
    protected $right;

    public function __construct($request) {
        //$right = new MiddlewareHome\Right();
        $this->db = new DatabaseConnector();
        /*if($right::rightChecker($request ,$this->db->getPDO(), new Response())== null) {
            $this->isValideToken = false;
        }else {
            $this->isValideToken = true;
        };*/
    }
    
    public function getDB() {
        return $this->db;
    }

    public function getToken() {
        return $this->isValideToken;
    }
}