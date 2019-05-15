<?php
declare(strict_types = 1);

namespace Application\Controllers;

use Application\Models\Responses\DefaultResponse;
use Application\Util\Http\HttpStatus;

abstract class Controller
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
            $this->response(
                new DefaultResponse('Controller method not found'),
                HttpStatus::NOT_FOUND
            );
        }
    }

    protected function response(
        $response_object,
        int $http_status
    ) : void
    {
        \http_response_code($http_status);
        \header('Content-Type: application/json');
        
        echo json_encode($response_object);
    }
}