<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Sitemap;

class SitemapController extends Controller
{
    public function generate()
    {
        $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost/SapphireEvents';
        $sitemap = new Sitemap($baseUrl);

        // Static pages
        $sitemap->addUrl('/', '1.0', 'daily');
        $sitemap->addUrl('/gallery', '0.9', 'weekly');
        $sitemap->addUrl('/services', '0.9', 'weekly');
        $sitemap->addUrl('/packages', '0.9', 'weekly');
        $sitemap->addUrl('/contact', '0.8', 'monthly');

        // Dynamic pages from database
        $db = $this->db->getConnection();
        
        $items = $db->query("SELECT id, created_at FROM gallery_items WHERE deleted_at IS NULL")->fetchAll();
        foreach ($items as $item) {
            $sitemap->addUrl('/gallery?item=' . $item['id'], '0.7', 'weekly');
        }

        try {
            $packageCategories = $db->query("SELECT slug FROM package_categories")->fetchAll();
            foreach ($packageCategories as $category) {
                $sitemap->addUrl('/packages/' . $category['slug'], '0.8', 'weekly');
            }
        } catch (\Throwable $e) {
            // Package tables may not be migrated yet.
        }

        header('Content-Type: application/xml');
        echo $sitemap->generate();
    }
}
