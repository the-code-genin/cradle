<?php
namespace Cradle\Application\Controllers;

use Cradle\Framework\Controller;

class MainController extends Controller
{
	/**
	 * The index page
	 */
	public function index(): void
	{
		$this->loadView('home');
	}

	/**
	 * The site maintenance page
	 */
	public function maintenance(): void
	{
		$this->loadView('maintenance');
	}

	/**
	 * The 404 error page
	 */
	public function error404(): void
	{
		http_response_code(404);
		$this->loadView('error404');
	}
}
