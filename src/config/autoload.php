<?php

/**
 * This file simply acts as an autoloader for all the framework configuration files.
 * Since constants can't be autoloaded like classes.
 */

$configFiles = scandir(__DIR__);
array_walk($configFiles, function ($file, $key) {
	if (preg_match('#[A-Z\-\_0-9]+\.php#', $file) && $file != 'autoload.php') {
		require_once __DIR__ . '/' . $file;
	}
});
