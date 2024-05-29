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
            //Debug
            echo "#Request data";
            var_dump($dataJSON);

            $query = "SELECT * FROM utilisateurs WHERE login = :login AND pass = :pass";
            $check = $this->getDB()->getPDO()->prepare($query);
            $check->setFetchMode(PDO::FETCH_ASSOC);
            $check->execute(['login' => $dataJSON->{"mail"},
                            'pass' => $dataJSON->{"password"}]);

            if($check->rowCount() == 0) {
                $erreur= "#Réponse <br> mauvais login ou mot de passe";
                echo $erreur;
            }
            else {
                echo 'CONNECTE';
                $data = [ 'token' => 'monToken'];
                //Réponse=>renvoyer un nouveau token au client et l'inscrit dans la BDD ( dans les cookies ? )
                echo "->Réponse du serveur";
                var_dump( $data );
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

}