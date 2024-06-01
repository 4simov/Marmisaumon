<?php
use App;
use Controllers;

$router = new App\Router();

$router->addRoute('POST', '/login', Controllers\UserController::class, 'getUserByEmail');
$router->addRoute('POST', '/inscription', Controllers\UserController::class, 'getInscription');
$router->addRoute('GET', '/readUser', Controllers\UserController::class, 'readUser');
$router->addRoute('PUT', '/updateUser', Controllers\UserController::class, 'updateUser');
$router->addRoute('PUT', '/updatePassword', Controllers\UserController::class, 'updatePassword');
$router->addRoute('DELETE', '/deleteUser', Controllers\UserController::class, 'deleteUser');