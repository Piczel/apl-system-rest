<?php
declare(strict_types = 1);

namespace Application\Models\Responses;

class DefaultResponse
{
    public $message;

    public function __construct(
        string $message
    )
    {
        $this->message = $message;
    }
}