<?php

namespace App\Service\Abstracts;

use App\Service\BaseService;
use App\Service\JWTTokenService;
use App\Interfaces\Service\ServiceInterface;

/**
 * Class AbstractService
 * @package App\Service\Abstracts
 */
abstract class AbstractService implements ServiceInterface
{
    /**
     * Set up query parameters
     *
     * @param $parameters
     * @return $this
     */
    abstract protected function setUpQueryParameters($parameters): BaseService;
}
