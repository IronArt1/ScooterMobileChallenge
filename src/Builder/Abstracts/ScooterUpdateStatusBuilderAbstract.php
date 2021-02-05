<?php

namespace App\Builder\Abstracts;

use App\Interfaces\Controller\ControllerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ScooterUpdateStatusBuilderAbstract
 *
 * @package App\Builder\Abstracts
 */
abstract class ScooterUpdateStatusBuilderAbstract extends BuilderAbstract
{
    /**
     * Validation parameters for POST request are
     */
    protected const PATCH_VALIDATION = [
        'occupied' => ControllerInterface::BOOLEAN_TYPE_HOLDER,
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
     * Updates a scooter status
     *
     * A 1st listener's
     */
    abstract protected function aScooterUpdateStatus(): void;

    /**
     * Make an appropriate response for a controller
     * A 2nd listener's
     */
    abstract protected function bMakeResponse(): void;
}
