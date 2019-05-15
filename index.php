<?php
declare(strict_types = 1);

use Application\Controllers\APIController;
use Application\Controllers\Controller;
use Application\Models\Responses\DefaultResponse;
use Application\Util\Http\HttpStatus;
use Application\Util\Exceptions\RequestException;

// Register auto include function
spl_autoload_register(function ($classname)
{
    $file = str_replace('\\', '/', $classname) . '.php';

    require_once $file;
});

// Register exception handler
set_error_handler(function ($error_code, $error_string, $error_file, $error_line) {
    throw new RequestException($error_string .' on line: '. $error_line .' in file: '. $error_file, HttpStatus::INTERNAL_SERVER_ERROR);
}, E_ALL);



// Split URL into array
$url = rtrim($_GET['route_url'], '/');
$url_array = explode('/', $url);

// Read mapping
$controller_name = $url_array[0] ?? 'none';
$method_name = $url_array[1] ?? 'none';
$url_params = array_slice($url_array, 2);

try
{
    // Finds controller or throws a request exception with 404 error
    switch($controller_name)
    {
        case 'api':
            $controller = new APIController;
            $controller->method($method_name, $url_params);
            break;
    
        case 'none':
        default:
            throw new RequestException('Controller not found', HttpStatus::NOT_FOUND);
    }
} catch(RequestException $exc)
{
    // Catches any request exceptions and returns the error
    $controller = new Controller;
    $controller->respond(
        new DefaultResponse($exc->message),
        $exc->http_status
    );
} catch(Exception $exc)
{
    // Treats any other exception as 500
    $controller = new Controller;
    $controller->respond(
        new DefaultResponse($exc->getMessage()),
        HttpStatus::INTERNAL_SERVER_ERROR
    );
}
