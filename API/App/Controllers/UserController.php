<?php

namespace Controllers;
use MiddlewareHome\Right;
use MyEnum\RolesEnum;
use PDO;
use System\DatabaseConnector;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

/**
 * Gère les interactions nécessaires avec la table de la BDD des utilisateurs de l'application
 */
class UserController extends Controller {
    private $table_name = "Utilisateur";
    
    /**
     * 
     * @param $dataJSON => correspond au body/json que doit contenir la requête
     * $request->{nomDeLaVariableVoulue} => renvoie la donnée souhaitée
     */
    function login($dataJSON = null){
        if ($dataJSON == null) {
            echo json_encode(["error" => true, "message" => "Le requestBody est null"]);
            return;
        } else {
            $query = "SELECT * FROM " . $this->table_name . " WHERE mail = :mail AND password = :password";
            $cmd = $this->getDB()->getPDO()->prepare($query);
            $cmd->execute(['mail' => $dataJSON->{"mail"}, 'password' => $dataJSON->{"password"}]);

            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            if (empty($row)) {
                echo json_encode(['error' => true, 'message' => 'Mauvais identifiants de connexion']);
            } else {
                $token = $this->generateGuid();
                $data = ['token' => $token];
                
                // Stockage dans la base du nouveau token
                $sql = "UPDATE " . $this->table_name . " SET token = :token WHERE mail = :mail";
                $stmt = $this->getDB()->getPDO()->prepare($sql);
                $stmt->execute(['token' => $token, 'mail' => $dataJSON->{"mail"}]);

                echo json_encode($data);
            }
        }
    }

