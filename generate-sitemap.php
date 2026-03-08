<?php

// Generate Sitemap
// Usage: php generate-sitemap.php

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/app/Core/Database.php';
require_once ROOT_PATH . '/app/Core/Sitemap.php';

use App\Core\Database;
use App\Core\Sitemap;

$db = Database::getInstance()->getConnection();

$baseUrl = $_ENV['APP_URL'] ?? 'http://localhost/SapphireEvents';
$sitemap = new Sitemap($baseUrl);

// Add static pages
$sitemap->addUrl('/', '1.0', 'daily');
$sitemap->addUrl('/gallery', '0.9', 'weekly');
$sitemap->addUrl('/contact', '0.8', 'monthly');

// Add gallery items
$items = $db->query("SELECT id, created_at FROM gallery_items WHERE deleted_at IS NULL")->fetchAll();
foreach ($items as $item) {
    $sitemap->addUrl('/gallery?item=' . $item['id'], '0.7', 'weekly');
}

// Save sitemap
$sitemapPath = ROOT_PATH . '/public/sitemap.xml';
if ($sitemap->save($sitemapPath)) {
    echo "✓ Sitemap generated: {$sitemapPath}\n";
} else {
    echo "✗ Failed to generate sitemap\n";
}
