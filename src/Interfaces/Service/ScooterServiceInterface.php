<?php

namespace App\Interfaces\Service;

use App\Manager\ScooterManager;
use App\Service\JWTTokenService;
use App\Interfaces\Manager\ManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;

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
     * Updates a scooter status
     */
    public function scooterUpdateStatus(Boolean $status): void;
}
