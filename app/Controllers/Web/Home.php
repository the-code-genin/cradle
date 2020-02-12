<?php
namespace App\Controllers\Web;

use Cradle\View;
use Cradle\Controller;
use Psr\Http\Message\ServerRequestInterface;

class Home extends Controller
{
    /**
     * The index page.
     */
    protected function index(ServerRequestInterface $request, object $params)
    {
        return new View('home.twig');
    }
}
