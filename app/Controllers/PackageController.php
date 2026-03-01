<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\CSRF;
use App\Models\Inquiry;
use App\Models\Package;
use App\Models\PackageCategory;

class PackageController extends Controller
{
    public function index()
    {
        $packageCategory = new PackageCategory();
        $package = new Package();

        $categories = $packageCategory->getWithPackageCount();
        $featuredPackages = $package->getFeatured(6);

        $this->view('packages.index', [
            'categories' => $categories,
            'featuredPackages' => $featuredPackages,
            'csrf_token' => CSRF::getToken(),
            'seo' => [
                'title' => 'Event Packages | Sapphire Events & Decorations',
                'description' => 'Discover curated event packages for weddings, birthdays, corporate celebrations and more, with flexible options from Sapphire Events.',
                'canonical' => route('/packages'),
                'url' => route('/packages'),
                'image' => 'assets/images/proposal-001.avif',
                'image_alt' => 'Sapphire Events package offerings',
            ],
        ]);
    }

    public function show()
    {
        $slug = $this->getPathVariable('slug');
        $packageCategory = new PackageCategory();
        $package = new Package();

        if (!$slug) {
            http_response_code(404);
            echo 'Package category not found';
            exit;
        }

        $category = $packageCategory->findBySlug($slug);
        if (!$category) {
            http_response_code(404);
            echo 'Package category not found';
            exit;
        }

        $packages = $package->getByCategoryId((int)$category['id']);

        $this->view('packages.show', [
            'category' => $category,
            'packages' => $packages,
            'csrf_token' => CSRF::getToken(),
            'booked' => isset($_GET['booked']) && $_GET['booked'] === '1',
            'bookingError' => $_GET['error'] ?? '',
            'seo' => [
                'title' => ($category['name'] ?? 'Event') . ' Packages | Sapphire Events',
                'description' => $category['description'] ?? 'Explore package options tailored to your event goals and budget with Sapphire Events.',
                'canonical' => route('/packages/' . $slug),
                'url' => route('/packages/' . $slug),
                'image' => $packages[0]['image'] ?? 'assets/images/ceo-image.png',
                'image_alt' => ($category['name'] ?? 'Event') . ' package options by Sapphire Events',
            ],
        ]);
    }

    public function book()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(route('/packages'));
        }

        if (!isset($_POST['_csrf_token']) || !CSRF::validate($_POST['_csrf_token'])) {
            $this->redirect(route('/packages'));
        }

        $rules = [
            'name' => 'required|min:3|max:150',
            'email' => 'required|email',
            'phone' => 'required|max:50',
            'event_date' => 'required',
            'package_id' => 'required',
            'message' => 'required|min:10|max:5000',
        ];

        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'event_date' => $_POST['event_date'] ?? '',
            'package_id' => $_POST['package_id'] ?? '',
            'message' => $_POST['message'] ?? '',
        ];

        $errors = $this->validate($data, $rules);
        if (!empty($errors)) {
            $targetSlug = $this->sanitize($_POST['category_slug'] ?? '');
            $target = $targetSlug ? route('/packages/' . $targetSlug) : route('/packages');
            $this->redirect($target . '?error=validation');
        }

        $package = new Package();
        $selectedPackage = $package->findWithCategory((int)$data['package_id']);
        if (!$selectedPackage) {
            $this->redirect(route('/packages') . '?error=invalid-package');
        }

        $sanitizedMessage = $this->sanitize($data['message']);
        $bookingContext = "Selected package: {$selectedPackage['title']} ({$selectedPackage['category_name']})\n"
            . "Price: {$selectedPackage['price_label']}\n"
            . "Preferred event date: {$this->sanitize($data['event_date'])}\n"
            . "---\n"
            . $sanitizedMessage;

        $inquiry = new Inquiry();
        $created = $inquiry->createInquiry([
            'name' => $this->sanitize($data['name']),
            'email' => $this->sanitize($data['email']),
            'phone' => $this->sanitize($data['phone']),
            'event_type' => 'Package Booking - ' . $selectedPackage['category_name'],
            'message' => $bookingContext,
        ]);

        $target = route('/packages/' . $selectedPackage['category_slug']);
        if ($created) {
            $this->redirect($target . '?booked=1');
        }

        $this->redirect($target . '?error=submit-failed');
    }

    private function getPathVariable(string $name): ?string
    {
        $router = \App\Core\App::getInstance()->getRouter();
        return $router->getPathVariable($name);
    }
}
