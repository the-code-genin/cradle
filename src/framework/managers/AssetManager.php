<?php
namespace Cradle\Framework\Managers;

/**
 * Convenience class for managing the application's asset files.
 * You should avoid working with the asset files manually if possible and you this wrapper instead.
 */
class AssetManager
{
	// The base path of the application's assets folder
	protected $basePath;

	// Valid file open modes
	protected $openModes = ['r', 'r+', 'rb', 'rb+', 'w', 'w+', 'wb', 'wb+', 'a', 'ab'];

	public function __construct()
	{
		$this->basePath = $_SERVER['DOCUMENT_ROOT'] . '/application/assets/';
	}

	/**
	 * Returns the full path to the assets folder with the trailing backslash.
	 */
	public function getAssetsPath(): string
	{
		return $this->basePath;
	}

	/**
	 * Creates a new file.
	 * An alias of writeFile.
	 */
	public function createFile(string $path, $contents): bool
	{
		return $this->writeFile($filePath, $contents);
	}

	/**
	 * Opens and returns a handle to a file in the specified mode.
	 */
	public function openFile(string $path, string $mode)
	{
		if (!in_array($mode, $this->openModes)) {
			throw new \Exception('Invalid file open mode');
		}

		$filePath = $this->basePath . $path;
		$file = fopen($filePath, $mode);
		return $file;
	}

	/**
	 * Closes a file handle.
	 */
	public function closeFile($reference): bool
	{
		return fclose($reference);
	}

	/**
	 * Copys a file from one location to another in the assets folder.
	 */
	public function copyFile(string $src, string $dest): bool
	{
		$srcPath = $this->basePath . $src;
		$destPath = $this->basePath . $dest;
		return copy($srcPath, $destPath);
	}

	/**
	 * Moves a file from one location to another in the assets folder.
	 */
	public function moveFile(string $src, string $dest): bool
	{
		$srcPath = $this->basePath . $src;
		$destPath = $this->basePath . $dest;
		return rename($srcPath, $destPath);
	}

	/**
	 * Checks if the file exists.
	 */
	public function fileExists(string $path): bool
	{
		$filePath = $this->basePath . $path;
		return is_file($filePath) & file_exists($filePath);
	}

	/**
	 * Gets the file size in bytes.
	 */
	public function fileSize(string $path): int
	{
		$filePath = $this->basePath . $path;
		return filesize($filePath);
	}

	/**
	 * Gets the file MIME type.
	 */
	public function fileMIME(string $path): ?string
	{
		$filePath = $this->basePath . $path;
		$mime = mime_content_type($filePath);
		if (!$mime) {
			return null;
		}

		return $mime;
	}

	/**
	 * Saves an uploaded file to the assets directory.
	 */
	public function saveUploadedFile(string $src, string $dest): bool
	{
		$destPath = $this->basePath . $dest;
		return move_uploaded_file($src, $destPath);
	}

	/**
	 * Reads the contents of a file.
	 */
	public function readFile(string $path)
	{
		$filePath = $this->basePath . $path;
		$contents = file_get_contents($filePath);
		return $contents;
	}

	/**
	 * Writes to a file.
	 * Will create the file if it does not exist.
	 */
	public function writeFile(string $path, $contents): bool
	{
		$filePath = $this->basePath . $path;
		$result = file_put_contents($filePath, $contents);

		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Deletes a file permanently.
	 */
	public function deleteFile(string $path): bool
	{
		$filePath = $this->basePath . $path;
		return unlink($filePath);
	}

	/**
	 * Renames a file or directory.
	 * An alias of moveFile
	 */
	public function rename(string $src, string $dest): bool
	{
		return $this->moveFile($src, $dest);
	}

	/**
	 * Creates a new directory.
	 */
	public function createDir(string $path): bool
	{
		$dirPath = $this->basePath . $path;
		return mkdir($dirPath);
	}

	/**
	 * Deletes an empty directory
	 */
	public function removeDir(string $path): bool
	{
		$dirPath = $this->basePath . $path;
		return rmdir($dirPath);
	}

	/**
	 * Checks whether a directory exists.
	 */
	public function dirExists(string $path = ''): bool
	{
		$dirPath = $this->basePath . $path;
		return is_dir($dirPath) & file_exists($dirPath);
	}

	/**
	 * Gets the list of files and subdirectories present in a directory.
	 * It excludes the '.' and '..' elements.
	 */
	public function listDir(string $path = ''): ?array
	{
		$dirPath = $this->basePath . $path;
		$contents = scandir($dirPath);
		if (!$contents) {
			return null;
		}

		// Remove the '.' and '..' elements
		array_splice($contents, array_search('.', $contents), 1);
		array_splice($contents, array_search('..', $contents), 1);

		return $contents;
	}
}
