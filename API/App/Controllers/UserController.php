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
     * $request->{nomDeLaVariableVoulue} => renvoie la donnée souhaité
     */
    function login($dataJSON = null){
        if( $dataJSON == null) {
            throw new \Exception("Le requestBody est null");
        }
        else {
            $query = "SELECT * FROM  " . $this->table_name . " WHERE mail = :mail AND password = :password";
            $cmd = $this->getDB()->getPDO()->prepare($query);
            $cmd->execute(['mail' => $dataJSON->{"mail"},
                            'password' => $dataJSON->{"password"}]);

            
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            if(!isset($row)) {
                $erreur= json_encode(['error' => false, 'message' => 'mauvais identifiants de connexion']);
                echo $erreur;
            }
            else {
                $token = $this->generateGuid();
                $data = ['token' => $token];
                //stockage dans la base du nouveau token
                $sql = "UPDATE  " . $this->table_name . " SET token = :token WHERE mail = :mail";
                $stmt =  $this->getDB()->getPDO()->prepare($sql);
                $stmt->execute(['token' => $token, 'mail' => $dataJSON->{"mail"}]);
                echo json_encode($data);
                //Réponse=>renvoyer un nouveau token au client et l'inscrit dans la BDD ( dans les cookies ? )
            }
        }
    }

    /* Inscription d'un utilisateur
     * @param $requestBody => correspond au body/json que doit contenir la requête
     */
    public function Inscription($dataJSON) {
        if (isset($dataJSON->{"mail"}) && isset($dataJSON->{"password"})) {
            // Vérifie si l'email existe déjà
            $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE Mail = :Mail";
            $cmd = $this->getPDO()->prepare($query);
            $cmd->bindParam(":Mail", $dataJSON->{'mail'});
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);
            var_dump($row);
            if (isset($row)) {
                echo json_encode(['error' => false, 'message' => 'Un compte avec cet e-mail ' . $dataJSON->{"mail"} .' existe déjà.']);
            } else {
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

            if($cmd->execute()) {
                echo json_encode(["success" => true, "message" => "Inscription réussie."]);
                // Appel de la fonction d'envoi d'email après l'inscription réussie
                $this->sendRegistrationEmail($dataJSON->{'mail'}, $dataJSON->{'name'});
            }
            else {
                echo json_encode(["error" => false, "message" => "Impossible de créer l'utilisateur."]);
            }
        } else {
            echo json_encode(["error" => false, "message" => "Données manquantes."]);
        }
    }

    function getUserById($requestBody) {
        if( $requestBody == null) {
            throw new \Exception("Le requestBody est null");
        }
        else {
            $pdo = $this->getDB()->getPDO();
            $query = "SELECT * FROM " . $this->table_name ." WHERE IdUtilisateur = :login";
            $check = $pdo->prepare($query);
            $check->setFetchMode(PDO::FETCH_ASSOC);
            $check->execute(['login' => $this->params[0]]);
            $user = $check->fetchAll();
            if($user < 1) {
                $erreur= "-x>Pas d'utilisateur à cette id";
                echo json_encode($erreur);
            }
            else {
                echo json_encode($user[0]["Mail"]);
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
                echo json_encode(["message" => "Utilisateur non trouvé."]);
            }
        } else {
            echo json_encode(["message" => "ID utilisateur manquant."]);
        }
    }

    // Mettre à jour les informations d'un utilisateur
    public function updateUser($requestBody) {
        $data = json_decode($requestBody, true);

        if (isset($data['IdUtilisateur']) && isset($data['Mail']) && isset($data['Pseudo']) && isset($data['IdRole'])) {
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
                echo json_encode(["success" => false, "message" => "Impossible de mettre à jour l'utilisateur."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Données manquantes."]);
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
    
    public function getRole($requestBody) {
        //Attribue la variable intitulée 'recherche' situé à la racine de l'objet Json => { "recherche" : "blabla" }. "??"
        $recherche = $input->{'recherche'} ?? '';// "??" applique ce qui est à droite si ce qui est à gauche est null
        $token = getallheaders()['Authorization'] ?? null;
        //préparation du Query pour manipuler la table
        $query = "SELECT * FROM ". $this->table_name . " WHERE Token = :token";
        //Récupération de la connection à la base de donnée pour y inscrire le Query
        $cmd = $this->getDB()->getPDO()->prepare($query);
        $cmd->bindParam(":token", $token);
        //exécution du query 
        $cmd->execute();
        
        $row = $cmd->fetch(PDO::FETCH_ASSOC);

        if(empty($row)) {
            echo json_encode(0);
        } else {
                echo json_encode($row["IdRole"]);
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
            $mail->Body    = 'Merci de vous êtres enregistrer sur Marmisaumon, ' . $userName . '!';
            $mail->AltBody = 'Merci de vous êtres enregistrer sur Marmisaumon, ' . $userName . '!';

            $mail->send();
            echo 'Message envoyé';
        } catch (Exception $e) {
            echo "Error, mail pas envoyé: {$mail->ErrorInfo}";
        }
    }
}
?>

