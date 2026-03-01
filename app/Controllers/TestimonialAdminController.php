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
        if (!$testimonial->find($id)) {
            $this->json(['error' => 'Testimonial not found'], 404);
            return;
        }

        $data = $this->buildPayload($_POST);
        $errors = $this->validate($data, $this->rules());
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 422);
            return;
        }

        if ($testimonial->update($id, $data)) {
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
        if ($testimonial->delete($id)) {
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
}
