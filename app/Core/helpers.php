<?php

/**
 * Global Helper Functions for URL and Asset Management
 */

if (!function_exists('configuredAppUrl')) {
    /**
     * Get APP_URL from environment with request-based fallback.
     *
     * @return string
     */
    function configuredAppUrl(): string {
        $configured = trim((string)($_ENV['APP_URL'] ?? ''));
        if ($configured !== '') {
            return rtrim($configured, '/');
        }

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme . '://' . $host;
    }
}

if (!function_exists('baseUrl')) {
    /**
     * Get the base URL of the application
     * 
     * @param string $path Optional path to append
     * @return string The base URL
     */
    function baseUrl($path = '') {
        $baseUrl = configuredAppUrl();
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
        $uri = (string)($_SERVER['REQUEST_URI'] ?? '/');
        $uriPath = parse_url($uri, PHP_URL_PATH) ?: '/';
        $query = (string)(parse_url($uri, PHP_URL_QUERY) ?? '');
        $normalizedBasePath = '/' . trim(determineBasePath(), '/');

        if ($normalizedBasePath !== '/') {
            if ($uriPath === $normalizedBasePath) {
                $uriPath = '/';
            } elseif (strpos($uriPath, $normalizedBasePath . '/') === 0) {
                $uriPath = '/' . ltrim(substr($uriPath, strlen($normalizedBasePath)), '/');
            }
        }

        $url = $baseUrl . '/' . ltrim($uriPath, '/');
        if ($query !== '') {
            $url .= '?' . $query;
        }

        return $url;
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
        $uri = (string)($_SERVER['REQUEST_URI'] ?? '/');
        $uriPath = parse_url($uri, PHP_URL_PATH) ?: '/';
        $normalizedBasePath = '/' . trim(determineBasePath(), '/');

        if ($normalizedBasePath !== '/') {
            if ($uriPath === $normalizedBasePath) {
                $uriPath = '/';
            } elseif (strpos($uriPath, $normalizedBasePath . '/') === 0) {
                $uriPath = '/' . ltrim(substr($uriPath, strlen($normalizedBasePath)), '/');
            }
        }
        $uri = rtrim($uriPath, '/');
        
        return $uri === ltrim($path, '/') || $uri === ('/' . ltrim($path, '/'));
    }
}

