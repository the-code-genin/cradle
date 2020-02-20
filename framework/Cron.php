<?php

namespace Cradle;

use Slim\App;
use DI\Container;

/**
 * The base class for all crons in the system.
 * All valid crons must either extend this class or extend one of its subclasses.
 * Cron methods should be made protected.
 */
abstract class Cron
{
	/** @var Container $container A container instance. */
	protected $container;

	/** @var Manager $db The database connection instance. */
	protected $db;

	public function __construct()
	{
		/** @var App $app */
		$app = require_once dirname(__DIR__) . '/bootstrap.php';
		$this->container = $app->getContainer();
		$this->db = $container->get('db');
	}

	/**
	 * When a controller method is called.
	 *
	 * @param string $name
	 * @param array $arguments
	 * @return mixed
	 */
	public function __call(string $name, array $arguments)
	{
		// If the app is in maintenenance mode don't execute the cron.
		if (getenv('APP_ENVIRONMENT') == 'maintenance') {
			return;
		}

		$result = call_user_func_array([$this, $name], $arguments);
		return $result;
	}
}
