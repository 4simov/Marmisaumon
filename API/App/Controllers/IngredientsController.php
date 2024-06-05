<?php

namespace Controllers;
use PDO;
use System\DatabaseConnector;

/**
 * Gère les interactions nécessaires avec la table de la BDD des utilisateurs de l'application
 */
class IngredientsController {
    private $conn;
    private $table_name = "ingredient";
}