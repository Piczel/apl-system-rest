<?php
declare(strict_types = 1);

namespace Application\Util\Http;

use Application\Util\Exceptions\RequestException;

class Param
{
    public static function get(
        string $name,
        bool $optional = false
    ) : ?string
    {
        if(!isset($_GET[$name]) && !$optional)
        {
            throw new RequestException('Undefined parameter: '. $name, HttpStatus::BAD_REQUEST);
        }

        return $_GET[$name] ?? null;
    }

    public static function get_int(
        string $name,
        bool $optional = false
    ) : ?int
    {
        if(!isset($_GET[$name]) && !$optional)
        {
            throw new RequestException('Undefined parameter: '. $name, HttpStatus::BAD_REQUEST);
        }

        return ($_GET[$name] ?? null) == null ? null : \intval($_GET[$name]);
    }
}