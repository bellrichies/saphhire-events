<?php

/**
 * Global Helper Functions for URL and Asset Management
 */

if (!function_exists('baseUrl')) {
    /**
     * Get the base URL of the application
     * 
     * @param string $path Optional path to append
     * @return string The base URL
     */
    function baseUrl($path = '') {
        $baseUrl = rtrim($_ENV['APP_URL'] ?? 'http://localhost/sapphireevents', '/');
        $basePath = determineBasePath();
        
        if ($basePath !== '/') {
            $normalizedBasePath = '/' . trim($basePath, '/');
            $configuredPath = parse_url($baseUrl, PHP_URL_PATH) ?: '';
            $normalizedConfiguredPath = $configuredPath === '' ? '/' : '/' . trim($configuredPath, '/');

            if ($normalizedConfiguredPath !== $normalizedBasePath) {
                $baseUrl = rtrim($baseUrl, '/') . $normalizedBasePath;
            }
        }
        
        if ($path) {
            $baseUrl = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
        }
        
        return $baseUrl;
    }
}

if (!function_exists('route')) {
    /**
     * Generate a relative URL for a given route
     * 
     * @param string $path The route path
     * @return string The full URL for the route
     */
    function route($path = '') {
        $basePath = determineBasePath();
        $path = ltrim($path, '/');
        return $basePath . ($path ? $path : '');
    }
}

if (!function_exists('asset')) {
    /**
     * Generate a URL for an asset (CSS, JS, images, etc.)
     * 
     * @param string $path The path to the asset relative to /public/assets/
     * @return string The full URL to the asset
     */
    function asset($path = '') {
        $basePath = determineBasePath();
        $path = ltrim($path, '/');
        return $basePath . 'assets/' . $path;
    }
}

if (!function_exists('url')) {
    /**
     * Generate a full URL
     * 
     * @param string $path The path to append to base URL
     * @return string The full URL
     */
    function url($path = '') {
        return baseUrl($path);
    }
}

if (!function_exists('currentUrl')) {
    /**
     * Get the current page URL
     * 
     * @return string The current URL
     */
    function currentUrl() {
        $baseUrl = baseUrl();
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $basePath = determineBasePath();
        
        // Remove base path from URI to get relative URL
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        return $baseUrl . '/' . ltrim($uri, '/');
    }
}

if (!function_exists('isRoute')) {
    /**
     * Check if current route matches the given path
     * 
     * @param string $path The route path to check
     * @return bool True if current route matches
     */
    function isRoute($path) {
        $path = ltrim($path, '/');
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $basePath = determineBasePath();
        
        // Remove base path and query string
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = strtok($uri, '?');
        $uri = rtrim($uri, '/');
        
        return $uri === ltrim($path, '/') || $uri === ('/' . ltrim($path, '/'));
    }
}

