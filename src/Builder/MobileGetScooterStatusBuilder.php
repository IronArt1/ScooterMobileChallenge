<?php

namespace App\Builder;

use App\Traits\GeneralBuilder;
use App\Service\ScooterService;
use App\Validator\ContainsAlpha;
use App\Validator\ContainsAlphaValidator;
use App\Interfaces\Builder\BuilderInterface;
use App\Exception\InsufficientDataException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Builder\Abstracts\MobileGetScooterStatusBuilderAbstract;

/**
 * Class MobileGetScooterStatusBuilder
 *
 * @package App\Builder
 */
final class MobileGetScooterStatusBuilder extends MobileGetScooterStatusBuilderAbstract implements BuilderInterface
{
    use GeneralBuilder;

    /**
     * A scooter's status'
     *
     * @var string
     */
    private $status;

    /**
     * Validates a scooter status
     *
     * @var ContainsAlphaValidator
     */
    private ContainsAlphaValidator $statusValidator;

    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * A scooter service's
     *
     * @var ScooterService
     */
    private ScooterService $scooterService;

    /**
     * MobileGetScooterStatusBuilder constructor.
     *
     * @param SerializerInterface $serializer
     * @param ScooterService $scooterService
     * @param ContainsAlphaValidator $statusValidator
     * @throws \ReflectionException
     */
    public function __construct(
        SerializerInterface $serializer,
        ScooterService $scooterService,
        ContainsAlphaValidator $statusValidator
    ) {
        parent::__construct();

        $this->serializer = $serializer;
        $this->scooterService = $scooterService;
        $this->statusValidator = $statusValidator;
    }

    /**
     * Set up input parameters
     *
     * @param mixed ...$parameters
     */
    public function setInputParameters(...$parameters): void
    {
        list($this->status, $this->body, $this->method) = $parameters[0];
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

        // a custom validator's
        $this->statusValidator->validate(
            $this->status,
            new ContainsAlpha()
        );

        $this->validateBodyOfRequest();
    }

    /**
     * @inheritDoc
     */
    final protected function aGetScootersStatus(): void
    {
        $this->scooterService->getScootersStatus(
            $this->body,
            $this->status,
            $this->response
        );
    }

    /**
     * Overrides a general response function since we need to
     * provide `groups` for a serializer
     *
     * @return JsonResponse
     */
    public function getResponse(): JsonResponse
    {
        $json = $this->serializer->serialize(
            $this->response,
            'json',
            array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
                ],
                ['groups' => ['main']]
            )
        );

        return new JsonResponse($json, $this->statusCode, [], true);
    }
}
