<?php
declare(strict_types = 1);

namespace Application\Services;

use DateTime;
use Application\Util\Database;
use Application\Util\Exceptions\RequestException;
use Application\Util\Http\HttpStatus;

class PresenceService
{
    public function get_students(
        int $companyID
    ) : array
    {   
        $current_date_string = \date('Y-m-d');

        $mon = new DateTime($current_date_string);
        while($mon->format('D') !== 'Mon')
        {
            $mon->modify('-1 Day');
        }

        $mon_string = $mon->format('Y-m-d');

        $tue = new DateTime($mon_string);
        $tue->modify('+1 Day');
        $wed = new DateTime($mon_string);
        $wed->modify('+2 Day');
        $thu = new DateTime($mon_string);
        $thu->modify('+3 Day');
        $fri = new DateTime($mon_string);
        $fri->modify('+4 Day');

        $connection = Database\Connection::get_instance();

        $students = $connection->query(

// ==== SQL ====
"SELECT
    COALESCE(mon.presenceType, '') AS 'mon',
    COALESCE(tue.presenceType, '') AS 'tue',
    COALESCE(wed.presenceType, '') AS 'wed',
    COALESCE(thu.presenceType, '') AS 'thu',
    COALESCE(fri.presenceType, '') AS 'fri',
    student.studentID              AS 'studentID'
FROM company
    INNER JOIN student
        ON student.forCompanyID = company.companyID
    INNER JOIN access_period AS ap
        ON student.forAccessPeriodName = ap.name
    LEFT JOIN presence AS mon
        ON mon.date = ? AND mon.forStudentID = student.studentID
    LEFT JOIN presence AS tue
        ON tue.date = ? AND tue.forStudentID = student.studentID
    LEFT JOIN presence AS wed
        ON wed.date = ? AND wed.forStudentID = student.studentID
    LEFT JOIN presence AS thu
        ON thu.date = ? AND thu.forStudentID = student.studentID
    LEFT JOIN presence AS fri
        ON fri.date = ? AND fri.forStudentID = student.studentID
WHERE company.companyID = ?
    AND NOW() BETWEEN ap.start AND ap.end"
// =============
            ,
            [
                $mon->format('Y-m-d'),
                $tue->format('Y-m-d'),
                $wed->format('Y-m-d'),
                $thu->format('Y-m-d'),
                $fri->format('Y-m-d'),
                $companyID
            ]
        );

        $students_response = [
            'mondayDate' => $mon_string,
            'students' => $students
        ];

        return $students_response;
    }

    public function report_presence(
        string $date,
        string $type,
        int $studentID,
        int $companyID
    ) : void
    {
        $connection = Database\Connection::get_instance();

        if(!\in_array(
            $type,
            ['PRESENT', 'UNAPPROVED_LEAVE', 'APPROVED_LEAVE', 'ILL', '']
        )) {
            throw new RequestException('Invalid presence type', HttpStatus::BAD_REQUEST);
        }

        $connection->execute(

// ==== SQL ====
'REPLACE INTO presence (
    forStudentID,
    `date`,
    presenceType
) SELECT ?, ?, ? 
WHERE (
    SELECT 1 
    FROM company
        INNER JOIN student ON forCompanyID = companyID
    WHERE companyID = ?
) > 0'
// =============
            ,
            [
                $studentID,
                $date,
                $type,
                $companyID
            ]
        );
    }
}