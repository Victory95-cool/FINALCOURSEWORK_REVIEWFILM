<?php

class ProfileImage
{
    private const MAX_BYTES = 5242880;

    public function saveUploaded($file)
    {
        if (!is_array($file) || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Avatar upload failed. Please choose another image.');
        }
        if ($file['size'] > self::MAX_BYTES) {
            throw new RuntimeException('Avatar image must be 5 MB or smaller.');
        }

        $imageInfo = getimagesize($file['tmp_name']);
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
        ];
        if (!$imageInfo || !isset($extensions[$mime]) || $imageInfo['mime'] !== $mime) {
            throw new RuntimeException('Avatar must be a valid JPG, PNG, or WebP image.');
        }

        $directory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'avatars';
        if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
            throw new RuntimeException('Avatar upload directory could not be created.');
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mime];
        if (!move_uploaded_file($file['tmp_name'], $directory . DIRECTORY_SEPARATOR . $filename)) {
            throw new RuntimeException('Avatar could not be saved.');
        }

        return 'images/avatars/' . $filename;
    }

    public function deleteIfManaged($path)
    {
        if (!is_string($path) || !preg_match('~^images/avatars/[a-f0-9]{32}\.(jpg|png|webp)$~', $path)) {
            return;
        }

        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);
        if (is_file($file) && !unlink($file)) {
            error_log('Unable to remove old profile avatar: ' . $path);
        }
    }
}
