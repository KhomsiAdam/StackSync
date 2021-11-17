<?php

namespace App\Core;

use App\Application;

class Views {
    /** Views handler
    * @param string $layout
    * @param string $view
    * @param array $params optional
    */
    // Render view with layout and optional parameters
    public static function render($layout, $view, $params = []) {
        return Application::$app->router->render($layout, $view, $params);
    }
}