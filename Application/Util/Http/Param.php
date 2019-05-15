<?php
declare(strict_types = 1);

namespace Application\Util\Http;

use Application\Util\Exceptions\RequestException;

class Param
{
    public static function get(
        string $name
    ) : string
    {
        if(!isset($_GET[$name]))
        {
            throw new RequestException('Undefined parameter: '. $name, HttpStatus::BAD_REQUEST);
        }

        return $_GET[$name];
    }
}