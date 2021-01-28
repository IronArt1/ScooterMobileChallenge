<?php

namespace App\Builder;

use App\Entity\Scooter;
use App\Traits\GeneralBuilder;
use App\Service\ScooterService;
use App\Interfaces\Builder\BuilderInterface;
use App\Exception\InsufficientDataException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Builder\Abstracts\ScooterUpdateLocationBuilderAbstract;

/**
 * Class ScooterUpdateLocationBuilder
 *
 * @package App\Builder
 */
final class ScooterUpdateLocationBuilder extends ScooterUpdateLocationBuilderAbstract implements BuilderInterface
{
    use GeneralBuilder;

    /**
     * An instance of Scooter's
     *
     * @var Scooter
     */
    private $scooter;

    /**
     * A scooter service's
     *
     * @var ScooterService
     */
    private $scooterService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;
    private Request $request;

    /**
     * ScooterUpdateStatusBuilder constructor.
     *
     * @param Scooter $scooter
     * @param ValidatorInterface $validator
     * @param SerializerInterface $serializer
     * @param ScooterService $scooterService
     * @throws \ReflectionException
     */
    public function __construct(
        Scooter $scooter,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        ScooterService $scooterService,
        Request $request
    ) {
        parent::__construct();

        $this->request = $request;
        $this->scooter = $scooter;
        $this->validator = $validator;
        $this->serializer = $serializer;
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
    final protected function aScooterUpdateLocation(): void
    {
        $this->scooterService->scooterUpdateLocation(
            $this->request->getContent(),
            $this->scooter,
            $this->serializer,
            $this->validator,
            $this->response,
            $this->statusCode
        );
    }
}
