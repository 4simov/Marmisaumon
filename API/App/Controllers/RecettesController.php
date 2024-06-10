<?php

namespace Controllers;
use PDO;
use System\DatabaseConnector;

/**
 * Gère les interactions nécessaires avec la table de la BDD des utilisateurs de l'application
 */
class RecettesController extends Controller{
    private $table_name = "recette";

    public function getRecette() {
        //Attribue la variable intitulée 'recherche' situé à la racine de l'objet Json => { "recherche" : "blabla" }. "??"
        $recherche = $input->{'recherche'} ?? '';// "??" applique ce qui est à droite si ce qui est à gauche est null

        //préparation du Query pour manipuler la table
        $query = "SELECT * FROM " . $this->table_name;
        //Récupération de la connection à la base de donnée pour y inscrire le Query
        $cmd = $this->getDB()->getPDO()->prepare($query);
        //exécution du query 
        $cmd->execute();

        //transcrit le résultat sous forme de tableau d'éléments => "fetchAll" pour récupérer tous les éléments, "fetch" seul renvoie le premier élément du tableau
        $row = $cmd->fetchAll(PDO::FETCH_ASSOC);

        //renvoie le tableau d'ingrédients en réponse au client sous forme de json => un tableau vide en cas d'ingrédient inexistant
        echo json_encode($row);
    }

    function getRecetteById($dataJson, $id) {

        //préparation du Query pour manipuler la table
        $query = "SELECT * FROM " . $this->table_name. " WHERE idRecette =:id;";
        //Récupération de la connection à la base de donnée pour y inscrire le Query
        $cmd = $this->getDB()->getPDO()->prepare($query);
        $cmd->bindParam(":id", $id);
        //exécution du query
        $cmd->execute();

        //transcrit le résultat sous forme de tableau d'éléments => "fetchAll" pour récupérer tous les éléments, "fetch" seul renvoie le premier élément du tableau
        $row = $cmd->fetch(PDO::FETCH_ASSOC);
        //Récupération des ingrédients associés à la recette
        $row = array_merge($row, array('ingredients' => $this->getIngredientRecette($id)));
        //Récupération des étapes
        $row = array_merge($row, array('etapes' => $this->getEtapes($id)));
        var_dump($row);
        //renvoie le tableau d'ingrédients en réponse au client sous forme de json => un tableau vide en cas d'ingrédient inexistant
        echo json_encode($row);
    }

    public function setRecette() {

    }

    public function deleteRecette() {
        
    }

    private function getIngredientRecette($idRecette) {

        //préparation du Query pour manipuler la table
        $query = "SELECT * FROM ingredientrecette WHERE idRecette =". $idRecette .";";
        //Récupération de la connection à la base de donnée pour y inscrire le Query
        $cmd = $this->getDB()->getPDO()->prepare($query);
        $cmd->execute();
        $row = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }

    private function getEtapes($id) {
        //préparation du Query pour manipuler la table 
        //$query = "SELECT * FROM etaperecette WHERE idRecette =". $idRecette .";";
        $query ="SELECT * FROM etapes ORDER BY ordre ASC";
        //Récupération de la connection à la base de donnée pour y inscrire le Query
        $cmd = $this->getDB()->getPDO()->prepare($query);
        $cmd->execute();
        $row = $cmd->fetchAll(PDO::FETCH_ASSOC);
        return $row;
    }
}