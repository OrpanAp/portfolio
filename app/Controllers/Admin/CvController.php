<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\CvService;
use App\Repositories\SettingRepository;

class CvController
{
    public function __construct(
        private View $view,
        private Request $request,
        private Response $response,
        private Csrf $csrf,
        private CvService $cvService,
        private SettingRepository $settingRepository,
        private string $appUrl
    ) {}

    public function index(): string
    {
        $cvPath =
            $this->settingRepository->get(
                'cv_path'
            );

        return $this->view->render(
            'admin.cv.index',
            [
                'title' => 'CV - Admin',
                'appUrl' => $this->appUrl,
                'cvPath' => $cvPath,
                'csrfField' => $this->csrf->field(),
            ],
            'layouts.admin'
        );
    }

    public function upload(): void
    {
        if (!$this->csrf->verify(
            $this->request->post('_csrf')
        )) {
            $this->response->send(
                'Invalid CSRF token.',
                419
            );

            return;
        }

        $file =
            $_FILES['cv'] ?? null;

        if (!is_array($file)) {
            $this->showError(
                'Please select a CV PDF file.'
            );

            return;
        }

        $oldCvPath =
            $this->settingRepository->get(
                'cv_path'
            );

        $fileName = null;

        try {

            $fileName =
                $this->cvService->uploadCv(
                    $file
                );

            $this->settingRepository->set(
                'cv_path',
                $fileName
            );
        } catch (\Throwable $e) {

            if (
                $fileName !== null &&
                $fileName !== ''
            ) {
                try {
                    $this->cvService->removeCv(
                        $fileName
                    );
                } catch (\Throwable $cleanupException) {
                    // Keep the original error.
                }
            }

            $this->showError(
                $e->getMessage()
            );

            return;
        }

        if (
            $oldCvPath !== null &&
            $oldCvPath !== '' &&
            $oldCvPath !== $fileName
        ) {
            $this->cvService->removeCv(
                $oldCvPath
            );
        }

        $this->response->redirect(
            $this->appUrl . '/admin/cv'
        );
    }

    public function download(): void
    {
        $cvPath =
            $this->settingRepository->get(
                'cv_path'
            );

        if (
            $cvPath === null ||
            $cvPath === ''
        ) {
            $this->response->send(
                'No CV has been uploaded.',
                404
            );

            return;
        }

        $cvPath = basename($cvPath);

        $filePath =
            $this->cvService->getCvPath(
                $cvPath
            );

        if (!is_file($filePath)) {
            $this->response->send(
                'CV file not found.',
                404
            );

            return;
        }

        header(
            'Content-Type: application/pdf'
        );

        header(
            'Content-Disposition: attachment; filename="CV.pdf"'
        );

        header(
            'Content-Length: ' . filesize($filePath)
        );

        readfile($filePath);

        exit;
    }

    private function showError(
        string $message
    ): void {
        $this->response->send(
            $message,
            422
        );
    }
}
