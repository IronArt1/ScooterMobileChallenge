<?php

namespace App\Exception;

use Codeception\Util\HttpCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TokenSignatureIsEmptyException.
 */
class TokenSignatureIsEmptyException extends HttpException
{
    public function __construct()
    {
        parent::__construct(
            HttpCode::UNAUTHORIZED,
            "A JWT must have a signature."
        );
    }
}
