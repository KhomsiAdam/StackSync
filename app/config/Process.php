<?php

namespace App\Config;

use ReflectionMethod;
use App\Config\Middleware;
use App\Controller\AuthApiController;
use App\Controller\UserApiController;

// The Process class groups the process methods of any Controller.
class Process
{

    // Process the method's name sent in the data and check if it exists in your ApiController
    public function processMethods($controller)
    {
        $controllerObj = new $controller();
        $controllerMethod = new ReflectionMethod("$controller", $this->method);
        if (!method_exists($controllerObj, $this->method)) {
            Middleware::throwError(METHOD_DOES_NOT_EXIST, "Method does not exist.");
        }
        // Then invoke said method on the object created
        $controllerMethod->invoke($controllerObj);
    }

    //* This handles the authentication do not remove or modify
    // Process Authentication Methods
    public function processAuth()
    {
        $this->processMethods(AuthApiController::class);
    }

    //* Create a Process Method for each ApiController you might have
    // Process User Methods
    public function processUser()
    {
        $this->processMethods(UserApiController::class);
    }

}
