<?php

namespace App\Exception;

use Codeception\Util\HttpCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TokenSignatureIsNotValidException.
 */
class TokenSignatureIsNotValidException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            HttpCode::UNAUTHORIZED,
            "A JWT signature is not valid."
        );
    }
}
