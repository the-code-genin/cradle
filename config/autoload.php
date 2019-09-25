<?php

/**
 * This file simply acts as an autoloader for all the framework configuration files.
 * Since constants can't be autoloaded like classes.
 */

$configFiles = scandir(__DIR__);
foreach ($configFiles as $file) { // Include every valid php file in the current directory apart from the autoload file
	if (preg_match('#[a-z\-\_0-9]+\.php#i', $file) && $file != 'autoload.php') {
		require_once __DIR__ . '/' . $file;
	}
}
