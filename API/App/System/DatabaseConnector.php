<?php

namespace System;
use PDO;

class DatabaseConnector {
    protected $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=marmiton", "root", "");
            echo'Connexion établie';
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function getPDO() {
        return $this->pdo;
    }
}