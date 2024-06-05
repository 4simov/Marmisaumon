<?php 
//Ce script permet de vérifier si l'utilisateur a les droit d'utilisation d'un Controller

namespace MiddlewareHome;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use MyEnum;
use MyEnum\RolesEnum;
use PDO;

/*
    Quand le token n'est plus valide renvoie null, à rediriger vers une page de connexion
*/
class Right {
    static function rightChecker( $pdo, $right) {
        //On récupère l'entrée du token dans le header de la requête
        $token = $all_headers = getallheaders()['Authorization'] ?? null;
        $user = [];
        if($token != null) {
            $query = "SELECT * FROM utilisateur WHERE Token = :token LIMIT 1";
            $check = $pdo->prepare($query);
            $check->execute(['token' => $token]);
            $user = $check->fetchAll(PDO::FETCH_ASSOC);
        }
        $role = RolesEnum::INVITE->value;
        
        if(count($user) > 0) {
            $role = $u["IdRole"];
        }

        if( $right->value <= $role) {
            return true;
        }
        else{
            return false;
        }

    }
}
