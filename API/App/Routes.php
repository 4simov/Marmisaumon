<?php
use App;
use Controllers;

$router = new App\Router();

$router->addRoute('/login', Controllers\UserController::class, 'getUserByEmail');
$router->addRoute('/inscription', Controllers\UserController::class, 'getInscription');