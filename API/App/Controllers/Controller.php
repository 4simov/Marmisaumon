<?php

namespace Controllers;
use System;
use System\DatabaseConnector;

/**
 * Classe mère dont hériteront tous les controlleurs de l'application
 */
class Controller {
    public $db;
    public function __construct() {
        $this->db = new DatabaseConnector();
    }
}