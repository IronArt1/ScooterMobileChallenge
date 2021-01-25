<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Codeception\Util\HttpCode;

/**
 * Class InsufficientDataException.
 */
class InsufficientDataException extends HttpException
{
    public function __construct(
        array $parameters,
        string $delimiter = ',',
        string $location = 'request'
    )
    {
        parent::__construct(
            HttpCode::BAD_REQUEST,
            "The following parameter(s) must be presented in a $location and must have value(s): " . implode($delimiter, $parameters)
        );
    }
}
