<?php
use App\Router;
use Controllers\UserController;
use MyEnum\HttpEnum;
use MyEnum\RolesEnum;
// Crée une instance du routeur
$router = new Router();

// Ajoutez les routes avec les méthodes HTTP appropriées
$router->addRoute(HttpEnum::GET->value .'/utilisateur', UserController::class, 'getUserByEmail', RolesEnum::INVITE);
$router->addRoute(HttpEnum::POST->value . '/utilisateur', UserController::class, 'getInscription', RolesEnum::INVITE);
$router->addRoute(HttpEnum::GET->value . '/utilisateur/profile', UserController::class, 'readUser', RolesEnum::INVITE);
$router->addRoute(HttpEnum::PUT->value . '/updateUser', UserController::class, 'updateUser', RolesEnum::UTILISATEUR);
$router->addRoute(HttpEnum::PUT->value . '/updatePassword', UserController::class, 'updatePassword', RolesEnum::UTILISATEUR);
$router->addRoute(HttpEnum::DELETE->value . '/deleteUser', UserController::class, 'deleteUser', RolesEnum::UTILISATEUR);

//Recettes
$router->addRoute(HttpEnum::GET->value . '/recette', UserController::class, 'updateUser', RolesEnum::INVITE);
$router->addRoute(HttpEnum::POST->value . '/recette', UserController::class, 'updateUser', RolesEnum::UTILISATEUR);
$router->addRoute(HttpEnum::DELETE->value . '/recette', UserController::class, 'updateUser', RolesEnum::UTILISATEUR);
