<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Gallery;

class AboutController extends Controller
{
    public function index()
    {
        $cacheFile = STORAGE_PATH . '/cache/about-page.json';
        $highlightImages = [];

        if (is_file($cacheFile) && (filemtime($cacheFile) ?: 0) >= (time() - 300)) {
            $cached = json_decode((string) file_get_contents($cacheFile), true);
            if (is_array($cached)) {
                $highlightImages = $cached;
            }
        }

        if ($highlightImages === []) {
            $gallery = new Gallery();
            $highlightImages = $gallery->getLatestImagesOnly(8);

            $cacheDirectory = dirname($cacheFile);
            if (!is_dir($cacheDirectory) && !@mkdir($cacheDirectory, 0775, true) && !is_dir($cacheDirectory)) {
                $cacheDirectory = null;
            }

            if ($cacheDirectory !== null) {
                file_put_contents(
                    $cacheFile,
                    json_encode($highlightImages, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
                    LOCK_EX
                );
            }
        }

        $this->view('about.index', [
            'highlightImages' => $highlightImages,
            'seo' => [
                'title' => 'About Sapphire Events | Creative Event Experts',
                'description' => 'Meet Sapphire Events & Decorations, a Tallinn-based team delivering elegant event planning, custom decor, and memorable guest experiences.',
                'canonical' => route('/about'),
                'url' => route('/about'),
                'image' => 'assets/images/about-home.avif',
                'image_alt' => 'Sapphire Events team and event styling work',
            ],
        ]);
    }
}
