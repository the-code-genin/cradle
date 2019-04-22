<?php
/**
 * The autoloader file contains function which help fetch classes from packages in the vendors application subdirectory
 */

// Autoloader function for PSR-4 namespaced packages in the vendors directory
spl_autoload_register(function ($class) {
	// Get the file path of the class to be loaded
	$class_path = str_replace('\\', '/', $class);
	$file_path = __DIR__ . '/' . $class_path . '.php';

	// Include the class file if it exists	
	if (file_exists($file_path)) {
		require_once $file_path;
	}
});



// Autoloader function for Cradle framework components
spl_autoload_register(function ($class) {
	// Confirm a that a cradle component is to be loaded
	$class_path = explode('\\', $class);
	if ($class_path[0] != 'Cradle') {
		return;
	}

	// Get the filepath of the component
	array_shift($class_path);
	for ($i = 0; $i < count($class_path) - 1; $i++) {
		$class_path[$i] = strtolower($class_path[$i]);
	}
	$file_path = $_SERVER['DOCUMENT_ROOT'] . '/' . implode('/', $class_path) . '.php';

	// Include the class file if it exists
	if (file_exists($file_path)) {
		require_once $file_path;
	}
});
