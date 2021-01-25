<?php

namespace App\Interfaces\Builder;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Interface SuperiorInterface
 * @package App\Interfaces\Builder
 */
interface SuperiorInterface
{
    /**
     * A general flow for processing data and making a response
     *
     * @param BuilderInterface $builder
     * @return JsonResponse
     */
    public function build(BuilderInterface $builder): JsonResponse;
}
