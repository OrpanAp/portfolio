<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\CategoryRepository;
use App\Repositories\PortfolioRepository;
use App\Repositories\TechnologyRepository;
use App\Services\IframeService;

class PortfolioController
{
    public function __construct(
        private View $view,
        private PortfolioRepository $portfolioRepository,
        private CategoryRepository $categoryRepository,
        private TechnologyRepository $technologyRepository,
        private string $appUrl,
        private IframeService $iframeService
    ) {}

    public function index(): string
    {
        /*
        |--------------------------------------------------------------------------
        | Search & Filters
        |--------------------------------------------------------------------------
        */

        $search =
            isset($_GET['search'])
            ? trim((string) $_GET['search'])
            : null;

        $categoryId =
            isset($_GET['category']) &&
            $_GET['category'] !== ''
            ? (int) $_GET['category']
            : null;

        $technologyId =
            isset($_GET['technology']) &&
            $_GET['technology'] !== ''
            ? (int) $_GET['technology']
            : null;

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $page =
            isset($_GET['page'])
            ? max(1, (int) $_GET['page'])
            : 1;

        $sort =
            isset($_GET['sort'])
            ? trim((string) $_GET['sort'])
            : 'featured';

        $perPage = 9;

        /*
        |--------------------------------------------------------------------------
        | Portfolio Results
        |--------------------------------------------------------------------------
        */

        $portfolioResults =
            $this->portfolioRepository->searchPublished(
                $search,
                $categoryId,
                $technologyId,
                $page,
                $perPage,
                $sort
            );

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $categories =
            $this->categoryRepository->getAll();

        $technologies =
            $this->technologyRepository->getAll();

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        return $this->view->render(
            'portfolio.index',
            [
                'title' =>
                'Portfolio',

                'appUrl' =>
                $this->appUrl,

                'portfolios' =>
                $portfolioResults['items'],

                'categories' =>
                $categories,

                'technologies' =>
                $technologies,

                'search' =>
                $search,

                'sort' =>
                $sort,

                'selectedCategory' =>
                $categoryId,

                'selectedTechnology' =>
                $technologyId,

                'page' =>
                $portfolioResults['page'],

                'perPage' =>
                $portfolioResults['per_page'],

                'total' =>
                $portfolioResults['total'],

                'totalPages' =>
                $portfolioResults['total_pages'],
            ]
        );
    }

    public function show(string $slug): string
    {
        $portfolio =
            $this->portfolioRepository->findBySlug(
                $slug
            );

        if ($portfolio === null) {

            http_response_code(404);

            return $this->view->render(
                'portfolio.show',
                [
                    'title' =>
                    'Project Not Found',

                    'appUrl' =>
                    $this->appUrl,

                    'portfolio' =>
                    null,
                ]
            );
        }

        return $this->view->render(
            'portfolio.show',
            [
                'title' =>
                $portfolio['title'],

                'appUrl' =>
                $this->appUrl,

                'portfolio' =>
                $portfolio,

                'previewUrl' =>
                $this->iframeService->getPreviewUrl(
                    $portfolio
                ),
            ]
        );
    }
}
