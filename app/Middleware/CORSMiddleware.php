<?php
namespace App\Middleware;

use Cradle\MiddleWare;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This middleware enables CORS support for the api.
 */
class CORSMiddleware extends MiddleWare
{
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
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
