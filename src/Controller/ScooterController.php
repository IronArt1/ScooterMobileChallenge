<?php

namespace App\Controller;

use App\Builder\ScooterUpdateLocationBuilder;
use App\Entity\Scooter;
use App\Builder\Superior;
use Codeception\Util\HttpCode;
use App\Service\ScooterService;
use Symfony\Component\HttpFoundation\{
    Request,
    Response,
    JsonResponse
};
use Doctrine\ORM\EntityManagerInterface;
use App\Builder\ScooterUpdateStatusBuilder;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/scooter/{id}/update-status", name="scooter-update-status", methods={"POST"})
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
     * @Route("/scooter/{id}/update-location", name="scooter-update-location", methods={"POST"})
     * @param Request $request
     * @param Scooter $scooter
     * @param ValidatorInterface $validator
     * @param ScooterService $scooterService
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     * @throws \ReflectionException
     */
    public function updateScooterLocation(
        Request $request,
        Scooter $scooter,
        ValidatorInterface $validator,
        ScooterService $scooterService,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
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
            $this->request
        );

        return $superior->build($scooterUpdateLocationBuilder);
    }
}
