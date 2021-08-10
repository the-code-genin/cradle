<?php

namespace Lib;

use Lib\JWT;
use Models\User;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthUserExtractor
{
    /**
     * Try to extract the authentication user from the request.
     *
     * @param Request $request
     * @return User|null
     */
    public static function extractAuthUserFromRequest(Request $request): ?User
    {
        try {
            // Verify the bearer token header is set
            $header = $request->getHeader('Authorization');
            if (count($header) != 1) throw new \Exception('Authorization header not set!');
            else if (!preg_match("/^Bearer (.+)$/i", $header[0])) throw new \Exception('Bearer token not set!');


            // Parse the bearer token
            preg_match("/^Bearer (.+)$/i", $header[0], $matches);
            $token = trim($matches[1]);
            $parsedToken = JWT::parseAccessToken($token);

            /** @var mixed $id */
            $id = $parsedToken->claims()->get('jti');


            // Validate the user object
            $user = User::where('id', $id)->first();
            if ($user == null) throw new \Exception('User is not Authenticated');
            else if ($user->authTokens()->where('auth_tokens.token', $token)->count() != 0) throw new \Exception('User is not Authenticated');
            else if ($user->status != 'active') throw new \Exception('User is banned from using the platform');


            // Return the auth user.
            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }
}
