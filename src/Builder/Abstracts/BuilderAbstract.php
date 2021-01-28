<?php

namespace App\Builder\Abstracts;

use App\Exception\{
    WrongDataTypeException,
    InsufficientDataException
};
use Codeception\Util\HttpCode;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Interfaces\Manager\ManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class BuilderAbstract
 * @package App\Builder\Abstracts
 */
abstract class BuilderAbstract
{
    /**
     * An array of methods that have to be run
     * for getting an appropriate response's.
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * A generated response for a controller
     *
     * @var array
     */
    protected $response;

    /**
     * In the create case should set up HttpCode::CREATED
     *
     * @var int
     */
    protected $statusCode = HttpCode::OK;

    /**
     * A parameter `body` from a request's
     *
     * @var mixed
     */
    protected $body = [];

    /**
     * For GET, POST validation's
     *
     * @var string
     */
    protected $method;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * BuilderAbstract constructor.
     * @param EventDispatcherInterface|null $dispatcher
     * @throws \ReflectionException
     */
    public function __construct(EventDispatcherInterface $dispatcher = null)
    {
        // usually there is a need of the one, so will see...
        if (isset($dispatcher)) {
            $this->dispatcher = $dispatcher;
        }

        $reflection = new \ReflectionClass(static::class);
        foreach ($reflection->getParentClass()->getMethods(\ReflectionMethod::IS_ABSTRACT) as $method) {
            $this->listeners[] = $method->getShortName();
        }

        asort($this->listeners, SORT_STRING);
    }

    /**
     * Calling listeners so as to create a general flow
     */
    public function build(): void
    {
        $i = 0;
        do {
            $this->{$this->listeners[$i]}();
            $i++;
        } while (isset($this->listeners[$i]));
    }

    /**
     * Generate a json response for a controller
     *
     * @return JsonResponse
     */
    public function getResponse(): JsonResponse
    {
        return new JsonResponse($this->response, $this->statusCode);
    }

    /**
     * Validate a body of certain requests
     *
     * @throws \App\Exception\InsufficientDataException
     */
    protected function validateBodyOfRequest($suffix = null): void
    {
        eval('$validation=static::' . $this->method . "_VALIDATION$suffix;");

        foreach ($validation as $key => $type) {
            if (empty($this->body[$key])) {
                throw new InsufficientDataException(
                    [
                        "`$key`"
                    ]
                );
            }

            if (gettype($this->body[$key]) != $type) {
                throw new WrongDataTypeException(
                    [
                        "$key",
                        "$type",
                    ]
                );
            }
        }
    }
}









