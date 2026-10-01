<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Repositories\CategoryRepository;
use App\Repositories\PortfolioRepository;
use App\Repositories\TechnologyRepository;
use App\Services\UploadService;
use PDOException;

class PortfolioController
{
    public function __construct(
        private View $view,
        private Request $request,
        private Response $response,
        private Csrf $csrf,
        private PortfolioRepository $portfolioRepository,
        private CategoryRepository $categoryRepository,
        private TechnologyRepository $technologyRepository,
        private UploadService $uploadService,
        private string $appUrl
    ) {}

    public function index(): string
    {
        $portfolios =
            $this->portfolioRepository->getAll();

        return $this->view->render(
            'admin.portfolio.index',
            [
                'title' => 'Portfolio',
                'appUrl' => $this->appUrl,
                'portfolios' => $portfolios,
                'csrfField' => $this->csrf->field(),
            ],
            'layouts.admin'
        );
    }

    public function create(): string
    {
        return $this->view->render(
            'admin.portfolio.create',
            [
                'title' => 'Add Portfolio',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'categories' =>
                $this->categoryRepository->getAll(),
                'technologies' =>
                $this->technologyRepository->getAll(),
                'error' => null,
                'portfolio' => $this->emptyPortfolio(),
                'selectedTechnologyIds' => [],
            ],
            'layouts.admin'
        );
    }

    public function store(): void
    {
        if (!$this->verifyCsrf()) {
            return;
        }

        $data = $this->collectFormData();

        $error = $this->validatePortfolio(
            $data,
            true
        );

        if ($error !== null) {
            $this->showCreateError(
                $error,
                $data
            );

            return;
        }

        if (
            $this->portfolioRepository->slugExists(
                $data['slug']
            )
        ) {
            $this->showCreateError(
                'This portfolio slug already exists.',
                $data
            );

            return;
        }

        $uploadedProjectFolder = null;

        try {

            if (
                $data['project_type'] === 'upload' &&
                $data['project_zip'] !== null
            ) {
                $projectFolder =
                    $this->makeProjectFolderName(
                        $data['slug']
                    );

                $this->uploadService->uploadProject(
                    $data['project_zip'],
                    $projectFolder
                );

                $uploadedProjectFolder = $projectFolder;

                $data['project_path'] =
                    $projectFolder;

                $data['entry_path'] =
                    $data['entry_path'] !== null &&
                    $data['entry_path'] !== ''
                    ? $data['entry_path']
                    : 'index.html';

                $entryFile =
                    __DIR__
                    . '/../../../public/projects/'
                    . $projectFolder
                    . '/'
                    . ltrim(
                        $data['entry_path'],
                        '/'
                    );

                if (!is_file($entryFile)) {
                    throw new \RuntimeException(
                        'The selected entry file does not exist in the uploaded project: '
                            . $data['entry_path']
                    );
                }
            }

            if (
                $data['thumbnail_file'] !== null &&
                ($data['thumbnail_file']['error'] ?? UPLOAD_ERR_NO_FILE)
                !== UPLOAD_ERR_NO_FILE
            ) {
                $data['thumbnail'] =
                    $this->uploadService->uploadThumbnail(
                        $data['thumbnail_file'],
                        __DIR__ . '/../../../public/uploads/thumbnails'
                    );
            }

            $this->portfolioRepository->create(
                $data['category_id'],
                $data['title'],
                $data['slug'],
                $data['description'],
                $data['thumbnail'],
                $data['project_type'],
                $data['project_path'],
                $data['entry_path'],
                $data['external_url'],
                $data['is_featured'],
                $data['is_published'],
                $data['sort_order'],
                $data['technology_ids']
            );
        } catch (\Throwable $e) {

            if ($uploadedProjectFolder !== null) {
                try {
                    $this->uploadService->removeProject(
                        $uploadedProjectFolder
                    );
                } catch (\Throwable $cleanupException) {
                    // Keep the original error message.
                }
            }

            $this->showCreateError(
                $e->getMessage(),
                $data
            );

            return;
        }

        $this->response->redirect(
            $this->appUrl . '/admin/portfolio'
        );
    }

    public function edit(string $id): string
    {
        $portfolioId = (int) $id;

        $portfolio =
            $this->portfolioRepository->findById(
                $portfolioId
            );

        if ($portfolio === null) {
            return $this->notFound();
        }

        $selectedTechnologyIds = [];

        foreach (
            $portfolio['technologies']
            as $technology
        ) {
            $selectedTechnologyIds[] =
                (int) $technology['id'];
        }

        return $this->view->render(
            'admin.portfolio.edit',
            [
                'title' => 'Edit Portfolio',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'categories' =>
                $this->categoryRepository->getAll(),
                'technologies' =>
                $this->technologyRepository->getAll(),
                'error' => null,
                'portfolio' => $portfolio,
                'selectedTechnologyIds' =>
                $selectedTechnologyIds,
            ],
            'layouts.admin'
        );
    }

