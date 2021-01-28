<?php

namespace App\Exception;

use Codeception\Util\HttpCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ScooterMalfunctioningException.
 */
class ScooterMalfunctioningException extends HttpException
{
    public function __construct($scooterId)
    {
        parent::__construct(
            HttpCode::BAD_REQUEST,
            "A scooter {$scooterId} can not be set to 'occupied' status."
        );
    }
}
