<?php

namespace App\Core;

use App\Core\Components;
use App\Core\Request;
use App\Core\Response;
use App\Core\Views;

class Router extends Components {

    public Request $request;
    public Response $response;

    protected array $routes = [];
    
    public function __construct(Request $request,Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback) {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve() {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
        // If the route requested doesn't exist, show a 404 page
        if($callback === false) {
            $this->response->setStatusCode(404);
            // Render the 404 page in the base layout
            return Views::render('base', '_404');
        }
        // Get the route requested from the callback
        if(is_array($callback)) {
            $callback[0] = new $callback[0]();
        }
        return call_user_func($callback, $this->request);
    }
}