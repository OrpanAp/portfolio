<?php

declare(strict_types=1);

namespace App\Core;

class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public function __construct(
        private Session $session
    ) {}

    public function token(): string
    {
        $token = $this->session->get(self::SESSION_KEY);

        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));

            $this->session->set(
                self::SESSION_KEY,
                $token
            );
        }

        return $token;
    }

    public function field(): string
    {
        return sprintf(
            '<input type="hidden" name="_csrf" value="%s">',
            htmlspecialchars(
                $this->token(),
                ENT_QUOTES,
                'UTF-8'
            )
        );
    }

    public function verify(?string $token): bool
    {
        if ($token === null || $token === '') {
            return false;
        }

        $sessionToken = $this->session->get(self::SESSION_KEY);

        if (
            !is_string($sessionToken) ||
            $sessionToken === ''
        ) {
            return false;
        }

        return hash_equals(
            $sessionToken,
            $token
        );
    }
}
