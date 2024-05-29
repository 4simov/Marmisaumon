<?php
use App;
use Controllers;
use MyEnum\HttpEnum;

$router = new App\Router();

echo HttpEnum::GET->value;
//Si l'url correspond à /user => utilisation de UserController->getuserByEmail($request)
$router->addRoute( HttpEnum::GET->value . '/user', Controllers\UserController::class, 'getUserByEmail');
$router->addRoute( HttpEnum::POST->value . '/user', Controllers\UserController::class, 'setUser');