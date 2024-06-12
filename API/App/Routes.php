<?php
use App\Router;
use Controllers\IngredientsController;
use Controllers\UserController;
use Controllers\RecettesController;
use MyEnum\HttpEnum;
use MyEnum\RolesEnum;

// Crée une instance du routeur
$router = new Router();

// Ajoutez les routes avec les méthodes HTTP appropriées 
$router->addRoute(HttpEnum::GET->value .'/utilisateurs', UserController::class, 'getUserByEmail', RolesEnum::INVITE);
$router->addRoute(HttpEnum::GET->value .'/utilisateurs/id', UserController::class, 'getUserById', RolesEnum::ADMIN);
$router->addRoute(HttpEnum::POST->value . '/utilisateurs', UserController::class, 'Inscription', RolesEnum::INVITE);
$router->addRoute(HttpEnum::POST->value . '/utilisateurs/login', UserController::class, 'login', RolesEnum::INVITE);
$router->addRoute(HttpEnum::GET->value .'/utilisateurs/role', UserController::class, 'getRole', RolesEnum::INVITE);
//$router->addRoute(HttpEnum::GET->value . '/utilisateur/profile', UserController::class, 'readUser', RolesEnum::INVITE);
//$router->addRoute(HttpEnum::PUT->value . '/updateUser', UserController::class, 'updateUser', RolesEnum::UTILISATEUR);
//$router->addRoute(HttpEnum::PUT->value . '/updatePassword', UserController::class, 'updatePassword', RolesEnum::UTILISATEUR);
$router->addRoute(HttpEnum::DELETE->value . '/deleteUser', UserController::class, 'deleteUser', RolesEnum::UTILISATEUR);

//Recettes
$router->addRoute(HttpEnum::GET->value . '/recettes', RecettesController::class, 'getRecette', RolesEnum::INVITE);
$router->addRoute(HttpEnum::GET->value . '/recettes/id/', RecettesController::class, 'getRecetteById', RolesEnum::INVITE);
$router->addRoute(HttpEnum::POST->value . '/recettes', RecettesController::class, '', RolesEnum::UTILISATEUR);
$router->addRoute(HttpEnum::DELETE->value . '/recettes/id/', RecettesController::class, '', RolesEnum::ADMIN);

//Ingrédients
$router->addRoute(HttpEnum::GET->value . '/ingredients', IngredientsController::class, 'getIngredients', RolesEnum::INVITE);
$router->addRoute(HttpEnum::GET->value . '/ingredients/id/', IngredientsController::class, 'getIngredients', RolesEnum::INVITE);
$router->addRoute(HttpEnum::POST->value . '/ingredients', IngredientsController::class, 'setIngredient', RolesEnum::UTILISATEUR);