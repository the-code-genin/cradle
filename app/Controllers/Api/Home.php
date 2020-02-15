<?php
namespace App\Controllers\Api;

use Cradle\Controller;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Session\Session ;

class Home extends Controller
{
    /**
     * The index route.
     */
    protected function index(ServerRequestInterface $request, object $params)
    {
        /** @var Session $session */
        $session = $this->container->get('session');
        
        return [
            'success' => true,
            'message' => 'Hello world',
            'data' => [],
            'error_code' => '',
        ];
    }
}
