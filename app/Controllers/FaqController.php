<?php

namespace App\Controllers;

use App\Core\Controller;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = trans('content.faqs_page.items', []);
        if (!is_array($faqs)) {
            $faqs = [];
        }

        $this->view('faqs.index', [
            'faqs' => $faqs,
            'seo' => [
                'title' => trans('content.faqs_page.seo.title', 'FAQs | Sapphire Events & Decorations'),
                'description' => trans('content.faqs_page.seo.description', 'Get answers about event planning, pricing, timelines, vendor coordination, and booking with Sapphire Events in Tallinn.'),
                'canonical' => route('/faqs'),
                'url' => route('/faqs'),
                'image' => 'assets/images/about-image-1.avif',
                'image_alt' => trans('content.faqs_page.seo.image_alt', 'Frequently asked questions for Sapphire Events'),
            ],
        ]);
    }
}
