<?php

use Dotenv\Dotenv;
use App\Application;
use App\Controller\WebController;
use App\Controller\AuthApiController;
use App\Controller\UserApiController;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable('../');
$dotenv->load();

$app = new Application(dirname(__DIR__));

// * WebController manages the Web side of the framework
// Web routes for views : GET
$app->router->get('/', [WebController::class, 'home']);
$app->router->get('/home', [WebController::class, 'home']);
// Web routes for views : POST

// * ApiControllers manages the API side of the framework with the Middleware
// API routes for endpoints : POST
$app->router->post('/auth', [AuthApiController::class, 'processAuth']);
$app->router->post('/user', [UserApiController::class, 'processUser']);

// Run the app and router, resolve paths and request methods and render different layout depending on callback
$app->run();
?>