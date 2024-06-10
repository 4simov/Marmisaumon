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
    private $pdo;
    protected $isValideToken = false;
    protected $rightChecker;
    protected $params;

    /**
     * @param params tableau réunissant par ordre de lecture les différents nombbre dans la requête
     * @param request ensemble du contenu du body de la request sous format JSON
     * @param roleMinimun id correspond aux niveau de droits minimum à avoir pour accéder au controller
     */
    public function __construct($request, $roleMinimun, $params) {
        //$right = new MiddlewareHome\Right();
        $this->rightChecker = new Right();
        $this->params = $params;
        $this->db = new DatabaseConnector();
        $this->isValideToken = $this->rightChecker::rightChecker($this->getDB()->getPDO(), $roleMinimun);
    }
    
    public function getDB() {
        return $this->db;
    }

    public function getPDO() {
        return $this->db->getPDO();
    }
    public function isRigth() {
        return $this->isValideToken;
    }

    public function getParams() {
        return $this->params;
    }
}