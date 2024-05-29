<?php

namespace Controllers;
use PDO;

/**
 * Gère les intéractions nécessaires avec la table de la BDD des utilisateurs de l'application
 */
class UserController extends Controller{

    /**
     * 
     * @param $requestBody => correspond au body/json que doit contenir la requête
     */
    function getUserByEmail($requestBody = null){
        if( $requestBody == null) {
            throw new \Exception("Le requestBody est null");
        }
        else {
            var_dump($requestBody);
            $query = "SELECT * FROM utilisateurs WHERE login = :login AND pass = :pass";
            $check = $this->getDB()->getPDO()->prepare($query);
            $check->setFetchMode(PDO::FETCH_ASSOC);
            $check->execute(['login' => $requestBody->{"mail"},
                            'pass' => $requestBody->{"password"}]);
            if($check->rowCount() == 0) {
                $erreur= "aucun compte correspondant au login";
                echo $erreur;
            }
            else {
                echo 'CONNECTE';
                $data = [ 'token' => 'monToken'];
                header('Content-type: application/json');
                var_dump( $data );
            }
        }
    }

    function setUser($requestBody) {
        if( $requestBody == null) {
            throw new \Exception("Le requestBody est null");
        }
        else {
            var_dump($requestBody);
            $pdo = $this->getDB()->getPDO();
            $query = "SELECT * FROM utilisateurs WHERE login = :login";
            $check = $pdo->prepare($query);
            $check->setFetchMode(PDO::FETCH_ASSOC);
            $check->execute(['login' => $requestBody->{"mail"}]);
            if($check->rowCount() > 0) {
                $erreur= "mail déjà existant";
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
            }
        }
    }

    function recoveryUser($requestBody) {

    }

}