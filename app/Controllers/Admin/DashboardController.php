<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\View;
use App\Core\Csrf;

class DashboardController
{
    public function __construct(
        private View $view,
        private Auth $auth,
        private Csrf $csrf,
        private string $appUrl
    ) {}

    public function index(): string
    {
        $user = $this->auth->user();

        return $this->view->render(
            'admin.dashboard',
            [
                'title' => 'Admin Dashboard',
                'appUrl' => $this->appUrl,
                'user' => $user,
                'csrfField' => $this->csrf->field(),
            ],
            'layouts.admin'
        );
    }
}
