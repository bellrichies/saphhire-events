<?php

namespace App\Core;

class Controller
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    protected function view(string $view, array $data = []): void
    {
        View::make($view, $data)->render();
    }

    protected function response(array $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }

    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            
            if (strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = ucfirst($field) . ' is required';
            }

            if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = ucfirst($field) . ' must be a valid email';
            }

            if (strpos($rule, 'min:') !== false) {
                preg_match('/min:(\d+)/', $rule, $matches);
                if (strlen($value) < $matches[1]) {
                    $errors[$field] = ucfirst($field) . ' must be at least ' . $matches[1] . ' characters';
                }
            }

            if (strpos($rule, 'max:') !== false) {
                preg_match('/max:(\d+)/', $rule, $matches);
                if (strlen($value) > $matches[1]) {
                    $errors[$field] = ucfirst($field) . ' must not exceed ' . $matches[1] . ' characters';
                }
            }
        }

        return $errors;
    }

    protected function sanitize(string $input): string
    {
        return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
    }

    protected function json(array $data, int $status = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    protected function appendLocalizedFields(array $payload, array $input, array $baseFields): array
    {
        $locales = $this->getTranslatableLocales();

        foreach ($baseFields as $field) {
            foreach ($locales as $locale) {
                $key = $field . '_' . $locale;
                if (!array_key_exists($key, $input)) {
                    continue;
                }

                $value = $input[$key];
                if (is_string($value)) {
                    $payload[$key] = trim($value);
                } else {
                    $payload[$key] = $value;
                }
            }
        }

        return $payload;
    }

    protected function getTranslatableLocales(): array
    {
        try {
            $config = require CONFIG_PATH . '/languages.php';
            $default = strtolower((string)($config['default'] ?? 'en'));
            $supported = $config['supported'] ?? [];

            $locales = [];
            foreach ($supported as $code => $meta) {
                $locale = strtolower((string)$code);
                if ($locale !== '' && $locale !== $default) {
                    $locales[] = $locale;
                }
            }

            return $locales;
        } catch (\Throwable $e) {
            return ['et', 'fi', 'ru'];
        }
    }
}
