<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\Testimonial;

class TestimonialAdminController extends Controller
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $testimonial = new Testimonial();
        $testimonials = $testimonial->all();

        $this->view('admin.testimonials.index', [
            'testimonials' => $testimonials,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $this->view('admin.testimonials.create', [
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function edit()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $testimonial = new Testimonial();
        $item = $testimonial->find($id);

        if (!$item) {
            $this->redirect(route('/admin/testimonials'));
        }

        $this->view('admin.testimonials.edit', [
            'testimonial' => $item,
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
            CSRF::regenerate();
            $this->json([
                'error' => 'CSRF token invalid',
                'csrf_token' => CSRF::getToken(),
            ], 403);
            return;
        }

        $data = $this->buildPayload($_POST);
        $errors = $this->validate($data, $this->rules());
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
            return;
        }

        $imageResult = $this->resolveImageFromRequest($_POST, $_FILES);
        if (isset($imageResult['error'])) {
            $this->json(['error' => $imageResult['error']], 422);
            return;
        }
        $data['image'] = $imageResult['image'] ?? null;

        $testimonial = new Testimonial();
        if ($testimonial->create($data)) {
            $this->json(['success' => true, 'message' => 'Testimonial created successfully']);
        } else {
            $this->json(['error' => 'Failed to create testimonial'], 500);
        }
    }

    public function update()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized']);
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

        $testimonial = new Testimonial();
        $existing = $testimonial->find($id);
        if (!$existing) {
            $this->json(['error' => 'Testimonial not found'], 404);
            return;
        }

        $data = $this->buildPayload($_POST);
        $errors = $this->validate($data, $this->rules());
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
            return;
        }

        $imageResult = $this->resolveImageFromRequest($_POST, $_FILES, $existing['image'] ?? null, true);
        if (isset($imageResult['error'])) {
            $this->json(['error' => $imageResult['error']], 422);
            return;
        }
        $data['image'] = $imageResult['image'] ?? null;

        if ($testimonial->update($id, $data)) {
            $this->cleanupLocalImageIfReplaced($existing['image'] ?? null, $data['image'] ?? null);
            $this->json(['success' => true, 'message' => 'Testimonial updated successfully']);
        } else {
            $this->json(['error' => 'Failed to update testimonial'], 500);
        }
    }

    public function delete()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized']);
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

        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $this->json(['error' => 'Invalid ID'], 422);
            return;
        }

        $testimonial = new Testimonial();
        $existing = $testimonial->find((int)$id);
        if ($testimonial->delete((int)$id)) {
            if (is_array($existing)) {
                $this->cleanupLocalImageIfReplaced($existing['image'] ?? null, null);
            }
            $this->json(['success' => true, 'message' => 'Testimonial deleted successfully']);
        } else {
            $this->json(['error' => 'Failed to delete testimonial'], 500);
        }
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    private function rules(): array
    {
        return [
            'name' => 'required|max:150',
            'content' => 'required',
        ];
    }

    private function buildPayload(array $input): array
    {
        return [
            'name' => $this->sanitize((string)($input['name'] ?? '')),
            'content' => trim((string)($input['content'] ?? '')),
        ];
    }

    private function resolveImageFromRequest(array $post, array $files, ?string $currentImage = null, bool $allowRemove = false): array
    {
        $image = $currentImage;
        $imageUrl = trim((string)($post['image_url'] ?? ''));
        $removeImage = $allowRemove && isset($post['remove_image']);
        $imageFile = $files['image'] ?? null;
        $uploadErrorCode = (int)($imageFile['error'] ?? UPLOAD_ERR_NO_FILE);
        $hasUploadedFile = $imageFile && $uploadErrorCode === UPLOAD_ERR_OK;

        if ($imageFile && $uploadErrorCode !== UPLOAD_ERR_NO_FILE && $uploadErrorCode !== UPLOAD_ERR_OK) {
            return ['error' => 'Image upload failed. Please try another file'];
        }

        if ($imageUrl !== '' && $hasUploadedFile) {
            return ['error' => 'Provide either an image URL or an uploaded file, not both'];
        }

        if ($removeImage && ($imageUrl !== '' || $hasUploadedFile)) {
            return ['error' => 'Choose only one image action: remove, URL, or file upload'];
        }

        if ($removeImage) {
            $image = null;
        } elseif ($imageUrl !== '') {
            if (!$this->isValidImageUrl($imageUrl)) {
                return ['error' => 'Invalid image URL. Use http://, https://, or a site path starting with /'];
            }
            $image = $imageUrl;
        } elseif ($hasUploadedFile) {
            $saved = \App\Core\ImageProcessor::process($imageFile);
            if (!$saved) {
                return ['error' => \App\Core\ImageProcessor::getLastError() ?? 'Failed to upload image'];
            }
            $image = $saved;
        }

        return ['image' => $image];
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
        if (!$this->isLocalUploadReference($oldImage)) {
            return;
        }

        if ($oldImage === $newImage) {
            return;
        }

        \App\Core\ImageProcessor::delete($oldImage);
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
