<?php

namespace App\Config;

require_once 'constants.php';

use PDO;
use Exception;

use App\Config\Dbh;
use \Firebase\JWT\JWT;

use App\Config\Process;

// The Middleware handles everything from requests, content-type, authorization header and token.
// Validates data, methods and parameters.
class Middleware extends Process
{

    protected $request;
    protected $result;
    protected $method;
    protected $param;
    protected $db_conn;

    // 1) Throw an error if the request methods are not allowed to your API
    public function __construct()
    {
        // * In this example this means only 'POST' is allowed
        if ($_SERVER['REQUEST_METHOD'] !== "POST") {
            self::throwError(REQUEST_METHOD_NOT_VALID, 'Request Method is unvalid.');
        }
        // Handling the request data and validating it
        $handler = fopen('php://input', 'r');
        $this->request = stream_get_contents($handler);
        $this->validateRequest($this->request);
        // Connect to Database
        $db = new Dbh();
        $this->db_conn = $db->connect();
    }

    // 2) Validate requests of: content type, methods, parameters
    public function validateRequest()
    {
        // * To block access to API endpoints from browsers and only allow when fetching with json content-type
        if (empty($_SERVER['CONTENT_TYPE'])) {
            exit('Access to API not Allowed from Browsers.');
        } else if ($_SERVER['CONTENT_TYPE'] !== 'application/json') {
            self::throwError(REQUEST_CONTENT_TYPE_NOT_VALID, 'Request Content-Type is unvalid.');
        }

        // Convert data retrieved to php object
        $data = json_decode($this->request, true);

        // Checking the method
        if (!isset($data['method']) || empty($data['method'])) {
            self::throwError(METHOD_NAME_REQUIRED, 'Method name is required.');
        }
        $this->method = self::sanitizeData($data['method']);

        // If params exists and are not empty assign them
        if (isset($data['params']) || !empty($data['params'])) {
            $this->param = $data['params'];
        }
    }

    // 3) The method requested in the data sent is processed with the Controller class in 'app/config/Controller.php'

    /** 4) Validate parameters
     * @param string $key 
     * @param mixed $value
     * @param string $dataType from the defined constants: INTEGER, BOOLEAN, STRING, EMAIL
     * @param boolean $required defaults to true, make it false if parameter is not required
     */
    public function validateParams($key, $value, $dataType, $required = true)
    {
        $key = self::sanitizeData($key);
        $value = self::sanitizeData($value);

        if ($required = true && empty($value) == true) {
            self::throwError(VALIDATE_PARAMETER_REQUIRED, $key . ' value is required.');
        }

        switch ($dataType) {
            case BOOLEAN:
                if (!is_bool($value)) {
                    self::throwError(VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for ' . $key . '. (must be a boolean)');
                }
                break;
            case INTEGER:
                if (!is_numeric($value)) {
                    self::throwError(VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for ' . $key . '. (must be a number)');
                }
                break;
            case STRING:
                if (!is_string($value)) {
                    self::throwError(VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for ' . $key . '. (must be a string)');
                }
                break;
            case EMAIL:
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    self::throwError(VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for ' . $key . '. (must be an email)');
                }
                break;
            default:
                self::throwError(VALIDATE_PARAMETER_DATATYPE, 'Datatype is not valid for ' . $key);
                break;
        }
        return $value;
    }

    // 5.1) Get header Authorization : Handling Apache, NGINX, fast CGI etc...
    public function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }
    // 5.2) Get access token from header
    public function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        self::throwError(AUTHORIZATION_HEADER_NOT_FOUND, 'Access Token Not found');
    }
    /** 5.3) SQL SELECT statement for verifying account id from token with database account table
     * @param object $payload
     * @param string $table
     */
    public function sqlVerifyAccountId($payload, $table)
    {
        $sql = "SELECT * FROM $table WHERE user_id = :user_id";
        $stmt = $this->db_conn->prepare($sql);
        $stmt->bindParam(':user_id', $payload->data->user_id);
        $stmt->execute();

        $account = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!is_array($account)) {
            self::returnResponse(USER_NOT_FOUND, 'This account is not found in the database.');
        }

        // Assign any data needed from the payload
        $this->user_id = $payload->data->user_id;
        $this->email = $payload->data->email;
    }

    // 5.4) Validate token received
    public function validateToken()
    {
        try {
            $token = $this->getBearerToken();
            $payload = JWT::decode($token, $_ENV['SECRET_KEY'], ['HS256']);

            $this->sqlVerifyAccountId($payload, $_ENV['USER_TABLE']);
        } catch (Exception $e) {
            self::throwError(ACCESS_TOKEN_ERRORS, $e->getMessage());
        }
    }

    /** Sanitize the data
     * @param mixed $data
     */
    public static function sanitizeData($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /** Generate random unique ID with optional prefix and variable length
     * @param string $prefix optional, defaults to none
     * @param integer $length defaults to 13
     */
    public static function generateId($prefix = '', $length = 13)
    {
        $bytes = random_bytes(ceil($length / 2));
        $id = $prefix . substr(bin2hex($bytes), 0, $length);
        return $id;
    }
    /** Hash password (BCRYPT):
     * @param string $password
     */
    public static function hash($password)
    {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        return $hashed_password;
    }

    /** Error handler
     * @param integer $code
     * @param string $message
     */
    public static function throwError($code, $message)
    {
        header('Content-Type: application/json');
        $errorMsg = json_encode(['error' => ['status' => $code, 'message' => $message]]);
        if ($_ENV['ERRORS'] === 'ON') {
            echo $errorMsg;
        }
        exit;
    }
    /** Response handler
     * @param integer $code
     * @param object $data
     */
    public static function returnResponse($code, $message, $result = [])
    {
        header('Content-Type: application/json');
        if ($_ENV['RESPONSES'] === 'ON') {
            $responseMsg = json_encode(['response' => ['status' => $code, 'message' => $message], 'result' => $result]);
        } else {
            $responseMsg = json_encode($result);
        }
        echo $responseMsg;
        exit;
    }
}
