<?php
declare(strict_types = 1);

namespace Application\Controllers;

use Application\Util\Http\Param;
use Application\Services\StudentService;
use Application\Models\Responses\DefaultResponse;
use Application\Util\Http\HttpStatus;

class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function student(array $url_params)
    {
        $name = Param::get('name');
        $companyID = Param::get_int('companyID', true);
        $accessPeriod = Param::get('accessPeriod', true);
        $studentID = Param::get_int('studentID', true);

        $student_service = new StudentService;

        $student_service->student(
            $name,
            $companyID,
            $accessPeriod,
            $studentID
        );

        $this->respond(new DefaultResponse('OK'), HttpStatus::OK);
    }
}