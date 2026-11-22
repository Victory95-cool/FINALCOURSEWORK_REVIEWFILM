<?php

class ImageUpload
{
    private const MAX_BYTES = 5242880;
    private const EXTENSIONS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    public function save($file, $directory)
    {
        if (!in_array($directory, ['posters', 'review-images'], true)) {
            throw new InvalidArgumentException('Unsupported image upload destination.');
        }
        if (!is_array($file) || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK || !isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Image upload failed. Please choose another image.');
        }
        $fileSize = filesize($file['tmp_name']);
        if ($fileSize === false || $fileSize > self::MAX_BYTES) {
            throw new RuntimeException('Image must be 5 MB or smaller.');
        }

        $imageInfo = getimagesize($file['tmp_name']);
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        if (!$imageInfo || !isset(self::EXTENSIONS[$mime]) || $imageInfo['mime'] !== $mime) {
            throw new RuntimeException('Image must be a valid JPG, PNG, or WebP file.');
        }

        $targetDirectory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $directory;
        if (!is_dir($targetDirectory) && !mkdir($targetDirectory, 0755, true) && !is_dir($targetDirectory)) {
            throw new RuntimeException('Image upload directory could not be created.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . self::EXTENSIONS[$mime];
        if (!move_uploaded_file($file['tmp_name'], $targetDirectory . DIRECTORY_SEPARATOR . $filename)) {
            throw new RuntimeException('Image could not be saved.');
        }

        return 'images/' . $directory . '/' . $filename;
    }

    public function deleteManaged($path, $directory)
    {
        if (!in_array($directory, ['posters', 'review-images'], true)
            || !is_string($path)
            || !preg_match('~^images/' . preg_quote($directory, '~') . '/[a-f0-9]{32}\.(jpg|png|webp)$~', $path)
        ) {
            return;
        }

        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
        if (is_file($file) && !unlink($file)) {
            error_log('Unable to remove managed image: ' . $path);
        }
    }
}
