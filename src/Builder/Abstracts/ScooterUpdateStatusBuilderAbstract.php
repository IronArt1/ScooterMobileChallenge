<?php

namespace App\Builder\Abstracts;

use App\Interfaces\Controller\ControllerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ScooterUpdateStatusBuilderAbstract
 * @package App\Builder\Abstracts
 */
abstract class ScooterUpdateStatusBuilderAbstract extends BuilderAbstract
{
    /**
     * Validation parameters for POST request
     */
    protected const POST_VALIDATION = [
        'occupied' => ControllerInterface::BOOLEAN_TYPE_HOLDER,
    ];

    /**
     * ScooterUpdateStatusBuilderAbstract constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     *
     * @throws \ReflectionException
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        parent::__construct($dispatcher);
    }

    /**
     * Updates a scooter status'
     *
     * A 1st listener's
     * @param boolean $status
     */
    abstract protected function aScooterUpdateStatus($status): void;

    /**
     * Make an appropriate response for a controller
     * A 2nd listener's
     */
    abstract protected function bMakeResponse(): void;
}
