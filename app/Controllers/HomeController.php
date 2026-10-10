<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Repositories\PortfolioRepository;
use App\Repositories\ProfileRepository;
use App\Repositories\SettingRepository;

class HomeController
{
    public function __construct(
        private View $view,
        private ProfileRepository $profileRepository,
        private SettingRepository $settingRepository,
        private PortfolioRepository $portfolioRepository,
        private string $appUrl
    ) {}

    public function index(): string
    {
        $profile =
            $this->profileRepository->get();

        $featuredPortfolios =
            $this->portfolioRepository->getPublished();

        $featuredPortfolios =
            array_values(
                array_filter(
                    $featuredPortfolios,
                    static function (
                        array $portfolio
                    ): bool {
                        return (int) (
                            $portfolio['is_featured']
                            ?? 0
                        ) === 1;
                    }
                )
            );

        usort(
            $featuredPortfolios,
            static function (
                array $a,
                array $b
            ): int {
                return strcmp(
                    (string) (
                        $b['created_at'] ?? ''
                    ),
                    (string) (
                        $a['created_at'] ?? ''
                    )
                );
            }
        );

        $featuredPortfolios =
            array_slice(
                $featuredPortfolios,
                0,
                6
            );

        $siteTitle =
            $this->settingRepository->get(
                'site_title'
            ) ?? '';

        $siteDescription =
            $this->settingRepository->get(
                'site_description'
            ) ?? '';

        return $this->view->render(
            'home.index',
            [
                'title' =>
                $siteTitle !== ''
                    ? $siteTitle
                    : 'Home',

                'appUrl' =>
                $this->appUrl,

                'profile' =>
                $profile,

                'siteTitle' =>
                $siteTitle,

                'siteDescription' =>
                $siteDescription,

                'featuredPortfolios' =>
                $featuredPortfolios,
            ]
        );
    }
}
