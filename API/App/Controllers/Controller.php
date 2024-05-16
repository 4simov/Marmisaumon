<?php

namespace Controllers;
use System;
use System\DatabaseConnector;

/**
 * Classe mère dont hériteront tous les controlleurs de l'application
 */
class Controller {
    protected $db;
    public function __construct() {
        $this->db = new DatabaseConnector();
    }
    public function getDB() {
        return $this->db;
    }
}