if (!function_exists('determineBasePath')) {
    /**
     * Determine the app base path from APP_URL.
     * 
     * @return string The base path with leading slash
     */
    function determineBasePath() {
        static $basePath = null;

        if ($basePath !== null) {
            return $basePath;
        }

        $configuredUrl = configuredAppUrl();
        $path = (string)(parse_url($configuredUrl, PHP_URL_PATH) ?? '');
        $trimmed = trim($path, '/');
        $basePath = $trimmed === '' ? '/' : '/' . $trimmed . '/';

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
     * Supports absolute URLs, rooted paths, media-library assets, public assets/images, and legacy uploads/gallery filenames.
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
            $basePath = determineBasePath();
            if ($basePath !== '/' && str_starts_with($image, $basePath)) {
                return $image;
            }
            return route($image);
        }

        if (str_starts_with($image, 'assets/') || str_starts_with($image, 'uploads/')) {
            return route('/' . $image);
        }

        $candidatePaths = [
            'assets/uploads/media/image/' . $image,
            'assets/images/' . $image,
            'uploads/gallery/' . $image,
        ];

        foreach ($candidatePaths as $candidatePath) {
            $absolutePath = PUBLIC_PATH . '/' . $candidatePath;
            if (is_file($absolutePath)) {
                return route('/' . $candidatePath);
            }
        }

        $mediaLibraryMatches = glob(PUBLIC_PATH . '/assets/uploads/media/image/*/*/' . $image);
        if (is_array($mediaLibraryMatches) && $mediaLibraryMatches !== []) {
            $relativePath = str_replace('\\', '/', substr($mediaLibraryMatches[0], strlen(PUBLIC_PATH) + 1));
            return route('/' . ltrim($relativePath, '/'));
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

if (!function_exists('siteSettings')) {
    /**
     * Load site settings from database with config-backed defaults.
     *
     * @param bool $refresh
     * @return array<string, string>
     */
    function siteSettings(bool $refresh = false): array {
        static $settings = null;

        if ($settings !== null && !$refresh) {
            return $settings;
        }

        $defaults = [
            'site_name' => (string)appConfig('site.name', 'Sapphire Events & Decorations'),
            'site_tagline' => (string)appConfig('site.tagline', 'Adding glitz & glam to your events'),
            'site_description' => (string)appConfig('site.description', ''),
            'site_email' => (string)appConfig('site.email', ''),
            'site_phone' => (string)appConfig('site.phone', ''),
            'site_address' => (string)appConfig('site.address', ''),
            'site_registration_code' => (string)appConfig('site.registration_code', ''),
            'site_logo' => 'assets/images/logo.png',
            'site_favicon' => 'assets/images/favicon.png',
            'site_og_image' => 'assets/images/ceo-image.png',
            'theme_primary_color' => (string)appConfig('colors.primary', '#0F3D3E'),
            'theme_accent_color' => (string)appConfig('colors.accent', '#C8A951'),
            'theme_light_color' => (string)appConfig('colors.light', '#F8F5F2'),
            'theme_dark_color' => (string)appConfig('colors.dark', '#1C1C1C'),
            'theme_body_font' => 'lora',
            'theme_heading_font' => 'syncopate',
            'theme_display_font' => 'dancing-script',
            'theme_ui_font' => 'montserrat',
            'theme_body_size' => '1rem',
            'theme_h1_size' => '3.5rem',
            'theme_h2_size' => '2.5rem',
            'inner_hero_render_mode' => 'gradient_only',
            'inner_hero_background_image' => '',
            'inner_hero_overlay_opacity' => '65',
            'inner_hero_overlay_start' => '#0F3D3E',
            'inner_hero_overlay_end' => '#1C1C1C',
            'social_instagram' => (string)appConfig('social.instagram', ''),
            'social_facebook' => (string)appConfig('social.facebook', ''),
            'social_tiktok' => (string)appConfig('social.tiktok', ''),
            'social_whatsapp' => (string)appConfig('social.whatsapp', ''),
        ];

        try {
            if (class_exists(\App\Models\SiteSetting::class)) {
                $model = new \App\Models\SiteSetting();
                $settings = array_merge($defaults, $model->allAsKeyValue());
                return $settings;
            }
        } catch (\Throwable $e) {
            // Fall back to config defaults if DB settings are unavailable.
        }

        $settings = $defaults;
        return $settings;
    }
}

if (!function_exists('themeFontOptions')) {
    /**
     * Get allowed theme font options.
     *
     * @return array<string, array{label:string, stack:string}>
     */
    function themeFontOptions(): array {
        return [
            'lora' => ['label' => 'Lora', 'stack' => "'Lora', serif"],
            'montserrat' => ['label' => 'Montserrat', 'stack' => "'Montserrat', sans-serif"],
            'league-spartan' => ['label' => 'League Spartan', 'stack' => "'League Spartan', sans-serif"],
            'cormorant-garamond' => ['label' => 'Cormorant Garamond', 'stack' => "'Cormorant Garamond', serif"],
            'dancing-script' => ['label' => 'Dancing Script', 'stack' => "'Dancing Script', cursive"],
            'manrope' => ['label' => 'Manrope', 'stack' => "'Manrope', sans-serif"],
            'syncopate' => ['label' => 'Syncopate', 'stack' => "'Syncopate', sans-serif"],
        ];
    }
}

if (!function_exists('themeFontStack')) {
    /**
     * Resolve a stored font key to a CSS font-family stack.
     *
     * @param string $key
     * @param string $fallback
     * @return string
     */
    function themeFontStack(string $key, string $fallback): string {
        $options = themeFontOptions();
        return $options[$key]['stack'] ?? $fallback;
    }
}

if (!function_exists('themeCssSize')) {
    /**
     * Normalize stored CSS size values.
     *
     * @param string $value
     * @param string $default
     * @return string
     */
    function themeCssSize(string $value, string $default): string {
        $normalized = strtolower(trim($value));
        if ($normalized === '') {
            return $default;
        }

        return preg_match('/^\d+(\.\d+)?(px|rem|em|%)$/', $normalized) === 1 ? $normalized : $default;
    }
}

if (!function_exists('hexToRgba')) {
    /**
     * Convert a hex color value to an rgba() CSS string.
     *
     * @param string $hex
     * @param float $alpha
     * @return string
     */
    function hexToRgba(string $hex, float $alpha): string {
        $normalized = ltrim(trim($hex), '#');
        if (strlen($normalized) === 3) {
            $normalized = preg_replace('/(.)/', '$1$1', $normalized) ?? $normalized;
        }

        if (!preg_match('/^[a-f0-9]{6}$/i', $normalized)) {
            $safeAlpha = max(0, min(1, $alpha));
            return 'rgba(0, 0, 0, ' . number_format($safeAlpha, 2, '.', '') . ')';
        }

        $safeAlpha = max(0, min(1, $alpha));
        $red = hexdec(substr($normalized, 0, 2));
        $green = hexdec(substr($normalized, 2, 2));
        $blue = hexdec(substr($normalized, 4, 2));

        return sprintf(
            'rgba(%d, %d, %d, %s)',
            $red,
            $green,
            $blue,
            number_format($safeAlpha, 2, '.', '')
        );
    }
}

if (!function_exists('innerHeroBackgroundStyle')) {
    /**
     * Build shared hero background style for non-home pages.
     *
     * @return string
     */
    function innerHeroBackgroundStyle(): string {
        $mode = (string)siteSetting('inner_hero_render_mode', 'gradient_only');
        $start = (string)siteSetting('inner_hero_overlay_start', '#0F3D3E');
        $end = (string)siteSetting('inner_hero_overlay_end', '#1C1C1C');
        $image = trim((string)siteSetting('inner_hero_background_image', ''));
        $opacityRaw = (int)siteSetting('inner_hero_overlay_opacity', '65');
        $opacity = max(0, min(100, $opacityRaw));
        $opacityRatio = number_format($opacity / 100, 2, '.', '');

        $gradient = "linear-gradient(135deg, {$start} 0%, {$end} 100%)";
        if ($mode !== 'image_overlay' || $image === '') {
            return "background: {$gradient};";
        }

        $imageUrl = uploadedImageUrl($image);
        if ($imageUrl === '') {
            return "background: {$gradient};";
        }

        $overlayGradient = 'linear-gradient(135deg, '
            . hexToRgba($start, (float)$opacityRatio) . ' 0%, '
            . hexToRgba($end, (float)$opacityRatio) . ' 100%)';

        return "background-image: {$overlayGradient}, url('" . htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') . "'); background-size: cover; background-position: center; background-repeat: no-repeat;";
    }
}

if (!function_exists('clearSiteWideCache')) {
    /**
     * Clear application cache files that can hold stale frontend content.
     *
     * @return array{deleted:int, failed:int, opcache_reset:bool}
     */
    function clearSiteWideCache(): array {
        $deleted = 0;
        $failed = 0;

        $patterns = [
            STORAGE_PATH . '/cache/*.json',
            STORAGE_PATH . '/translations-cache/*.json',
        ];

        foreach ($patterns as $pattern) {
            $files = glob($pattern);
            if (!is_array($files)) {
                continue;
            }

            foreach ($files as $file) {
                if (!is_string($file) || !is_file($file)) {
                    continue;
                }

                if (@unlink($file)) {
                    $deleted++;
                } else {
                    $failed++;
                }
            }
        }

        $opcacheReset = false;
        if (function_exists('opcache_reset')) {
            $opcacheReset = @opcache_reset() === true;
        }

        return [
            'deleted' => $deleted,
            'failed' => $failed,
            'opcache_reset' => $opcacheReset,
        ];
    }
}

if (!function_exists('siteSetting')) {
    /**
     * Get a single site setting.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function siteSetting(string $key, mixed $default = null): mixed {
        $settings = siteSettings();

        if (array_key_exists($key, $settings)) {
            return $settings[$key];
        }

        return $default;
    }
}

if (!function_exists('sitePhoneHref')) {
    /**
     * Convert a display phone number into a tel: href value.
     *
     * @return string
     */
    function sitePhoneHref(): string {
        $phone = (string)siteSetting('site_phone', appConfig('site.phone', ''));
        $normalized = preg_replace('/[^\d+]/', '', $phone);
        return $normalized ?: $phone;
    }
}

if (!function_exists('siteWhatsappUrl')) {
    /**
     * Resolve WhatsApp contact URL.
     *
     * @return string
     */
    function siteWhatsappUrl(): string {
        $configured = trim((string)siteSetting('social_whatsapp', appConfig('social.whatsapp', '')));
        if ($configured !== '') {
            return $configured;
        }

        $digits = preg_replace('/\D+/', '', (string)siteSetting('site_phone', appConfig('site.phone', '')));
        return $digits !== '' ? 'https://wa.me/' . $digits : '';
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
        $normalizedBasePath = '/' . trim(determineBasePath(), '/');

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
        $siteName = (string)siteSetting('site_name', appConfig('site.name', 'Sapphire Events & Decorations'));
        $defaultImage = (string)siteSetting('site_og_image', 'assets/images/ceo-image.png');
        $localeMap = [
            'en' => 'en_US',
            'et' => 'et_EE',
            'fi' => 'fi_FI',
            'ru' => 'ru_RU',
        ];

        $language = getCurrentLanguage();
        $meta = [
            'title' => (string)appConfig('seo.title', $siteName),
            'description' => (string)appConfig('seo.description', siteSetting('site_description', appConfig('site.description', ''))),
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
