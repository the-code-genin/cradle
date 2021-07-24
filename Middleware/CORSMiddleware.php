<?php

namespace Middleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

/**
 * This middleware enables CORS support for the API endpoints.
 */
class CORSMiddleware implements MiddlewareInterface
{
    public function process(Request $request, RequestHandler $handler): Response
    {
        $response = $handler->handle($request);
        return $response->withHeader('Cache-Control', ['no-cache', 'must-revalidate'])
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            ->withHeader('Access-Control-Max-Age', '86400')
            ->withHeader('Access-Control-Allow-Headers', [
                'Origin', 'X-Requested-With', 'Content-Type', 'Accept', 'Authorization', 'X-Http-Method-Override', 'Cookie'
            ])
            ->withHeader('Access-Control-Allow-Methods', ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'])
            ->withHeader('Access-Control-Allow-Origin', '*');
    }
}