    public function update(string $id): void
    {
        if (!$this->verifyCsrf()) {
            return;
        }

        $oldProjectFolder = null;
        $newProjectFolder = null;
        $uploadedProjectFolder = null;
        $oldThumbnail = null;
        $uploadedThumbnail = null;
        $entryPathError = null;

        $portfolioId = (int) $id;

        $portfolio =
            $this->portfolioRepository->findById(
                $portfolioId
            );

        if ($portfolio === null) {
            $this->response->send(
                'Portfolio item not found.',
                404
            );

            return;
        }

        if (
            is_array($portfolio) &&
            ($portfolio['project_type'] ?? null) === 'upload'
        ) {
            $oldProjectFolder =
                $portfolio['project_path'] ?? null;
        }

        $data = $this->collectFormData();

        $oldThumbnail = $portfolio['thumbnail'] ?? null;

        $data['thumbnail'] = $oldThumbnail;

        $error = $this->validatePortfolio(
            $data
        );

        if ($error !== null) {
            $this->showEditError(
                $error,
                $portfolio,
                $data
            );

            return;
        }

        if (
            $this->portfolioRepository->slugExists(
                $data['slug'],
                $portfolioId
            )
        ) {
            $this->showEditError(
                'This portfolio slug already exists.',
                $portfolio,
                $data
            );

            return;
        }

        try {

            if (
                $data['thumbnail_file'] !== null &&
                ($data['thumbnail_file']['error'] ?? UPLOAD_ERR_NO_FILE)
                !== UPLOAD_ERR_NO_FILE
            ) {
                $data['thumbnail'] =
                    $this->uploadService->uploadThumbnail(
                        $data['thumbnail_file'],
                        __DIR__ . '/../../../public/uploads/thumbnails'
                    );

                $uploadedThumbnail = $data['thumbnail'];
            }

            if (
                $data['project_type'] === 'upload' &&
                $data['project_zip'] !== null &&
                ($data['project_zip']['error'] ?? UPLOAD_ERR_NO_FILE)
                !== UPLOAD_ERR_NO_FILE
            ) {
                $projectFolder =
                    $this->makeProjectFolderName(
                        $data['slug']
                    );

                if (
                    $oldProjectFolder !== null &&
                    $projectFolder === $oldProjectFolder
                ) {
                    $projectFolder .= '-new';
                }

                $this->uploadService->uploadProject(
                    $data['project_zip'],
                    $projectFolder
                );

                $uploadedProjectFolder =
                    $projectFolder;

                $newProjectFolder =
                    $projectFolder;

                $data['project_path'] =
                    $projectFolder;

                $data['entry_path'] =
                    $data['entry_path'] !== null &&
                    $data['entry_path'] !== ''
                    ? $data['entry_path']
                    : 'index.html';

                $entryFile =
                    __DIR__
                    . '/../../../public/projects/'
                    . $projectFolder
                    . '/'
                    . ltrim(
                        $data['entry_path'],
                        '/'
                    );

                if (!is_file($entryFile)) {
                    throw new \RuntimeException(
                        'The selected entry file does not exist in the uploaded project: '
                            . $data['entry_path']
                    );
                }
            }



            if (
                $data['project_type'] === 'upload' &&
                $uploadedProjectFolder === null &&
                $oldProjectFolder !== null &&
                $oldProjectFolder !== ''
            ) {
                $entryPath =
                    $data['entry_path'] !== null &&
                    $data['entry_path'] !== ''
                    ? $data['entry_path']
                    : 'index.html';

                $entryFile =
                    __DIR__
                    . '/../../../public/projects/'
                    . $oldProjectFolder
                    . '/'
                    . ltrim(
                        $entryPath,
                        '/'
                    );

                if (!is_file($entryFile)) {
                    $entryPathError =
                        'The selected entry file does not exist in the existing project: '
                        . $entryPath;
                }
            }

            if ($entryPathError !== null) {
                $this->showEditError(
                    $entryPathError,
                    $portfolio,
                    $data
                );

                return;
            }

            $this->portfolioRepository->update(
                $portfolioId,
                $data['category_id'],
                $data['title'],
                $data['slug'],
                $data['description'],
                $data['thumbnail'],
                $data['project_type'],
                $data['project_path'],
                $data['entry_path'],
                $data['external_url'],
                $data['is_featured'],
                $data['is_published'],
                $data['sort_order'],
                $data['technology_ids']
            );
        } catch (\Throwable $e) {

            if ($uploadedProjectFolder !== null) {
                try {
                    $this->uploadService->removeProject(
                        $uploadedProjectFolder
                    );
                } catch (\Throwable $cleanupException) {
                    // Keep the original error.
                }
            }

            if ($uploadedThumbnail !== null) {
                $thumbnailFile =
                    __DIR__
                    . '/../../../public/'
                    . ltrim(
                        $uploadedThumbnail,
                        '/'
                    );

                if (is_file($thumbnailFile)) {
                    @unlink($thumbnailFile);
                }
            }

            $this->showEditError(
                'Unable to update the portfolio item.',
                $portfolio,
                $data
            );

            return;
        }

        if (
            $uploadedThumbnail !== null &&
            $oldThumbnail !== null &&
            $oldThumbnail !== $uploadedThumbnail
        ) {
            $oldThumbnailFile =
                __DIR__
                . '/../../../public/'
                . ltrim(
                    $oldThumbnail,
                    '/'
                );

            if (is_file($oldThumbnailFile)) {
                @unlink($oldThumbnailFile);
            }
        }

        if (

            $oldProjectFolder !== null &&
            (
                $newProjectFolder === null ||
                $newProjectFolder !== $oldProjectFolder
            ) &&
            $this->uploadService->projectExists(
                $oldProjectFolder
            )
        ) {
            $this->uploadService->removeProject(
                $oldProjectFolder
            );
        }

        $this->response->redirect(
            $this->appUrl . '/admin/portfolio'
        );
    }

