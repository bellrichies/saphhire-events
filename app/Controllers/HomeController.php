<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\Service;
use App\Models\Testimonial;

class HomeController extends Controller
{
    private const HOME_CACHE_VERSION = 'v2';

    public function index()
    {
        $language = function_exists('getCurrentLanguage') ? (string) getCurrentLanguage() : 'en';
        $cachedPayload = $this->loadHomeCache($language);

        if ($cachedPayload !== null) {
            $this->view('home.index', $cachedPayload);
            return;
        }

        $gallery = new Gallery();
        $service = new Service();
        $package = new Package();
        $packageCategory = new PackageCategory();
        $testimonial = new Testimonial();

        $featuredGallery = $gallery->getFeatured(16);
        $services = $service->getLatestWithImage(6);
        $featuredPackages = $package->getFeatured(6);
        $packageCategories = $packageCategory->getWithPackageCount(6);
        $testimonials = $testimonial->getLatest(3);

        $payload = [
            'featuredGallery' => $featuredGallery,
            'services' => $services,
            'featuredPackages' => $featuredPackages,
            'packageCategories' => $packageCategories,
            'testimonials' => $testimonials,
            'seo' => [
                'title' => 'Event Planning & Decoration in Tallinn | Sapphire Events',
                'description' => 'Sapphire Events & Decorations creates weddings, birthdays, proposals and corporate events with premium planning and decor services in Tallinn, Estonia.',
                'canonical' => route('/'),
                'url' => route('/'),
                'image' => siteSetting('site_og_image', 'assets/images/ceo-image.png'),
                'image_alt' => 'Luxury event planning and decoration by Sapphire Events',
            ],
        ];

        $this->storeHomeCache($language, $payload);

        $this->view('home.index', $payload);
    }

    private function loadHomeCache(string $language): ?array
    {
        $cacheFile = $this->homeCachePath($language);
        $ttl = 300;

        if (!is_file($cacheFile)) {
            return null;
        }

        if ((filemtime($cacheFile) ?: 0) < (time() - $ttl)) {
            return null;
        }

        $raw = file_get_contents($cacheFile);
        if (!is_string($raw) || $raw === '') {
            return null;
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : null;
    }

    private function storeHomeCache(string $language, array $payload): void
    {
        $cacheFile = $this->homeCachePath($language);
        $cacheDirectory = dirname($cacheFile);

        if (!is_dir($cacheDirectory) && !@mkdir($cacheDirectory, 0775, true) && !is_dir($cacheDirectory)) {
            return;
        }

        $encoded = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($encoded === false) {
            return;
        }

        file_put_contents($cacheFile, $encoded, LOCK_EX);
    }

    private function homeCachePath(string $language): string
    {
        $safeLanguage = preg_replace('/[^a-z0-9_-]/i', '', strtolower($language)) ?: 'en';

        return STORAGE_PATH . '/cache/home-' . self::HOME_CACHE_VERSION . '-' . $safeLanguage . '.json';
    }
}
