<?php
declare(strict_types = 1);

namespace Application\Models\Responses;

class SecretResponse
{
    public $companyID;
    public $secret;

    public function __construct(
        int $companyID,
        string $secret
    )
    {
        $this->companyID = $companyID;
        $this->secret = $secret;
    }
}