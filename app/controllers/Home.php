<?php
namespace App\Controllers;

use Cradle\Components\Controller;

class Home extends Controller
{
    /**
    * The index page.
    */
    public function index(): void
    {
        $this->loadView('home');
    }

    /**
    * The site maintenance page.
    */
    public function maintenance(): void
    {
        $this->loadView('framework/maintenance');
    }

    /**
    * The 404 error page.
    */
    public function error404(): void
    {
        $this->setStatusCode(404);
        $this->loadView('framework/error404');
    }
}
