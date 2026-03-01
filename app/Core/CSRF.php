<?php

namespace App\Core;

class CSRF
{
    private const TOKEN_NAME = '_csrf_token';
    private const TOKEN_LENGTH = 32;
    private const TOKEN_LIFETIME = 3600;

    public static function generateToken(): string
    {
        if (!isset($_SESSION[self::TOKEN_NAME])) {
            $_SESSION[self::TOKEN_NAME] = bin2hex(random_bytes(self::TOKEN_LENGTH / 2));
        }

        // Sliding expiration: keep active sessions from unexpectedly expiring tokens.
        $_SESSION[self::TOKEN_NAME . '_time'] = time();
        return $_SESSION[self::TOKEN_NAME];
    }

    public static function getToken(): string
    {
        return self::generateToken();
    }

    public static function validate(string $token): bool
    {
        if (!isset($_SESSION[self::TOKEN_NAME])) {
            return false;
        }

        $timeoutExists = isset($_SESSION[self::TOKEN_NAME . '_time']);
        $isExpired = $timeoutExists && (time() - $_SESSION[self::TOKEN_NAME . '_time'] > self::TOKEN_LIFETIME);

        if ($isExpired) {
            unset($_SESSION[self::TOKEN_NAME], $_SESSION[self::TOKEN_NAME . '_time']);
            return false;
        }

        $isValid = hash_equals($_SESSION[self::TOKEN_NAME], $token);
        if ($isValid) {
            $_SESSION[self::TOKEN_NAME . '_time'] = time();
        }

        return $isValid;
    }

    public static function regenerate(): void
    {
        unset($_SESSION[self::TOKEN_NAME], $_SESSION[self::TOKEN_NAME . '_time']);
        self::generateToken();
    }

    public static function hidden(): string
    {
        return sprintf(
            '<input type="hidden" name="_csrf_token" value="%s">',
            htmlspecialchars(self::getToken(), ENT_QUOTES, 'UTF-8')
        );
    }
}
