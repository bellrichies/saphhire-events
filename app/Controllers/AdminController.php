<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Core\MachineTranslator;
use App\Models\Admin;
use App\Models\Gallery;
use App\Models\Inquiry;
use App\Models\NewsletterSubscription;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;

class AdminController extends Controller
{
    private const LOGIN_ATTEMPTS = 'login_attempts';
    private const LOGIN_TIMEOUT = 900; // 15 minutes

    public function loginForm()
    {
        if ($this->isLoggedIn()) {
            $this->redirect(route('/admin/dashboard'));
        }

        $this->view('admin.login', [
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate($_POST['_csrf_token'])) {
            http_response_code(403);
            $this->json(['error' => 'CSRF token invalid']);
            return;
        }

        if ($this->isRateLimited()) {
            http_response_code(429);
            $this->json(['error' => 'Too many login attempts. Try again in 15 minutes.']);
            return;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $data = ['email' => $email, 'password' => $password];
        $errors = $this->validate($data, $rules);

        if (!empty($errors)) {
            http_response_code(422);
            $this->json(['errors' => $errors]);
            return;
        }

        $admin = new Admin();
        if ($admin->verifyPassword($email, $password)) {
            $_SESSION[self::LOGIN_ATTEMPTS] = 0;
            $_SESSION['admin_id'] = $admin->findByEmail($email)['id'];
            $_SESSION['admin_email'] = $email;
            
            session_regenerate_id(true);
            CSRF::regenerate();

            $this->json(['success' => true, 'redirect' => route('/admin/dashboard')]);
        } else {
            $this->recordFailedAttempt();
            http_response_code(401);
            $this->json(['error' => 'Invalid email or password']);
        }
    }

    public function logout()
    {
        session_destroy();
        $this->redirect(route('/admin/login'));
    }

    public function dashboard()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect(route('/admin/login'));
        }

        $gallery = new Gallery();
        $inquiry = new Inquiry();
        $newsletter = new NewsletterSubscription();
        $testimonial = new Testimonial();
        $service = new Service();
        $team = new TeamMember();

        $this->view('admin.dashboard', [
            'stats' => [
                'gallery' => $gallery->count(),
                'inquiries' => $inquiry->count(),
                'newsletters' => $newsletter->count(),
                'testimonials' => $testimonial->count(),
                'services' => $service->count(),
                'team' => $team->count(),
            ],
            'translationCacheStatus' => $_GET['translation_cache'] ?? null,
            'translationCacheDeleted' => isset($_GET['deleted']) ? (int) $_GET['deleted'] : null,
            'csrf_token' => CSRF::getToken(),
        ]);
    }

    public function clearTranslationCache()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect(route('/admin/login'));
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(route('/admin/dashboard'));
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate($_POST['_csrf_token'])) {
            $this->redirect(route('/admin/dashboard?translation_cache=csrf_error'));
        }

        $translator = new MachineTranslator();
        $result = $translator->clearCache();

        if ($result['failed'] > 0) {
            $this->redirect(route('/admin/dashboard?translation_cache=error&deleted=' . $result['deleted']));
        }

        $this->redirect(route('/admin/dashboard?translation_cache=cleared&deleted=' . $result['deleted']));
    }

    private function isLoggedIn(): bool
    {
        return isset($_SESSION['admin_id']) && isset($_SESSION['admin_email']);
    }

    private function isRateLimited(): bool
    {
        if (!isset($_SESSION[self::LOGIN_ATTEMPTS])) {
            $_SESSION[self::LOGIN_ATTEMPTS] = 0;
            $_SESSION[self::LOGIN_ATTEMPTS . '_time'] = time();
        }

        if (time() - $_SESSION[self::LOGIN_ATTEMPTS . '_time'] > self::LOGIN_TIMEOUT) {
            $_SESSION[self::LOGIN_ATTEMPTS] = 0;
            $_SESSION[self::LOGIN_ATTEMPTS . '_time'] = time();
        }

        return $_SESSION[self::LOGIN_ATTEMPTS] >= 5;
    }

    private function recordFailedAttempt(): void
    {
        $_SESSION[self::LOGIN_ATTEMPTS] = ($_SESSION[self::LOGIN_ATTEMPTS] ?? 0) + 1;
        $_SESSION[self::LOGIN_ATTEMPTS . '_time'] = time();
    }
}
