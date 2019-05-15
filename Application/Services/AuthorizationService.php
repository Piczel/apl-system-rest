<?php
declare(strict_types = 1);

namespace Application\Services;

use Application\Models\Responses\SecretResponse;
use Application\Util\Database;
use Application\Util\Exceptions\RequestException;
use Application\Util\Http\HttpStatus;

class AuthorizationService
{
    public function generate_secret(
        string $company_name,
        string $password
    ) : SecretResponse
    {
        $connection = Database\Connection::get_instance();

        // Checks credentials against database 
        $companyID = $connection->query(
            'SELECT `companyID` FROM company WHERE UPPER(`name`) = UPPER(?) AND `password` = ?',
            [
                $company_name,
                $password
            ]
        )[0]['companyID'] ?? null;

        if($companyID == null)
        {
            throw new RequestException('Invalid credentials', HttpStatus::UNAUTHORIZED);
        }

        // Generates a secret
        $secret = \substr(\bin2hex(\random_bytes(40)), 0, 50);

        // Updates secret in database
        if(!$connection->execute(
            'UPDATE company SET `secret` = ? WHERE companyID = ?',
            [
                $secret,
                $companyID
            ]
        )) {
            throw new RequestException('Error updating secret', HttpStatus::INTERNAL_SERVER_ERROR);
        }

        return new SecretResponse($companyID, $secret);
    }

    public function authorize(
        int $companyID,
        string $secret
    ) : bool
    {
        $connection = Database\Connection::get_instance();

        if($connection->count(
            'SELECT 1 FROM company WHERE companyID = ? AND `secret` = ?',
            [
                $companyID,
                $secret
            ]
        ) === 1) {
            return true;
        }

        return false;
    }
}