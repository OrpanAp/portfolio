<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\SettingsService;

class SettingsController
{
    public function __construct(
        private View $view,
        private Request $request,
        private Response $response,
        private Csrf $csrf,
        private SettingsService $settingsService,
        private string $appUrl
    ) {}

    public function index(): string
    {
        return $this->view->render(
            'admin.settings.edit',
            [
                'title' => 'Settings',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'settings' =>
                $this->settingsService
                    ->getEditableSettings(),
                'error' => null,
            ],
            'layouts.admin'
        );
    }

    public function update(): void
    {
        if (!$this->verifyCsrf()) {
            return;
        }

        $settings =
            $this->request->allPost();

        unset(
            $settings['_csrf']
        );

        $error =
            $this->settingsService
            ->validate($settings);

        if ($error !== null) {
            $this->showError(
                $error,
                $settings
            );

            return;
        }

        $this->settingsService
            ->saveSettings($settings);

        $this->response->redirect(
            $this->appUrl . '/admin/settings'
        );
    }

    private function verifyCsrf(): bool
    {
        $token =
            $this->request->post('_csrf');

        if (!$this->csrf->verify($token)) {
            $this->response->send(
                'Invalid CSRF token.',
                403
            );

            return false;
        }

        return true;
    }

    private function showError(
        string $error,
        array $settings
    ): void {
        $normalizedSettings =
            $this->settingsService
            ->getEditableSettings();

        foreach (
            $normalizedSettings as $key => $value
        ) {
            if (
                array_key_exists(
                    $key,
                    $settings
                )
            ) {
                $normalizedSettings[$key] =
                    trim(
                        (string) $settings[$key]
                    );
            }
        }

        $html = $this->view->render(
            'admin.settings.edit',
            [
                'title' => 'Settings',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'settings' => $normalizedSettings,
                'error' => $error,
            ],
            'layouts.admin'
        );

        $this->response->send(
            $html,
            422
        );
    }
}
