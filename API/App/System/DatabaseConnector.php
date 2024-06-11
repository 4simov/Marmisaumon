<?php

namespace System;
use PDO;

/**
 * 
 */
class DatabaseConnector {
    /**
     * Classe permettant d'interagir avec la base de donnée grace aux paramètres inscrit dans son constructeur
     */
    protected $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=marmiton", "root", "");
            //$this->pdo = new PDO("mysql:host=localhost;dbname=marmisaumon", "root", "root");
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function getPDO() {
        return $this->pdo;
    }

    /**
     * Génère les tables manquantes sur la BDD
     */
    public function InitDatabase() {
        
    }
}
