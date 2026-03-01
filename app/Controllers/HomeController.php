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
    public function index()
    {
        $gallery = new Gallery();
        $service = new Service();
        $package = new Package();
        $packageCategory = new PackageCategory();
        $testimonial = new Testimonial();

        $featuredGallery = $gallery->getFeatured(15);
        $services = $service->getAllWithImage();
        $featuredPackages = $package->getFeatured(6);
        $packageCategories = $packageCategory->getWithPackageCount(6);
        $testimonials = $testimonial->getLatest(3);

        $this->view('home.index', [
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
                'image' => 'assets/images/ceo-image.png',
                'image_alt' => 'Luxury event planning and decoration by Sapphire Events',
            ],
        ]);
    }
}
