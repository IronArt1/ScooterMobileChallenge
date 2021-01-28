<?php

namespace App\Interfaces\Service;

use App\Interfaces\Manager\ManagerInterface;

/**
 * Interface ServiceInterface
 *
 * @package App\Interfaces\Service
 */
interface ServiceInterface
{
    /**
     * Common headers for every API request're
     */
    public const HEADERS = [
        'Api-Key-Verified' => true,
        'Cache-Control'    => 'no-cache',
        'Content-Type'     => 'application/json',
        'Accept'           => 'application/json',
        'Connection'       =>  'keep-alive',
        'Authorization'    => 'Bearer '
    ];

    /**
     * Types of requests being used by App
     */
    public const REQUEST_TYPE_GET  = 'GET';
    public const REQUEST_TYPE_POST = 'POST';

    /**
     * An authenticated user id header's.
     */
    public const RESERVED_HEADER_AUTHENTICATED_APP_ID = 'Authenticated-App-Id';

    /**
     * An amount of token parts'
     */
    public const AMOUNT_OF_TOKEN_PARTS_HOLDER = 3;
    
    /**
     * A holder for Symfony URL type
     */
    public const SYMFONY_URL_HOLDER = 'urlSymfony';

    /**
     * A search type holder is
     */
    public const CREATE_HOLDER = 'create';

    /**
     * Set a certain JSON for body in a Guzzle request
     *
     * @param array $types
     * @return ServiceInterface
     */
    public function setBodyFields(array $types): self;

    /**
     * Make data for body
     *
     * @return object
     */
    public function makeBody(): \stdClass;

    /**
     * Sets up fields for fieldsMatches in a request
     *
     * @param array $data
     * @param array $parameters
     * @param string|null $defaultName
     * @param string|null $defaultProperty
     * @return array
     */
    public function setUpFieldsData(
        array &$data,
        array $parameters,
        string $defaultName = null,
        string $defaultProperty = null
    ): array;
}
