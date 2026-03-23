<?php

// Generate Sitemap
// Usage: php generate-sitemap.php

define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');

require_once ROOT_PATH . '/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->safeLoad();

require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Sitemap.php';

use App\Core\Database;
use App\Core\Sitemap;

$db = Database::getInstance()->getConnection();
$configuredUrl = trim((string)($_ENV['APP_URL'] ?? ''));
$baseUrl = 'https://sapphire.bundly.ng';
if ($configuredUrl !== '' && stripos($configuredUrl, 'localhost') === false) {
    $baseUrl = rtrim($configuredUrl, '/');
}
$sitemap = new Sitemap($baseUrl);

$staticPages = [
    ['path' => '/', 'priority' => '1.0', 'changefreq' => 'daily'],
    ['path' => '/about', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['path' => '/team', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['path' => '/faqs', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['path' => '/contact', 'priority' => '0.8', 'changefreq' => 'monthly'],
    ['path' => '/gallery', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['path' => '/services', 'priority' => '0.9', 'changefreq' => 'weekly'],
    ['path' => '/packages', 'priority' => '0.9', 'changefreq' => 'weekly'],
];

foreach ($staticPages as $page) {
    $sitemap->addUrl($page['path'], $page['priority'], $page['changefreq']);
}

try {
    $services = $db->query(
        "SELECT id, created_at
         FROM services
         WHERE deleted_at IS NULL"
    )->fetchAll();

    foreach ($services as $service) {
        $sitemap->addUrl(
            '/services/' . (int)$service['id'],
            '0.7',
            'monthly',
            !empty($service['created_at']) ? date('Y-m-d', strtotime((string)$service['created_at'])) : null
        );
    }
} catch (\Throwable $e) {
    // Keep CLI sitemap generation resilient if services are not available yet.
}

try {
    $packageCategories = $db->query(
        "SELECT slug, created_at
         FROM package_categories
         WHERE slug IS NOT NULL AND slug != ''"
    )->fetchAll();

    foreach ($packageCategories as $category) {
        $sitemap->addUrl(
            '/packages/' . $category['slug'],
            '0.8',
            'weekly',
            !empty($category['created_at']) ? date('Y-m-d', strtotime((string)$category['created_at'])) : null
        );
    }
} catch (\Throwable $e) {
    // Keep CLI sitemap generation resilient if package tables are not available yet.
}

$sitemapPath = ROOT_PATH . '/public/sitemap.xml';
if ($sitemap->save($sitemapPath)) {
    echo "[OK] Sitemap generated: {$sitemapPath}\n";
} else {
    echo "[FAIL] Failed to generate sitemap\n";
}
