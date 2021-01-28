<?php

namespace App\Service;

use App\Repository\ScooterRepository;
use Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Uid\Uuid;

/**
 * Class IdTypeArgumentResolver
 *
 * @package App\Service
 */
class IdTypeArgumentResolver //implements ArgumentValueResolverInterface
{
    /**
     * A required scooter route's
     */
    private const ROUTE = 'scooter-update-status';

    /**
     * @var ScooterRepository
     */
    private $scooterRepository;

    /**
     * IdTypeArgumentResolver constructor.
     *
     * @param ScooterRepository $scooterRepository
     */
    public function __construct(ScooterRepository $scooterRepository) {
        $this->scooterRepository = $scooterRepository;
    }

    /**
     * Postponed...
     *
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return bool
     */
    public function supports(Request $request, ArgumentMetadata $argument)
    {
        return $argument->getName() === 'scooter123'
            && $request->isMethod(BaseService::REQUEST_TYPE_POST)
            && $request->attributes->get('_route') === self::ROUTE;
    }

    /**
     * Postponed...
     *
     * @param Request $request
     * @param ArgumentMetadata $argument
     * @return false|int|iterable
     */
    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $uid = Uuid::v4($request->attributes->get('id'));

        yield $this->scooterRepository->findOneBy(['id' => 0xD237F0FF41D34F759B75D112A4422F37]);
    }
 }