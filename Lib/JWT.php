<?php

namespace Lib;

use Models\User;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;

class JWT {
    /**
     * Get configuration for the JWT token.
     *
     * @return Configuration
     */
    protected static function getConfig(): Configuration
    {
        return Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::plainText(getenv('APP_KEY'))
        );
    }
    /**
     * Generate a JWT access token for a user.
     *
     * @param User $user
     * @return string
     */
    public static function generateAccessToken(User $user): string
    {
        $config = static::getConfig();
        return $config
            ->builder()
            ->issuedBy(getenv('APP_URL'))
            ->identifiedBy($user->id)
            ->getToken($config->signer(), $config->signingKey())
            ->toString();
    }

    /**
     * Verify an access token and return the user id
     *
     * @param string $token
     * @return integer
     */
    public static function parseAccessToken(string $token): Token
    {
        $config = static::getConfig();
        $parsedToken = $config->parser()->parse($token);
        $constraints = $config->validationConstraints();

        // Validate the parsed token
        try {
            $config->validator()->assert($parsedToken, ...$constraints);
        } catch (RequiredConstraintsViolated $e) {
            throw new \Exception('Invalid access token.');
        }

        // Return the parsed token
        return $parsedToken;
    }
}