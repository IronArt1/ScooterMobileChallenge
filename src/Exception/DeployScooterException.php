<?php

namespace App\Exception;

use Codeception\Util\HttpCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class DeployScooterException.
 */
class DeployScooterException extends HttpException
{
    /**
     * DeployScooterException constructor.
     *
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        parent::__construct(
            HttpCode::SERVICE_UNAVAILABLE,
            'Can\'t issue `DeployScooter` command properly.',
            $exception
        );
    }
}
