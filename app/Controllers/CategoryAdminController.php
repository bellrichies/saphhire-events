<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\Category;

class CategoryAdminController extends Controller
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $category = new Category();
        $categories = $category->getWithItemCount();

        $this->view('admin.categories.index', [
            'categories' => $categories,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function create()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $this->view('admin.categories.create', [
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
            $this->json(['error' => 'CSRF token invalid'], 403);
            return;
        }

        $name = $this->sanitize($_POST['name'] ?? '');
        $slug = strtolower(str_replace(' ', '-', $name));

        if (empty($name)) {
            $this->json(['error' => 'Category name is required'], 422);
            return;
        }

        $category = new Category();
        if ($category->create([':name' => $name, ':slug' => $slug])) {
            $this->json(['success' => true, 'message' => 'Category created successfully']);
        } else {
            $this->json(['error' => 'Failed to create category'], 500);
        }
    }

    public function delete()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized']);
            return;
        }

        $id = $_POST['id'] ?? 0;
        
        if (!$id) {
            $this->json(['error' => 'Invalid ID'], 422);
            return;
        }

        $category = new Category();
        if ($category->delete($id)) {
            $this->json(['success' => true, 'message' => 'Category deleted successfully']);
        } else {
            $this->json(['error' => 'Failed to delete category'], 500);
        }
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['admin_id']);
    }
}
