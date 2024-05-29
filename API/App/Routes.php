<?php
use App;
use Controllers;

$router = new App\Router();

$router->addRoute('/login', Controllers\UserController::class, 'getUserByEmail');
$router->addRoute('/createUser', Controllers\UserController::class, 'setUser');