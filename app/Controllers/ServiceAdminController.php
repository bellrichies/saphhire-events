<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\Service;

class ServiceAdminController extends Controller
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $service = new Service();
        $services = $service->all();

        $this->view('admin.services.index', [
            'services' => $services,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $this->view('admin.services.create', [
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function edit()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $service = new Service();
        $item = $service->find($id);

        if (!$item) {
            $this->redirect(route('/admin/services'));
        }

        $this->view('admin.services.edit', [
            'service' => $item,
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

        $rules = [
            'title' => 'required|max:255',
            'description' => 'required',
        ];

        $data = [
            'title' => trim((string)($_POST['title'] ?? '')),
            'description' => $this->normalizeMultilineContent((string)($_POST['description'] ?? '')),
        ];

        $errors = $this->validate($data, $rules);
        if (!empty($errors)) {
            http_response_code(422);
            $this->json(['errors' => $errors]);
            return;
        }

        $imageName = null;
        $imageUrl = trim((string)($_POST['image_url'] ?? ''));
        $imageFile = $_FILES['image'] ?? null;
        $uploadErrorCode = (int)($imageFile['error'] ?? UPLOAD_ERR_NO_FILE);
        $hasUploadedFile = $imageFile && $uploadErrorCode === UPLOAD_ERR_OK;

        if ($imageFile && $uploadErrorCode !== UPLOAD_ERR_NO_FILE && $uploadErrorCode !== UPLOAD_ERR_OK) {
            http_response_code(422);
            $this->json(['error' => $this->uploadErrorMessage($uploadErrorCode)]);
            return;
        }

        if ($imageUrl !== '' && $hasUploadedFile) {
            http_response_code(422);
            $this->json(['error' => 'Provide either an image URL or an uploaded file, not both']);
            return;
        }

        if ($imageUrl !== '') {
            if (!$this->isValidImageUrl($imageUrl)) {
                http_response_code(422);
                $this->json(['error' => 'Invalid image URL. Use http://, https://, or a site path starting with /']);
                return;
            }
            $imageName = $imageUrl;
        } elseif ($hasUploadedFile) {
            $imageName = $this->saveImage($imageFile);
            if (!$imageName) {
                http_response_code(422);
                $this->json(['error' => \App\Core\ImageProcessor::getLastError() ?? 'Failed to upload image']);
                return;
            }
        }

        $service = new Service();
        $payload = [
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $imageName,
        ];
        $payload = $this->appendServiceLocalizedFields($payload, $_POST);

        if ($service->create($payload)) {
            $this->json(['success' => true, 'message' => 'Service created successfully']);
        } else {
            http_response_code(500);
            $this->json(['error' => 'Failed to create service']);
        }
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
            $this->json(['error' => 'Invalid ID']);
            return;
        }

        $service = new Service();
        $existing = $service->find($id);
        if (!$existing) {
            http_response_code(404);
            $this->json(['error' => 'Service not found']);
            return;
        }

        $rules = [
            'title' => 'required|max:255',
            'description' => 'required',
        ];

        $data = [
            'title' => trim((string)($_POST['title'] ?? '')),
            'description' => $this->normalizeMultilineContent((string)($_POST['description'] ?? '')),
        ];

        $errors = $this->validate($data, $rules);
        if (!empty($errors)) {
            http_response_code(422);
            $this->json(['errors' => $errors]);
            return;
        }

        $imageName = $existing['image'] ?? null;
        $imageUrl = trim((string)($_POST['image_url'] ?? ''));
        $removeImage = isset($_POST['remove_image']);
        $imageFile = $_FILES['image'] ?? null;
        $uploadErrorCode = (int)($imageFile['error'] ?? UPLOAD_ERR_NO_FILE);
        $hasUploadedFile = $imageFile && $uploadErrorCode === UPLOAD_ERR_OK;

        if ($imageFile && $uploadErrorCode !== UPLOAD_ERR_NO_FILE && $uploadErrorCode !== UPLOAD_ERR_OK) {
            http_response_code(422);
            $this->json(['error' => $this->uploadErrorMessage($uploadErrorCode)]);
            return;
        }

        if ($imageUrl !== '' && $hasUploadedFile) {
            http_response_code(422);
            $this->json(['error' => 'Provide either an image URL or an uploaded file, not both']);
            return;
        }

        if ($removeImage && ($imageUrl !== '' || $hasUploadedFile)) {
            http_response_code(422);
            $this->json(['error' => 'Choose only one image action: remove, URL, or file upload']);
            return;
        }

        if ($removeImage) {
            $imageName = null;
        } elseif ($imageUrl !== '') {
            if (!$this->isValidImageUrl($imageUrl)) {
                http_response_code(422);
                $this->json(['error' => 'Invalid image URL. Use http://, https://, or a site path starting with /']);
                return;
            }
            $imageName = $imageUrl;
        } elseif ($hasUploadedFile) {
            $newImage = $this->saveImage($imageFile);
            if (!$newImage) {
                http_response_code(422);
                $this->json(['error' => \App\Core\ImageProcessor::getLastError() ?? 'Failed to upload image']);
                return;
            }

            $imageName = $newImage;
        }

        $payload = [
            'title' => $data['title'],
            'description' => $data['description'],
            'image' => $imageName,
        ];
        $payload = $this->appendServiceLocalizedFields($payload, $_POST);

        $updated = $service->update($id, $payload);

        if ($updated) {
            $this->cleanupLocalImageIfReplaced($existing['image'] ?? null, $imageName);
            $this->json(['success' => true, 'message' => 'Service updated successfully']);
            return;
        }

        http_response_code(500);
        $this->json(['error' => 'Failed to update service']);
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

        $service = new Service();
        if ($service->delete($id)) {
            $this->json(['success' => true, 'message' => 'Service deleted successfully']);
        } else {
            http_response_code(500);
            $this->json(['error' => 'Failed to delete service']);
        }
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    private function saveImage(array $file): ?string
    {
        return \App\Core\ImageProcessor::process($file);
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

    private function normalizeMultilineContent(string $value): string
    {
        return str_replace(["\r\n", "\r"], "\n", $value);
    }

    private function appendServiceLocalizedFields(array $payload, array $input): array
    {
        foreach ($this->getTranslatableLocales() as $locale) {
            $titleKey = 'title_' . $locale;
            if (array_key_exists($titleKey, $input)) {
                $payload[$titleKey] = trim((string)$input[$titleKey]);
            }

            $descriptionKey = 'description_' . $locale;
            if (array_key_exists($descriptionKey, $input)) {
                $payload[$descriptionKey] = $this->normalizeMultilineContent((string)$input[$descriptionKey]);
            }
        }

        return $payload;
    }

    private function uploadErrorMessage(int $code): string
    {
        return match ($code) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'Image exceeds the maximum allowed upload size',
            UPLOAD_ERR_PARTIAL => 'Image upload was interrupted. Please try again',
            UPLOAD_ERR_NO_TMP_DIR => 'Server missing temporary upload directory',
            UPLOAD_ERR_CANT_WRITE => 'Server cannot write uploaded image',
            UPLOAD_ERR_EXTENSION => 'Image upload blocked by server extension',
            default => 'Image upload failed. Please try another file',
        };
    }
}
