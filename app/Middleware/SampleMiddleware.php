<?php
namespace App\Middleware;

use Cradle\MiddleWare;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * This is just a sample before middleware to illustrate how middleware are supposed to be created in cradle.
 */
class SampleMiddleware extends MiddleWare
{
	public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);
        return $response;
    }
}
