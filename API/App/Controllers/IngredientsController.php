<?php

namespace Controllers;
use PDO;
use System\DatabaseConnector;

/**
 * Gère les interactions nécessaires avec la table de la BDD des utilisateurs de l'application
 */
class IngredientsController extends Controller {
    private $conn;
    private $table_name = "ingredient";

    /**
     * On recherche tous les ingrédients existant dans la table correspondant à la variable $table_name
     * @param $input -> par défaut c'est une chaîne vide mais l'utilisateur peut écrire un chaîne de caractère pour affiner la liste d'ingrédients
     */
    function getIngredients($input) {
        //Attribue la variable intitulée 'recherche' situé à la racine de l'objet Json => { "recherche" : "blabla" }. "??"
        $recherche = $input->{'recherche'} ?? '';// "??" applique ce qui est à droite si ce qui est à gauche est null

        //préparation du Query pour manipuler la table
        $query = "SELECT * FROM " . $this->table_name. " WHERE nom LIKE '%" . $recherche . "%';";
        //Récupération de la connection à la base de donnée pour y inscrire le Query
        $cmd = $this->getDB()->getPDO()->prepare($query);
        //exécution du query 
        $cmd->execute();

        //transcrit le résultat sous forme de tableau d'éléments => "fetchAll" pour récupérer tous les éléments, "fetch" seul renvoie le premier élément du tableau
        $row = $cmd->fetchAll(PDO::FETCH_ASSOC);
        
        //renvoie le tableau d'ingrédients en réponse au client sous forme de json => un tableau vide en cas d'ingrédient inexistant
        echo json_encode($row);
    }

    /**
     * On créé un Ingrédient
     * @param $dataJson => le json doit comporter au moins l'élément: "nom"; "description" et "image" sont optionnels
     */
    function setIngredient($dataJson) {
        //On récupère les différentes la variable voulue dans le Json
        $nom = $dataJson->{'nom'} ?? null;
        $img = $dataJson->{'image'} ?? null;
        $description = $dataJson->{'description'} ?? null;

        if($nom != null) {
            //Ici on veut des variables dynamique pour créer la ligne de la table => l'intitulé de la colonne de la table "=:" la variable dynamique à définir avec "biendParam
            $query = "INSERT INTO ingredient SET nom=:nom, image =:image, description =:description;";
            $cmd = $this->getPDO()->prepare($query);
            $cmd->bindParam(":nom", $nom);
            $cmd->bindParam(":image", $img);
            $cmd->bindParam(":description", $description);
            $cmd->execute();

            echo json_encode(["success" => true, "message" => "Ingredient créé avec succès."]);
        } else {
            echo json_encode(["error" => true, "message" => "Les données de la requête sont incomplètes."]);
        }
    }
}
