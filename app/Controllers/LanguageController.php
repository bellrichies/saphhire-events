<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Translator;

class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switch()
    {
        $language = strtolower(trim((string) ($_GET['lang'] ?? $_POST['lang'] ?? '')));

        if ($language === '') {
            $this->redirect(route('/'));
            return;
        }

        $translator = Translator::getInstance();
        $supported = $translator->getSupportedLanguages();
        if (!isset($supported[$language])) {
            $this->redirect($this->resolveRedirect(null));
            return;
        }

        $translator->setLanguage($language);

        $requestedRedirect = $_GET['redirect'] ?? $_POST['redirect'] ?? null;
        $this->redirect($this->resolveRedirect($requestedRedirect));
    }

    /**
     * Get language info (for AJAX)
     */
    public function info()
    {
        $translator = Translator::getInstance();
        
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'current_language' => $translator->getCurrentLanguage(),
            'languages' => $translator->getSupportedLanguages(),
        ]);
        exit;
    }

    private function resolveRedirect(?string $requestedRedirect): string
    {
        $basePath = rtrim(determineBasePath(), '/');
        $fallback = route('/');

        $candidate = trim((string) $requestedRedirect);
        if ($candidate === '') {
            $candidate = (string) ($_SERVER['HTTP_REFERER'] ?? '');
        }
        if ($candidate === '') {
            return $fallback;
        }

        $parts = parse_url($candidate);
        if ($parts === false) {
            return $fallback;
        }

        // Prevent open redirects to external hosts/schemes.
        if (isset($parts['scheme']) || isset($parts['host'])) {
            return $fallback;
        }

        $path = $parts['path'] ?? '/';
        if ($path === '') {
            $path = '/';
        }
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        if ($path !== $basePath && strpos($path, $basePath . '/') !== 0) {
            $path = $basePath . '/' . ltrim($path, '/');
        }

        $queryParams = [];
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $queryParams);
            unset($queryParams['lang']);
        }

        $queryString = http_build_query($queryParams);
        return $path . ($queryString !== '' ? '?' . $queryString : '');
    }
}
