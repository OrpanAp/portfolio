<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    public function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_set_cookie_params([
                'httponly' => true,
                'secure' => isset($_SERVER['HTTPS'])
                    && $_SERVER['HTTPS'] !== 'off',
                'samesite' => 'Lax',
            ]);

            session_start();
        }
    }

    public function set(string $key, mixed $value): void
    {
        $this->start();

        $_SESSION[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $this->start();

        return $_SESSION[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        $this->start();

        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void
    {
        $this->start();

        unset($_SESSION[$key]);
    }

    public function all(): array
    {
        $this->start();

        return $_SESSION;
    }

    public function flash(string $key, mixed $value): void
    {
        $this->set('_flash_' . $key, $value);
    }

    public function getFlash(string $key, mixed $default = null): mixed
    {
        $flashKey = '_flash_' . $key;

        $value = $this->get($flashKey, $default);

        $this->remove($flashKey);

        return $value;
    }

    public function regenerate(): void
    {
        $this->start();

        session_regenerate_id(true);
    }

    public function destroy(): void
    {
        $this->start();

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                (bool) $params['secure'],
                (bool) $params['httponly']
            );
        }

        session_destroy();
    }
}
