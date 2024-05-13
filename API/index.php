<?php

/*$request = $_SERVER['REQUEST_URI'];

switch ($request) {
   case '/' :
      require __DIR__ .'/controllers/accueil.php';
      break;
   case '/login':
      $controller = new App\Controllers\UserController();
      $controller->getUserByEmail('');
      require __DIR__ . '/controllers/login.php';
      break;
   default :
      require __DIR__ .'/errors/404.php';
}*/
use App\Routes;
require __DIR__ . '/vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];

require __DIR__ . '/App/Routes.php';
//$obj = json_decode(file_get_contents($uri), true);
//echo $obj;
//echo 'My username is ' . getenv("ADMIN") . '!';
$router->dispatch($uri);