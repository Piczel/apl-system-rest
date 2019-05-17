<?php
declare(strict_types = 1);

namespace Application\Services;

use Application\Util\Database;
use Application\Util\Exceptions\RequestException;
use Application\Util\Http\HttpStatus;

class StudentService
{
    public function student(
        string $name,
        int $companyID = null,
        string $forAccessPeriodName = null,
        int $studentID = null
    ) : void
    {
        $connection = Database\Connection::get_instance();

        if(!$connection->execute(
            'REPLACE INTO student (studentID, `name`, forCompanyID, forAccessPeriodName) VALUES (?, ?, ?, ?)',
            [
                $studentID,
                $name,
                $companyID,
                $forAccessPeriodName
            ]
        )) {
            throw new RequestException('Could not insert student', HttpStatus::INTERNAL_SERVER_ERROR);
        }
    }
}