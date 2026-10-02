<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\CategoryRepository;
use PDOException;

class CategoryController
{
    public function __construct(
        private View $view,
        private Request $request,
        private Response $response,
        private Csrf $csrf,
        private CategoryRepository $categoryRepository,
        private string $appUrl
    ) {}

    public function index(): string
    {
        $categories =
            $this->categoryRepository->getAll();

        return $this->view->render(
            'admin.categories.index',
            [
                'title' => 'Categories',
                'appUrl' => $this->appUrl,
                'categories' => $categories,
                'csrfField' => $this->csrf->field(),
            ],
            'layouts.admin'
        );
    }

    public function create(): string
    {
        return $this->view->render(
            'admin.categories.create',
            [
                'title' => 'Add Category',
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
                'Category name is required.',
                $name,
                $slug
            );

            exit;
        }

        if ($slug === '') {
            $slug = $this->makeSlug($name);
        }

        if ($this->categoryRepository->findBySlug($slug) !== null) {
            $this->showCreateError(
                'This category slug already exists.',
                $name,
                $slug
            );

            exit;
        }

        try {

            $this->categoryRepository->create(
                $name,
                $slug
            );
        } catch (PDOException $e) {

            $this->showCreateError(
                'Unable to create the category.',
                $name,
                $slug
            );

            exit;
        }

        $this->response->redirect(
            $this->appUrl . '/admin/categories'
        );
    }

    public function edit(string $id): string
    {
        $categoryId = (int) $id;

        $category =
            $this->categoryRepository->findById(
                $categoryId
            );

        if ($category === null) {
            http_response_code(404);

            return $this->view->render(
                'admin.categories.not-found',
                [
                    'title' => 'Category Not Found',
                    'appUrl' => $this->appUrl,
                    'csrfField' => $this->csrf->field(),
                ],
                'layouts.admin'
            );
        }

        return $this->view->render(
            'admin.categories.edit',
            [
                'title' => 'Edit Category',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'category' => $category,
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

        $categoryId = (int) $id;

        $category =
            $this->categoryRepository->findById(
                $categoryId
            );

        if ($category === null) {
            $this->response->send(
                'Category not found.',
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
                'Category name is required.',
                $category
            );

            exit;
        }

        if ($slug === '') {
            $slug = $this->makeSlug($name);
        }

        $existingCategory =
            $this->categoryRepository->findBySlug(
                $slug
            );

        if (
            $existingCategory !== null &&
            (int) $existingCategory['id'] !== $categoryId
        ) {
            $category['name'] = $name;
            $category['slug'] = $slug;

            $this->showEditError(
                'This category slug already exists.',
                $category
            );

            exit;
        }

        try {

            $this->categoryRepository->update(
                $categoryId,
                $name,
                $slug
            );
        } catch (PDOException $e) {

            $category['name'] = $name;
            $category['slug'] = $slug;

            $this->showEditError(
                'Unable to update the category.',
                $category
            );

            exit;
        }

        $this->response->redirect(
            $this->appUrl . '/admin/categories'
        );
    }

    public function delete(string $id): void
    {
        if (!$this->verifyCsrf()) {
            return;
        }

        $categoryId = (int) $id;

        $category =
            $this->categoryRepository->findById(
                $categoryId
            );

        if ($category === null) {
            $this->response->send(
                'Category not found.',
                404
            );

            exit;
        }

        try {

            $this->categoryRepository->delete(
                $categoryId
            );
        } catch (PDOException $e) {

            $this->response->send(
                'This category cannot be deleted because it is being used by a portfolio project.',
                409
            );

            exit;
        }

        $this->response->redirect(
            $this->appUrl . '/admin/categories'
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
            'admin.categories.create',
            [
                'title' => 'Add Category',
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
        array $category
    ): void {
        $html = $this->view->render(
            'admin.categories.edit',
            [
                'title' => 'Edit Category',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'error' => $error,
                'category' => $category,
            ],
            'layouts.admin'
        );

        $this->response->send(
            $html,
            422
        );
    }
}
