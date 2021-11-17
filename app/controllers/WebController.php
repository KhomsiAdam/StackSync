<?php
// Route methods for Web Views in index.php
namespace App\Controller;

use App\Core\Request;
use App\Core\Views;

class WebController
{
    // Example page with params with GET
    // public function example()
    // {
    //     $params = [
    //         'key' => "value"
    //     ];
    //     return ViewsController::render('base', 'example', $params);
    // }

    /**  Exemple with POST
     * @param string App\Core\Request $request
     */
    // public static function example(Request $request)
    // {
    //     $body = $request->getBody();
    //     echo '<pre>';
    //     print_r($body);
    //     echo '</pre>';
    // }

    // Home page
    public function home()
    {
        return Views::render('base', 'home');
    }
}
