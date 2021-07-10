<?php
namespace Middleware;

use Lib\JWT;
use Models\User;
use Lib\Errors\AuthenticationError;
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
            else if (!preg_match("/^Bearer (.+)$/i", $header[0])) throw new \Exception('Bearer token not set!');
        

            // Parse the bearer token
            preg_match("/^Bearer (.+)$/i", $header[0], $matches);
            $token = trim($matches[1]);
            $parsedToken = JWT::parseAccessToken($token);

            /** @var mixed $id */
            $id = $parsedToken->claims()->id;


            // Validate the user object
            $user = User::where('id', $id)->first();
            if ($user == null) throw new \Exception('User is not Authenticated');
            else if ($user->authTokens()->where('auth_tokens.token', $token)->count() != 0) throw new \Exception('User is not Authenticated');
            else if ($user->status != 'active') throw new \Exception('User is banned from using the platform');


            // Make the user object available for subsequent requests
            $request = $request->withAttribute('authUser', $user);
        } catch(\Exception $e) {
            $response = new Psr7Response();
            $response->getBody()->write((string) new AuthenticationError($e->getMessage()));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }


        // Process the request
        $response = $handler->handle($request);
        return $response;
    }
}
