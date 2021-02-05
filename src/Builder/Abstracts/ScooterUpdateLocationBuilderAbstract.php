<?php

namespace App\Builder\Abstracts;

use App\Interfaces\Controller\ControllerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ScooterUpdateLocationBuilderAbstract
 *
 * @package App\Builder\Abstracts
 */
abstract class ScooterUpdateLocationBuilderAbstract extends BuilderAbstract
{
    /**
     * Validation parameters for POST request are
     */
    protected const PATCH_VALIDATION = [
        'updatedAt' => ControllerInterface::STRING_TYPE_HOLDER,
        'latitude' => ControllerInterface::STRING_TYPE_HOLDER,
        'longitude' => ControllerInterface::STRING_TYPE_HOLDER
    ];

    /**
     * ScooterUpdateStatusBuilderAbstract constructor.
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Updates a scooter location
     *
     * A 1st listener's
     */
    abstract protected function aScooterUpdateLocation(): void;

    /**
     * Checks a necessity of issuing an event of updating scooter's status
     * A 2nd listener's
     */
    abstract protected function bCheckingNecessityOfScooterStatusUpdate(): void;

    /**
     * Makes an appropriate response for a controller
     * A 3rd listener's
     */
    abstract protected function cMakeResponse(): void;
}
