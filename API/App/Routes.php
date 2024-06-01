<?php
use App\Router;
use Controllers\UserController;

// Crée une instance du routeur
$router = new Router();

// Ajoutez les routes avec les méthodes HTTP appropriées
$router->addRoute('GET', '/utilisateur', UserController::class, 'getUserByEmail');
$router->addRoute('POST', '/utilisateur', UserController::class, 'getInscription');
$router->addRoute('GET', '/utilisateur/profile', UserController::class, 'readUser');
$router->addRoute('PUT', '/updateUser', UserController::class, 'updateUser');
$router->addRoute('PUT', '/updatePassword', UserController::class, 'updatePassword');
$router->addRoute('DELETE', '/deleteUser', UserController::class, 'deleteUser');
