<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\ImageProcessor;
use App\Models\SiteSetting;

class SiteSettingsAdminController extends Controller
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $settingsModel = new SiteSetting();

        $this->view('admin.settings.index', [
            'settings' => array_merge($settingsModel->defaults(), $settingsModel->allAsKeyValue()),
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function update()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate((string)$_POST['_csrf_token'])) {
            CSRF::regenerate();
            $this->json(['error' => 'CSRF token invalid', 'csrf_token' => CSRF::getToken()], 403);
            return;
        }

        $settingsModel = new SiteSetting();
        $currentSettings = array_merge($settingsModel->defaults(), $settingsModel->allAsKeyValue());

        $payload = [
            'site_name' => trim((string)($_POST['site_name'] ?? '')),
            'site_tagline' => trim((string)($_POST['site_tagline'] ?? '')),
            'site_description' => trim((string)($_POST['site_description'] ?? '')),
            'site_email' => trim((string)($_POST['site_email'] ?? '')),
            'site_phone' => trim((string)($_POST['site_phone'] ?? '')),
            'site_address' => trim((string)($_POST['site_address'] ?? '')),
            'site_registration_code' => trim((string)($_POST['site_registration_code'] ?? '')),
            'theme_primary_color' => trim((string)($_POST['theme_primary_color'] ?? '')),
            'theme_accent_color' => trim((string)($_POST['theme_accent_color'] ?? '')),
            'theme_light_color' => trim((string)($_POST['theme_light_color'] ?? '')),
            'theme_dark_color' => trim((string)($_POST['theme_dark_color'] ?? '')),
            'theme_body_font' => trim((string)($_POST['theme_body_font'] ?? '')),
            'theme_heading_font' => trim((string)($_POST['theme_heading_font'] ?? '')),
            'theme_display_font' => trim((string)($_POST['theme_display_font'] ?? '')),
            'theme_ui_font' => trim((string)($_POST['theme_ui_font'] ?? '')),
            'theme_body_size' => trim((string)($_POST['theme_body_size'] ?? '')),
            'theme_h1_size' => trim((string)($_POST['theme_h1_size'] ?? '')),
            'theme_h2_size' => trim((string)($_POST['theme_h2_size'] ?? '')),
            'inner_hero_render_mode' => trim((string)($_POST['inner_hero_render_mode'] ?? '')),
            'inner_hero_overlay_opacity' => trim((string)($_POST['inner_hero_overlay_opacity'] ?? '')),
            'inner_hero_overlay_start' => trim((string)($_POST['inner_hero_overlay_start'] ?? '')),
            'inner_hero_overlay_end' => trim((string)($_POST['inner_hero_overlay_end'] ?? '')),
            'social_instagram' => trim((string)($_POST['social_instagram'] ?? '')),
            'social_facebook' => trim((string)($_POST['social_facebook'] ?? '')),
            'social_tiktok' => trim((string)($_POST['social_tiktok'] ?? '')),
            'social_whatsapp' => trim((string)($_POST['social_whatsapp'] ?? '')),
        ];

        $errors = [];

        if ($payload['site_name'] === '') {
            $errors['site_name'] = 'Site name is required.';
        } elseif (mb_strlen($payload['site_name']) > 150) {
            $errors['site_name'] = 'Site name must not exceed 150 characters.';
        }

        if ($payload['site_email'] === '') {
            $errors['site_email'] = 'Contact email is required.';
        } elseif (!filter_var($payload['site_email'], FILTER_VALIDATE_EMAIL)) {
            $errors['site_email'] = 'Enter a valid contact email address.';
        }

        if ($payload['site_phone'] === '') {
            $errors['site_phone'] = 'Phone number is required.';
        } elseif (mb_strlen($payload['site_phone']) > 60) {
            $errors['site_phone'] = 'Phone number must not exceed 60 characters.';
        }

        if ($payload['site_address'] === '') {
            $errors['site_address'] = 'Office address is required.';
        }

        $fontOptions = array_keys(themeFontOptions());
        foreach ([
            'theme_body_font' => 'Body font',
            'theme_heading_font' => 'Heading font',
            'theme_display_font' => 'Display font',
            'theme_ui_font' => 'UI font',
        ] as $field => $label) {
            if (!in_array($payload[$field], $fontOptions, true)) {
                $errors[$field] = $label . ' selection is invalid.';
            }
        }

        foreach ([
            'theme_primary_color' => 'Primary color',
            'theme_accent_color' => 'Accent color',
            'theme_light_color' => 'Light color',
            'theme_dark_color' => 'Dark color',
            'inner_hero_overlay_start' => 'Hero overlay start color',
            'inner_hero_overlay_end' => 'Hero overlay end color',
        ] as $field => $label) {
            if (!$this->isValidHexColor($payload[$field])) {
                $errors[$field] = $label . ' must be a valid hex color.';
            }
        }

        foreach ([
            'theme_body_size' => 'Body size',
            'theme_h1_size' => 'H1 size',
            'theme_h2_size' => 'H2 size',
        ] as $field => $label) {
            if (!$this->isValidCssSize($payload[$field])) {
                $errors[$field] = $label . ' must use px, rem, em, or %.';
            }
        }

        if (!in_array($payload['inner_hero_render_mode'], ['gradient_only', 'image_overlay'], true)) {
            $errors['inner_hero_render_mode'] = 'Inner hero render mode is invalid.';
        }

        if (!preg_match('/^\d{1,3}$/', $payload['inner_hero_overlay_opacity']) || (int)$payload['inner_hero_overlay_opacity'] < 0 || (int)$payload['inner_hero_overlay_opacity'] > 100) {
            $errors['inner_hero_overlay_opacity'] = 'Overlay opacity must be between 0 and 100.';
        }

        foreach ([
            'social_instagram' => 'Instagram URL',
            'social_facebook' => 'Facebook URL',
            'social_tiktok' => 'TikTok URL',
            'social_whatsapp' => 'WhatsApp URL',
        ] as $field => $label) {
            $value = $payload[$field];
            if ($value !== '' && !$this->isValidImageOrLinkUrl($value, false)) {
                $errors[$field] = $label . ' must be a valid http://, https://, or site path URL.';
            }
        }

        $imageFields = [
            'site_logo' => ['url' => 'site_logo_url', 'file' => 'site_logo_file', 'label' => 'Site logo'],
            'site_favicon' => ['url' => 'site_favicon_url', 'file' => 'site_favicon_file', 'label' => 'Favicon'],
            'site_og_image' => ['url' => 'site_og_image_url', 'file' => 'site_og_image_file', 'label' => 'Open Graph image'],
            'inner_hero_background_image' => ['url' => 'inner_hero_background_image_url', 'file' => 'inner_hero_background_image_file', 'label' => 'Inner hero background image'],
        ];

        foreach ($imageFields as $settingKey => $config) {
            $url = trim((string)($_POST[$config['url']] ?? ''));
            $file = $_FILES[$config['file']] ?? null;
            $uploadErrorCode = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
            $hasUploadedFile = is_array($file) && $uploadErrorCode === UPLOAD_ERR_OK;

            if (is_array($file) && $uploadErrorCode !== UPLOAD_ERR_NO_FILE && $uploadErrorCode !== UPLOAD_ERR_OK) {
                $errors[$settingKey] = $this->uploadErrorMessage($uploadErrorCode);
                continue;
            }

            if ($url !== '' && $hasUploadedFile) {
                $errors[$settingKey] = 'Provide either a media URL or an uploaded file for ' . strtolower($config['label']) . ', not both.';
                continue;
            }

            if ($url !== '' && !$this->isValidImageOrLinkUrl($url, true)) {
                $errors[$settingKey] = $config['label'] . ' must be a valid image URL or site path.';
                continue;
            }

            if ($url !== '') {
                $payload[$settingKey] = $url;
                continue;
            }

            if ($hasUploadedFile) {
                $stored = ImageProcessor::process($file);
                if (!$stored) {
                    $errors[$settingKey] = ImageProcessor::getLastError() ?? ('Unable to upload ' . strtolower($config['label']) . '.');
                    continue;
                }

                $payload[$settingKey] = $stored;
                continue;
            }

            $payload[$settingKey] = (string)($currentSettings[$settingKey] ?? '');
        }

        if ($errors !== []) {
            $this->json(['errors' => $errors], 422);
            return;
        }

        if (!$settingsModel->setMany($payload)) {
            $this->json(['error' => 'Failed to save site settings.'], 500);
            return;
        }

        if (function_exists('siteSettings')) {
            siteSettings(true);
        }

        $cacheResult = function_exists('clearSiteWideCache')
            ? clearSiteWideCache()
            : ['deleted' => 0, 'failed' => 0, 'opcache_reset' => false];

        $this->json([
            'success' => true,
            'message' => 'Global site settings updated successfully.',
            'cache' => $cacheResult,
        ]);
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    private function isValidImageOrLinkUrl(string $url, bool $imageOnly = true): bool
    {
        if ($url === '' || mb_strlen($url) > 255) {
            return false;
        }

        if (str_starts_with($url, '/')) {
            $path = strtolower((string)parse_url($url, PHP_URL_PATH));
            return !$imageOnly || preg_match('/\.(jpg|jpeg|png|webp|avif|gif|svg|ico)$/', $path) === 1;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
        if (!in_array($scheme, ['http', 'https'], true)) {
            return false;
        }

        if (!$imageOnly) {
            return true;
        }

        $path = strtolower((string)parse_url($url, PHP_URL_PATH));
        return preg_match('/\.(jpg|jpeg|png|webp|avif|gif|svg|ico)$/', $path) === 1;
    }

    private function uploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Image exceeds the maximum allowed upload size.',
            UPLOAD_ERR_PARTIAL => 'Image upload was interrupted. Please try again.',
            UPLOAD_ERR_NO_TMP_DIR => 'Server missing temporary upload directory.',
            UPLOAD_ERR_CANT_WRITE => 'Server cannot write uploaded image.',
            UPLOAD_ERR_EXTENSION => 'Image upload blocked by server extension.',
            default => 'Image upload failed. Please try another file.',
        };
    }

    private function isValidHexColor(string $value): bool
    {
        return preg_match('/^#([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/', $value) === 1;
    }

    private function isValidCssSize(string $value): bool
    {
        return preg_match('/^\d+(\.\d+)?(px|rem|em|%)$/', strtolower($value)) === 1;
    }
}
