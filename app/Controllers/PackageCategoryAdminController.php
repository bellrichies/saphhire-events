<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
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
        $category = $packageCategory->find($id);

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

        $name = $this->sanitize($_POST['name'] ?? '');
        $description = $this->sanitize($_POST['description'] ?? '');
        $slugInput = $this->sanitize($_POST['slug'] ?? '');
        $slug = $slugInput ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $displayOrder = (int)($_POST['display_order'] ?? 0);

        if ($name === '') {
            http_response_code(422);
            $this->json(['error' => 'Category name is required']);
            return;
        }

        $packageCategory = new PackageCategory();
        $created = $packageCategory->create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'display_order' => $displayOrder,
        ]);

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
        $name = $this->sanitize($_POST['name'] ?? '');
        $description = $this->sanitize($_POST['description'] ?? '');
        $slugInput = $this->sanitize($_POST['slug'] ?? '');
        $slug = $slugInput ?: strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        $displayOrder = (int)($_POST['display_order'] ?? 0);

        if ($id <= 0 || $name === '') {
            http_response_code(422);
            $this->json(['error' => 'Invalid payload']);
            return;
        }

        $packageCategory = new PackageCategory();
        $updated = $packageCategory->update($id, [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'display_order' => $displayOrder,
        ]);

        if ($updated) {
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
        if ($packageCategory->delete($id)) {
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
}
