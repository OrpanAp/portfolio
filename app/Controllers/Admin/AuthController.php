<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\AuthService;

class AuthController
{
    public function __construct(
        private View $view,
        private Request $request,
        private Response $response,
        private Auth $auth,
        private Csrf $csrf,
        private AuthService $authService,
        private string $appUrl
    ) {}

    public function showLogin(): string
    {
        if ($this->auth->isAdmin()) {
            $this->response->redirect(
                $this->appUrl . '/admin'
            );
        }

        return $this->view->render(
            'admin.auth.login',
            [
                'title' => 'Admin Login',
                'appUrl' => $this->appUrl,
                'csrfField' => $this->csrf->field(),
                'error' => null,
            ],
            null
        );
    }

    public function login(): never
    {
        $token = $this->request->post('_csrf');

        if (!$this->csrf->verify($token)) {
            $this->response->send(
                'Invalid CSRF token.',
                403
            );

            exit;
        }

        $email = trim(
            (string) $this->request->post('email')
        );

        $password = (string) $this->request->post(
            'password'
        );

        if (
            $email === '' ||
            $password === ''
        ) {
            $this->response->send(
                'Email and password are required.',
                422
            );

            exit;
        }

        $success = $this->authService->attempt(
            $email,
            $password
        );

        if (!$success) {
            $html = $this->view->render(
                'admin.auth.login',
                [
                    'title' => 'Admin Login',
                    'appUrl' => $this->appUrl,
                    'csrfField' => $this->csrf->field(),
                    'error' => 'Invalid admin credentials.',
                ],
                null
            );

            $this->response->send(
                $html,
                401
            );

            exit;
        }

        $this->response->redirect(
            $this->appUrl . '/admin'
        );
    }

    public function logout(): never
    {
        $token = $this->request->post('_csrf');

        if (!$this->csrf->verify($token)) {
            $this->response->send(
                'Invalid CSRF token.',
                403
            );

            exit;
        }

        $this->authService->logout();

        $this->response->redirect(
            $this->appUrl . '/admin/login'
        );
    }
}
