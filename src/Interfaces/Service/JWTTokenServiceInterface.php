<?php

namespace App\Interfaces\Service;

/**
 * Interface JWTTokenServiceInterface
 * @package App\Interfaces\Service
 */
interface JWTTokenServiceInterface
{
    /**
     * A header of a token's
     */
    public const HEADER = [
        "alg" => "HS256",
        "typ" => "JWT"
    ];

    /**
     * An amount of token parts'
     */
    public const AMOUNT_OF_TOKEN_PARTS_HOLDER = 3;

    /**
     * Generate a JWT for a customer
     *
     * @param array $payload
     * @return string
     */
    public function generateToken(array $payload): string;

    /**
     * Verify a signature
     *
     * @param array $parts
     * @return \stdClass
     */
    public function verifySignature(array $parts): \stdClass;

    /**
     * A safe way to compare using ASCII
     *
     * @param string $expected
     * @param string $generated
     *
     * @return bool
     */
    public function hashEquals($expected, $generated): bool;

    /**
     * Validate a structure of a token and get a payload
     *
     * @param string $token
     * @param string $tokenType
     */
    public function validateToken(
        string $token,
        string $tokenType
    ): void;
}
