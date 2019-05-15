<?php
declare(strict_types = 1);

namespace Application\Util\Http;

class HttpStatus {

    const OK                    = 200;

    const BAD_REQUEST           = 400;
    const UNAUTHORIZED          = 401;
    const FORBIDDEN             = 403;
    const NOT_FOUND             = 404;

    const INTERNAL_SERVER_ERROR = 500;
}