<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\Inquiry;
use RuntimeException;
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
            'phone' => 'max:50',
            'service_type' => 'required|max:150',
            'event_type' => '',
            'event_date' => 'required',
            'event_time' => 'required|max:20',
            'budget' => 'required|max:150',
            'guest_count' => 'required|max:50',
            'event_location' => 'required|max:255',
            'lead_source' => 'max:100',
            'youtube_video_url' => 'max:255',
            'message' => 'required|min:10|max:5000',
        ];

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'service_type' => $_POST['service_type'] ?? '',
            'event_type' => $_POST['event_type'] ?? '',
            'event_date' => $_POST['event_date'] ?? '',
            'event_time' => $_POST['event_time'] ?? '',
            'budget' => $_POST['budget'] ?? '',
            'guest_count' => $_POST['guest_count'] ?? '',
            'event_location' => $_POST['event_location'] ?? '',
            'lead_source' => $_POST['lead_source'] ?? '',
            'youtube_video_url' => $_POST['youtube_video_url'] ?? '',
            'message' => $_POST['message'] ?? '',
            'package_id' => $_POST['package_id'] ?? '',
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

        if (!empty($data['event_time'])) {
            $eventTime = \DateTime::createFromFormat('H:i', $data['event_time']);
            $isValidTime = $eventTime && $eventTime->format('H:i') === $data['event_time'];
            if (!$isValidTime) {
                http_response_code(422);
                $this->json(['errors' => ['event_time' => 'Event time must be a valid time']]);
                return;
            }
        }

        $youtubeVideoUrl = trim((string)$data['youtube_video_url']);
        if ($youtubeVideoUrl !== '' && !$this->isValidYoutubeUrl($youtubeVideoUrl)) {
            http_response_code(422);
            $this->json(['errors' => ['youtube_video_url' => 'Please enter a valid YouTube video link.']]);
            return;
        }

        $uploadMeta = '';
        $uploadPath = '';
        if (isset($_FILES['inspiration_image']) && is_array($_FILES['inspiration_image'])) {
            $upload = $_FILES['inspiration_image'];
            $uploadError = (int)($upload['error'] ?? UPLOAD_ERR_NO_FILE);

            if ($uploadError !== UPLOAD_ERR_NO_FILE) {
                if ($uploadError !== UPLOAD_ERR_OK) {
                    http_response_code(422);
                    $this->json(['errors' => ['inspiration_image' => 'Image upload failed. Please try another file.']]);
                    return;
                }

                $tmpPath = (string)($upload['tmp_name'] ?? '');
                $originalName = trim((string)($upload['name'] ?? ''));
                $size = (int)($upload['size'] ?? 0);

                $maxSizeBytes = 10 * 1024 * 1024;
                if ($size <= 0 || $size > $maxSizeBytes) {
                    http_response_code(422);
                    $this->json(['errors' => ['inspiration_image' => 'Image must be greater than 0 bytes and up to 10MB.']]);
                    return;
                }

                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
                $mimeType = $tmpPath !== '' ? (string)(mime_content_type($tmpPath) ?: '') : '';
                if ($mimeType === '' || !in_array($mimeType, $allowedMimeTypes, true)) {
                    http_response_code(422);
                    $this->json(['errors' => ['inspiration_image' => 'Only JPG, PNG, WEBP, or AVIF images are allowed.']]);
                    return;
                }

                try {
                    [$uploadMeta, $uploadPath] = $this->storeInspirationImage($upload, $mimeType);
                } catch (RuntimeException $e) {
                    http_response_code(422);
                    $this->json(['errors' => ['inspiration_image' => $e->getMessage()]]);
                    return;
                }

            }
        }

        $data['message'] = implode("\n", [
            'Service Type: ' . $data['service_type'],
            'Occasion: ' . $data['event_type'],
            'Event Date: ' . $data['event_date'],
            'Event Time: ' . $data['event_time'],
            'Budget: ' . $data['budget'],
            'Guest Count: ' . $data['guest_count'],
            'Event Location: ' . $data['event_location'],
            'Selected Package ID: ' . ($data['package_id'] !== '' ? $data['package_id'] : 'None selected'),
            'Inspiration Image: ' . ($uploadMeta !== '' ? $uploadMeta : 'Not provided'),
            'Inspiration Image Path: ' . ($uploadPath !== '' ? $uploadPath : 'Not provided'),
            'YouTube Video Link: ' . ($youtubeVideoUrl !== '' ? $youtubeVideoUrl : 'Not provided'),
            'How Did You Hear About Us: ' . $data['lead_source'],
            '',
            'Additional Details:',
            $data['message'],
        ]);

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

    /**
     * @param array<string, mixed> $upload
     * @return array{0:string,1:string}
     */
    private function storeInspirationImage(array $upload, string $mimeType): array
    {
        $tmpPath = (string)($upload['tmp_name'] ?? '');
        $originalName = trim((string)($upload['name'] ?? ''));

        if ($tmpPath === '' || !is_uploaded_file($tmpPath)) {
            throw new RuntimeException('Invalid uploaded file. Please re-upload the image.');
        }

        $targetDir = PUBLIC_PATH . '/assets/uploads/inquiries';
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
            throw new RuntimeException('Unable to prepare upload folder. Please try again.');
        }

        $extensionByMime = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/avif' => 'avif',
        ];
        $extension = $extensionByMime[$mimeType] ?? strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));
        if ($extension === '') {
            $extension = 'jpg';
        }

        try {
            $randomSuffix = bin2hex(random_bytes(8));
        } catch (Throwable $e) {
            $randomSuffix = (string)mt_rand(100000, 999999);
        }

        $filename = 'inquiry_' . date('Ymd_His') . '_' . $randomSuffix . '.' . $extension;
        $relativePath = 'assets/uploads/inquiries/' . $filename;
        $targetPath = $targetDir . '/' . $filename;

        if (!move_uploaded_file($tmpPath, $targetPath)) {
            throw new RuntimeException('Failed to save uploaded image. Please try again.');
        }

        $displayName = $originalName !== '' ? $originalName : $filename;

        return [$displayName, $relativePath];
    }

    private function isValidYoutubeUrl(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $host = strtolower((string)parse_url($url, PHP_URL_HOST));
        if ($host === '') {
            return false;
        }

        $allowedHosts = [
            'youtube.com',
            'www.youtube.com',
            'm.youtube.com',
            'youtu.be',
            'www.youtu.be',
        ];

        return in_array($host, $allowedHosts, true);
    }
}
