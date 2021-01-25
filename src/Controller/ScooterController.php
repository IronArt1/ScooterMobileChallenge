<?php

namespace App\Controller;

use App\Builder\ScooterUpdateStatusBuilder;
use App\Builder\Superior;
use App\Entity\Scooter;
use App\Service\ScooterService;
use Codeception\Util\HttpCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ScooterController extends BaseController
{
    /**
     * Updates a scooter's status
     *
     * @Route("/scooter/update-status", name="scooter-update-status", methods={"POST"})
     * @param ScooterService $scooterService
     * @return Response
     * @throws \ReflectionException
     */
    //#[Route('/scooter/update-status', name: 'scooter-update-status', methods: ['POST'])]
    public function scooterUpdateStatus(ScooterService $scooterService): Response
    {
        $superior = new Superior(
            $this->request->getContent(),
            $this->request->getMethod()
        );

        $scooterUpdateStatusBuilder = new ScooterUpdateStatusBuilder(
            $scooterService,
            $this->dispatcher
        );

        return $superior->build($scooterUpdateStatusBuilder);
    }

    /**
     * Allows update location for a scooter
     *
     * @param Scooter $scooter
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    #[Route('/scooter/update-location', name: 'scooter-update-location')]
    public function updateScooterLocation(
        Scooter $scooter,
        EntityManagerInterface $entityManager,
        SerializerInterface $serializer,
        Request $request,
        ValidatorInterface $validator
    ): JsonResponse {
        $serializer->deserialize(
            $request->getContent(),
            Scooter::class,
            'json',
            [
                'object_to_populate' => $scooter,
                'groups' => ['input']
            ]
        );

        $violations = $validator->validate($scooter);
        if ($violations->count() > 0) {
            return $this->json($violations, HttpCode::BAD_REQUEST);
        }

        $entityManager->persist($scooter);
        $entityManager->flush();

        return $this->json(
            $scooter,
            HttpCode::OK,
            [],
            [
                'groups' => ['location']
            ]
        );
    }
}
