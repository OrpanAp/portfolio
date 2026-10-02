<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\TechnologyRepository;
use PDOException;

class TechnologyController
{
    public function __construct(
        private View $view,
        private Request $request,
        private Response $response,
        private Csrf $csrf,
        private TechnologyRepository $technologyRepository,
        private string $appUrl
    ) {}

    public function index(): string
    {
        $technologies =
            $this->technologyRepository->getAll();

        return $this->view->render(
            'admin.technologies.index',
            [
                'title' => 'Technologies',
                'appUrl' => $this->appUrl,
                'technologies' => $technologies,
                'csrfField' => $this->csrf->field(),
            ],
            'layouts.admin'
        );
    }

    public function create(): string
    {
        return $this->view->render(
            'admin.technologies.create',
            [
                'title' => 'Add Technology',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'error' => null,
                'name' => '',
                'slug' => '',
            ],
            'layouts.admin'
        );
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            return;
        }

        $name = trim(
            (string) $this->request->post('name')
        );

        $slug = trim(
            (string) $this->request->post('slug')
        );

        if ($name === '') {
            $this->showCreateError(
                'Technology name is required.',
                $name,
                $slug
            );

            exit;
        }

        if ($slug === '') {
            $slug = $this->makeSlug($name);
        }

        if (
            $this->technologyRepository->findBySlug($slug)
            !== null
        ) {
            $this->showCreateError(
                'This technology slug already exists.',
                $name,
                $slug
            );

            exit;
        }

        try {

            $this->technologyRepository->create(
                $name,
                $slug
            );
        } catch (PDOException $e) {

            $this->showCreateError(
                'Unable to create the technology.',
                $name,
                $slug
            );

            exit;
        }

        $this->response->redirect(
            $this->appUrl . '/admin/technologies'
        );
    }

    public function edit(string $id): string
    {
        $technologyId = (int) $id;

        $technology =
            $this->technologyRepository->findById(
                $technologyId
            );

        if ($technology === null) {
            http_response_code(404);

            return $this->view->render(
                'admin.technologies.not-found',
                [
                    'title' => 'Technology Not Found',
                    'appUrl' => $this->appUrl,
                    'csrfField' => $this->csrf->field(),
                ],
                'layouts.admin'
            );
        }

        return $this->view->render(
            'admin.technologies.edit',
            [
                'title' => 'Edit Technology',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'technology' => $technology,
                'error' => null,
            ],
            'layouts.admin'
        );
    }

    public function update(string $id): void
    {
        if (!$this->verifyCsrf()) {
            return;
        }

        $technologyId = (int) $id;

        $technology =
            $this->technologyRepository->findById(
                $technologyId
            );

        if ($technology === null) {
            $this->response->send(
                'Technology not found.',
                404
            );

            exit;
        }

        $name = trim(
            (string) $this->request->post('name')
        );

        $slug = trim(
            (string) $this->request->post('slug')
        );

        if ($name === '') {
            $this->showEditError(
                'Technology name is required.',
                $technology
            );

            exit;
        }

        if ($slug === '') {
            $slug = $this->makeSlug($name);
        }

        $existingTechnology =
            $this->technologyRepository->findBySlug(
                $slug
            );

        if (
            $existingTechnology !== null &&
            (int) $existingTechnology['id'] !== $technologyId
        ) {
            $technology['name'] = $name;
            $technology['slug'] = $slug;

            $this->showEditError(
                'This technology slug already exists.',
                $technology
            );

            exit;
        }

        try {

            $this->technologyRepository->update(
                $technologyId,
                $name,
                $slug
            );
        } catch (PDOException $e) {

            $technology['name'] = $name;
            $technology['slug'] = $slug;

            $this->showEditError(
                'Unable to update the technology.',
                $technology
            );

            exit;
        }

        $this->response->redirect(
            $this->appUrl . '/admin/technologies'
        );
    }

    public function delete(string $id): void
    {
        if (!$this->verifyCsrf()) {
            return;
        }

        $technologyId = (int) $id;

        $technology =
            $this->technologyRepository->findById(
                $technologyId
            );

        if ($technology === null) {
            $this->response->send(
                'Technology not found.',
                404
            );

            exit;
        }

        try {

            $this->technologyRepository->delete(
                $technologyId
            );
        } catch (PDOException $e) {

            $this->response->send(
                'Unable to delete the technology.',
                409
            );

            exit;
        }

        $this->response->redirect(
            $this->appUrl . '/admin/technologies'
        );
    }

    private function verifyCsrf(): bool
    {
        $token = $this->request->post('_csrf');

        if (!$this->csrf->verify($token)) {

            $this->response->send(
                'Invalid CSRF token.',
                403
            );

            return false;
        }

        return true;
    }

    private function makeSlug(string $value): string
    {
        $value = strtolower(
            trim($value)
        );

        $value = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $value
        );

        $value = trim(
            (string) $value,
            '-'
        );

        return $value;
    }

    private function showCreateError(
        string $error,
        string $name,
        string $slug
    ): void {
        $html = $this->view->render(
            'admin.technologies.create',
            [
                'title' => 'Add Technology',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'error' => $error,
                'name' => $name,
                'slug' => $slug,
            ],
            'layouts.admin'
        );

        $this->response->send(
            $html,
            422
        );
    }

    private function showEditError(
        string $error,
        array $technology
    ): void {
        $html = $this->view->render(
            'admin.technologies.edit',
            [
                'title' => 'Edit Technology',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'error' => $error,
                'technology' => $technology,
            ],
            'layouts.admin'
        );

        $this->response->send(
            $html,
            422
        );
    }
}
