<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Response;
use App\Core\View;
use App\Repositories\SettingRepository;
use App\Services\CvService;

class CvController
{
    public function __construct(
        private View $view,
        private Response $response,
        private SettingRepository $settingRepository,
        private CvService $cvService,
        private string $appUrl
    ) {}

    public function index(): string
    {
        $cvPath =
            $this->settingRepository->get(
                'cv_path'
            );

        return $this->view->render(
            'cv.index',
            [
                'title' => 'CV',
                'appUrl' => $this->appUrl,
                'cvPath' => $cvPath,
            ],
            'layouts.app'
        );
    }

    public function preview(): void
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

        readfile($filePath);

        exit;
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
}
