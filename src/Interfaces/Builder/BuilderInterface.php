<?php

namespace App\Interfaces\Builder;

use App\Interfaces\Controller\ControllerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Interface BuilderInterface
 *
 * @package App\Interfaces\Builder
 */
interface BuilderInterface extends ControllerInterface
{
    public function setInputParameters(...$parameters): void;

    public function checkInputParameters(): void;

    public function build(): void;

    public function getResponse(): JsonResponse;
}