    /**
     * Inscription d'un utilisateur
     * @param $dataJSON => correspond au body/json que doit contenir la requête
     */
    public function Inscription($dataJSON) {
        if (isset($dataJSON->{"mail"}) && isset($dataJSON->{"password"})) {
            // Vérifie si l'email existe déjà
            $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE Mail = :Mail";
            $cmd = $this->getPDO()->prepare($query);
            $cmd->bindParam(":Mail", $dataJSON->{'mail'});
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);

            if ($row['count'] > 0) {
                echo json_encode(['error' => true, 'message' => 'Un compte avec cet e-mail ' . $dataJSON->{"mail"} . ' existe déjà.']);
                return;
            }

            // Crée un nouvel utilisateur
            $query = "INSERT INTO " . $this->table_name . " SET Mail=:Mail, Password=:Password, Pseudo=:Pseudo, IdRole=:IdRole";
            $cmd = $this->getPDO()->prepare($query);

            // Hashage du mot de passe avant l'enregistrement
            $password_hash = password_hash($dataJSON->{'password'}, PASSWORD_BCRYPT);
            $role = RolesEnum::UTILISATEUR->value;
            
            // Bind des valeurs
            $cmd->bindParam(":Mail", $dataJSON->{'mail'});
            $cmd->bindParam(":Password", $password_hash);
            $cmd->bindParam(":Pseudo", $dataJSON->{'name'});
            $cmd->bindParam(":IdRole", $role);

            if ($cmd->execute()) {
                // Appel de la fonction d'envoi d'email après l'inscription réussie
                $this->sendRegistrationEmail($dataJSON->{'mail'}, $dataJSON->{'name'});
                echo json_encode(["success" => true, "message" => "Inscription réussie."]);
            } else {
                echo json_encode(["error" => true, "message" => "Impossible de créer l'utilisateur."]);
            }
        } else {
            echo json_encode(["error" => true, "message" => "Données manquantes."]);
        }
    }

    function getUserById($requestBody) {
        if ($requestBody == null) {
            echo json_encode(["error" => true, "message" => "Le requestBody est null"]);
            return;
        } else {
            $pdo = $this->getDB()->getPDO();
            $query = "SELECT * FROM " . $this->table_name . " WHERE IdUtilisateur = :login";
            $check = $pdo->prepare($query);
            $check->setFetchMode(PDO::FETCH_ASSOC);
            $check->execute(['login' => $this->params[0]]);
            $user = $check->fetchAll();
            if (empty($user)) {
                echo json_encode(["error" => true, "message" => "Pas d'utilisateur avec cet ID"]);
            } else {
                echo json_encode($user[0]);
            }
        }
    }

    // Lire les informations d'un utilisateur par ID
    public function readUser($requestBody) {
        $data = json_decode($requestBody, true);
        if (isset($data['IdUtilisateur'])) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE IdUtilisateur = ?";
            $stmt = $this->getPDO()->prepare($query);
            $stmt->bindParam(1, $data['IdUtilisateur']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode($user);
            } else {
                echo json_encode(["error" => true, "message" => "Utilisateur non trouvé."]);
            }
        } else {
            echo json_encode(["error" => true, "message" => "ID utilisateur manquant."]);
        }
    }

    // Mettre à jour les informations d'un utilisateur
    public function updateUser($requestBody) {
        $data = json_decode($requestBody, true);

        if (!empty($data['IdUtilisateur']) && !empty($data['Mail']) && !empty($data['Pseudo']) && !empty($data['IdRole'])) {
            $query = "UPDATE " . $this->table_name . " SET Mail = :Mail, Pseudo = :Pseudo, IdRole = :IdRole WHERE IdUtilisateur = :IdUtilisateur";
            $stmt = $this->getPDO()->prepare($query);

            // Bind des valeurs
            $stmt->bindParam(":IdUtilisateur", $data['IdUtilisateur']);
            $stmt->bindParam(":Mail", $data['Mail']);
            $stmt->bindParam(":Pseudo", $data['Pseudo']);
            $stmt->bindParam(":IdRole", $data['IdRole']);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Utilisateur mis à jour avec succès."]);
            } else {
                echo json_encode(["error" => true, "message" => "Impossible de mettre à jour l'utilisateur."]);
            }
        } else {
            echo json_encode(["error" => true, "message" => "Données manquantes."]);
        }
    }

    function generateGuid() {
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            assert(strlen($data) == 16);

            $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // Version 4
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // Variant

            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        } else {
            mt_srand((double)microtime() * 10000);
            $charid = strtolower(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"

            $uuid = substr($charid,  0, 8) . $hyphen
                  . substr($charid,  8, 4) . $hyphen
                  . substr($charid, 12, 4) . $hyphen
                  . substr($charid, 16, 4) . $hyphen
                  . substr($charid, 20, 12);

            return $uuid;
        }
    }

    // Modifier le mot de passe d'un utilisateur
    public function updatePassword($requestBody) {
        $data = json_decode($requestBody, true);

        if (!empty($data['IdUtilisateur']) && !empty($data['Password'])) {
            $query = "UPDATE " . $this->table_name . " SET Password = :Password WHERE IdUtilisateur = :IdUtilisateur";
            $stmt = $this->getPDO()->prepare($query);

            // Hashage du nouveau mot de passe
            $password_hash = password_hash($data['Password'], PASSWORD_BCRYPT);

            // Bind des valeurs
            $stmt->bindParam(":IdUtilisateur", $data['IdUtilisateur']);
            $stmt->bindParam(":Password", $password_hash);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Mot de passe mis à jour avec succès."]);
            } else {
                echo json_encode(["error" => true, "message" => "Impossible de mettre à jour le mot de passe."]);
            }
        } else {
            echo json_encode(["error" => true, "message" => "Données manquantes."]);
        }
    }

    // Supprimer un utilisateur
    public function deleteUser($requestBody) {
        $data = json_decode($requestBody, true);
        if (isset($data['IdUtilisateur'])) {
            $query = "DELETE FROM " . $this->table_name . " WHERE IdUtilisateur = ?";
            $stmt = $this->getPDO()->prepare($query);
            $stmt->bindParam(1, $data['IdUtilisateur']);

            if ($stmt->execute()) {
                echo json_encode(["success" => true, "message" => "Utilisateur supprimé avec succès."]);
            } else {
                echo json_encode(["error" => true, "message" => "Impossible de supprimer l'utilisateur."]);
            }
        } else {
            echo json_encode(["error" => true, "message" => "ID utilisateur manquant."]);
        }
    }
    
    public function getRole($requestBody) {
        $data = json_decode($requestBody, true);
        $token = getallheaders()['Authorization'] ?? null;

        if ($token) {
            $query = "SELECT * FROM " . $this->table_name . " WHERE Token = :token";
            $cmd = $this->getDB()->getPDO()->prepare($query);
            $cmd->bindParam(":token", $token);
            $cmd->execute();

            $row = $cmd->fetch(PDO::FETCH_ASSOC);

            if (empty($row)) {
                echo json_encode(["error" => true, "message" => "Token invalide"]);
            } else {
                echo json_encode(["IdRole" => $row["IdRole"]]);
            }
        } else {
            echo json_encode(["error" => true, "message" => "Token manquant"]);
        }
    }

    function sendRegistrationEmail($userEmail, $userName) {
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'marmisaumoncube3@gmail.com'; // Votre adresse e-mail Gmail
            $mail->Password = '*CuBe3cEsI*'; // Votre mot de passe Gmail ou mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinataires
            $mail->setFrom('marmisaumoncube3@gmail.com', 'Equipe Administrative Marmisaumon');
            $mail->addAddress($userEmail, $userName);

            // Contenu de l'e-mail
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenue sur Marmisaumon !';
            $mail->Body    = 'Merci de vous être enregistré sur Marmisaumon, ' . $userName . '!';
            $mail->AltBody = 'Merci de vous être enregistré sur Marmisaumon, ' . $userName . '!';

            $mail->send();
            // Suppression de l'écho pour éviter de mélanger avec les réponses JSON
        } catch (Exception $e) {
            // Log de l'erreur si besoin, sans écho
        }
    }
}
?>
