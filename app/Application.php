<?php

namespace App;

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

class Application {

    public static string $ROOT_DIR;
    public static Application $app;

    public function __construct($rootPath) {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
    }

    public function run() {
        echo $this->router->resolve();
    }
}