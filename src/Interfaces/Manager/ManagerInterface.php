<?php

namespace App\Interfaces\Manager;

use App\Entity\BaseEntity;
use App\Manager\BaseManager;
use App\Interfaces\Controller\ControllerInterface;

/**
 * Interface ManagerInterface
 * @package App\Interfaces\Service
 */
interface ManagerInterface
{
    /**
     * Mapping data to an appropriate entity
     *
     * @param BaseEntity $entity
     * @param array|null $data
     * @return BaseManager
     */
    public function processMapping(BaseEntity $entity, $data = null): BaseManager;

    /**
     * Make a response for Controller from a current scope of data
     *
     * @param array|null $data
     * @return array
     */
    public function makeResponse(array $data = null): array;

    /**
     * Create a query data
     *
     * @param array $collection
     * @param string $parameter
     * @param string $holderName
     * @return array
     */
    public static function getQueryData(
        array $collection,
        string $parameter,
        string $holderName = ControllerInterface::ID_HOLDER
    ): array;

    /**
     * Create a simple response
     *
     * @param array $collection
     * @return array
     */
    public function createSimpleResponse(array $collection): array;
}
