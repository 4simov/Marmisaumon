<?php 
//Ce script permet de vérifier si l'utilisateur a les droit d'utilisation d'un Controller

namespace MiddlewareHome;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Response;
use MyEnum;
use PDO;

/*
    Quand le token n'est plus valide renvoie null, à rediriger vers une page de connexion
*/
class Right {
    static function rightChecker( $request, $pdo, $next) {
        echo "xwdfgsdfhsfghsgswthqt";
        var_dump($request);
        //Converti en un format plus simple à manipuler, le json
        $dataJSON = json_decode($request);
        echo $dataJSON->mail;

        //Récupération du token dans la bdd pour comparer celle du client
        var_dump($dataJSON);
        $query = "SELECT * FROM utilisateurs WHERE login = :login AND token = :token LIMIT 1";
        $check = $pdo->prepare($query);
        $check->execute(['login' => $dataJSON->mail, 'token' => $dataJSON->token]);
        $user = $check->fetchAll();
        var_dump($user);
        foreach($user as $u) {
            if( $dataJSON->token == $u["token"] ) {
                header("Authorization: Bearer " .$u["token"]);
                echo "vous avez trouvé le token ! ";
                var_dump(VisiteursEnum::INVITE);
                $enum = MyEnum\VisiteursEnum->UTILISATEUR;
                return $enum;
                return $request;
            }
        }
        return null;
    }
}
