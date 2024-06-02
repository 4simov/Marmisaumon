<?php
use App\Router;
use Controllers\UserController;
use MyEnum\HttpEnum;
// Crée une instance du routeur
$router = new Router();

// Ajoutez les routes avec les méthodes HTTP appropriées
$router->addRoute(HttpEnum::GET->value .'/utilisateur', UserController::class, 'getUserByEmail');
$router->addRoute(HttpEnum::POST->value . '/utilisateur', UserController::class, 'getInscription');
$router->addRoute(HttpEnum::GET->value . '/utilisateur/profile', UserController::class, 'readUser');
$router->addRoute(HttpEnum::PUT->value . '/updateUser', UserController::class, 'updateUser');
$router->addRoute(HttpEnum::PUT->value . '/updatePassword', UserController::class, 'updatePassword');
$router->addRoute(HttpEnum::DELETE->value . '/deleteUser', UserController::class, 'deleteUser');
