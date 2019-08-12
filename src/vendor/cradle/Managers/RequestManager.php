<?php
namespace Cradle\Managers;

/**
 * The class acts as a wrapper for handling request and form data.
 */
class RequestManager
{
	/**
	 * Gets and purifies POST data.
	 * 
	 * @param string $key The key for the POST data
	 * 
	 * @return string|null The value of the POST data
	 */
	public function post(string $key): ?string
	{
		if (!isset($_POST[$key])) {
			return null;
		}

		return $_POST[$key];
	}

	/**
	 * Gets and purifies GET data.
	 * 
	 * @param string $key The key for the GET data
	 * 
	 * @return string|null The value of the GET data
	 */
	public function get(string $key): ?string
	{
		if (!isset($_GET[$key])) {
			return null;
		}

		return urldecode($_GET[$key]);
	}

	/**
	 * Gets an uploaded file.
	 * 
	 * @param string $key The key for the file
	 * 
	 * @return array|null An associated array representing the uploaded file
	 */
	public function file(string $key): ?array
	{
		if (!isset($_FILES[$key])) {
			return null;
		}

		return $_FILES[$key];
	}
}
