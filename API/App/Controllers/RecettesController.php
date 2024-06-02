<?php

namespace Controllers;
use PDO;
use System\DatabaseConnector;

/**
 * Gère les interactions nécessaires avec la table de la BDD des utilisateurs de l'application
 */
class RecettesController {
    private $conn;
    private $table_name = "recette";

    public function __construct() {
        $database = new DatabaseConnector();
        $this->conn = $database->getPDO();
    }
}