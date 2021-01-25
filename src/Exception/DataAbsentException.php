<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Codeception\Util\HttpCode;

/**
 * Class DataAbsentException.
 */
class DataAbsentException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            HttpCode::NOT_FOUND,
            'Data does not have any entries regarding provided information.'
        );
    }
}
