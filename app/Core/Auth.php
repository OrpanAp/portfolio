<?php

declare(strict_types=1);

namespace App\Core;

class Auth
{
    private const USER_SESSION_KEY = 'auth_user';

    public function __construct(
        private Session $session
    ) {}

    public function login(array $user): void
    {
        $this->session->regenerate();

        $this->session->set(self::USER_SESSION_KEY, [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
        ]);
    }

    public function logout(): void
    {
        $this->session->destroy();
    }

    public function check(): bool
    {
        return $this->session->has(self::USER_SESSION_KEY);
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function user(): ?array
    {
        $user = $this->session->get(self::USER_SESSION_KEY);

        if (!is_array($user)) {
            return null;
        }

        return $user;
    }

    public function id(): ?int
    {
        $user = $this->user();

        if ($user === null) {
            return null;
        }

        return (int) $user['id'];
    }

    public function role(): ?string
    {
        $user = $this->user();

        if ($user === null) {
            return null;
        }

        return $user['role'] ?? null;
    }

    public function isAdmin(): bool
    {
        return $this->role() === 'admin';
    }
}
