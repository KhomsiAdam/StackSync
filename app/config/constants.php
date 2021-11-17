<?php

	/* Defined Constants for various purposes */
    // * You can add your own error and success messages with their codes here for use

	// Data Types
	define('BOOLEAN', 	'1');
	define('INTEGER', 	'2');
	define('STRING', 	'3');
	define('EMAIL', 	'4');

	/* Error Codes */
    // Requests
	define('REQUEST_NOT_VALID', 			        400);
	define('REQUEST_METHOD_NOT_VALID',		        405);
	define('REQUEST_CONTENT_TYPE_NOT_VALID',	    406);
    // Methods
	define('METHOD_NAME_REQUIRED', 					444);
	define('METHOD_DOES_NOT_EXIST', 				404);
    // Parameters
	define('METHOD_PARAMS_REQUIRED', 				445);
    define('VALIDATE_PARAMETER_REQUIRED', 			446);
	define('VALIDATE_PARAMETER_DATATYPE', 			447);
    // Authentication
	define('INVALID_USER',   					108);
	define('USER_NOT_ACTIVE', 					109);
	define('USER_NOT_FOUND', 					110);

    // Response Codes 
	define('RESPONSE_MESSAGE', 						200);

	// JWT Token Authentication Errors
	define('JWT_PROCESSING_ERROR',					102);
	define('AUTHORIZATION_HEADER_NOT_FOUND',		412);
	define('ACCESS_TOKEN_ERRORS',					401);