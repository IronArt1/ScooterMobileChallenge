<?php

namespace App\Exception;

use Codeception\Util\HttpCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TokenAlgorithmException.
 */
class TokenAlgorithmException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            HttpCode::BAD_REQUEST,
            "A JWT is not valid."
        );
    }
}
