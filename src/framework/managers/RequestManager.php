<?php
namespace Cradle\Framework\Managers;

/**
 * The class acts as a wrapper for handling request and form data.
 */
class RequestManager
{
	/**
	 * Gets and purifies POST data.
	 */
	public static function post(string $key): ?string
	{
		if (!isset($_POST[$key])) {
			return null;
		}

		return $_POST[$key];
	}

	/**
	 * Gets and purifies GET data.
	 */
	public static function get(string $key): ?string
	{
		if (!isset($_GET[$key])) {
			return null;
		}

		return urldecode($_GET[$key]);
	}

	/**
	 * Gets an uploaded file.
	 */
	public static function file(string $key): ?array
	{
		if (!isset($_FILES[$key])) {
			return null;
		}

		return $_FILES[$key];
	}
}
