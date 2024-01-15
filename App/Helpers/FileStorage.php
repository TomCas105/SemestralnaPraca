<?php

namespace App\Helpers;

use App\Core\HTTPException;

class FileStorage
{
    // Dir, where to store file
    const UPLOAD_DIR = "App/Resources/images" . DIRECTORY_SEPARATOR ."previews";

    /**
     * Uploads file to given directory and returns new filename
     * @param array $fileData
     * @return string New unique filename
     * @throws HTTPException Throw exception if file move was unsuccessful
     */
    public static function saveFile($fileData) : string {
        // generating prefix
        $filePrefix = hrtime(true);
        // build storing file path
        $filePath = FileStorage::UPLOAD_DIR . DIRECTORY_SEPARATOR . $filePrefix . "-" .$fileData['name'];
        // do the move
        if (move_uploaded_file($fileData['tmp_name'], $filePath)) {
            return $filePrefix . "-" .$fileData['name'];
        } else {
            throw new HTTPException(503, 'File '.$filePath.' upload failed. Did you create upload directory? ');
        }
    }

    /**
     * Deletes file from file storage
     * @param string $filename
     * @return void
     */
    public static function deleteFile(string $filename)
    {
        unlink(FileStorage::UPLOAD_DIR . DIRECTORY_SEPARATOR . $filename);
    }
}