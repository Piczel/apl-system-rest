<?php
declare(strict_types = 1);

namespace Application\Util\Exceptions;

use Exception;

class RequestException extends Exception
{
    public $http_status;
    public $message;

    public function __construct(
        string $message,
        int $http_status
    )
    {
        parent::__construct($message);

        $this->message = $message;
        $this->http_status = $http_status;
    }
}