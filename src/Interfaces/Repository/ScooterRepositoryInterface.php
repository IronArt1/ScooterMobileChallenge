<?php

namespace App\Interfaces\Repository;


/**
 * Interface ScooterRepositoryInterface
 *
 * @package App\Interfaces\Repository
 */
interface ScooterRepositoryInterface
{
    /**
     * Scooter's statuses are
     */
    public const AVAILABLE_STATUS = 'available';
    public const OCCUPIED_STATUS = 'occupied';
}
