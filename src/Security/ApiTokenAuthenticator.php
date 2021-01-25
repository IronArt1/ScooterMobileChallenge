<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use App\Service\BaseService;
use App\Service\JWTTokenService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class ApiTokenAuthenticator
 *
 * @package App\Security
 */
class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * API Token repository's
     *
     * @var ApiTokenRepository
     */
    private $apiTokenRepo;

    /**
     * @var JWTTokenService
     */
    private $JWTTokenService;

    public function __construct(
        ApiTokenRepository $apiTokenRepo,
        JWTTokenService $JWTTokenService
    ) {
        $this->apiTokenRepo = $apiTokenRepo;
        $this->JWTTokenService = $JWTTokenService;
    }

    public function supports(Request $request)
    {
        // look for header "Authorization: Bearer <token>"
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');

        // skip beyond "Bearer "
        return substr($authorizationHeader, 7);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $this->apiTokenRepo->findOneBy([
            'token' => $credentials
        ]);

        if (!$token) {
            throw new CustomUserMessageAuthenticationException(
                'Invalid API Token'
            );
        }

        $this->JWTTokenService->validateToken($token->getToken(), $token->getType());

        return $token->getOwner();
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'message' => $exception->getMessageKey()
        ], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // allow the request to continue
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new \Exception('Not used: entry_point from other authentication is used');
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
