<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\SettingRepository;

class SettingsService
{
    /**
     * Settings that can be edited from the admin panel.
     *
     * cv_path is intentionally excluded because CV uploads
     * are managed by the dedicated CV feature.
     */
    private const EDITABLE_SETTINGS = [
        'site_title',
        'site_description',
        'github_url',
        'linkedin_url',
        'facebook_url',
        'instagram_url',
        'twitter_url',
    ];

    public function __construct(
        private SettingRepository $settingRepository
    ) {}

    /**
     * Get all editable settings.
     */
    public function getEditableSettings(): array
    {
        $settings = [];

        foreach (self::EDITABLE_SETTINGS as $key) {
            $settings[$key] =
                $this->settingRepository->get($key) ?? '';
        }

        return $settings;
    }

    /**
     * Settings exposed to every public page (title, description, socials),
     * with safe fallbacks when a value is empty.
     */
    public function getPublicSettings(): array
    {
        $settings = $this->getEditableSettings();

        if (trim($settings['site_title']) === '') {
            $settings['site_title'] = 'My Portfolio';
        }

        return [
            'siteTitle' => $settings['site_title'],
            'siteDescription' => $settings['site_description'],
            'socialLinks' => array_filter([
                'GitHub' => $settings['github_url'],
                'LinkedIn' => $settings['linkedin_url'],
                'Facebook' => $settings['facebook_url'],
                'Instagram' => $settings['instagram_url'],
                'Twitter / X' => $settings['twitter_url'],
            ], static fn (string $url): bool => $url !== ''),
        ];
    }

    /**
     * Save all editable settings.
     */
    public function saveSettings(
        array $settings
    ): void {
        foreach (self::EDITABLE_SETTINGS as $key) {
            $value = trim(
                (string) (
                    $settings[$key] ?? ''
                )
            );

            $this->settingRepository->set(
                $key,
                $value
            );
        }
    }

    /**
     * Validate all editable settings.
     *
     * Returns null when valid, otherwise an error message.
     */
    public function validate(
        array $settings
    ): ?string {
        $siteTitle = trim(
            (string) (
                $settings['site_title'] ?? ''
            )
        );

        if ($siteTitle === '') {
            return 'Site title is required.';
        }

        if (
            mb_strlen($siteTitle) >
            150
        ) {
            return 'Site title must not exceed 150 characters.';
        }

        $siteDescription = trim(
            (string) (
                $settings['site_description'] ?? ''
            )
        );

        if (
            mb_strlen($siteDescription) >
            500
        ) {
            return 'Site description must not exceed 500 characters.';
        }

        foreach (
            [
                'github_url',
                'linkedin_url',
                'facebook_url',
                'instagram_url',
                'twitter_url',
            ] as $key
        ) {
            $url = trim(
                (string) (
                    $settings[$key] ?? ''
                )
            );

            if (
                $url !== '' &&
                filter_var(
                    $url,
                    FILTER_VALIDATE_URL
                ) === false
            ) {
                return $this->urlErrorMessage(
                    $key
                );
            }
        }

        return null;
    }

    /**
     * Return only the settings supported by the admin form.
     */
    public function editableKeys(): array
    {
        return self::EDITABLE_SETTINGS;
    }

    private function urlErrorMessage(
        string $key
    ): string {
        return match ($key) {
            'github_url' =>
            'Please enter a valid GitHub URL.',

            'linkedin_url' =>
            'Please enter a valid LinkedIn URL.',

            'facebook_url' =>
            'Please enter a valid Facebook URL.',

            'instagram_url' =>
            'Please enter a valid Instagram URL.',

            'twitter_url' =>
            'Please enter a valid Twitter/X URL.',

            default =>
            'Please enter a valid URL.',
        };
    }
}