    public function delete(string $id): void
    {
        if (!$this->verifyCsrf()) {
            return;
        }

        $portfolioId = (int) $id;

        $portfolio =
            $this->portfolioRepository->findById(
                $portfolioId
            );

        if ($portfolio === null) {
            $this->response->send(
                'Portfolio item not found.',
                404
            );

            return;
        }

        try {
            $this->portfolioRepository->delete(
                $portfolioId
            );
        } catch (PDOException $e) {
            $this->response->send(
                'Unable to delete the portfolio item.',
                409
            );

            return;
        }

        if (
            ($portfolio['project_type'] ?? null) === 'upload' &&
            is_string($portfolio['project_path'] ?? null) &&
            $portfolio['project_path'] !== ''
        ) {
            try {
                $this->uploadService->removeProject(
                    $portfolio['project_path']
                );
            } catch (\Throwable $e) {
                // Keep the database deletion successful.
            }
        }

        $thumbnail = $portfolio['thumbnail'] ?? null;

        if (is_string($thumbnail) && $thumbnail !== '') {
            $thumbnailFile =
                __DIR__
                . '/../../../public/'
                . ltrim(
                    $thumbnail,
                    '/'
                );

            if (is_file($thumbnailFile)) {
                @unlink($thumbnailFile);
            }
        }

        $this->response->redirect(
            $this->appUrl . '/admin/portfolio'
        );
    }

    private function collectFormData(): array
    {
        $title = trim(
            (string) $this->request->post('title')
        );

        $slug = trim(
            (string) $this->request->post('slug')
        );

        if ($slug === '') {
            $slug = $this->makeSlug($title);
        }

        $categoryId = (int) $this->request->post(
            'category_id',
            0
        );

        $description = trim(
            (string) $this->request->post('description')
        );

        $projectType = trim(
            (string) $this->request->post(
                'project_type'
            )
        );

        $projectPath = trim(
            (string) $this->request->post(
                'project_path'
            )
        );

        $entryPath = trim(
            (string) $this->request->post(
                'entry_path'
            )
        );

        $externalUrl = trim(
            (string) $this->request->post(
                'external_url'
            )
        );

        $isFeatured =
            $this->request->post('is_featured') !== null;

        $isPublished =
            $this->request->post('is_published') !== null;

        $sortOrder = (int) $this->request->post(
            'sort_order',
            0
        );

        $technologyIds =
            $this->request->post(
                'technology_ids',
                []
            );

        if (!is_array($technologyIds)) {
            $technologyIds = [];
        }

        $technologyIds = array_map(
            'intval',
            $technologyIds
        );

        $technologyIds = array_values(
            array_unique($technologyIds)
        );

        $projectZip = $_FILES['project_zip'] ?? null;

        $thumbnail = $_FILES['thumbnail'] ?? null;

        return [
            'category_id' => $categoryId,
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'thumbnail' => null,
            'thumbnail_file' => $thumbnail,
            'project_type' => $projectType,
            'project_path' =>
            $projectPath !== ''
                ? $projectPath
                : null,
            'entry_path' =>
            $entryPath !== ''
                ? $entryPath
                : null,
            'external_url' =>
            $externalUrl !== ''
                ? $externalUrl
                : null,
            'is_featured' => $isFeatured,
            'is_published' => $isPublished,
            'sort_order' => $sortOrder,
            'technology_ids' => $technologyIds,
            'project_zip' => $projectZip,
        ];
    }

