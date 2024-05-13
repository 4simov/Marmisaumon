<?php

use App\Routes;
require __DIR__ . '/vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];

require __DIR__ . '/App/Routes.php';

$router->dispatch($uri);