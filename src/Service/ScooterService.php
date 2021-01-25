<?php

namespace App\Service;

use App\Interfaces\Service\ScooterServiceInterface;
use App\Repository\ScooterRepository;
use phpDocumentor\Reflection\Types\Boolean;

/**
 * Class ScooterService
 *
 * @package App\Service
 */
class ScooterService extends BaseService implements ScooterServiceInterface
{
    /**
     * @var ScooterRepository
     */
    private $scooterRepository;

    /**
     * ScooterService constructor.
     *
     * @param array $data
     */
    public function __construct(
        array $data = ['test' => 'it will be not optional!!'],
        ScooterRepository $scooterRepository
    ) {
        parent::__construct($data);

        $this->scooterRepository = $scooterRepository;
    }

    /**
     * @inheritDoc
     */
    public function scooterUpdateStatus(Boolean $status): void
    {
        $this->scooterRepository->scooterUpdateStatus($status);
    }
}
