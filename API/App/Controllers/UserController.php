<?php

namespace Controllers;
use PDO;

/**
 * Gère les intéractions nécessaires avec la table de la BDD des utilisateurs de l'application
 */
class UserController extends Controller{

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

    function setUser($requestBody) {
        if( $requestBody == null) {
            throw new \Exception("Le requestBody est null");
        }
        else {
            $pdo = $this->getDB()->getPDO();
            $query = "SELECT * FROM utilisateurs WHERE login = :login";
            $check = $pdo->prepare($query);
            $check->setFetchMode(PDO::FETCH_ASSOC);
            $check->execute(['login' => $requestBody->{"mail"}]);
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

    function recoveryUser($requestBody) {

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
}