<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\TeamMember;
use App\Models\Testimonial;

class AboutController extends Controller
{
    public function index()
    {
        $testimonial = new Testimonial();
        $team = new TeamMember();
        $testimonials = $testimonial->getLatest(6);
        $teamMembers = $team->getActive();

        $this->view('about.index', [
            'testimonials' => $testimonials,
            'teamMembers' => $teamMembers,
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
