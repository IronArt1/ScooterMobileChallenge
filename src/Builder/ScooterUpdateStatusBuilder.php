<?php

namespace App\Builder;

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
    /**
     * A current scooter's status'
     *
     * @var boolean
     */
    protected $occupied;

    /**
     * A scooter service's
     *
     * @var ScooterService
     */
    private ScooterService $scooterService;

    /**
     * CreateConsumerBuilder constructor.
     *
     * @param ScooterService $scooterService
     * @param EventDispatcherInterface $dispatcher
     *
     * @throws \ReflectionException
     */
    public function __construct(
        ScooterService $scooterService,
        EventDispatcherInterface $dispatcher
    ) {
        parent::__construct($dispatcher);

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

        foreach (['body', 'occupied'] as $param) {
            if (empty($this->{$param})) {
                throw new InsufficientDataException(
                    [
                        "`$param`"
                    ]
                );
            }
        }

        $this->validateBodyOfRequest();
    }

    /**
     * @inheritDoc
     */
    final protected function aScooterUpdateStatus($status): void
    {
        $this->scooterService->scooterUpdateStatus($status);
    }

    /**
     * Make an appropriate response for a controller
     *
     * @throws \Exception
     */
    final protected function bMakeResponse(): void
    {
        $this->statusCode = HttpCode::CREATED;
        $this->response = [];
    }
}
