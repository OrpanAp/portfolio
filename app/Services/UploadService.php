<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;
use ZipArchive;

class UploadService
{
    private const MAX_ARCHIVE_ENTRIES = 5000;

    private const MAX_ARCHIVE_FILE_SIZE = 104857600;

    private const MAX_ARCHIVE_TOTAL_SIZE = 524288000;

    public function __construct(
        private string $projectsPath,
        private int $maxFileSize = 52428800
    ) {}

    /**
     * Upload and extract a ZIP project.
     *
     * Returns the relative project folder name.
     */
    public function uploadProject(
        array $file,
        string $projectFolder
    ): string {
        $this->validateUpload($file);

        $projectFolder = $this->sanitizeFolderName(
            $projectFolder
        );

        $projectPath = $this->projectsPath
            . DIRECTORY_SEPARATOR
            . $projectFolder;

        if (is_dir($projectPath)) {
            throw new RuntimeException(
                'The project folder already exists.'
            );
        }

        if (!is_dir($this->projectsPath)) {
            if (!mkdir(
                $this->projectsPath,
                0755,
                true
            ) && !is_dir($this->projectsPath)) {
                throw new RuntimeException(
                    'Unable to create the projects directory.'
                );
            }
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException(
                'Invalid uploaded file.'
            );
        }

        $zip = new ZipArchive();

        $result = $zip->open(
            $file['tmp_name']
        );

        if ($result !== true) {
            throw new RuntimeException(
                'Unable to open the ZIP file.'
            );
        }

        if (!$this->isSafeArchive($zip)) {
            $zip->close();

            throw new RuntimeException(
                'The ZIP file contains an unsafe file path.'
            );
        }

        if (!mkdir(
            $projectPath,
            0755,
            true
        ) && !is_dir($projectPath)) {
            $zip->close();

            throw new RuntimeException(
                'Unable to create the project directory.'
            );
        }

        if (!$zip->extractTo($projectPath)) {
            $zip->close();

            $this->removeDirectory($projectPath);

            throw new RuntimeException(
                'Unable to extract the ZIP project.'
            );
        }

        $zip->close();

        /*
        |--------------------------------------------------------------------------
        | Flatten Single Root Directory
        |--------------------------------------------------------------------------
        */

        $items = scandir($projectPath);

        if ($items !== false) {

            $items = array_values(
                array_filter(
                    $items,
                    static fn(string $item): bool =>
                    $item !== '.'
                        && $item !== '..'
                )
            );

            if (count($items) === 1) {

                $rootFolder =
                    $projectPath
                    . DIRECTORY_SEPARATOR
                    . $items[0];

                if (is_dir($rootFolder)) {

                    $rootItems = scandir(
                        $rootFolder
                    );

                    if ($rootItems !== false) {

                        foreach ($rootItems as $rootItem) {

                            if (
                                $rootItem === '.'
                                || $rootItem === '..'
                            ) {
                                continue;
                            }

                            $source =
                                $rootFolder
                                . DIRECTORY_SEPARATOR
                                . $rootItem;

                            $destination =
                                $projectPath
                                . DIRECTORY_SEPARATOR
                                . $rootItem;

                            if (!rename(
                                $source,
                                $destination
                            )) {

                                $this->removeDirectory(
                                    $projectPath
                                );

                                throw new RuntimeException(
                                    'Unable to normalize the extracted project.'
                                );
                            }
                        }
                    }

                    rmdir($rootFolder);
                }
            }
        }

        return $projectFolder;
    }

    /**
     * Upload a portfolio thumbnail.
     *
     * Returns the public relative path.
     */
    public function uploadThumbnail(
        array $file,
        string $thumbnailPath,
        int $maxFileSize = 5242880
    ): string {
        $this->validateThumbnailUpload(
            $file,
            $maxFileSize
        );

        if (!is_dir($thumbnailPath)) {
            if (!mkdir(
                $thumbnailPath,
                0755,
                true
            ) && !is_dir($thumbnailPath)) {
                throw new RuntimeException(
                    'Unable to create the thumbnails directory.'
                );
            }
        }

        $extension = strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );

        $fileName = 'thumb_'
            . bin2hex(
                random_bytes(16)
            )
            . '.'
            . $extension;

        $destination = rtrim(
            $thumbnailPath,
            DIRECTORY_SEPARATOR
        )
            . DIRECTORY_SEPARATOR
            . $fileName;

        if (!move_uploaded_file(
            $file['tmp_name'],
            $destination
        )) {
            throw new RuntimeException(
                'Unable to save the thumbnail.'
            );
        }

