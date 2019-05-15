<?php
declare(strict_types = 1);

namespace Application\Controllers;

use Application\Models\Responses\DefaultResponse;
use Application\Util\Http\HttpStatus;

class Controller
{

    public function __construct()
    {

    }

    public function method(
        string $name,
        array $url_params
    ) : void 
    {
        if(\method_exists($this, $name))
        {
            $this->{$name}($url_params);
        } else
        {
            $this->respond(
                new DefaultResponse('Controller method not found'),
                HttpStatus::NOT_FOUND
            );
        }
    }

    public function respond(
        $response_object,
        int $http_status
    ) : void
    {
        \http_response_code($http_status);
        \header('Content-Type: application/json');
        
        echo json_encode($response_object);
    }
}