    private function validatePortfolio(
        array $data,
        bool $isUpdate = false
    ): ?string {
        if ($data['title'] === '') {
            return 'Portfolio title is required.';
        }

        if ($data['slug'] === '') {
            return 'Portfolio slug is required.';
        }

        if ($data['description'] === '') {
            return 'Portfolio description is required.';
        }

        if ($data['category_id'] <= 0) {
            return 'Please select a category.';
        }

        if (
            $this->categoryRepository->findById(
                $data['category_id']
            ) === null
        ) {
            return 'Selected category does not exist.';
        }

        $allowedTypes = [
            'upload',
            'url',
        ];

        if (
            !in_array(
                $data['project_type'],
                $allowedTypes,
                true
            )
        ) {
            return 'Please select a valid project type.';
        }

        if ($data['project_type'] === 'url') {

            if ($data['external_url'] === '') {
                return 'External project URL is required.';
            }

            if (
                filter_var(
                    $data['external_url'],
                    FILTER_VALIDATE_URL
                ) === false
            ) {
                return 'Please enter a valid project URL.';
            }

            $scheme = parse_url(
                $data['external_url'],
                PHP_URL_SCHEME
            );

            if (
                !is_string($scheme) ||
                !in_array(
                    strtolower($scheme),
                    [
                        'http',
                        'https',
                    ],
                    true
                )
            ) {
                return 'Project URL must use HTTP or HTTPS.';
            }
        }

        if ($data['project_type'] === 'upload') {

            if (
                !$isUpdate &&
                $data['project_zip'] === null
            ) {
                return 'Please upload a project ZIP file.';
            }

            if (
                $data['entry_path'] !== null &&
                $data['entry_path'] !== ''
            ) {
                $entryPath = str_replace(
                    '\\',
                    '/',
                    $data['entry_path']
                );

                if (
                    str_starts_with(
                        $entryPath,
                        '/'
                    )
                ) {
                    return 'Project entry path must be relative.';
                }

                if (
                    preg_match(
                        '#(^|/)\.\.?(/|$)#',
                        $entryPath
                    )
                ) {
                    return 'Project entry path contains an unsafe path.';
                }
            }
        }

        if ($data['sort_order'] < 0) {
            return 'Sort order cannot be negative.';
        }

        return null;
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

        return trim(
            (string) $value,
            '-'
        );
    }

    private function emptyPortfolio(): array
    {
        return [
            'id' => null,
            'category_id' => 0,
            'title' => '',
            'slug' => '',
            'description' => '',
            'thumbnail' => null,
            'project_type' => 'url',
            'project_path' => null,
            'entry_path' => null,
            'external_url' => '',
            'is_featured' => 0,
            'is_published' => 1,
            'sort_order' => 0,
        ];
    }

    private function makeProjectFolderName(
        string $slug
    ): string {
        $slug = trim($slug);

        $slug = preg_replace(
            '/[^a-zA-Z0-9_-]+/',
            '-',
            $slug
        );

        $slug = trim(
            (string) $slug,
            '-_'
        );

        if ($slug === '') {
            throw new \RuntimeException(
                'Unable to create a project folder name.'
            );
        }

        return $slug;
    }

    private function verifyCsrf(): bool
    {
        $token = $this->request->post('_csrf');

        if (!$this->csrf->verify($token)) {
            $this->response->send(
                'Invalid CSRF token.',
                419
            );

            return false;
        }

        return true;
    }

    private function showCreateError(
        string $error,
        array $data
    ): void {
        $portfolio = [
            ...$this->emptyPortfolio(),
            ...$data,
        ];

        $html = $this->view->render(
            'admin.portfolio.create',
            [
                'title' => 'Add Portfolio',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'categories' =>
                $this->categoryRepository->getAll(),
                'technologies' =>
                $this->technologyRepository->getAll(),
                'error' => $error,
                'portfolio' => $portfolio,
                'selectedTechnologyIds' =>
                $data['technology_ids'],
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
        array $portfolio,
        array $data
    ): void {
        $portfolio = [
            ...$portfolio,
            ...$data,
        ];

        $html = $this->view->render(
            'admin.portfolio.edit',
            [
                'title' => 'Edit Portfolio',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'categories' =>
                $this->categoryRepository->getAll(),
                'technologies' =>
                $this->technologyRepository->getAll(),
                'error' => $error,
                'portfolio' => $portfolio,
                'selectedTechnologyIds' =>
                $data['technology_ids'],
            ],
            'layouts.admin'
        );

        $this->response->send(
            $html,
            422
        );
    }

    private function notFound(): string
    {
        http_response_code(404);

        return $this->view->render(
            'admin.portfolio.not-found',
            [
                'title' => 'Portfolio Not Found',
                'appUrl' => $this->appUrl,
            ],
            'layouts.admin'
        );
    }
}
