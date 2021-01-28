<?php

namespace App\Interfaces\Service;

use App\Entity\Scooter;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Interface ScooterServiceInterface
 *
 * @package App\Interfaces\Service
 */
interface ScooterServiceInterface
{
    /**
     * A default type for a service.
     */
    public const TYPES_HOLDER = [
        'Scooter'
    ];

    /**
     * Updates a scooter's status
     *
     * @param Scooter $scooter
     * @param boolean $status
     */
    public function scooterUpdateStatus(
        Scooter $scooter,
        bool $status
    ): void;

    /**
     * Updates a scooter's location
     *
     * @param $content
     * @param Scooter $scooter
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @param array $response
     * @param string $statusCode
     */
    public function scooterUpdateLocation(
        $content,
        Scooter $scooter,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        array &$response,
        string &$statusCode
    ): void;
}
