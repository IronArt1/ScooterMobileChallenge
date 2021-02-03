<?php

namespace App\Builder;

use App\Entity\Scooter;
use App\Traits\GeneralBuilder;
use Codeception\Util\HttpCode;
use App\Service\ScooterService;
use App\Interfaces\Builder\BuilderInterface;
use App\Exception\InsufficientDataException;
use App\Builder\Abstracts\ScooterUpdateStatusBuilderAbstract;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ScooterUpdateStatusBuilder
 *
 * @package App\Builder
 */
final class ScooterUpdateStatusBuilder extends ScooterUpdateStatusBuilderAbstract implements BuilderInterface
{
    use GeneralBuilder;

    /**
     * An instance of Scooter's
     *
     * @var Scooter
     */
    private Scooter $scooter;

    /**
     * A scooter service's
     *
     * @var ScooterService
     */
    private ScooterService $scooterService;

    /**
     * ScooterUpdateStatusBuilder constructor.
     *
     * @param Scooter $scooter
     * @param ScooterService $scooterService
     * @throws \ReflectionException
     */
    public function __construct(
        Scooter $scooter,
        ScooterService $scooterService
    ) {
        parent::__construct();

        $this->scooter = $scooter;
        $this->scooterService = $scooterService;
    }

    /**
     * Set up input parameters
     *
     * @param mixed ...$parameters
     */
    public function setInputParameters(...$parameters): void
    {
        list($this->body, $this->method) = $parameters[0];
    }

    /**
     * Checking out required input parameters
     */
    public function checkInputParameters(): void
    {
        $this->body = json_decode($this->body, true);

        if (empty($this->body)) {
            throw new InsufficientDataException(
                [
                    "`body`"
                ]
            );
        }

        $this->validateBodyOfRequest();
    }

    /**
     * @inheritDoc
     */
    final protected function aScooterUpdateStatus(): void
    {
        $this->scooterService->scooterUpdateStatus(
            $this->scooter,
            $this->body['occupied']
        );

        $this->response = [];
    }
}
