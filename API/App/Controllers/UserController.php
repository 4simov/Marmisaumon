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
}