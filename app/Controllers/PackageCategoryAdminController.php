<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\ImageProcessor;
use App\Models\PackageCategory;

class PackageCategoryAdminController extends Controller
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $packageCategory = new PackageCategory();
        $categories = $packageCategory->getWithPackageCount();

        $this->view('admin.package-categories.index', [
            'categories' => $categories,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $this->view('admin.package-categories.create', [
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function edit()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $packageCategory = new PackageCategory();
        $category = $packageCategory->findRaw($id);

        if (!$category) {
            $this->redirect(route('/admin/package-categories'));
        }

        $this->view('admin.package-categories.edit', [
            'category' => $category,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function store()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized']);
            return;
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate($_POST['_csrf_token'])) {
            http_response_code(403);
            $this->json(['error' => 'CSRF token invalid']);
            return;
        }

        $payload = $this->buildPayload($_POST, $_FILES);
        if (isset($payload['error'])) {
            http_response_code(422);
            $this->json(['error' => $payload['error']]);
            return;
        }

        $packageCategory = new PackageCategory();
        $created = $packageCategory->create($payload);

        if ($created) {
            $this->json(['success' => true, 'message' => 'Package category created successfully']);
            return;
        }

        http_response_code(500);
        $this->json(['error' => 'Failed to create package category']);
    }

    public function update()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized']);
            return;
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate($_POST['_csrf_token'])) {
            http_response_code(403);
            $this->json(['error' => 'CSRF token invalid']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(422);
            $this->json(['error' => 'Invalid payload']);
            return;
        }

        $packageCategory = new PackageCategory();
        $current = $packageCategory->findRaw($id);
        if (!$current) {
            http_response_code(404);
            $this->json(['error' => 'Package category not found']);
            return;
        }

        $payload = $this->buildPayload($_POST, $_FILES, $current['image'] ?? null, true, $id);
        if (isset($payload['error'])) {
            http_response_code(422);
            $this->json(['error' => $payload['error']]);
            return;
        }

        $updated = $packageCategory->update($id, $payload);

        if ($updated) {
            $this->cleanupLocalImageIfReplaced($current['image'] ?? null, $payload['image'] ?? null);
            $this->json(['success' => true, 'message' => 'Package category updated successfully']);
            return;
        }

        http_response_code(500);
        $this->json(['error' => 'Failed to update package category']);
    }

    public function delete()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized']);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(422);
            $this->json(['error' => 'Invalid ID']);
            return;
        }

        $packageCategory = new PackageCategory();
        $current = $packageCategory->findRaw($id);
        if ($packageCategory->delete($id)) {
            $this->cleanupLocalImageIfReplaced($current['image'] ?? null, null);
            $this->json(['success' => true, 'message' => 'Category deleted successfully']);
            return;
        }

        http_response_code(500);
        $this->json(['error' => 'Failed to delete category']);
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    private function buildPayload(
        array $post,
        array $files,
        ?string $currentImage = null,
        bool $allowRemoveImage = false,
        int $currentId = 0
    ): array {
        $name = $this->sanitize($post['name'] ?? '');
        $description = $this->sanitize($post['description'] ?? '');
        $slugInput = strtolower($this->sanitize($post['slug'] ?? ''));
        $slug = $slugInput !== '' ? $slugInput : strtolower(trim((string)preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $displayOrder = (int)($post['display_order'] ?? 0);

        if ($name === '') {
            return ['error' => 'Category name is required'];
        }

        if ($slug === '') {
            return ['error' => 'Category slug could not be generated'];
        }

        if (!$this->isValidSlug($slug)) {
            return ['error' => 'Slug can only contain letters, numbers, and hyphens'];
        }

        $existing = (new PackageCategory())->findBySlugRaw($slug);
        if ($existing && (int)($existing['id'] ?? 0) !== $currentId) {
            return ['error' => 'Slug is already in use'];
        }

        $image = $currentImage;
        $imageUrl = trim((string)($post['image_url'] ?? ''));
        $removeImage = $allowRemoveImage && isset($post['remove_image']);
        $imageFile = $files['image'] ?? null;
        $uploadErrorCode = (int)($imageFile['error'] ?? UPLOAD_ERR_NO_FILE);
        $hasUploadedFile = is_array($imageFile) && $uploadErrorCode === UPLOAD_ERR_OK;

        if (is_array($imageFile) && $uploadErrorCode !== UPLOAD_ERR_NO_FILE && $uploadErrorCode !== UPLOAD_ERR_OK) {
            return ['error' => 'Image upload failed. Please try another file'];
        }

        if ($removeImage && ($imageUrl !== '' || $hasUploadedFile)) {
            return ['error' => 'Choose only one image action: remove, media URL, or file upload'];
        }

        if ($imageUrl !== '' && $hasUploadedFile) {
            return ['error' => 'Provide either a media library image URL or an uploaded file, not both'];
        }

        if ($removeImage) {
            $image = null;
        } elseif ($imageUrl !== '') {
            if (!$this->isValidImageUrl($imageUrl)) {
                return ['error' => 'Invalid image URL. Use http://, https://, or a site path starting with /'];
            }
            $image = $imageUrl;
        } elseif ($hasUploadedFile) {
            $saved = ImageProcessor::process($imageFile);
            if (!$saved) {
                return ['error' => ImageProcessor::getLastError() ?? 'Failed to upload image'];
            }
            $image = $saved;
        }

        return $this->appendLocalizedFields([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'image' => $image,
            'display_order' => $displayOrder,
        ], $post, ['name', 'description']);
    }

    private function isValidSlug(string $slug): bool
    {
        return preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) === 1;
    }

    private function isValidImageUrl(string $url): bool
    {
        if ($url === '' || strlen($url) > 255) {
            return false;
        }

        if (str_starts_with($url, '/')) {
            return preg_match('#^/[^\s]+$#', $url) === 1;
        }

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = strtolower((string)parse_url($url, PHP_URL_SCHEME));
        return in_array($scheme, ['http', 'https'], true);
    }

    private function cleanupLocalImageIfReplaced(?string $oldImage, ?string $newImage): void
    {
        if (!$this->isLocalUploadReference($oldImage) || $oldImage === $newImage) {
            return;
        }

        ImageProcessor::delete($oldImage);
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
}
