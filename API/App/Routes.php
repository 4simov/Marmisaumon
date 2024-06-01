<?php
use App;
use Controllers\UserController;
use MyEnum\HttpEnum;

$router = new App\Router();

echo HttpEnum::GET->value;
//Si l'url correspond à /user => utilisation de UserController->getuserByEmail($request)
$router->addRoute( HttpEnum::GET->value . '/utilisateur', UserController::class, 'getUserByEmail');
$router->addRoute( HttpEnum::POST->value . '/utilisateur', UserController::class, 'setUser');