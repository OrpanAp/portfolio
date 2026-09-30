<?php

declare(strict_types=1);

namespace App\Services;

class IframeService
{
    public function __construct(
        private string $appUrl
    ) {}

    public function getPreviewUrl(
        array $portfolio
    ): ?string {
        $projectType =
            $portfolio['project_type'] ?? null;

        if ($projectType === 'url') {

            $externalUrl =
                $portfolio['external_url'] ?? null;

            if (
                is_string($externalUrl) &&
                $externalUrl !== ''
            ) {
                return $externalUrl;
            }

            return null;
        }

        if ($projectType === 'upload') {

            $projectPath =
                $portfolio['project_path'] ?? null;

            $entryPath =
                $portfolio['entry_path'] ?? null;

            if (
                !is_string($projectPath) ||
                $projectPath === '' ||
                !is_string($entryPath) ||
                $entryPath === ''
            ) {
                return null;
            }

            return rtrim(
                $this->appUrl,
                '/'
            )
                . '/projects/'
                . rawurlencode($projectPath)
                . '/'
                . ltrim($entryPath, '/');
        }

        return null;
    }
}
