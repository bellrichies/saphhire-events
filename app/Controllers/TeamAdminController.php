<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\TeamMember;

class TeamAdminController extends Controller
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $team = new TeamMember();
        $members = $team->all();

        $this->view('admin.team.index', [
            'members' => $members,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $this->view('admin.team.create', [
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function edit()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $team = new TeamMember();
        $member = $team->find($id);

        if (!$member) {
            $this->redirect(route('/admin/team'));
        }

        $this->view('admin.team.edit', [
            'member' => $member,
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

        $data = $this->buildPayload($_POST);
        $errors = $this->validate($data, $this->rules());
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
            return;
        }

        $imageFile = $_FILES['image'] ?? null;
        $uploadErrorCode = $imageFile['error'] ?? UPLOAD_ERR_NO_FILE;
        $hasUploadedFile = $imageFile && $uploadErrorCode === UPLOAD_ERR_OK;
        $imageUrl = trim((string)($_POST['image_url'] ?? ''));

        if ($imageFile && $uploadErrorCode !== UPLOAD_ERR_NO_FILE && $uploadErrorCode !== UPLOAD_ERR_OK) {
            $this->json(['error' => 'Image upload failed. Please try another file'], 422);
            return;
        }

        if ($imageUrl !== '' && $hasUploadedFile) {
            $this->json(['error' => 'Provide either an image URL or an uploaded file, not both'], 422);
            return;
        }

        if ($imageUrl === '' && !$hasUploadedFile) {
            $this->json(['error' => 'Please provide an image URL or upload an image'], 422);
            return;
        }

        if ($imageUrl !== '' && !$this->isValidImageUrl($imageUrl)) {
            $this->json(['error' => 'Invalid image URL. Use http://, https://, or a site path starting with /'], 422);
            return;
        }

        $image = $imageUrl;
        if ($hasUploadedFile) {
            $image = $this->saveImage($imageFile);
            if (!$image) {
                $this->json(['error' => \App\Core\ImageProcessor::getLastError() ?? 'Failed to upload image'], 422);
                return;
            }
        }

        $data['image'] = $image;

        $team = new TeamMember();
        if ($team->create($data)) {
            $this->json(['success' => true, 'message' => 'Team member created successfully']);
            return;
        }

        $this->json(['error' => 'Failed to create team member'], 500);
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

        $team = new TeamMember();
        $existing = $team->find($id);
        if (!$existing) {
            $this->json(['error' => 'Team member not found'], 404);
            return;
        }

        $data = $this->buildPayload($_POST);
        $errors = $this->validate($data, $this->rules());
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
            return;
        }

        $imageFile = $_FILES['image'] ?? null;
        $uploadErrorCode = $imageFile['error'] ?? UPLOAD_ERR_NO_FILE;
        $hasUploadedFile = $imageFile && $uploadErrorCode === UPLOAD_ERR_OK;
        $imageUrl = trim((string)($_POST['image_url'] ?? ''));
        $removeImage = isset($_POST['remove_image']);
        $image = $existing['image'] ?? null;

        if ($imageFile && $uploadErrorCode !== UPLOAD_ERR_NO_FILE && $uploadErrorCode !== UPLOAD_ERR_OK) {
            $this->json(['error' => 'Image upload failed. Please try another file'], 422);
            return;
        }

        if ($imageUrl !== '' && $hasUploadedFile) {
            $this->json(['error' => 'Provide either an image URL or an uploaded file, not both'], 422);
            return;
        }

        if ($removeImage && ($imageUrl !== '' || $hasUploadedFile)) {
            $this->json(['error' => 'Choose only one image action: remove, URL, or file upload'], 422);
            return;
        }

        if ($removeImage) {
            $image = null;
        } elseif ($imageUrl !== '') {
            if (!$this->isValidImageUrl($imageUrl)) {
                $this->json(['error' => 'Invalid image URL. Use http://, https://, or a site path starting with /'], 422);
                return;
            }
            $image = $imageUrl;
        } elseif ($hasUploadedFile) {
            $newImage = $this->saveImage($imageFile);
            if (!$newImage) {
                $this->json(['error' => \App\Core\ImageProcessor::getLastError() ?? 'Failed to upload image'], 422);
                return;
            }
            $image = $newImage;
        }

        if (!$image) {
            $this->json(['error' => 'Please provide an image URL or upload an image'], 422);
            return;
        }

        $data['image'] = $image;

        if ($team->update($id, $data)) {
            $this->cleanupLocalImageIfReplaced($existing['image'] ?? null, $image);
            $this->json(['success' => true, 'message' => 'Team member updated successfully']);
            return;
        }

        $this->json(['error' => 'Failed to update team member'], 500);
    }

    public function delete()
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

        $team = new TeamMember();
        $member = $team->find($id);
        if ($team->delete($id)) {
            if ($member) {
                $this->cleanupLocalImageIfReplaced($member['image'] ?? null, null);
            }
            $this->json(['success' => true, 'message' => 'Team member deleted successfully']);
            return;
        }

        $this->json(['error' => 'Failed to delete team member'], 500);
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    private function rules(): array
    {
        return [
            'name' => 'required|max:150',
            'role' => 'required|max:150',
            'bio' => 'required',
        ];
    }

    private function buildPayload(array $input): array
    {
        return [
            'name' => $this->sanitize((string)($input['name'] ?? '')),
            'role' => $this->sanitize((string)($input['role'] ?? '')),
            'bio' => trim((string)($input['bio'] ?? '')),
            'display_order' => (int)($input['display_order'] ?? 0),
            'is_active' => isset($input['is_active']) ? 1 : 0,
        ];
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
}
