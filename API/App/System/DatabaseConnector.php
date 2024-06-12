<?php

namespace System;
use PDO;

/**
 * 
 */
class DatabaseConnector {
    /**
     * Classe permettant d'interagir avec la base de donnée grâce aux paramètres inscrits dans son constructeur
     */
    protected static $pdo;

    public function __construct() {
        try {
            self::$pdo = new PDO("mysql:host=localhost;dbname=marmisaumon", "root", "root");
            // echo "Connexion à la base de données réussie.\n";
        }
        catch (PDOException $e) {
            echo "Erreur de connexion à la base de données : " . $e->getMessage() . "\n";
        }
    }

    public static function getPDO() {
        return self::$pdo;
    }

    /**
     * Génère les tables manquantes sur la BDD
     */
    public static function InitDatabase() {
        $tables = [
            "Role" => "CREATE TABLE Role (
                Id_Role INT PRIMARY KEY AUTO_INCREMENT,
                Type VARCHAR(50) NOT NULL
            )",
            "Utilisateur" => "CREATE TABLE Utilisateur (
                IdUtilisateur INT PRIMARY KEY AUTO_INCREMENT,
                Mail VARCHAR(50) NOT NULL UNIQUE,
                Pseudo VARCHAR(25) NOT NULL,
                Password VARCHAR(300) NOT NULL,
                Token VARCHAR(255),
                Avatar BLOB,
                Description VARCHAR(1000),
                IdRole INT NOT NULL,
                FOREIGN KEY (IdRole) REFERENCES Role(Id_Role)
            )",
            "Recette" => "CREATE TABLE Recette (
                IdRecette INT PRIMARY KEY AUTO_INCREMENT,
                PhotoPresentation BLOB NOT NULL,
                Titre VARCHAR(100) NOT NULL,
                DateCreation DATE NOT NULL,
                DateMiseAJour DATE,
                TempsPreparation INT NOT NULL,
                IdUtilisateur INT NOT NULL,
                FOREIGN KEY (IdUtilisateur) REFERENCES Utilisateur(IdUtilisateur)
            )",
            "Tag" => "CREATE TABLE Tag (
                IdTag INT PRIMARY KEY AUTO_INCREMENT,
                Libelle VARCHAR(50) NOT NULL
            )",
            "TagRecette" => "CREATE TABLE TagRecette (
                IdTagRecette INT PRIMARY KEY AUTO_INCREMENT,
                IdRecette INT NOT NULL,
                IdTag INT NOT NULL,
                FOREIGN KEY (IdRecette) REFERENCES Recette(IdRecette),
                FOREIGN KEY (IdTag) REFERENCES Tag(IdTag)
            )",
            "Ustensile" => "CREATE TABLE Ustensile (
                IdUstensile INT PRIMARY KEY AUTO_INCREMENT,
                Nom VARCHAR(50) NOT NULL,
                Image BLOB,
                Description VARCHAR(250)
            )",
            "UstensileRecette" => "CREATE TABLE UstensileRecette (
                IdRecette INT,
                IdUstensile INT,
                Id INT PRIMARY KEY AUTO_INCREMENT,
                FOREIGN KEY (IdRecette) REFERENCES Recette(IdRecette),
                FOREIGN KEY (IdUstensile) REFERENCES Ustensile(IdUstensile)
            )",
            "Etapes" => "CREATE TABLE Etapes (
                IdEtapes INT PRIMARY KEY AUTO_INCREMENT,
                Description TEXT NOT NULL,
                Ordre TINYINT NOT NULL
            )",
            "EtapeRecette" => "CREATE TABLE EtapeRecette (
                IdEtapeRecette INT PRIMARY KEY AUTO_INCREMENT,
                IdRecette INT,
                IdEtapes INT,
                FOREIGN KEY (IdRecette) REFERENCES Recette(IdRecette),
                FOREIGN KEY (IdEtapes) REFERENCES Etapes(IdEtapes)
            )",
            "Ingredient" => "CREATE TABLE Ingredient (
                IdIngredient INT PRIMARY KEY AUTO_INCREMENT,
                Nom VARCHAR(50) NOT NULL,
                Image BLOB,
                Description VARCHAR(250)
            )",
            "IngredientRecette" => "CREATE TABLE IngredientRecette (
                IdIngredientRecette INT PRIMARY KEY AUTO_INCREMENT,
                IdRecette INT,
                IdIngredient INT,
                FOREIGN KEY (IdRecette) REFERENCES Recette(IdRecette),
                FOREIGN KEY (IdIngredient) REFERENCES Ingredient(IdIngredient)
            )",
            "Avis" => "CREATE TABLE Avis (
                IdAvis INT PRIMARY KEY AUTO_INCREMENT,
                Note INT NOT NULL CHECK (Note BETWEEN 1 AND 5),
                Commentaire VARCHAR(1000)
            )",
            "AvisRecette" => "CREATE TABLE AvisRecette (
                IdAvisRecette INT PRIMARY KEY AUTO_INCREMENT,
                IdRecette INT,
                IdAvis INT,
                IdUtilisateur INT,
                FOREIGN KEY (IdRecette) REFERENCES Recette(IdRecette),
                FOREIGN KEY (IdAvis) REFERENCES Avis(IdAvis),
                FOREIGN KEY (IdUtilisateur) REFERENCES Utilisateur(IdUtilisateur)
            )"
        ];

        foreach ($tables as $table => $createQuery) {
            // echo "Vérification de l'existence de la table '$table'...\n";
            try {
                self::$pdo->query("SELECT 1 FROM $table LIMIT 1");
               // echo "La table '$table' existe déjà.\n";
            } catch (PDOException $e) {
                // echo "La table '$table' n'existe pas. Création de la table...\n";
                if (self::$pdo->exec($createQuery) !== false) {
                   echo "Table '$table' creee avec succès.\n";
                } else {
                    echo "Erreur lors de la creation de la table '$table'.\n";
                }
            }
        }

        // Insert default roles
        self::insertDefaultRoles();
    }

    private static function insertDefaultRoles() {
        $roles = [
            ['Id_Role' => 1, 'Type' => 'Invite'],
            ['Id_Role' => 2, 'Type' => 'Utilisateur'],
            ['Id_Role' => 3, 'Type' => 'Moderateur'],
            ['Id_Role' => 4, 'Type' => 'Administrateur']
        ];

       // echo "Insertion des rôles par défaut dans la table 'Role'...\n";
        foreach ($roles as $role) {
            $stmt = self::$pdo->prepare("INSERT IGNORE INTO Role (Id_Role, Type) VALUES (:Id_Role, :Type)");
            $stmt->bindParam(':Id_Role', $role['Id_Role']);
            $stmt->bindParam(':Type', $role['Type']);
            if ($stmt->execute()) {
                echo json_encode(['success'=> 'success_db']);
            } else {
                echo "Erreur lors de l'insertion du rôle '{$role['Type']}'.\n";
            }
        }
    }
}
?>
