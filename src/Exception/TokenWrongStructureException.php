<?php

namespace App\Exception;

use Codeception\Util\HttpCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class TokenWrongStructureException.
 */
class TokenWrongStructureException extends HttpException
{
    public function __construct(string $tokenType)
    {
        parent::__construct(
            HttpCode::UNAUTHORIZED,
            "A token `$tokenType` is invalid."
        );
    }
}
