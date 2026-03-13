<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Gallery;
use App\Models\Category;
use App\Models\Testimonial;

class GalleryController extends Controller
{
    public function index()
    {
        $category = new Category();
        $gallery = new Gallery();
        $testimonial = new Testimonial();

        $categoryId = $_GET['category'] ?? null;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 21;

        $categories = $category->all();
        
        if ($categoryId) {
            $items = $gallery->getByCategory((int)$categoryId, $page, $perPage);
            $total = $gallery->countByCategory((int)$categoryId);
        } else {
            $items = $gallery->getAllWithCategoryPaginated($page, $perPage);
            $total = $gallery->count();
        }

        $totalPages = ceil($total / $perPage);
        $testimonials = $testimonial->getLatest(3);
        $categoryName = '';
        foreach ($categories as $categoryItem) {
            if ((string)($categoryItem['id'] ?? '') === (string)$categoryId) {
                $categoryName = (string)($categoryItem['name'] ?? '');
                break;
            }
        }

        $galleryTitle = $categoryName !== ''
            ? $categoryName . ' ' . trans('content.gallery_page.seo.category_suffix', 'Gallery | Sapphire Events')
            : trans('content.gallery_page.seo.title', 'Event Gallery | Sapphire Events & Decorations');
        $galleryDescription = $categoryName !== ''
            ? trans('content.gallery_page.seo.category_prefix', 'Browse our ') . $categoryName . trans('content.gallery_page.seo.category_suffix_description', ' event portfolio with curated decor, styling, and celebration highlights from Sapphire Events.')
            : trans('content.gallery_page.seo.description', 'Explore Sapphire Events gallery featuring weddings, birthdays, proposals, corporate events, and custom decor transformations.');

        $this->view('gallery.index', [
            'items' => $items,
            'categories' => $categories,
            'currentCategory' => $categoryId,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'testimonials' => $testimonials,
            'seo' => [
                'title' => $galleryTitle,
                'description' => $galleryDescription,
                'canonical' => route('/gallery'),
                'url' => currentUrl(),
                'image' => 'assets/images/gallery-image-007.avif',
                'image_alt' => trans('content.gallery_page.seo.image_alt', 'Sapphire Events gallery showcase'),
            ],
        ]);
    }
}
