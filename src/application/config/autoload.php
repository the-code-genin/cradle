<?php
/**
 * This file simply acts as an autoloader for all the configuration files in this directory.
 * Constants can't be autoloaded by spl functions like classes.
 */

// Require all the valid site configuration files
// Must be all uppercase and can only contain alphabets, numbers, hypens and underscores
$configFiles = scandir(__DIR__);
array_walk($configFiles, function ($file, $key) {
	if (preg_match('#[A-Z\-\_0-9]+\.php#', $file)) {
		require_once __DIR__ . '/' . $file;
	}
});
