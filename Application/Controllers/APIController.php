<?php
declare(strict_types = 1);

namespace Application\Controllers;

use Application\Services\AuthorizationService;
use Application\Util\Http\HttpStatus;
use Application\Util\Http\Param;
use Application\Util\Exceptions\RequestException;
use Application\Models\Responses\DefaultResponse;
use Application\Services\PresenceService;

class APIController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function secret()
    {

        $company_name = Param::get('companyName');
        $password = Param::get('password');
        
        $authorization_service = new AuthorizationService;

        $secret_response = $authorization_service->generate_secret(
            $company_name,
            $password
        );

        $this->respond($secret_response, HttpStatus::OK);
    }

    protected function students()
    {
        
        $companyID = Param::get_int('companyID');
        $secret = Param::get('secret');

        $authorization_service = new AuthorizationService;

        if(!$authorization_service->authorize($companyID, $secret))
        {
            throw new RequestException('Invalid secret', HttpStatus::UNAUTHORIZED);
        }
        
        $presence_service = new PresenceService;

        $student_response = $presence_service->get_students($companyID);

        $this->respond($student_response, HttpStatus::OK);
    }

    protected function presence()
    {

        $companyID  = Param::get_int('companyID');
        $secret     = Param::get('secret');
        $date       = Param::get('date');
        $type       = Param::get('type');
        $studentID  = Param::get_int('studentID');

        $authorization_service = new AuthorizationService;

        if(!$authorization_service->authorize($companyID, $secret))
        {
            throw new RequestException('Invalid secret', HttpStatus::UNAUTHORIZED);
        }

        $presence_service = new PresenceService;

        $presence_service->report_presence(
            $date,
            $type,
            $studentID,
            $companyID
        );

        $this->respond(new DefaultResponse("OK"), HttpStatus::OK);
    }
}