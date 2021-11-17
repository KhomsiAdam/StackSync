<?php

namespace App\Core;

class Request {
    public function getPath() {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position === false) {
            return $path;
        }
        return substr($path, 0, $position);
    }
    
    public function getMethod() {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getBody() {
        $body = [];

        if($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $body[$key]  = $value;
            }
        }
        if($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $body[$key] = $value;
            }
        }
        return $body;
    }
}