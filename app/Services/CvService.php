<?php

declare(strict_types=1);

namespace App\Services;

use RuntimeException;

class CvService
{
    public function __construct(
        private string $cvPath,
        private int $maxFileSize = 10485760
    ) {}

    public function uploadCv(
        array $file
    ): string {
        $this->validateUpload($file);

        if (!is_dir($this->cvPath)) {
            if (
                !mkdir(
                    $this->cvPath,
                    0755,
                    true
                ) &&
                !is_dir($this->cvPath)
            ) {
                throw new RuntimeException(
                    'Unable to create the CV directory.'
                );
            }
        }

        if (!is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException(
                'Invalid uploaded file.'
            );
        }

        $fileName = 'cv_' . bin2hex(
            random_bytes(16)
        ) . '.pdf';

        $destination =
            $this->cvPath
            . DIRECTORY_SEPARATOR
            . $fileName;

        if (
            !move_uploaded_file(
                $file['tmp_name'],
                $destination
            )
        ) {
            throw new RuntimeException(
                'Unable to save the CV file.'
            );
        }

        return $fileName;
    }

    public function removeCv(
        ?string $fileName
    ): void {
        if (
            $fileName === null ||
            $fileName === ''
        ) {
            return;
        }

        $fileName = basename($fileName);

        $filePath =
            $this->cvPath
            . DIRECTORY_SEPARATOR
            . $fileName;

        if (is_file($filePath)) {
            unlink($filePath);
        }
    }

    private function validateUpload(
        array $file
    ): void {
        $error =
            $file['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($error !== UPLOAD_ERR_OK) {
            throw new RuntimeException(
                $this->uploadErrorMessage($error)
            );
        }

        $tmpName = $file['tmp_name'] ?? '';

        if ($tmpName === '') {
            throw new RuntimeException(
                'No uploaded file was found.'
            );
        }

        $fileSize =
            (int) (
                $file['size'] ?? 0
            );

        if ($fileSize <= 0) {
            throw new RuntimeException(
                'The uploaded CV is empty.'
            );
        }

        if (
            $fileSize >
            $this->maxFileSize
        ) {
            throw new RuntimeException(
                'The CV file is too large.'
            );
        }

        $extension = strtolower(
            pathinfo(
                $file['name'] ?? '',
                PATHINFO_EXTENSION
            )
        );

        if ($extension !== 'pdf') {
            throw new RuntimeException(
                'Only PDF CV files are allowed.'
            );
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);

        $mimeType = $finfo->file($tmpName);

        if ($mimeType !== 'application/pdf') {
            throw new RuntimeException(
                'The uploaded file is not a valid PDF.'
            );
        }
    }

    public function getCvPath(
        string $fileName
    ): string {
        $fileName = basename($fileName);

        return $this->cvPath
            . DIRECTORY_SEPARATOR
            . $fileName;
    }

    private function uploadErrorMessage(
        int $error
    ): string {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE,
            UPLOAD_ERR_FORM_SIZE =>
            'The uploaded CV is too large.',

            UPLOAD_ERR_PARTIAL =>
            'The CV upload was incomplete.',

            UPLOAD_ERR_NO_FILE =>
            'No CV file was uploaded.',

            UPLOAD_ERR_NO_TMP_DIR =>
            'The server temporary directory is missing.',

            UPLOAD_ERR_CANT_WRITE =>
            'The server could not write the CV file.',

            UPLOAD_ERR_EXTENSION =>
            'The CV upload was stopped by a server extension.',

            default =>
            'An unknown CV upload error occurred.',
        };
    }
}
