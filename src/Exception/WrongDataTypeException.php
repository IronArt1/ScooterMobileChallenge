<?php

namespace App\Exception;

use Codeception\Util\HttpCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class WrongDataTypeException.
 */
class WrongDataTypeException extends HttpException
{
    public function __construct(array $parameters, $location = 'request')
    {
        parent::__construct(
            HttpCode::BAD_REQUEST,
            "The following parameter `{$parameters[0]}` in a $location must have the following type(s) - `{$parameters[1]}`"
        );
    }
}
