<?php

namespace App\Controllers;

use App\Core\Controller;

class TeamController extends Controller
{
    public function index()
    {
        $this->view('team.index', [
            'seo' => [
                'title' => 'Meet Our Team | Sapphire Events Creative Directors',
                'description' => 'Meet Racheal and Israel, the creative minds behind Sapphire Events & Decorations. Discover the expertise and passion driving our event excellence.',
                'canonical' => route('/team'),
                'url' => route('/team'),
                'image' => 'assets/images/favicon.png',
                'image_alt' => 'Sapphire Events Team - Creative Directors',
            ],
        ]);
    }
}
