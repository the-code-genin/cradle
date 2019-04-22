<?php
namespace Iyiola\Util;

/**
 * The class handles query data and purifies it automatically for use by the application
 */
class Request
{	
	/**
	 * Gets and purifies REQUEST data
	 */
	public static function request(string $key): ?string
	{
		if (!isset($_REQUEST[$key])) {
			return null;
		}

		return urldecode($_REQUEST[$key]);
	}

	/**
	 * Gets and purifies POST data
	 */
	public static function post(string $key): ?string
	{
		if (!isset($_POST[$key])) {
			return null;
		}

		return $_POST[$key];
	}
	
	/**
	 * Gets and purifies GET data
	 */
	public static function get(string $key): ?string
	{
		if (!isset($_GET[$key])) {
			return null;
		}

		return urldecode($_GET[$key]);
	}

	/**
	 * Gets an uploaded file
	 */
	public static function file(string $key): ?array
	{
		if (!isset($_FILES[$key])) {
			return null;
		}

		return $_FILES[$key];
	}
}
