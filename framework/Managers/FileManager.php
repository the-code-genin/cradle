<?php
namespace Cradle\Managers;

/**
 * Convenience class for managing the application's file storage.
 */
class FileManager
{
	/** @var string $basePath The base path of the application's storage folder */
	protected $basePath;

	/** @var array $openModes Valid file open modes */
	protected $openModes = ['r', 'r+', 'rb', 'rb+', 'w', 'w+', 'wb', 'wb+', 'a', 'ab'];

	public function __construct()
	{
		$this->basePath = STORAGE_DIR;
	}

	/**
	 * Returns the full path to the storage folder.
	 * 
	 * @return string The path to the storage folder
	 */
	public function getStoragePath(): string
	{
		return $this->basePath;
	}

	/**
	 * Prepends the storage path to the string supplied to turn it into the full filepath for that asset.
	 * Note: Ensure the path supplied does not start with a forward slash '/' this might cause issues.
	 * 
	 * @param string $filePath The file path to the file
	 * 
	 * @return string The full file path
	 */
	public function getFilePath(string $filePath): string
	{
		return $this->basePath . '/' . $filePath;
	}

	/**
	 * Creates a new file.
	 * An alias of writeFile.
	 * 
	 * @param string $filePath The file path to the file to be created
	 * @param mixed $contents The contents of the file to be created
	 * 
	 * @return bool Returns true if the file was created successfully false if not
	 */
	public function createFile(string $filePath, $contents): bool
	{
		return $this->writeFile($filePath, $contents);
	}

	/**
	 * Opens and returns a handle to a file in the specified mode.
	 * 
	 * @param string $filePath The file path to the file to be opened
	 * @param string $mode The file open mode e.g r, w, a e.t.c
	 * 
	 * @return resource The opened file handle
	 */
	public function openFile(string $filePath, string $mode)
	{
		if (!in_array($mode, $this->openModes)) {
			throw new \Exception('Invalid file open mode');
		}

		$filePath = $this->getFilePath($filePath);
		$file = fopen($filePath, $mode);
		return $file;
	}

	/**
	 * Closes a file handle.
	 * 
	 * @param resource $reference The reference to the file to be closed
	 * 
	 * @return bool Returns true on success and false otherwise
	 */
	public function closeFile(&$reference): bool
	{
		return fclose($reference);
	}

	/**
	 * Copies a file from one location to another in the assets folder.
	 * 
	 * @param string $src The file path of the source file
	 * @param string $dest The file path of the destination file
	 * 
	 * @return bool Returns true on success and false otherwise
	 */
	public function copyFile(string $src, string $dest): bool
	{
		$srcPath = $this->getFilePath($src);
		$destPath = $this->getFilePath($dest);
		return copy($srcPath, $destPath);
	}

	/**
	 * Moves a file from one location to another in the assets folder.
	 * 
	 * @param string $src The file path of the source file
	 * @param string $dest The file path of the destination file
	 * 
	 * @return bool Returns true on success and false otherwise
	 */
	public function moveFile(string $src, string $dest): bool
	{
		$srcPath = $this->getFilePath($src);
		$destPath = $this->getFilePath($dest);
		return rename($srcPath, $destPath);
	}

	/**
	 * Checks if the file exists.
	 * 
	 * @param string $path The file path
	 * 
	 * @return bool Returns true on existence and false otherwise
	 */
	public function fileExists(string $path): bool
	{
		$filePath = $this->getFilePath($path);
		return is_file($filePath) && file_exists($filePath);
	}

	/**
	 * Gets the file size in bytes.
	 * 
	 * @param string $path The file path
	 * 
	 * @return int The file size in bytes
	 */
	public function getFileSize(string $path): int
	{
		$filePath = $this->getFilePath($path);
		return filesize($filePath);
	}

	/**
	 * Gets the file MIME type.
	 * 
	 * @param string $path The file path
	 * 
	 * @return string|null The file MIME type
	 */
	public function getFileMIME(string $path): ?string
	{
		$filePath = $this->getFilePath($path);
		$mime = mime_content_type($filePath);
		if (!$mime) {
			return null;
		}

		return $mime;
	}

	/**
	 * Saves an uploaded file to the assets directory.
	 * 
	 * @param string $src The file path of the source file
	 * @param string $dest The file path of the destination file
	 * 
	 * @return bool Returns true on success and false otherwise
	 */
	public function saveUploadedFile(string $src, string $dest): bool
	{
		$destPath = $this->getFilePath($dest);
		return move_uploaded_file($src, $destPath);
	}

	/**
	 * Reads the contents of a file.
	 * 
	 * @param string $path The file path
	 * 
	 * @return mixed The contents of the file
	 */
	public function readFile(string $path)
	{
		$filePath = $this->getFilePath($path);
		$contents = file_get_contents($filePath);
		return $contents;
	}

	/**
	 * Writes to a file.
	 * Will create the file if it does not exist.
	 * 
	 * @param string $path The file path
	 * @param mixed $contents The contents to write to the file
	 * 
	 * @return bool Returns true on success and false otherwise
	 */
	public function writeFile(string $path, $contents): bool
	{
		$filePath = $this->getFilePath($path);
		$result = file_put_contents($filePath, $contents);

		if ($result) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Deletes a file permanently.
	 * 
	 * @param string $path The file path
	 * 
	 * @return bool Returns true on success and false otherwise
	 */
	public function deleteFile(string $path): bool
	{
		$filePath = $this->getFilePath($path);
		return unlink($filePath);
	}

	/**
	 * Renames a file or directory.
	 * An alias of moveFile
	 * 
	 * @param string $src The file path of the source file
	 * @param string $dest The file path of the destination file
	 * 
	 * @return bool Returns true on success and false otherwise
	 */
	public function rename(string $src, string $dest): bool
	{
		return $this->moveFile($src, $dest);
	}

	/**
	 * Creates a new directory.
	 * 
	 * @param string $path The directory path
	 * 
	 * @return bool Returns true on success and false otherwise
	 */
	public function createDir(string $path): bool
	{
		$dirPath = $this->getFilePath($path);
		return mkdir($dirPath);
	}

	/**
	 * Deletes an empty directory
	 * 
	 * @param string $path The directory path
	 * 
	 * @return bool Returns true on success and false otherwise
	 */
	public function removeDir(string $path): bool
	{
		$dirPath = $this->getFilePath($path);
		return rmdir($dirPath);
	}

	/**
	 * Checks whether a directory exists.
	 * 
	 * @param string $path The directory path
	 * 
	 * @return bool Returns true on directory existence and false otherwise
	 */
	public function dirExists(string $path = ''): bool
	{
		$dirPath = $this->getFilePath($path);
		return is_dir($dirPath) && file_exists($dirPath);
	}

	/**
	 * Gets the list of files and subdirectories present in a directory.
	 * It excludes the '.' and '..' elements.
	 * 
	 * @param string $path The directory path
	 * 
	 * @return array|null An array of the files and subdirectories present in the directory without the '.' and '..' elements
	 */
	public function listDir(string $path = ''): ?array
	{
		$dirPath = $this->getFilePath($path);
		$contents = scandir($dirPath);
		if (!$contents) {
			return null;
		}

		// The '.' and '..' elements are removed
		array_splice($contents, array_search('.', $contents), 1);
		array_splice($contents, array_search('..', $contents), 1);

		return $contents;
	}
}
