<?php
/**
 * The autoloader file contains functions which help fetch classes from packages in the vendors directory
 */

// Autoloader function for PSR-4 namespaced packages in the vendors directory
spl_autoload_register(function ($class) {
	// Get the file path of the class to be loaded
	$classPath = str_replace('\\', '/', $class);
	$filePath = __DIR__ . '/' . $classPath . '.php';

	// Include the file if it exists
	if (file_exists($filePath)) {
		require_once $filePath;
	}
});



// Autoloader function for cradle core framework components
spl_autoload_register(function ($class) {
	// Confirm a that a cradle core framework component is to be loaded
	$classPath = explode('\\', $class);
	if ($classPath[0] != 'Cradle') {
		return;
	}

	// Get the file path of the component to be loaded
	array_shift($classPath);
	for ($i = 0; $i < count($classPath) - 1; $i++) {
		$classPath[$i] = strtolower($classPath[$i]);
	}
	$filePath = $_SERVER['DOCUMENT_ROOT'] . '/' . implode('/', $classPath) . '.php';

	// Include the file if it exists
	if (file_exists($filePath)) {
		require_once $filePath;
	}
});
