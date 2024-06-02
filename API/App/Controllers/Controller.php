<?php

namespace Controllers;
use MyEnum\RolesEnum;
use MiddlewareHome\Right;
use System;
use System\DatabaseConnector;
use GuzzleHttp\Psr7\Response;
use MiddlewareHome;

/**
 * Classe mère dont hériteront tous les controlleurs de l'application
 */
class Controller {
    protected $db;
    protected $isValideToken = false;
    protected $rightChecker;

    public function __construct($request, $roleMinimun) {
        //$right = new MiddlewareHome\Right();
        $this->rightChecker = new Right();
        $this->db = new DatabaseConnector();
        $this->isValideToken = $this->rightChecker::rightChecker($this->getDB()->getPDO(), $roleMinimun);
    }
    
    public function getDB() {
        return $this->db;
    }

    public function isRigth() {
        return $this->isValideToken;
    }
}