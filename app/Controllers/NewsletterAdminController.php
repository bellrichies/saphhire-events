<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\NewsletterSubscription;

class NewsletterAdminController extends Controller
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $filters = $this->getFilters();
        $search = $filters['search'];
        $status = $filters['status'];

        $model = new NewsletterSubscription();
        $subscriptions = $model->getLatest(500, $search, $status);

        $this->view('admin.newsletters.index', [
            'subscriptions' => $subscriptions,
            'search' => $search,
            'status' => $status,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function exportCsv()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $filters = $this->getFilters();
        $model = new NewsletterSubscription();
        $subscriptions = $model->getAll($filters['search'], $filters['status']);

        $filename = 'newsletter-leads-' . date('Ymd-His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        if ($output === false) {
            http_response_code(500);
            echo 'Failed to generate export.';
            exit;
        }

        fputcsv($output, ['Email', 'Status', 'Source', 'Locale', 'IP Address', 'Subscribed At', 'Created At']);
        foreach ($subscriptions as $subscription) {
            fputcsv($output, [
                (string)($subscription['email'] ?? ''),
                (string)($subscription['status'] ?? ''),
                (string)($subscription['source'] ?? ''),
                (string)($subscription['locale'] ?? ''),
                (string)($subscription['ip_address'] ?? ''),
                (string)($subscription['subscribed_at'] ?? ''),
                (string)($subscription['created_at'] ?? ''),
            ]);
        }

        fclose($output);
        exit;
    }

    public function exportTxt()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $filters = $this->getFilters();
        $model = new NewsletterSubscription();
        $subscriptions = $model->getAll($filters['search'], $filters['status']);

        $filename = 'newsletter-leads-' . date('Ymd-His') . '.txt';
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        foreach ($subscriptions as $subscription) {
            echo (string)($subscription['email'] ?? '') . PHP_EOL;
        }

        exit;
    }

    public function updateStatus()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate((string)$_POST['_csrf_token'])) {
            CSRF::regenerate();
            $this->json([
                'error' => 'CSRF token invalid',
                'csrf_token' => CSRF::getToken(),
            ], 403);
            return;
        }

        $id = (int)($_POST['id'] ?? 0);
        $status = strtolower(trim((string)($_POST['status'] ?? '')));

        if ($id <= 0 || !in_array($status, ['active', 'unsubscribed', 'bounced'], true)) {
            $this->json(['error' => 'Invalid input'], 422);
            return;
        }

        $model = new NewsletterSubscription();
        $record = $model->find($id);
        if (!$record) {
            $this->json(['error' => 'Subscription not found'], 404);
            return;
        }

        if ($model->updateStatus($id, $status)) {
            $this->json([
                'success' => true,
                'message' => 'Subscription status updated.',
            ]);
            return;
        }

        $this->json(['error' => 'Failed to update subscription status'], 500);
    }

    public function delete()
    {
        if (!$this->isAdmin()) {
            $this->json(['error' => 'Unauthorized'], 401);
            return;
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate((string)$_POST['_csrf_token'])) {
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

        $model = new NewsletterSubscription();
        if (!$model->find($id)) {
            $this->json(['error' => 'Subscription not found'], 404);
            return;
        }

        if ($model->delete($id)) {
            $this->json([
                'success' => true,
                'message' => 'Subscription deleted successfully.',
            ]);
            return;
        }

        $this->json(['error' => 'Failed to delete subscription'], 500);
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['admin_id']);
    }

    private function getFilters(): array
    {
        return [
            'search' => trim((string)($_GET['q'] ?? '')),
            'status' => strtolower(trim((string)($_GET['status'] ?? 'all'))),
        ];
    }
}
