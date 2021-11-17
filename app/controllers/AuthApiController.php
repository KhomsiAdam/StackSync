<?php

namespace App\Controller;

use App\Config\Middleware;
use App\Model\AuthModel;
use Exception;

use \Firebase\JWT\JWT;

class AuthApiController extends Middleware
{
    // Array to hold any data you need about a user
    private $user_data = [];

    public function __construct()
    {
        parent::__construct();
    }

    // Generating token for login
    public function login()
    {
        $email = $this->validateParams($_ENV['USER_EMAIL'], $this->param[$_ENV['USER_EMAIL']], STRING);
        $password = $this->validateParams($_ENV['USER_PASSWORD'], $this->param[$_ENV['USER_PASSWORD']], STRING);
        try {
            $authObj = new AuthModel();
            $this->user_data = $authObj->sqlVerifyUser($email, $_ENV['USER_EMAIL'], $password, $_ENV['USER_PASSWORD'], $_ENV['USER_TABLE']);
            if (is_array($this->user_data)) {
        
                // Generating a JWT token
                $iss = $_ENV['DB_HOST'];
                $iat = time();
                $minutes = 30;
                // Token expiration time in seconds: 60 * 15 = 15min; 60 * 30 = 30min; 60 * 60 = 1hour...
                $exp = $iat + (60 * $minutes);
                $aud = $_ENV['AUDIENCE_TABLE'];
                // Array containing any user data needed from token you can add an entry based on needs
                $data = array(
                    $_ENV['USER_ID'] => $this->user_data[$_ENV['USER_ID']],
                    $_ENV['USER_EMAIL'] => $this->user_data[$_ENV['USER_EMAIL']],
                );

                // Generating the payload
                $payload = array(
                    "iss" => $iss,
                    "iat" => $iat,
                    "exp" => $exp,
                    "aud" => $aud,
                    "data" => $data
                );

                // Generate JWT token based on payload and secret key
                $jwt = JWT::encode($payload, $_ENV['SECRET_KEY']);
                $token = ['token' => $jwt];
                Middleware::returnResponse(RESPONSE_MESSAGE, "Token generated successfully", $token);
            } else {
                $token = ['token' => null];
                Middleware::returnResponse(INVALID_USER, 'Email or password is incorrect.', $token);
            }
        } catch (Exception $e) {
            Middleware::throwError(JWT_PROCESSING_ERROR, $e->getMessage());
        }
    }
}