<?php

namespace App\Builder\Abstracts;

/**
 * Class DeployScootersBuilderAbstract
 * A road map for deploying scooters process'
 *
 * @package App\Builder\Abstracts
 */
abstract class DeployScootersBuilderAbstract extends BuilderAbstract
{
    /**
     * A listener for checking out ... is
     */
    const CHECK_ = 'checking ...';

    /**
     * DeployScootersBuilderAbstract constructor.
     * @throws \ReflectionException
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Deploy scooters on the ground
     * a listener 1st's
     */
    abstract protected function aDeployScooters(): void;

    /**
     * Make an appropriate response for a controller
     * a listener 2nd's
     */
    abstract protected function bMakeResponse(): void;
}