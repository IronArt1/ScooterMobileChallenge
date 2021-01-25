<?php

namespace App\Builder;

use App\Interfaces\Builder\{
    SuperiorInterface,
    BuilderInterface
};
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Superior
 * @package App\Builder
 */
class Superior implements SuperiorInterface
{
    /**
     * Input parameters're
     *
     * @var array
     */
    protected $parameters;

    /**
     * Superior constructor.
     * @param mixed ...$parameters
     */
    public function __construct(...$parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * A general flow for processing data and making a response
     *
     * @param BuilderInterface $builder
     * @return JsonResponse
     */
    public function build(BuilderInterface $builder): JsonResponse
    {
        $builder->setInputParameters($this->parameters);
        $builder->checkInputParameters();
        $builder->build();

        return $builder->getResponse();
    }
}