        return 'uploads/thumbnails/' . $fileName;
    }

    public function removeProject(
        string $projectFolder
    ): void {
        $projectFolder = $this->sanitizeFolderName(
            $projectFolder
        );

        $projectPath = $this->projectsPath
            . DIRECTORY_SEPARATOR
            . $projectFolder;

        if (is_dir($projectPath)) {
            $this->removeDirectory($projectPath);
        }
    }

    public function projectExists(
        string $projectFolder
    ): bool {
        $projectFolder = $this->sanitizeFolderName(
            $projectFolder
        );

        $projectPath = $this->projectsPath
            . DIRECTORY_SEPARATOR
            . $projectFolder;

        return is_dir($projectPath);
    }

    /**
     * Validate the uploaded ZIP file.
     */
    private function validateUpload(
        array $file
    ): void {
        if (
            !isset(
                $file['error'],
                $file['tmp_name'],
                $file['size'],
                $file['name']
            )
        ) {
            throw new RuntimeException(
                'Invalid upload data.'
            );
        }

        if (
            (int) $file['error']
            !== UPLOAD_ERR_OK
        ) {
            throw new RuntimeException(
                $this->uploadErrorMessage(
                    (int) $file['error']
                )
            );
        }

        if (
            !is_uploaded_file(
                $file['tmp_name']
            )
        ) {
            throw new RuntimeException(
                'Invalid uploaded file.'
            );
        }

        if (
            (int) $file['size']
            <= 0
        ) {
            throw new RuntimeException(
                'The uploaded file is empty.'
            );
        }

        if (
            (int) $file['size']
            > $this->maxFileSize
        ) {
            throw new RuntimeException(
                'The uploaded ZIP file is too large.'
            );
        }

        $extension = strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );

        if ($extension !== 'zip') {
            throw new RuntimeException(
                'Only ZIP files are allowed.'
            );
        }
    }

    /**
     * Validate the uploaded thumbnail.
     */
    private function validateThumbnailUpload(
        array $file,
        int $maxFileSize
    ): void {
        if (
            !isset(
                $file['error'],
                $file['tmp_name'],
                $file['size'],
                $file['name']
            )
        ) {
            throw new RuntimeException(
                'Invalid thumbnail upload data.'
            );
        }

        if (
            (int) $file['error']
            !== UPLOAD_ERR_OK
        ) {
            throw new RuntimeException(
                $this->uploadErrorMessage(
                    (int) $file['error']
                )
            );
        }

        if (
            !is_uploaded_file(
                $file['tmp_name']
            )
        ) {
            throw new RuntimeException(
                'Invalid uploaded thumbnail.'
            );
        }

        if (
            (int) $file['size']
            <= 0
        ) {
            throw new RuntimeException(
                'The uploaded thumbnail is empty.'
            );
        }

        if (
            (int) $file['size']
            > $maxFileSize
        ) {
            throw new RuntimeException(
                'The thumbnail file is too large.'
            );
        }

        $extension = strtolower(
            pathinfo(
                $file['name'],
                PATHINFO_EXTENSION
            )
        );

        $allowedExtensions = [
            'jpg',
            'jpeg',
            'png',
            'webp',
        ];

        if (
            !in_array(
                $extension,
                $allowedExtensions,
                true
            )
        ) {
            throw new RuntimeException(
                'Only JPG, JPEG, PNG, and WebP thumbnails are allowed.'
            );
        }

        $imageInfo = @getimagesize(
            $file['tmp_name']
        );

        if ($imageInfo === false) {
            throw new RuntimeException(
                'The uploaded thumbnail is not a valid image.'
            );
        }

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];

        if (
            !isset($imageInfo['mime'])
            || !in_array(
                $imageInfo['mime'],
                $allowedMimeTypes,
                true
            )
        ) {
            throw new RuntimeException(
                'The uploaded thumbnail format is not supported.'
            );
        }
    }

    /**
     * Check every ZIP entry for path traversal.
     */
    private function isSafeArchive(
        ZipArchive $zip
    ): bool {
        if (
            $zip->numFiles > self::MAX_ARCHIVE_ENTRIES
        ) {
            return false;
        }

        $totalSize = 0;

        for (
            $index = 0;
            $index < $zip->numFiles;
            $index++
        ) {
            $entryName = $zip->getNameIndex(
                $index
            );

            if (
                !is_string($entryName) ||
                $entryName === ''
            ) {
                return false;
            }

            $entryName = str_replace(
                '\\',
                '/',
                $entryName
            );

            if (
                str_starts_with(
                    $entryName,
                    '/'
                )
            ) {
                return false;
            }

            if (
                preg_match(
                    '#(^|/)\.\.?(/|$)#',
                    $entryName
                )
            ) {
                return false;
            }

            $entry = $zip->statIndex(
                $index
            );

            if ($entry === false) {
                return false;
            }

            $entrySize = $entry['size'] ?? null;

            if (
                !is_int($entrySize) ||
                $entrySize < 0
            ) {
                return false;
            }

            if (
                $entrySize > self::MAX_ARCHIVE_FILE_SIZE
            ) {
                return false;
            }

            $totalSize += $entrySize;

            if (
                $totalSize > self::MAX_ARCHIVE_TOTAL_SIZE
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Make sure the project folder name is safe.
     */
    private function sanitizeFolderName(
        string $folder
    ): string {
        $folder = trim($folder);

        $folder = preg_replace(
            '/[^a-zA-Z0-9_-]+/',
            '-',
            $folder
        );

        $folder = trim(
            (string) $folder,
            '-_'
        );

        if ($folder === '') {
            throw new RuntimeException(
                'Invalid project folder name.'
            );
        }

        return $folder;
    }

    /**
     * Convert PHP upload error codes
     * into readable messages.
     */
    private function uploadErrorMessage(
        int $error
    ): string {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE,
            UPLOAD_ERR_FORM_SIZE =>
            'The uploaded file is too large.',

            UPLOAD_ERR_PARTIAL =>
            'The file was only partially uploaded.',

            UPLOAD_ERR_NO_FILE =>
            'No file was uploaded.',

            UPLOAD_ERR_NO_TMP_DIR =>
            'The temporary upload directory is missing.',

            UPLOAD_ERR_CANT_WRITE =>
            'The server could not write the uploaded file.',

            UPLOAD_ERR_EXTENSION =>
            'The upload was stopped by a PHP extension.',

            default =>
            'The file upload failed.',
        };
    }

    /**
     * Remove a directory recursively.
     */
    private function removeDirectory(
        string $directory
    ): void {
        if (!is_dir($directory)) {
            return;
        }

        $items = scandir($directory);

        if ($items === false) {
            return;
        }

        foreach ($items as $item) {

            if (
                $item === '.' ||
                $item === '..'
            ) {
                continue;
            }

            $path = $directory
                . DIRECTORY_SEPARATOR
                . $item;

            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }

        rmdir($directory);
    }
}
