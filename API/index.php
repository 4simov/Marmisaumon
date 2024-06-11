<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE, PUT");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Access-Control-Allow-Origin");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

use App\Routes;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/App/Routes.php';

$uri = $_SERVER['REQUEST_URI'];

$request = ServerRequest::fromGlobals();
$response = new Response();

$router->dispatch($request->getUri()->getPath());