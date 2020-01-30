<?php
namespace App\Controllers;

use Cradle\View;
use Cradle\Controller;
use Psr\Http\Message\RequestInterface;

class Home extends Controller
{
    /**
     * The index page.
     */
    protected function index(RequestInterface $request, object $params)
    {
        return new View('home.twig');
    }
}
