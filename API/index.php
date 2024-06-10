<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Gérer les requêtes OPTIONS (pré-vol)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}
use App\Routes;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/App/Routes.php';

$uri = $_SERVER['REQUEST_URI']
// Le reste de votre code
$request = ServerRequest::fromGlobals();
$response = new Response();

//MiddlewareRequest

//$right = new MiddlewareHome\Right();
//var_dump($request->getUri()->getPath());
//$response = $right::rightChecker($request, $router->dispatch($request->getUri()->getPath(), $response));

//var_dump($request->getUri()->getPath());
$router->dispatch($request->getUri()->getPath());