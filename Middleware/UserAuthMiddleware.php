<?php

namespace Middleware;

use Database\Repositories\Users;
use Lib\ApiResponses;
use Lib\JWT;
use Psr\Http\Server\MiddlewareInterface;
use Nyholm\Psr7\Response as Psr7Response;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * This middleware implements the auth interface.
 */
class UserAuthMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        try {
            // Verify the bearer token header is set
            $header = $request->getHeader('Authorization');
            if (count($header) != 1) throw new \Exception('Authorization header not set!');
            else if (!preg_match("/^Bearer (.+)$/i", $header[0])) throw new \Exception('Bad/Invalid bearer token.');

            // Parse the bearer token
            preg_match("/^Bearer (.+)$/i", $header[0], $matches);
            $token = trim($matches[1]);
            $parsedToken = JWT::parseAccessToken($token);

            /** @var mixed $id */
            $id = $parsedToken->claims()->get('jti');

            // Validate the user object
            $user = Users::getUserById($id);
            if ($user == null) throw new \Exception('Bad/Invalid bearer token.');
            else if (Users::checkUserHasAuthToken($user->id, $token)) throw new \Exception('Expired bearer token.');
            else if ($user->status != 'active') throw new \Exception('User is banned from using the platform');


            // Make the user object available for subsequent requests
            $request = $request->withAttribute('authUser', $user);
        } catch (\Exception $e) {
            $response = new Psr7Response();
            return ApiResponses::generateAuthenticationError($response, $e->getMessage());
        }

        // Process the request
        $response = $handler->handle($request);
        return $response;
    }
}