if (!function_exists('determineBasePath')) {
    /**
     * Determine the base path of the application
     * This handles both /public directly and /sapphireevents/public redirects
     * 
     * @return string The base path with leading slash
     */
    function determineBasePath() {
        $basePath = '/sapphireevents/';
        
        // If we're accessing through .htaccess redirect, the path is already correct
        // The rewrite rule converts /sapphireevents/something to /sapphireevents/public/index.php?/something
        // So we just need to return our base path
        
        return $basePath;
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a given URL
     * 
     * @param string $path The path to redirect to
     * @param int $statusCode The HTTP status code
     * @return void
     */
    function redirect($path = '', $statusCode = 302) {
        $url = route($path);
        header("Location: " . $url, true, $statusCode);
        exit;
    }
}

if(!function_exists('redirectBack')) {
    /**
     * Redirect back to the referring page
     * 
     * @param int $statusCode The HTTP status code
     * @return void
     */
    function redirectBack($statusCode = 302) {
        $referer = $_SERVER['HTTP_REFERER'] ?? route('/');
        header("Location: " . $referer, true, $statusCode);
        exit;
    }
}

if (!function_exists('secure_url')) {
    /**
     * Generate a secure (HTTPS) URL
     * 
     * @param string $path The path to append
     * @return string The secure URL
     */
    function secure_url($path = '') {
        $url = baseUrl($path);
        return str_replace('http://', 'https://', $url);
    }
}

if (!function_exists('uploadedImageUrl')) {
    /**
     * Resolve an image reference saved in DB to a browser-safe URL.
     * Supports absolute URLs, rooted paths, assets/images/* and legacy uploads/gallery filenames.
     *
     * @param string|null $image
     * @return string
     */
    function uploadedImageUrl(?string $image): string {
        if (!$image) {
            return '';
        }

        $image = trim($image);
        if ($image === '') {
            return '';
        }

        if (preg_match('/^https?:\/\//i', $image)) {
            return $image;
        }

        if (str_starts_with($image, '/')) {
            return route($image);
        }

        if (str_starts_with($image, 'assets/') || str_starts_with($image, 'uploads/')) {
            return route('/' . $image);
        }

        return route('/uploads/gallery/' . $image);
    }
}

if (!function_exists('trans')) {
    /**
     * Get a translation string
     * 
     * @param string $key The translation key (file.key format)
     * @param string $default Optional default value
     * @return string The translated string
     */
    function trans($key, $default = '') {
        $translator = \App\Core\Translator::getInstance();
        return $translator->translate($key, $default);
    }
}

if (!function_exists('getCurrentLanguage')) {
    /**
     * Get the current language code
     * 
     * @return string The language code (e.g., 'en', 'et', 'fi', 'ru')
     */
    function getCurrentLanguage() {
        $translator = \App\Core\Translator::getInstance();
        return $translator->getCurrentLanguage();
    }
}

if (!function_exists('getSupportedLanguages')) {
    /**
     * Get all supported languages with metadata
     * 
     * @return array Array of supported languages
     */
    function getSupportedLanguages() {
        $translator = \App\Core\Translator::getInstance();
        return $translator->getSupportedLanguages();
    }
}

if (!function_exists('getLanguageInfo')) {
    /**
     * Get information about a specific language
     * 
     * @param string $language Optional language code (uses current if not specified)
     * @return array Language metadata
     */
    function getLanguageInfo($language = null) {
        $translator = \App\Core\Translator::getInstance();
        return $translator->getLanguageInfo($language);
    }
}

if (!function_exists('setLanguage')) {
    /**
     * Set the current language
     * 
     * @param string $language The language code
     * @return bool Success status
     */
    function setLanguage($language) {
        $translator = \App\Core\Translator::getInstance();
        return $translator->setLanguage($language);
    }
}

if (!function_exists('appConfig')) {
    /**
     * Read app config once and optionally return a nested key.
     *
     * @param string|null $key Dot-notation key
     * @param mixed $default
     * @return mixed
     */
    function appConfig(?string $key = null, mixed $default = null): mixed {
        static $config = null;

        if ($config === null) {
            $configFile = CONFIG_PATH . '/app.php';
            $config = file_exists($configFile) ? (require $configFile) : [];
        }

        if ($key === null || $key === '') {
            return $config;
        }

        $segments = explode('.', $key);
        $value = $config;
        foreach ($segments as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }

        return $value;
    }
}

if (!function_exists('seoTrimText')) {
    /**
     * Normalize and trim text for SEO tags.
     *
     * @param string|null $text
     * @param int $maxLength
     * @return string
     */
    function seoTrimText(?string $text, int $maxLength): string {
        $clean = trim(preg_replace('/\s+/', ' ', strip_tags((string)$text)));
        if ($clean === '') {
            return '';
        }

        if (mb_strlen($clean) <= $maxLength) {
            return $clean;
        }

        return rtrim(mb_substr($clean, 0, $maxLength - 1)) . '…';
    }
}

if (!function_exists('canonicalUrl')) {
    /**
     * Build a canonical URL from current request path.
     *
     * @return string
     */
    function canonicalUrl(): string {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($requestUri, PHP_URL_PATH) ?: '/';
        $basePath = determineBasePath();
        $normalizedBasePath = '/' . trim($basePath, '/');

        if ($path === $normalizedBasePath || $path === $normalizedBasePath . '/') {
            $path = '/';
        } elseif (strpos($path, $normalizedBasePath . '/') === 0) {
            $path = '/' . ltrim(substr($path, strlen($normalizedBasePath)), '/');
        }

        if ($path === '' || $path === '//') {
            $path = '/';
        }

        return baseUrl(ltrim($path, '/'));
    }
}

if (!function_exists('toAbsoluteUrl')) {
    /**
     * Convert relative app paths to absolute URLs.
     *
     * @param string|null $url
     * @return string
     */
    function toAbsoluteUrl(?string $url): string {
        $value = trim((string)$url);
        if ($value === '') {
            return '';
        }

        if (preg_match('/^https?:\/\//i', $value)) {
            return $value;
        }

        $relative = ltrim($value, '/');
        $normalizedBasePath = trim(determineBasePath(), '/');

        if ($normalizedBasePath !== '' && str_starts_with($relative, $normalizedBasePath . '/')) {
            $relative = substr($relative, strlen($normalizedBasePath) + 1);
        } elseif ($normalizedBasePath !== '' && $relative === $normalizedBasePath) {
            $relative = '';
        }

        return url($relative);
    }
}

if (!function_exists('buildSeoMeta')) {
    /**
     * Build normalized SEO metadata for SSR rendering.
     *
     * @param array<string, mixed> $overrides
     * @return array<string, string>
     */
    function buildSeoMeta(array $overrides = []): array {
        $siteName = (string)appConfig('site.name', 'Sapphire Events & Decorations');
        $defaultImage = 'assets/images/ceo-image.png';
        $localeMap = [
            'en' => 'en_US',
            'et' => 'et_EE',
            'fi' => 'fi_FI',
            'ru' => 'ru_RU',
        ];

        $language = getCurrentLanguage();
        $meta = [
            'title' => (string)appConfig('seo.title', $siteName),
            'description' => (string)appConfig('seo.description', appConfig('site.description', '')),
            'keywords' => (string)appConfig('seo.keywords', ''),
            'canonical' => canonicalUrl(),
            'url' => canonicalUrl(),
            'image' => $defaultImage,
            'image_alt' => $siteName . ' event planning and decoration',
            'type' => 'website',
            'site_name' => $siteName,
            'locale' => $localeMap[$language] ?? 'en_US',
            'robots' => 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1',
            'twitter_card' => 'summary_large_image',
            'twitter_site' => '',
        ];

        foreach ($overrides as $key => $value) {
            if ($value === null) {
                continue;
            }
            $meta[$key] = (string)$value;
        }

        $meta['title'] = seoTrimText($meta['title'], 65);
        $meta['description'] = seoTrimText($meta['description'], 160);
        $meta['keywords'] = seoTrimText($meta['keywords'], 255);
        $meta['canonical'] = toAbsoluteUrl($meta['canonical']);
        $meta['url'] = toAbsoluteUrl($meta['url']) ?: $meta['canonical'];
        $meta['image'] = toAbsoluteUrl($meta['image']);
        $meta['image_alt'] = seoTrimText($meta['image_alt'], 120);
        $meta['site_name'] = seoTrimText($meta['site_name'], 80);
        $meta['type'] = in_array($meta['type'], ['website', 'article'], true) ? $meta['type'] : 'website';

        $imageExtension = strtolower((string)pathinfo((string)parse_url($meta['image'], PHP_URL_PATH), PATHINFO_EXTENSION));
        if ($meta['image'] === '' || $imageExtension === 'avif') {
            $meta['image'] = toAbsoluteUrl($defaultImage);
        }

        return $meta;
    }
}
