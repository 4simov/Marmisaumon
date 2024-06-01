<?php
use App\Router;
use Controllers\UserController;

// Crée une instance du routeur
$router = new Router();

// Ajoutez les routes avec les méthodes HTTP appropriées
$router->addRoute('POST', '/login', UserController::class, 'getUserByEmail');
$router->addRoute('POST', '/inscription', UserController::class, 'getInscription');
$router->addRoute('GET', '/readUser', UserController::class, 'readUser');
$router->addRoute('PUT', '/updateUser', UserController::class, 'updateUser');
$router->addRoute('PUT', '/updatePassword', UserController::class, 'updatePassword');
$router->addRoute('DELETE', '/deleteUser', UserController::class, 'deleteUser');
