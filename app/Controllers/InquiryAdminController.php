<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Inquiry;

class InquiryAdminController extends Controller
{
    public function index()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $inquiry = new Inquiry();
        $inquiries = $inquiry->getLatest(50);

        $this->view('admin.inquiries.index', [
            'inquiries' => $inquiries,
        ]);
    }

    public function show()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $id = $_GET['id'] ?? 0;
        
        $inquiry = new Inquiry();
        $item = $inquiry->find($id);

        if (!$item) {
            $this->redirect(route('/admin/inquiries'));
        }

        $this->view('admin.inquiries.show', [
            'inquiry' => $item,
        ]);
    }

    public function exportCsv()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $inquiry = new Inquiry();
        $emails = $inquiry->getSenderEmails();

        $filename = 'inquiry-sender-emails-' . date('Ymd-His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        if ($output === false) {
            http_response_code(500);
            echo 'Failed to generate export.';
            exit;
        }

        fputcsv($output, ['Email']);
        foreach ($emails as $email) {
            fputcsv($output, [$email]);
        }

        fclose($output);
        exit;
    }

    public function exportTxt()
    {
        if (!$this->isAdmin()) {
            $this->redirect(route('/admin/login'));
        }

        $inquiry = new Inquiry();
        $emails = $inquiry->getSenderEmails();

        $filename = 'inquiry-sender-emails-' . date('Ymd-His') . '.txt';
        header('Content-Type: text/plain; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        foreach ($emails as $email) {
            echo $email . PHP_EOL;
        }

        exit;
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

        $inquiry = new Inquiry();
        if ($inquiry->delete($id)) {
            $this->json(['success' => true, 'message' => 'Inquiry deleted successfully']);
        } else {
            $this->json(['error' => 'Failed to delete inquiry'], 500);
        }
    }

    private function isAdmin(): bool
    {
        return isset($_SESSION['admin_id']);
    }
}
