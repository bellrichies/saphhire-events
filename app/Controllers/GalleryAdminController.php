<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\Gallery;
use App\Models\Category;
use Throwable;

class GalleryAdminController extends Controller
{
    private const ALLOWED_IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
    private const ALLOWED_VIDEO_MIMES = ['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'];
    private const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
    private const ALLOWED_VIDEO_EXTENSIONS = ['mp4', 'webm', 'ogv', 'ogg', 'mov'];
    private const MAX_IMAGE_SIZE = 20971520; // 20MB
    private const MAX_VIDEO_SIZE = 52428800; // 50MB
    private const IMAGE_UPLOAD_DIR = PUBLIC_PATH . '/assets/images/';
    private const VIDEO_UPLOAD_DIR = PUBLIC_PATH . '/assets/videos/';
    private const STORED_IMAGE_PREFIX = 'assets/images/';
    private const STORED_VIDEO_PREFIX = 'assets/videos/';

    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $gallery = new Gallery();
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = (int)($_GET['per_page'] ?? 10);
        if ($perPage < 1) {
            $perPage = 10;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $totalItems = $gallery->count();
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $items = $gallery->getAllWithCategoryPaginated($page, $perPage);

        $this->view('admin.gallery.index', [
            'items' => $items,
            'page' => $page,
            'perPage' => $perPage,
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $category = new Category();
        $categories = $category->all();

        $this->view('admin.gallery.create', [
            'categories' => $categories,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function edit()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $gallery = new Gallery();
        $item = $gallery->find($id);

        if (!$item) {
            $this->redirect(route('/admin/gallery'));
        }

        $category = new Category();
        $categories = $category->all();

        $this->view('admin.gallery.edit', [
            'item' => $item,
            'categories' => $categories,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function store()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate($_POST['_csrf_token'])) {
            CSRF::regenerate();
            $this->json([
                'error' => 'CSRF token invalid',
                'csrf_token' => CSRF::getToken(),
            ], 403);
            return;
        }

        $rules = [
            'title' => 'required|max:255',
            'category_id' => 'required',
            'description' => 'required',
        ];

        $data = [
            'title' => $_POST['title'] ?? '',
            'category_id' => $_POST['category_id'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];

        $errors = $this->validate($data, $rules);
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
            return;
        }

        $mediaUrl = trim((string)($_POST['media_url'] ?? $_POST['image_url'] ?? ''));
        $mediaFile = $_FILES['media'] ?? ($_FILES['image'] ?? null);
        $mediaName = null;
        $hasUploadedFile = $mediaFile && ($mediaFile['error'] ?? UPLOAD_ERR_NO_FILE) === 0;
        $uploadErrorCode = $mediaFile['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($mediaFile && $uploadErrorCode !== UPLOAD_ERR_NO_FILE && $uploadErrorCode !== 0) {
            $this->json(['error' => 'Media upload failed. Please try another file'], 422);
            return;
        }

        if ($mediaUrl !== '' && $hasUploadedFile) {
            $this->json(['error' => 'Provide either a media URL or an uploaded file, not both'], 422);
            return;
        }

        if ($mediaUrl !== '') {
            if (!$this->isValidMediaUrl($mediaUrl)) {
                $this->json(['error' => 'Invalid media URL. Supported formats: JPG, PNG, WEBP, AVIF, MP4, WEBM, OGV, MOV'], 422);
                return;
            }
            $mediaName = $mediaUrl;
        } elseif ($hasUploadedFile) {
            $mediaName = $this->saveMedia($mediaFile);
            if (!$mediaName) {
                $this->json(['error' => 'Failed to upload media'], 422);
                return;
            }
        }

        try {
            $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
            $gallery = new Gallery();
            $result = $gallery->create([
                'title' => $data['title'],
                'category_id' => (int)$data['category_id'],
                'description' => $data['description'],
                'image' => $mediaName ?? 'placeholder.jpg',
                'is_featured' => $isFeatured,
            ]);

            if ($result) {
                $this->json(['success' => true, 'message' => 'Gallery item created successfully']);
                return;
            }

            $this->json(['error' => 'Failed to create gallery item'], 500);
        } catch (Throwable $e) {
            $this->reportServerError('Failed to create gallery item', $e, 'gallery.store');
        }
    }

    public function update()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate($_POST['_csrf_token'])) {
            CSRF::regenerate();
            $this->json([
                'error' => 'CSRF token invalid',
                'csrf_token' => CSRF::getToken(),
            ], 403);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->json(['error' => 'Invalid ID'], 422);
            return;
        }

        $gallery = new Gallery();
        $existing = $gallery->find($id);
        if (!$existing) {
            $this->json(['error' => 'Gallery item not found'], 404);
            return;
        }

        $rules = [
            'title' => 'required|max:255',
            'category_id' => 'required',
            'description' => 'required',
        ];

        $data = [
            'title' => $_POST['title'] ?? '',
            'category_id' => $_POST['category_id'] ?? '',
            'description' => $_POST['description'] ?? '',
        ];

        $errors = $this->validate($data, $rules);
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
            return;
        }

        $mediaName = $existing['image'] ?? null;
        $mediaUrl = trim((string)($_POST['media_url'] ?? $_POST['image_url'] ?? ''));
        $removeMedia = isset($_POST['remove_media']) || isset($_POST['remove_image']);
        $mediaFile = $_FILES['media'] ?? ($_FILES['image'] ?? null);
        $hasUploadedFile = $mediaFile && ($mediaFile['error'] ?? UPLOAD_ERR_NO_FILE) === 0;
        $uploadErrorCode = $mediaFile['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($mediaFile && $uploadErrorCode !== UPLOAD_ERR_NO_FILE && $uploadErrorCode !== 0) {
            $this->json(['error' => 'Media upload failed. Please try another file'], 422);
            return;
        }

        if ($mediaUrl !== '' && $hasUploadedFile) {
            $this->json(['error' => 'Provide either a media URL or an uploaded file, not both'], 422);
            return;
        }

        if ($removeMedia && ($mediaUrl !== '' || $hasUploadedFile)) {
            $this->json(['error' => 'Choose only one media action: remove, URL, or file upload'], 422);
            return;
        }

        if ($removeMedia) {
            $mediaName = null;
        } elseif ($mediaUrl !== '') {
            if (!$this->isValidMediaUrl($mediaUrl)) {
                $this->json(['error' => 'Invalid media URL. Supported formats: JPG, PNG, WEBP, AVIF, MP4, WEBM, OGV, MOV'], 422);
                return;
            }
            $mediaName = $mediaUrl;
        } elseif ($hasUploadedFile) {
            $newMedia = $this->saveMedia($mediaFile);
            if (!$newMedia) {
                $this->json(['error' => 'Failed to upload media'], 422);
                return;
            }
            $mediaName = $newMedia;
        }

        try {
            $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
            $updated = $gallery->update($id, [
                'title' => $data['title'],
                'category_id' => (int)$data['category_id'],
                'description' => $data['description'],
                'image' => $mediaName,
                'is_featured' => $isFeatured,
            ]);

            if ($updated) {
                $this->cleanupLocalMediaIfReplaced($existing['image'] ?? null, $mediaName);
                $this->json(['success' => true, 'message' => 'Gallery item updated successfully']);
                return;
            }

            $this->json(['error' => 'Failed to update gallery item'], 500);
        } catch (Throwable $e) {
            $this->reportServerError('Failed to update gallery item', $e, 'gallery.update');
        }
    }

    public function delete()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->json(['error' => 'Invalid ID'], 422);
            return;
        }

        $gallery = new Gallery();
        if ($gallery->delete($id)) {
            $this->json(['success' => true, 'message' => 'Gallery item deleted successfully']);
        } else {
            $this->json(['error' => 'Failed to delete gallery item'], 500);
        }
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    private function saveMedia(array $file): ?string
    {
        $size = (int)($file['size'] ?? 0);
        $name = (string)($file['name'] ?? '');
        $tmpName = (string)($file['tmp_name'] ?? '');

        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return null;
        }

        $detectedMime = $this->detectMime($tmpName);
        $normalizedMime = $this->normalizeMime($detectedMime ?? (string)($file['type'] ?? ''));
        $extension = strtolower((string)pathinfo($name, PATHINFO_EXTENSION));

        $isVideo = in_array($normalizedMime, self::ALLOWED_VIDEO_MIMES, true) || in_array($extension, self::ALLOWED_VIDEO_EXTENSIONS, true);
        $isImage = in_array($normalizedMime, self::ALLOWED_IMAGE_MIMES, true) || in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS, true);

