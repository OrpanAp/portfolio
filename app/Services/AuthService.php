<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Auth;
use App\Repositories\UserRepository;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private Auth $auth
    ) {}

    public function attempt(
        string $email,
        string $password
    ): bool {
        $user =
            $this->userRepository->findByEmail($email);

        if ($user === null) {
            return false;
        }

        if ($user['role'] !== 'admin') {
            return false;
        }

        if (
            !password_verify(
                $password,
                $user['password']
            )
        ) {
            return false;
        }

        $this->auth->login($user);

        return true;
    }

    public function logout(): void
    {
        $this->auth->logout();
    }
}
