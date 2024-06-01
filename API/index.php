<?php

use App\Router;
require __DIR__ . '/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Inclure le fichier routes.php pour configurer les routes
require __DIR__ . '/App/Routes.php';

// Récupérer l'URI et la méthode de la requête
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Dispatcher la requête à la route correspondante
$router->dispatch($uri, $method);
