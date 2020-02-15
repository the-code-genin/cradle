<?php

namespace Cradle;

use Slim\App;
use DI\Container;

/**
 * The base class for all crons in the system.
 * All valid crons must either extend this class or extend one of its subclasses.
 */
abstract class Cron
{
	/** @var Container $container A container instance. */
	protected $container;

	public function __construct()
	{
		/** @var App $app */
		$app = require_once dirname(__DIR__) . '/bootstrap.php';
		$this->container = $app->getContainer();
	}

	/**
	 * When a controller method is called.
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return void
	 */
	public function __call(string $name, array $arguments): void
	{
		$params = (object) $arguments[0];
		call_user_func_array([$this, $name], [$params]);
	}
}
