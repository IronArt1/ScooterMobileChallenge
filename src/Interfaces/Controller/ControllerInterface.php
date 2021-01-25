<?php

namespace App\Interfaces\Controller;

/**
 * Interface ControllerInterface
 * @package App\Interfaces\Controller
 */
interface ControllerInterface
{
    /**
     * An authenticated user id header's.
     */
    public const RESERVED_HEADER_AUTHENTICATED_APP_ID = 'Authenticated-App-Id';

    /**
     * An application ID's
     */
    public const MY_ACUVUE_APP_ID = 'myScooterAppId';

    /**
     * A myScooterId holder's
     */
    public const  MY_Scooter_ID_HOLDER = '/myScooterId';

    /**
     * A mobile holder's
     */
    public const MOBILE_HOLDER = '/mobile';

    /**
     * A string type holder's
     */
    public const STRING_TYPE_HOLDER = 'string';

    /**
     * A boolean type holder's
     */
    public const BOOLEAN_TYPE_HOLDER = 'boolean';

    /**
     * A status type holder's
     */
    public const STATUS_TYPE_HOLDER = 'status';

    /**
     * An array type holder's
     */
    public const ARRAY_TYPE_HOLDER = 'array';
}
