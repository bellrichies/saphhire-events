<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\NewsletterSubscription;
use Throwable;

class NewsletterController extends Controller
{
    private const RATE_LIMIT_WINDOW_SECONDS = 3600;
    private const RATE_LIMIT_MAX_ATTEMPTS = 25;

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
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

        if (!empty($_POST['website'])) {
            // Honeypot trap for bots.
            $this->json([
                'success' => true,
                'message' => 'Thank you for subscribing.',
            ]);
            return;
        }

        $email = trim((string)($_POST['email'] ?? ''));
        if (!$this->isValidEmail($email)) {
            $this->json([
                'errors' => ['email' => 'Please enter a valid email address.'],
            ], 422);
            return;
        }

        $subscriptionModel = new NewsletterSubscription();
        $ipAddress = $this->getClientIpAddress();

        if ($ipAddress !== '') {
            $attempts = $subscriptionModel->countRecentByIp($ipAddress, self::RATE_LIMIT_WINDOW_SECONDS);
            if ($attempts >= self::RATE_LIMIT_MAX_ATTEMPTS) {
                $this->json([
                    'error' => 'Too many subscription attempts. Please try again later.',
                ], 429);
                return;
            }
        }

        $locale = function_exists('getCurrentLanguage') ? (string)getCurrentLanguage() : 'en';

        try {
            $result = $subscriptionModel->createOrReactivate($email, [
                'source' => 'footer-newsletter',
                'locale' => $locale,
                'ip_address' => $ipAddress,
                'user_agent' => (string)($_SERVER['HTTP_USER_AGENT'] ?? ''),
            ]);

            if (!($result['success'] ?? false)) {
                $this->json(['error' => 'Unable to complete subscription. Please try again.'], 500);
                return;
            }

            if ($result['already_subscribed'] ?? false) {
                $this->json([
                    'success' => true,
                    'already_subscribed' => true,
                    'message' => 'This email is already subscribed.',
                ]);
                return;
            }

            if ($result['reactivated'] ?? false) {
                $this->json([
                    'success' => true,
                    'reactivated' => true,
                    'message' => 'Subscription reactivated successfully.',
                ]);
                return;
            }

            $this->json([
                'success' => true,
                'message' => 'Thank you for subscribing.',
            ]);
        } catch (Throwable $e) {
            error_log('Newsletter subscription failed: ' . $e->getMessage());
            $this->json([
                'error' => 'Unable to process your request right now. Please try again later.',
            ], 500);
        }
    }

    private function isValidEmail(string $email): bool
    {
        if ($email === '' || strlen($email) > 254) {
            return false;
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function getClientIpAddress(): string
    {
        $remote = trim((string)($_SERVER['REMOTE_ADDR'] ?? ''));
        if ($remote !== '' && filter_var($remote, FILTER_VALIDATE_IP)) {
            return $remote;
        }

        return '';
    }
}
