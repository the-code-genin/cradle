<?php
namespace App\Controllers\Api;

use Cradle\Controller;
use Psr\Http\Message\ServerRequestInterface;

class Home extends Controller
{
    /**
     * The index route.
     */
    protected function index(ServerRequestInterface $request, object $params)
    {
        return [
            'success' => true,
            'message' => 'Hello world',
            'data' => [],
            'error_code' => '',
        ];
    }
}
