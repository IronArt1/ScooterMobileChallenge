<?php

namespace App\Controller;

use App\Entity\Scooter;
use App\Builder\Superior;
use App\Service\ScooterService;
use App\Builder\{
    ScooterUpdateStatusBuilder,
    ScooterUpdateLocationBuilder
};
use Symfony\Component\HttpFoundation\{
    Response,
    JsonResponse
};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ScooterController
 *
 * @package App\Controller
 */
class ScooterController extends BaseController
{
    /**
     * Updates a scooter's status
     *
     * @Route("/scooter/{id}/update-status", name="scooter-update-status", methods={"PATCH"})
     * @param Scooter $scooter
     * @param ScooterService $scooterService
     * @return Response
     * @throws \ReflectionException
     */
    public function scooterUpdateStatus(
        Scooter $scooter,
        ScooterService $scooterService
    ): JsonResponse {
        // Here we can verify that a scooter is an actual owner of its table record
        // and can make requested changes (against imposters)
        $this->denyAccessUnlessGranted('MANAGE', $scooter);
        $superior = new Superior(
            $this->request->getContent(),
            $this->request->getMethod()
        );

        $scooterUpdateStatusBuilder = new ScooterUpdateStatusBuilder(
            $scooter,
            $scooterService
        );

        return $superior->build($scooterUpdateStatusBuilder);
    }

    /**
     * Allows update location for a scooter
     *
     * @Route("/scooter/{id}/update-location", name="scooter-update-location", methods={"PATCH"})
     * @param Scooter $scooter
     * @param ValidatorInterface $validator
     * @param ScooterService $scooterService
     * @param SerializerInterface $serializer
     * @param MessageBusInterface $eventBus
     * @return JsonResponse
     * @throws \ReflectionException
     */
    public function updateScooterLocation(
        Scooter $scooter,
        ValidatorInterface $validator,
        ScooterService $scooterService,
        SerializerInterface $serializer,
        MessageBusInterface $eventBus
    ): JsonResponse {
        // see a comment at line 35
        $this->denyAccessUnlessGranted('MANAGE', $scooter);

        $superior = new Superior(
            $this->request->getContent(),
            $this->request->getMethod()
        );

        $scooterUpdateLocationBuilder = new ScooterUpdateLocationBuilder(
            $scooter,
            $validator,
            $serializer,
            $scooterService,
            $this->request,
            $eventBus
        );

        return $superior->build($scooterUpdateLocationBuilder);
    }
}
