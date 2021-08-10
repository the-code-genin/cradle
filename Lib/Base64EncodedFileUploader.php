<?php

namespace Lib;

class Base64EncodedFileUploader
{
    /**
     * Uploads a file relative to the storage directory.
     *
     * @param string $data
     * @param string $path
     * @return string The upload file name
     */
    public static function uploadFile(string $data, string $path): string
    {
        // Parse the image data
        $blob = base64_decode(@explode(',', $data, 2)[1]);
        $imageMeta = @getimagesizefromstring($blob);
        if (!$blob || !$imageMeta) throw new \Exception('Invalid image provided');


        // Parse the image name from the path
        $fullImageName = basename($path);
        $fullImageNameArray = explode('.', $fullImageName);
        $imageExtension = strtolower(preg_replace('/[^a-z0-9_]+/i', '-', array_pop($fullImageNameArray)));
        $imageName = strtolower(preg_replace('/[^a-z0-9_]+/i', '-', implode('.', $fullImageNameArray)));
        $parsedImageName = sprintf("%s-%s.%s", $imageName, random_int(111111, 999999), $imageExtension);

        // Parse the relative directory name from the path without the first backslash
        $relativeDirectoryPath = dirname($path);
        if ($relativeDirectoryPath[0] == '/') $relativeDirectoryPath = substr($relativeDirectoryPath, 1);


        // Upload the new image
        $result = file_put_contents(dirname(__DIR__) . '/storage/' . $relativeDirectoryPath . '/' . $parsedImageName, $blob);
        if (!$result) throw new \Exception("An error occured while uploading the file.");


        // Return the uploaded file path relative to the storage directory.
        return $parsedImageName;
    }
}
