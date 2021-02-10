<?php

namespace App\Builder\Abstracts;

use App\Interfaces\Controller\ControllerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class MobileGetScooterStatusBuilderAbstract
 *
 * @package App\Builder\Abstracts
 */
abstract class MobileGetScooterStatusBuilderAbstract extends BuilderAbstract
{
    /**
     * Validation parameters for POST request are
     */
    protected const POST_VALIDATION = [
        'startLatitude'  => ControllerInterface::STRING_TYPE_HOLDER,
        'startLongitude' => ControllerInterface::STRING_TYPE_HOLDER,
        'endLatitude'    => ControllerInterface::STRING_TYPE_HOLDER,
        'endLongitude'   => ControllerInterface::STRING_TYPE_HOLDER,
    ];

    /**
     * MobileGetScooterStatusBuilderAbstract constructor.
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Gets scooters status
     *
     * A 1st listener's
     */
    abstract protected function aGetScootersStatus(): void;

    /**
     * Make an appropriate response for a controller
     * A 2nd listener's
     */
    abstract protected function bMakeResponse(): void;
}
