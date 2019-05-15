<?php
declare(strict_types = 1);

namespace Application\Controllers;

use Application\Services\AuthorizationService;
use Application\Util\Http\HttpStatus;
use Application\Util\Http\Param;

class APIController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function secret() {

        $company_name = Param::get('companyName');
        $password = Param::get('password');
        
        $api_service = new AuthorizationService;

        $secret_response = $api_service->generate_secret(
            $company_name,
            $password
        );

        $this->respond($secret_response, HttpStatus::OK);
    }
}