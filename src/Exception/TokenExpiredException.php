<?php

namespace App\Exception;

use Codeception\Util\HttpCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TokenExpiredException.
 */
class TokenExpiredException extends HttpException
{
    public function __construct($tokenName)
    {
        parent::__construct(
            HttpCode::BAD_REQUEST,
            "A token `$tokenName` is invalid."
        );
    }
}
