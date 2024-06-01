<?php

namespace Controllers;

use System\DatabaseConnector;
use PDO;

/**
 * Gère les interactions nécessaires avec la table de la BDD des utilisateurs de l'application
 */
class UserController {
    private $conn;
    private $table_name = "utilisateur";

    public function __construct() {
        $database = new DatabaseConnector();
        $this->conn = $database->getPDO();
    }

    /**
     * Inscription d'un utilisateur
     * @param $requestBody => correspond au body/json que doit contenir la requête
     */
    public function getInscription($requestBody) {
        $data = json_decode($requestBody, true);

        if (isset($data['Pseudo']) && isset($data['Mail']) && isset($data['Password']) && isset($data['IdRole'])) {
            // Vérifie si l'email existe déjà
            $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE Mail = :Mail";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":Mail", $data['Mail']);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row['count'] > 0) {
                echo json_encode(['success' => false, 'message' => 'Un compte avec cet e-mail existe déjà.']);
                return;
            }

            // Crée un nouvel utilisateur
            $query = "INSERT INTO " . $this->table_name . " SET Mail=:Mail, Password=:Password, Pseudo=:Pseudo, IdRole=:IdRole";
            $stmt = $this->conn->prepare($query);

            // Hashage du mot de passe avant l'enregistrement
            $password_hash = password_hash($data['Password'], PASSWORD_BCRYPT);

            // Bind des valeurs
            $stmt->bindParam(":Mail", $data['Mail']);
            $stmt->bindParam(":Password", $password_hash);
            $stmt->bindParam(":Pseudo", $data['Pseudo']);
            $stmt->bindParam(":IdRole", $data['IdRole']);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Inscription réussie."]);
            } else {
                echo json_encode(["success" => false, "message" => "Impossible de créer l'utilisateur."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Données manquantes."]);
        }
    }

    // Lire les informations d'un utilisateur par ID
    public function readUser($requestBody) {
        $data = json_decode($requestBody, true);
        if (isset($data['IdUtilisateur'])) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE IdUtilisateur = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $data['IdUtilisateur']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode($user);
            } else {
                echo json_encode(["message" => "Utilisateur non trouvé."]);
            }
        } else {
            echo json_encode(["message" => "ID utilisateur manquant."]);
        }
    }

    // Mettre à jour les informations d'un utilisateur
    public function updateUser($requestBody) {
        $data = json_decode($requestBody, true);

        if (!empty($data['IdUtilisateur']) && !empty($data['Mail']) && !empty($data['Pseudo']) && !empty($data['IdRole'])) {
            $query = "UPDATE " . $this->table_name . " SET Mail = :Mail, Pseudo = :Pseudo, IdRole = :IdRole WHERE IdUtilisateur = :IdUtilisateur";
            $stmt = $this->conn->prepare($query);

            // Bind des valeurs
            $stmt->bindParam(":IdUtilisateur", $data['IdUtilisateur']);
            $stmt->bindParam(":Mail", $data['Mail']);
            $stmt->bindParam(":Pseudo", $data['Pseudo']);
            $stmt->bindParam(":IdRole", $data['IdRole']);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Utilisateur mis à jour avec succès."]);
            } else {
                echo json_encode(["success" => false, "message" => "Impossible de mettre à jour l'utilisateur."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Données manquantes."]);
        }
    }

    // Modifier le mot de passe d'un utilisateur
    public function updatePassword($requestBody) {
        $data = json_decode($requestBody, true);

        if (!empty($data['IdUtilisateur']) && !empty($data['Password'])) {
            $query = "UPDATE " . $this->table_name . " SET Password = :Password WHERE IdUtilisateur = :IdUtilisateur";
            $stmt = $this->conn->prepare($query);

            // Hashage du nouveau mot de passe
            $password_hash = password_hash($data['Password'], PASSWORD_BCRYPT);

            // Bind des valeurs
            $stmt->bindParam(":IdUtilisateur", $data['IdUtilisateur']);
            $stmt->bindParam(":Password", $password_hash);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Mot de passe mis à jour avec succès."]);
            } else {
                echo json_encode(["success" => false, "message" => "Impossible de mettre à jour le mot de passe."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Données manquantes."]);
        }
    }

    // Supprimer un utilisateur
    public function deleteUser($requestBody) {
        $data = json_decode($requestBody, true);
        if (isset($data['IdUtilisateur'])) {
            $query = "DELETE FROM " . $this->table_name . " WHERE IdUtilisateur = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $data['IdUtilisateur']);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Utilisateur supprimé avec succès."]);
            } else {
                echo json_encode(["success" => false, "message" => "Impossible de supprimer l'utilisateur."]);
            }
        } else {
            echo json_encode(["message" => "ID utilisateur manquant."]);
        }
    }
}
?>
