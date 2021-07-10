<?php

namespace Lib;

use Models\User;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

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
    public static function parseAccessToken(string $token): Plain
    {
        $config = static::getConfig();
        $parsedToken = $config->parser()->parse($token);
        return $parsedToken;
    }
}