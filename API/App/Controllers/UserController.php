<?php

namespace Controllers;

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
    }

    function setUser($requestBody) {

    }

    function recoveryUser($requestBody) {

    }

    public function getInscription($requestBody) {
        // Décode le corps de la requête JSON
        $data = json_decode($requestBody, true);

        // Vérifie que toutes les données nécessaires sont présentes
        if (isset($data['name']) && isset($data['email']) && isset($data['password']) && isset($data['confirm_password'])) {
            // Simule une réponse réussie
            echo json_encode(['success' => true, 'message' => 'Inscription réussie']);
        } else {
            // Retourne une erreur si les données sont manquantes
            echo json_encode(['success' => false, 'message' => 'Données manquantes']);
        }
    }
}


