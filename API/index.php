<?php

use App\Routes;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/App/Routes.php';

$uri = $_SERVER['REQUEST_URI'];

$request = ServerRequest::fromGlobals();
$response = new Response();

//MiddlewareRequest

//$right = new MiddlewareHome\Right();
//var_dump($request->getUri()->getPath());
//$response = $right::rightChecker($request, $router->dispatch($request->getUri()->getPath(), $response));

//var_dump($request->getUri()->getPath());
$router->dispatch($request->getUri()->getPath(), $request);