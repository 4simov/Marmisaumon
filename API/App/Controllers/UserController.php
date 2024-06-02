<?php

namespace Controllers;
use MiddlewareHome\Right;
use PDO;
use System\DatabaseConnector;

/**
 * Gère les interactions nécessaires avec la table de la BDD des utilisateurs de l'application
 */
class UserController extends Controller {
    private $table_name = "utilisateur";
    /**
     * 
     * @param $dataJSON => correspond au body/json que doit contenir la requête
     * $request->{nomDeLaVariableVoulue} => renvoie la donnée souhaité
     */
    function getUserByEmail($dataJSON = null){
        if( $dataJSON == null) {
            throw new \Exception("Le requestBody est null");
        }
        else {
            $query = "SELECT * FROM utilisateurs WHERE login = :login AND pass = :pass";
            $check = $this->getDB()->getPDO()->prepare($query);
            $check->setFetchMode(PDO::FETCH_ASSOC);
            $check->execute(['login' => $dataJSON->{"email"},
                            'pass' => $dataJSON->{"password"}]);

            if($check->rowCount() == 0) {
                $erreur= "#Réponse <br> mauvais login ou mot de passe";
                echo $erreur;
            }
            else {
                $token = $this->generateGuid();
                $data = [ 'token' => $token];
                //stockage dans la base du nouveau token
                $sql = "UPDATE utilisateurs SET token = :token WHERE login = :login";
                $stmt =  $this->getDB()->getPDO()->prepare($sql);
                $stmt->execute(['token' => $token, 'login' => $dataJSON->{"email"}]);

                header('Content-type: application/json');
                echo json_encode( $data );
                //Réponse=>renvoyer un nouveau token au client et l'inscrit dans la BDD ( dans les cookies ? )
            }
        }
    }

    /* Inscription d'un utilisateur
     * @param $requestBody => correspond au body/json que doit contenir la requête
     */
    public function getInscription($dataJSON) {
        if ($dataJSON->token != null && isset($dataJSON->{"email"}) && isset($dataJSON->{"password"})) {
            // Vérifie si l'email existe déjà
            $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE Mail = :Mail";
            $cmd = $this->getPDO()->prepare($query);
            $cmd->bindParam(":Mail", $data['Mail']);
            $cmd->execute();
            $row = $cmd->fetch(PDO::FETCH_ASSOC);

            if ($row['count'] > 0) {
                echo json_encode(['success' => false, 'message' => 'Un compte avec cet e-mail existe déjà.']);
                return;
            }

            // Crée un nouvel utilisateur
            $query = "INSERT INTO " . $this->table_name . " SET Mail=:Mail, Password=:Password, Pseudo=:Pseudo, IdRole=:IdRole";
            $cmd = $this->getPDO()->prepare($query);

            // Hashage du mot de passe avant l'enregistrement
            $password_hash = password_hash($dataJSON->{'password'}, PASSWORD_BCRYPT);

            $role = 1;
            // Bind des valeurs
            $cmd->bindParam(":Mail", $dataJSON->{'email'});
            $cmd->bindParam(":Password", $password_hash);
            $cmd->bindParam(":Pseudo", $dataJSON->{'name'});
            $cmd->bindParam(":IdRole", $role);

            if ($cmd->execute()) {
                echo json_encode(["success" => true, "message" => "Inscription réussie."]);
            } else {
                echo json_encode(["success" => false, "message" => "Impossible de créer l'utilisateur."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Données manquantes."]);
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
                echo $erreur;
            }
            else {
                echo json_encode($user[0]["Mail"]);
            }
        }
    }

    function setUser($requestBody) {
        if( $requestBody == null) {
            throw new \Exception("Le requestBody est null");
        }
        else {
            $pdo = $this->getDB()->getPDO();
            $query = "SELECT * FROM " . $this->table_name ." WHERE IdUtilisateur = :login";
            $check = $pdo->prepare($query);
            $check->setFetchMode(PDO::FETCH_ASSOC);
            $check->execute(['login' => $this->params[0]]);
            if($check->rowCount() > 0) {
                $erreur= "-x>mail déjà existant";
                echo $erreur;
            }
            else {
                $ins = $pdo->prepare("insert into utilisateurs (nom, prenom, login, pass) values(?,?,?,?)");
                $ins->execute(array(
                    "testNom",
                    "testPrenom",
                    $requestBody->{"mail"},
                    $requestBody->{"password"},
                ));
                echo "->compte créé";
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
}
?>