        if (!$isVideo && !$isImage) {
            return null;
        }

        $maxSize = $isVideo ? self::MAX_VIDEO_SIZE : self::MAX_IMAGE_SIZE;
        if ($size <= 0 || $size > $maxSize) {
            return null;
        }

        if ($extension === '') {
            $extension = $isVideo ? 'mp4' : 'jpg';
        }

        if ($isVideo && !in_array($extension, self::ALLOWED_VIDEO_EXTENSIONS, true)) {
            $extension = 'mp4';
        }

        if ($isImage && !in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS, true)) {
            $extension = 'jpg';
        }

        $targetDir = $isVideo ? self::VIDEO_UPLOAD_DIR : self::IMAGE_UPLOAD_DIR;
        $targetPrefix = $isVideo ? self::STORED_VIDEO_PREFIX : self::STORED_IMAGE_PREFIX;

        if (!is_dir($targetDir) && !@mkdir($targetDir, 0755, true)) {
            return null;
        }

        if (!is_writable($targetDir)) {
            return null;
        }

        $filename = 'gallery_' . uniqid() . '_' . time() . '.' . $extension;
        $targetPath = $targetDir . $filename;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            return null;
        }

        return $targetPrefix . $filename;
    }

    private function isValidMediaUrl(string $url): bool
    {
        if ($url === '' || strlen($url) > 255) {
            return false;
        }

        if (str_starts_with($url, '/')) {
            if (preg_match('#^/[^\s]+$#', $url) !== 1) {
                return false;
            }
            return $this->isSupportedMediaExtension((string)parse_url($url, PHP_URL_PATH));
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
        if (!in_array($scheme, ['http', 'https'], true)) {
            return false;
        }

        return $this->isSupportedMediaExtension((string)parse_url($url, PHP_URL_PATH));
    }

    private function cleanupLocalMediaIfReplaced(?string $oldMedia, ?string $newMedia): void
    {
        if (!$this->isLocalUploadReference($oldMedia)) {
            return;
        }

        if ($oldMedia === $newMedia) {
            return;
        }

        \App\Core\ImageProcessor::delete($oldMedia);
    }

    private function isLocalUploadReference(?string $image): bool
    {
        if (!$image) {
            return false;
        }

        if (preg_match('/^https?:\/\//', $image) || str_starts_with($image, '/')) {
            return false;
        }

        return true;
    }

    private function isSupportedMediaExtension(string $path): bool
    {
        $ext = strtolower((string)pathinfo($path, PATHINFO_EXTENSION));
        if ($ext === '') {
            return false;
        }

        return in_array($ext, self::ALLOWED_IMAGE_EXTENSIONS, true)
            || in_array($ext, self::ALLOWED_VIDEO_EXTENSIONS, true);
    }

    private function detectMime(string $tmpName): ?string
    {
        if (!is_file($tmpName)) {
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

    private function normalizeMime(string $mime): string
    {
        $mime = strtolower(trim($mime));
        return match ($mime) {
            'image/jpg', 'image/pjpeg' => 'image/jpeg',
            'video/x-matroska' => 'video/webm',
            default => $mime,
        };
    }

    private function reportServerError(string $message, Throwable $e, string $context): void
    {
        $errorId = uniqid('err_', true);
        error_log(sprintf(
            '[%s] %s | %s: %s in %s:%d',
            $errorId,
            $context,
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine()
        ));

        $response = [
            'error' => $message,
            'error_id' => $errorId,
        ];

        if (!empty($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] !== 'false' && $_ENV['APP_DEBUG'] !== '0') {
            $response['details'] = $e->getMessage();
        }

        $this->json($response, 500);
    }
}
