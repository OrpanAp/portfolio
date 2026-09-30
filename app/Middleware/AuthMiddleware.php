<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Response;

class AuthMiddleware
{
    public function __construct(
        private Auth $auth,
        private Response $response,
        private string $appUrl
    ) {}

    public function handle(): void
    {
        if (!$this->auth->isAdmin()) {
            $this->response->redirect(
                $this->appUrl . '/admin/login'
            );
        }
    }
}
