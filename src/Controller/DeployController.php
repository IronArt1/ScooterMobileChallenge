<?php

namespace App\Controller;

use App\Message\Command\DeployScooters;
use Symfony\Component\HttpFoundation\{
    Response,
    JsonResponse
};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class DeployController
 *
 * @package App\Controller
 */
class DeployController extends BaseController
{
    /**
     * A random amount of scooters'
     */
    private const AMOUNT_OF_SCOOTERS = 10;

    /**
     * Deploys available scooters
     *
     * @Route("/deploy/init", name="deploy-init", methods={"GET"})
     * @param MessageBusInterface $messageBus
     * @return JsonResponse
     */
    public function deployScooters(MessageBusInterface $messageBus): JsonResponse
    {
        // for monitoring reasons do:
        // $ tail -f var/log/messenger.log
        $messageBus->dispatch(new DeployScooters(self::AMOUNT_OF_SCOOTERS));

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
