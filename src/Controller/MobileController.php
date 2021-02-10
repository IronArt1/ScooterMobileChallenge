<?php

namespace App\Controller;

use App\Entity\Mobile;
use App\Builder\Superior;
use App\Service\ScooterService;
use Symfony\Component\HttpFoundation\{
    Response,
    JsonResponse
};
use App\Repository\MobileRepository;
use App\Validator\ContainsAlphaValidator;
use App\Builder\MobileGetScooterStatusBuilder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class MobileController
 *
 * @package App\Controller
 */
class MobileController extends BaseController
{
    /**
     * Gets available scooters
     *
     * @Route("/mobile/{id}/get-scooters-status/{status}", name="get-scooters-status", methods={"POST"})
     * @param string $status [all|available|occupied]
     * @param ScooterService $scooterService
     * @param SerializerInterface $serializer
     * @param ContainsAlphaValidator $statusValidator
     * @return Response
     * @throws \ReflectionException
     */
    public function getScootersStatus(
        string $status,
        Mobile $mobile,
        ScooterService $scooterService,
        SerializerInterface $serializer,
        ContainsAlphaValidator $statusValidator
    ): JsonResponse {
        // Here we can verify that a mobile is an actual owner of its table record
        // and can make such requests (against imposters)
        $this->denyAccessUnlessGranted('OBSERVE', $mobile);
        $superior = new Superior(
            $status,
            $this->request->getContent(),
            $this->request->getMethod()
        );

        $mobileGetScooterStatusBuilder = new MobileGetScooterStatusBuilder(
            $serializer,
            $scooterService,
            $statusValidator
        );

        return $superior->build($mobileGetScooterStatusBuilder);
    }

    /**
     * A dummy end point is that must be a microservice in a real App
     *
     * @Route("/mobile", name="get-mobile", methods={"GET"})
     * @param SerializerInterface $serializer
     * @param MobileRepository $mobileRepository
     */
    public function dummyAPIEndPoint(
        SerializerInterface $serializer,
        MobileRepository $mobileRepository
    ): JsonResponse
    {
        $mobiles = $mobileRepository->findAll();

        $json = $serializer->serialize(
            $mobiles,
            'json',
            array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
            ],
                ['groups' => ['main']]
            )
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}
