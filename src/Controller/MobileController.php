<?php

namespace App\Controller;

use App\Entity\Mobile;
use App\Builder\Superior;
use Codeception\Util\HttpCode;
use Symfony\Component\HttpFoundation\{
    Request,
    Response,
    JsonResponse
};
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class MobileController
 *
 * @package App\Controller
 */
class MobileController extends BaseController
{
    /**
    */
    public function scooterUpdateStatus(

    ): JsonResponse {

    }

    /**
    */
    public function updateScooterLocation(

    ): JsonResponse {

    }
}
