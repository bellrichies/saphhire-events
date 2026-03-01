<?php

namespace App\Core;

class Sitemap
{
    private string $baseUrl;
    private array $urls = [];

    public function __construct(string $baseUrl)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
    }

    public function addUrl(string $path, string $priority = '0.8', string $changefreq = 'weekly'): void
    {
        $this->urls[] = [
            'loc' => $this->baseUrl . $path,
            'lastmod' => date('Y-m-d'),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }

    public function generate(): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($this->urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url['loc']) . "</loc>\n";
            $xml .= "    <lastmod>" . $url['lastmod'] . "</lastmod>\n";
            $xml .= "    <changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "    <priority>" . $url['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';
        return $xml;
    }

    public function save(string $filePath): bool
    {
        return file_put_contents($filePath, $this->generate()) !== false;
    }
}
