<?php

namespace App\Exception;

use Codeception\Util\HttpCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TokenDoesNotHaveExpireTimeException.
 */
class TokenDoesNotHaveExpireTimeException extends HttpException
{
    public function __construct($tokenType)
    {
        parent::__construct(
            HttpCode::BAD_REQUEST,
            "A payload of a `$tokenType` does not have an expire time."
        );
    }
}
