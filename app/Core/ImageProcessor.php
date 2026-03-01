<?php

namespace App\Core;

class ImageProcessor
{
    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
    private const MAX_SIZE = 20971520; // 20MB
    private const UPLOAD_DIR = PUBLIC_PATH . '/assets/images/';
    private const LEGACY_UPLOAD_DIR = UPLOADS_PATH . '/gallery/';
    private const STORED_PREFIX = 'assets/images/';
    private static ?string $lastError = null;

    public static function process(array $file): ?string
    {
        self::$lastError = null;

        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            self::$lastError = self::uploadErrorMessage((int)($file['error'] ?? UPLOAD_ERR_NO_FILE));
            return null;
        }

        $size = (int)($file['size'] ?? 0);
        if (!self::validateSize($size)) {
            self::$lastError = 'Image exceeds max upload size of 20MB';
            return null;
        }

        $detectedMime = self::detectMime($file);
        if (!self::validateMime($detectedMime, (string)($file['type'] ?? ''), (string)($file['name'] ?? ''))) {
            self::$lastError = 'Unsupported image format. Allowed: JPEG, PNG, WEBP, AVIF';
            return null;
        }

        $filename = self::generateFilename((string)($file['name'] ?? ''), $detectedMime);
        $filepath = self::UPLOAD_DIR . $filename;

        if (!self::ensureUploadDirectory()) {
            self::$lastError = 'Upload directory is not writable';
            return null;
        }

        $tmpName = (string)($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            self::$lastError = 'Invalid upload payload';
            return null;
        }

        if (move_uploaded_file($tmpName, $filepath)) {
            return self::STORED_PREFIX . $filename;
        }

        self::$lastError = 'Unable to move uploaded image to destination';
        return null;
    }

    public static function getLastError(): ?string
    {
        return self::$lastError;
    }

    private static function detectMime(array $file): ?string
    {
        $tmpName = (string)($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_file($tmpName)) {
            return null;
        }

        $finfo = function_exists('finfo_open') ? @finfo_open(FILEINFO_MIME_TYPE) : false;
        if ($finfo) {
            $mime = @finfo_file($finfo, $tmpName);
            @finfo_close($finfo);
            if (is_string($mime) && $mime !== '') {
                return strtolower(trim($mime));
            }
        }

        if (function_exists('mime_content_type')) {
            $mime = @mime_content_type($tmpName);
            if (is_string($mime) && $mime !== '') {
                return strtolower(trim($mime));
            }
        }

        return null;
    }

    private static function normalizeMime(string $mime): string
    {
        $mime = strtolower(trim($mime));
        if ($mime === 'image/jpg' || $mime === 'image/pjpeg') {
            return 'image/jpeg';
        }

        return $mime;
    }

    private static function validateMime(?string $detectedMime, string $reportedMime, string $originalName): bool
    {
        $detectedMime = $detectedMime ? self::normalizeMime($detectedMime) : '';
        $reportedMime = $reportedMime !== '' ? self::normalizeMime($reportedMime) : '';

        if ($detectedMime !== '' && in_array($detectedMime, self::ALLOWED_MIMES, true)) {
            return true;
        }

        if ($reportedMime !== '' && in_array($reportedMime, self::ALLOWED_MIMES, true)) {
            return true;
        }

        $ext = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'avif'], true);
    }

    private static function validateSize(int $size): bool
    {
        return $size <= self::MAX_SIZE;
    }

    private static function generateFilename(string $originalName, ?string $detectedMime = null): string
    {
        $ext = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));
        if ($ext === '') {
            $ext = match (self::normalizeMime((string)$detectedMime)) {
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/avif' => 'avif',
                default => 'jpg',
            };
        }

        return 'gallery_' . uniqid() . '_' . time() . '.' . $ext;
    }

    private static function ensureUploadDirectory(): bool
    {
        if (!is_dir(self::UPLOAD_DIR) && !@mkdir(self::UPLOAD_DIR, 0755, true)) {
            return false;
        }

        return is_writable(self::UPLOAD_DIR);
    }

    private static function uploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Image exceeds the maximum allowed upload size',
            UPLOAD_ERR_PARTIAL => 'Image upload was interrupted. Please try again',
            UPLOAD_ERR_NO_FILE => 'No image file was provided',
            UPLOAD_ERR_NO_TMP_DIR => 'Server missing temporary upload directory',
            UPLOAD_ERR_CANT_WRITE => 'Server cannot write uploaded image',
            UPLOAD_ERR_EXTENSION => 'Image upload blocked by server extension',
            default => 'Invalid image upload request',
        };
    }

    public static function delete(string $imagePath): bool
    {
        $normalized = ltrim(trim($imagePath), '/');
        $legacyFilename = basename($normalized);
        $candidates = [];

        if ($normalized !== '') {
            if (str_starts_with($normalized, self::STORED_PREFIX)) {
                $candidates[] = PUBLIC_PATH . '/' . $normalized;
            }

            if (!preg_match('/^https?:\/\//', $normalized)) {
                $candidates[] = self::UPLOAD_DIR . basename($normalized);
                $candidates[] = self::LEGACY_UPLOAD_DIR . $legacyFilename;
            }
        }

        foreach (array_unique($candidates) as $filepath) {
            if (file_exists($filepath) && !@unlink($filepath)) {
                return false;
            }
        }

        return true;
    }

    public static function getUrl(string $imagePath): string
    {
        return uploadedImageUrl($imagePath);
    }
}
