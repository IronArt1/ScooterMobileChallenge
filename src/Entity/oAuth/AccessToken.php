<?php

namespace App\Entity\oAuth;

use OpenApi\Annotations as OA;

/**
 * Class AccessToken
 * @property string $refresh_token_expires_in
 * @property string $token_type
 * @property string $issued_at
 * @property string $mobile_id
 * @property string $access_token
 * @property string $refresh_token
 * @property string $scope
 * @property string $refresh_token_issued_at
 * @property string $scooter_id
 *
 * @package App\Entity\oAuth
 *
 * @OA\Info(
 *    description="An example's: {'refresh_token_expires_in': '239', 'token_type': 'BearerToken', 'issued_at': '1534777970288',
          'mobile_id': '43g4avGl8f9Ak6TBO0ELmhsRqjp2sGxP', 'access_token': 'hYadYpyspFBPan3vEhD7GhS91UzJ',
          'refresh_token': 'fSGgjdNlkDkbiArG5VRtuICZnJLb1tS0', 'scope': 'professional', 'refresh_token_issued_at': '1534777970288',
          'scooter_id': 'ABCDEFG'}"
 * )
 */
class AccessToken
{
    /**
     * @OA\Property(
     *    type="string",
     *    description="A certain amount of seconds'. An example's: 239"
     * ),
     * @var string
     */
    protected $refresh_token_expires_in = 'error';

    /**
     * @OA\Property(...)
     * @var string
     */
    protected $token_type = 'error';

    /**
     * @OA\Property(...)
     * @var string
     */
    protected $issued_at = 'error';

    /**
     * @OA\Property(...)
     * @var string
     */
    protected $mobile_id = 'error';

    /**
     * @OA\Property(...)
     * @var string
     */
    protected $access_token = 'error';

    /**
     * @OA\Property(...)
     * @var string
     */
    protected $refresh_token = 'error';

    /**
     * @OA\Property(...)
     * @var string
     */
    protected $scope = 'error';

    /**
     * @OA\Property(...)
     * @var string
     */
    protected $refresh_token_issued_at = 'error';

    /**
     * @OA\Property(...)
     * @var string
     */
    protected $scooter_id = 'error';
}
