<?php
namespace Cradle\Components;

/**
 * The base class for all models in the system.
 * All valid model should extend this class or any of its subclasses for uniformity between models.
 */
abstract class Model
{
	abstract protected function getDB();
}
