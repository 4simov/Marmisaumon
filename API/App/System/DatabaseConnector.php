<?php

namespace System;
use PDO;

class DatabaseConnector {
    public $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=marmiton", "root", "");
            echo'Connexion établie';
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}