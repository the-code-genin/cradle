<?php
namespace Cradle\Components;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * The base class for all models in the system.
 * All valid model should extend this class or any of its subclasses for uniformity between models.
 */
class Model extends EloquentModel
{
	/**
	 * Return a new new manager instance
	 *
	 * @return \Illuminate\Database\Capsule\Manager
	 */
	public static function getDB()
	{
		$manager = new Manager;
		return $manager->getConnection();
	}
}
