<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\Media;

class MediaAdminController extends Controller
{
    private const BASE_UPLOAD_DIR = PUBLIC_PATH . '/assets/uploads/media';
    private const BASE_PUBLIC_PREFIX = 'assets/uploads/media';
    private const MAX_IMAGE_SIZE = 20 * 1024 * 1024;
    private const MAX_VIDEO_SIZE = 150 * 1024 * 1024;
    private const MAX_FILE_SIZE = 25 * 1024 * 1024;
    private const IMAGE_MIME_TO_EXT = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/avif' => 'avif',
        'image/gif' => 'gif',
    ];
    private const VIDEO_MIME_TO_EXT = [
        'video/mp4' => 'mp4',
        'video/webm' => 'webm',
        'video/ogg' => 'ogv',
        'video/quicktime' => 'mov',
    ];
    private const FILE_MIME_TO_EXT = [
        'application/pdf' => 'pdf',
        'text/plain' => 'txt',
        'text/csv' => 'csv',
        'application/zip' => 'zip',
        'application/x-zip-compressed' => 'zip',
        'application/x-rar-compressed' => 'rar',
        'application/x-7z-compressed' => '7z',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
        'application/vnd.ms-powerpoint' => 'ppt',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
    ];

    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $this->view('admin.media.index', [
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function list()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = (int)($_GET['per_page'] ?? 24);
        $perPage = max(1, min(60, $perPage));

        $type = strtolower(trim((string)($_GET['type'] ?? '')));
        if (!in_array($type, ['', 'image', 'video', 'file'], true)) {
            $type = '';
        }

        $search = trim((string)($_GET['search'] ?? ''));

        $media = new Media();
        $total = $media->countFiltered($type !== '' ? $type : null, $search !== '' ? $search : null);
        $items = $media->listPaginated($page, $perPage, $type !== '' ? $type : null, $search !== '' ? $search : null);

        $normalized = array_map(fn(array $item): array => $this->normalizeMediaItem($item), $items);
        $totalPages = max(1, (int)ceil($total / $perPage));

        $this->json([
            'items' => $normalized,
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
            ],
        ]);
    }

    public function upload()
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

        $file = $_FILES['file'] ?? null;
        if (!is_array($file)) {
            $this->json(['error' => 'No media file provided'], 422);
            return;
        }

        $saved = $this->saveMediaFile($file);
        if (isset($saved['error'])) {
            $this->json(['error' => $saved['error']], 422);
            return;
        }

        $media = new Media();
        $created = $media->create([
            'file_name' => $saved['file_name'],
            'original_name' => $saved['original_name'],
            'disk_path' => $saved['disk_path'],
            'public_url' => $saved['public_url'],
            'mime_type' => $saved['mime_type'],
            'media_type' => $saved['media_type'],
            'extension' => $saved['extension'],
            'size_bytes' => $saved['size_bytes'],
            'uploaded_by' => (int)($_SESSION['admin_id'] ?? 0) ?: null,
        ]);

        if (!$created) {
            $this->deleteFileIfExists($saved['disk_path']);
            $this->json(['error' => 'Failed to save media metadata'], 500);
            return;
        }

        $id = (int)$this->db->getConnection()->lastInsertId();
        $item = $media->find($id);

        $this->json([
            'success' => true,
            'message' => 'Media uploaded successfully',
            'item' => $item ? $this->normalizeMediaItem($item) : null,
        ]);
    }

    public function delete()
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

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->json(['error' => 'Invalid media ID'], 422);
            return;
        }

        $media = new Media();
        $item = $media->find($id);
        if (!$item) {
            $this->json(['error' => 'Media item not found'], 404);
            return;
        }

        $usage = $this->findMediaUsage((string)($item['public_url'] ?? ''), (string)($item['disk_path'] ?? ''));
        if (!empty($usage)) {
            $this->json([
                'error' => 'Media is currently in use and cannot be deleted',
                'references' => $usage,
            ], 409);
            return;
        }

        $deleted = $media->delete($id);
        if (!$deleted) {
            $this->json(['error' => 'Failed to delete media record'], 500);
            return;
        }

        $this->deleteFileIfExists((string)($item['disk_path'] ?? ''));

        $this->json(['success' => true, 'message' => 'Media deleted successfully']);
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    private function normalizeMediaItem(array $item): array
    {
        $storedPublic = (string)($item['public_url'] ?? '');
        if ($storedPublic === '') {
            $storedPublic = (string)($item['disk_path'] ?? '');
        }

        $normalizedPath = '/' . ltrim($storedPublic, '/');
        $resolvedPublicUrl = \route($normalizedPath);

        return [
            'id' => (int)($item['id'] ?? 0),
            'file_name' => (string)($item['file_name'] ?? ''),
            'original_name' => (string)($item['original_name'] ?? ''),
            'public_url' => $resolvedPublicUrl,
            'storage_path' => ltrim($normalizedPath, '/'),
            'mime_type' => (string)($item['mime_type'] ?? ''),
            'media_type' => (string)($item['media_type'] ?? 'file'),
            'extension' => (string)($item['extension'] ?? ''),
            'size_bytes' => (int)($item['size_bytes'] ?? 0),
            'created_at' => (string)($item['created_at'] ?? ''),
        ];
    }

    private function saveMediaFile(array $file): array
    {
        $errorCode = (int)($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($errorCode !== UPLOAD_ERR_OK) {
            return ['error' => $this->uploadErrorMessage($errorCode)];
        }

        $tmpPath = (string)($file['tmp_name'] ?? '');
        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            return ['error' => 'Invalid upload payload'];
        }

        $size = (int)($file['size'] ?? 0);
        if ($size <= 0) {
            return ['error' => 'Uploaded file is empty'];
        }

        $originalName = trim((string)($file['name'] ?? ''));
        $detectedMime = $this->detectMime($tmpPath);
        if ($detectedMime === '') {
            return ['error' => 'Unable to detect file type'];
        }

        $classification = $this->classifyMime($detectedMime, $originalName);
        if (isset($classification['error'])) {
            return $classification;
        }

        $maxSize = match ($classification['media_type']) {
            'video' => self::MAX_VIDEO_SIZE,
            'image' => self::MAX_IMAGE_SIZE,
            default => self::MAX_FILE_SIZE,
        };
        if ($size > $maxSize) {
            return ['error' => 'File exceeds the allowed upload size for this type'];
        }

        $dateSegment = date('Y/m');
        $targetDir = self::BASE_UPLOAD_DIR . '/' . $classification['media_type'] . '/' . $dateSegment;
        if (!is_dir($targetDir) && !@mkdir($targetDir, 0755, true)) {
            return ['error' => 'Unable to prepare media upload directory'];
        }
        if (!is_writable($targetDir)) {
            return ['error' => 'Media upload directory is not writable'];
        }

        $storedFilename = bin2hex(random_bytes(16)) . '.' . $classification['extension'];
        $fullPath = $targetDir . '/' . $storedFilename;
        if (!move_uploaded_file($tmpPath, $fullPath)) {
            return ['error' => 'Failed to move uploaded file'];
        }

        $diskPath = self::BASE_PUBLIC_PREFIX . '/' . $classification['media_type'] . '/' . $dateSegment . '/' . $storedFilename;

        return [
            'file_name' => $storedFilename,
            'original_name' => $originalName !== '' ? $originalName : $storedFilename,
            'disk_path' => $diskPath,
            'public_url' => '/' . ltrim($diskPath, '/'),
            'mime_type' => $detectedMime,
            'media_type' => $classification['media_type'],
            'extension' => $classification['extension'],
            'size_bytes' => $size,
        ];
    }

    private function classifyMime(string $mimeType, string $originalName): array
    {
        $mime = strtolower(trim($mimeType));
        $extFromName = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));

        if (isset(self::IMAGE_MIME_TO_EXT[$mime])) {
            $allowed = array_values(array_unique(array_map(
                static fn(string $ext): string => strtolower($ext),
                array_values(self::IMAGE_MIME_TO_EXT)
            )));
            return [
                'media_type' => 'image',
                'extension' => in_array($extFromName, $allowed, true) ? $extFromName : self::IMAGE_MIME_TO_EXT[$mime],
            ];
        }

        if (isset(self::VIDEO_MIME_TO_EXT[$mime])) {
            $allowed = array_values(array_unique(array_map(
                static fn(string $ext): string => strtolower($ext),
                array_values(self::VIDEO_MIME_TO_EXT)
            )));
            return [
                'media_type' => 'video',
                'extension' => in_array($extFromName, $allowed, true) ? $extFromName : self::VIDEO_MIME_TO_EXT[$mime],
            ];
        }

        if (isset(self::FILE_MIME_TO_EXT[$mime])) {
            $allowed = array_values(array_unique(array_map(
                static fn(string $ext): string => strtolower($ext),
                array_values(self::FILE_MIME_TO_EXT)
            )));
            return [
                'media_type' => 'file',
                'extension' => in_array($extFromName, $allowed, true) ? $extFromName : self::FILE_MIME_TO_EXT[$mime],
            ];
        }

        return ['error' => 'Unsupported file type'];
    }

    private function detectMime(string $tmpPath): string
    {
        $finfo = function_exists('finfo_open') ? @finfo_open(FILEINFO_MIME_TYPE) : false;
        if ($finfo) {
            $mime = @finfo_file($finfo, $tmpPath);
            @finfo_close($finfo);
            if (is_string($mime) && trim($mime) !== '') {
                return strtolower(trim($mime));
            }
        }

        if (function_exists('mime_content_type')) {
            $mime = @mime_content_type($tmpPath);
            if (is_string($mime) && trim($mime) !== '') {
                return strtolower(trim($mime));
            }
        }

        return '';
    }

    private function uploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Uploaded file exceeds the maximum allowed upload size',
            UPLOAD_ERR_PARTIAL => 'File upload was interrupted',
            UPLOAD_ERR_NO_FILE => 'No file uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Server missing temporary upload directory',
            UPLOAD_ERR_CANT_WRITE => 'Server cannot write uploaded file',
            UPLOAD_ERR_EXTENSION => 'Upload blocked by server extension',
            default => 'Invalid media upload request',
        };
    }

    private function deleteFileIfExists(string $diskPath): void
    {
        $normalized = ltrim(trim($diskPath), '/');
        if ($normalized === '') {
            return;
        }

        $fullPath = PUBLIC_PATH . '/' . $normalized;
        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function findMediaUsage(string $publicUrl, string $diskPath): array
    {
        $normalizedPublic = ltrim($publicUrl, '/');
        $normalizedDisk = ltrim($diskPath, '/');
        $prefixedPublic = '/' . $normalizedPublic;
        $prefixedDisk = '/' . $normalizedDisk;

        $targets = array_values(array_filter(array_unique([
            $normalizedPublic,
            $normalizedDisk,
            $prefixedPublic,
            $prefixedDisk,
        ])));

        if (empty($targets)) {
            return [];
        }

        $checks = [
            ['table' => 'gallery_items', 'column' => 'image', 'label' => 'Gallery'],
            ['table' => 'package_categories', 'column' => 'image', 'label' => 'Package Categories'],
            ['table' => 'services', 'column' => 'image', 'label' => 'Services'],
            ['table' => 'packages', 'column' => 'image', 'label' => 'Packages'],
            ['table' => 'team_members', 'column' => 'image', 'label' => 'Team'],
            ['table' => 'testimonials', 'column' => 'image', 'label' => 'Testimonials'],
            ['table' => 'site_settings', 'column' => 'setting_value', 'label' => 'Site Settings'],
        ];

        $usage = [];
        foreach ($checks as $check) {
            try {
                $columnExists = $this->db->getConnection()->query("SHOW COLUMNS FROM `{$check['table']}` LIKE '{$check['column']}'")->fetch();
                if (!$columnExists) {
                    continue;
                }

                $where = [];
                $params = [];
                foreach ($targets as $index => $value) {
                    $key = ':v' . $index;
                    $where[] = "{$check['column']} = {$key}";
                    $params[$key] = $value;
                }

                $sql = "SELECT COUNT(*) AS count FROM `{$check['table']}` WHERE " . implode(' OR ', $where);
                $stmt = $this->db->getConnection()->prepare($sql);
                $stmt->execute($params);
                $count = (int)($stmt->fetch()['count'] ?? 0);

                if ($count > 0) {
                    $usage[] = [
                        'table' => $check['table'],
                        'label' => $check['label'],
                        'count' => $count,
                    ];
                }
            } catch (\Throwable $e) {
                // Ignore check failures to keep delete flow safe and resilient.
                continue;
            }
        }

        return $usage;
    }
}
