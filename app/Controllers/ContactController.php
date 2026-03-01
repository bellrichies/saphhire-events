<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\Inquiry;
use Throwable;

class ContactController extends Controller
{
    public function index()
    {
        $this->view('contact.index', [
            'csrf_token' => CSRF::getToken(),
            'seo' => [
                'title' => 'Contact Sapphire Events | Book Your Event Consultation',
                'description' => 'Contact Sapphire Events & Decorations to plan your wedding, birthday, proposal, corporate event, or custom celebration in Tallinn.',
                'canonical' => route('/contact'),
                'url' => route('/contact'),
                'image' => 'assets/images/ceo-image.png',
                'image_alt' => 'Contact Sapphire Events and Decorations',
            ],
        ]);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate($_POST['_csrf_token'])) {
            http_response_code(403);
            $this->json(['error' => 'CSRF token invalid']);
            return;
        }

        $rules = [
            'name' => 'required|min:3|max:150',
            'email' => 'required|email',
            'phone' => 'required|max:50',
            'event_type' => 'required',
            'message' => 'required|min:10|max:5000',
        ];

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'event_type' => $_POST['event_type'] ?? '',
            'event_date' => $_POST['event_date'] ?? '',
            'message' => $_POST['message'] ?? '',
        ];

        $errors = $this->validate($data, $rules);

        if (!empty($errors)) {
            http_response_code(422);
            $this->json(['errors' => $errors]);
            return;
        }

        if (!empty($data['event_date'])) {
            $eventDate = \DateTime::createFromFormat('Y-m-d', $data['event_date']);
            $isValidDate = $eventDate && $eventDate->format('Y-m-d') === $data['event_date'];
            if (!$isValidDate) {
                http_response_code(422);
                $this->json(['errors' => ['event_date' => 'Event date must be a valid date']]);
                return;
            }
        }

        $sanitizedData = array_map([$this, 'sanitize'], $data);

        try {
            $inquiry = new Inquiry();
            if ($inquiry->createInquiry($sanitizedData)) {
                $this->json(['success' => true, 'message' => 'Inquiry submitted successfully']);
                return;
            }
        } catch (Throwable $e) {
            error_log('Contact inquiry submission failed: ' . $e->getMessage());
        }

        if (!headers_sent()) {
            http_response_code(500);
        }
        $this->json(['error' => 'Failed to submit inquiry. Please try again.']);
    }
}
