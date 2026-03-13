<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Gallery;
use App\Models\TeamMember;
use App\Models\Testimonial;

class AboutController extends Controller
{
    public function index()
    {
        $testimonial = new Testimonial();
        $team = new TeamMember();
        $gallery = new Gallery();
        $testimonials = $testimonial->getLatest(6);
        $teamMembers = $team->getActive();
        $highlightImages = $gallery->getRandomImagesOnly(12);

        $this->view('about.index', [
            'testimonials' => $testimonials,
            'teamMembers' => $teamMembers,
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
