<?php

namespace App\Controllers;

use App\Core\Controller;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = [
            // About Our Services Section
            [
                'id' => 1,
                'category' => 'About Our Services',
                'question' => 'What types of events do you specialize in?',
                'answer' => 'We specialize in a comprehensive range of events including weddings, corporate events, birthday parties, private parties, conferences, baby showers, bridal showers, proposals, engagements, and other bespoke gatherings. Whether it\'s an intimate gathering of 5 people or a large-scale celebration of thousands, we bring your vision to life with style and sophistication.'
            ],
            [
                'id' => 2,
                'category' => 'About Our Services',
                'question' => 'What guest count do you work with?',
                'answer' => 'Sapphire Events has no minimum guest count. We work with any size gathering, from intimate events with 5 people to large-scale celebrations with thousands of guests. We customize our pricing and services according to your preferences, goals, and specific requirements.'
            ],
            [
                'id' => 3,
                'category' => 'About Our Services',
                'question' => 'Do you offer event planning services?',
                'answer' => 'Absolutely. As experienced event planners, we guide you through every step of the process - from initial concept to flawless execution - ensuring your event runs smoothly. We offer full planning services, decoration-only packages, and day-of coordination options.'
            ],
            [
                'id' => 4,
                'category' => 'About Our Services',
                'question' => 'Can you help with event design and custom themes?',
                'answer' => 'Yes. Our team offers full design services including theming, decor, layout planning, and styling to match your event vision and mood. We love custom themes and unique ideas, and we create backdrops and photo areas that make each event memorable.'
            ],
            [
                'id' => 5,
                'category' => 'About Our Services',
                'question' => 'Do you specialize in kids\' themed parties and decorations?',
                'answer' => 'Yes. We specialize in kids\' parties and themed experiences for children of all ages. We also collaborate with animators, cartoon characters, face painters, and other entertainment vendors to create unforgettable celebrations.'
            ],

            // Booking & Timeline Section
            [
                'id' => 6,
                'category' => 'Booking & Timeline',
                'question' => 'How far in advance should I book my event?',
                'answer' => 'We recommend booking 3-6 months in advance to ensure availability and enough planning time. We can also accommodate last-minute bookings depending on our schedule.'
            ],
            [
                'id' => 7,
                'category' => 'Booking & Timeline',
                'question' => 'How do I start the booking process?',
                'answer' => 'Getting started is simple. Reach out via our contact form, social media, or phone. We schedule a consultation to understand your vision, budget, and timeline, then provide a tailored proposal.'
            ],
            [
                'id' => 8,
                'category' => 'Booking & Timeline',
                'question' => 'What happens if I need to cancel or reschedule my event?',
                'answer' => 'We understand plans can change. We\'ll discuss your options, including rescheduling and cancellation policies, based on timing and event scope. Contact us as soon as possible for the best outcome.'
            ],

            // Pricing & Payment Section
            [
                'id' => 9,
                'category' => 'Pricing & Payment',
                'question' => 'How much does it cost to plan and decorate an event?',
                'answer' => 'Pricing depends on event type, venue, guest count, and selected services. We offer customized and standard packages for different budgets. Contact us for a tailored quote.'
            ],
            [
                'id' => 10,
                'category' => 'Pricing & Payment',
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept major credit cards, debit cards, and bank transfers. A deposit is required to secure your date.'
            ],
            [
                'id' => 11,
                'category' => 'Pricing & Payment',
                'question' => 'What are your payment plan options?',
                'answer' => 'We offer flexible payment plans: (1) 70 percent upfront and 30 percent one month before the event, (2) full payment after consultation, or (3) a custom schedule with a 40 percent retainer. Full payment is required for bookings made less than one month before the event.'
            ],

            // Event Details & Support Section
            [
                'id' => 12,
                'category' => 'Event Details & Support',
                'question' => 'Can you provide or recommend vendors (catering, entertainment, etc.)?',
                'answer' => 'Yes. We work with trusted vendors for catering, entertainment, photography, decor, and more. We can recommend partners or collaborate with your existing vendors.'
            ],
            [
                'id' => 13,
                'category' => 'Event Details & Support',
                'question' => 'Can you work with my existing vendors?',
                'answer' => 'Absolutely. We coordinate with your existing caterers, photographers, musicians, and other vendors to ensure a smooth and cohesive event experience.'
            ],

            // Travel & Locations Section
            [
                'id' => 14,
                'category' => 'Travel & Locations',
                'question' => 'What areas do you serve?',
                'answer' => 'We are based in Tallinn, Estonia and serve cities across Estonia. We are also available for events within Europe depending on logistics.'
            ],
            [
                'id' => 15,
                'category' => 'Travel & Locations',
                'question' => 'Are you available to travel for events?',
                'answer' => 'Yes. We can travel throughout Estonia and within Europe for destination events. Contact us to discuss your location and travel requirements.'
            ],

            // Getting Started Section
            [
                'id' => 16,
                'category' => 'Getting Started',
                'question' => 'How do I get more information or ask additional questions?',
                'answer' => 'Reach out through our contact form, call +372-5160427, message us on social media (@sapphire_events_decorations), or email Sapphireeventsglitz@gmail.com. We are happy to help with any questions.'
            ]
        ];

        $this->view('faqs.index', [
            'faqs' => $faqs,
            'seo' => [
                'title' => 'FAQs | Sapphire Events & Decorations',
                'description' => 'Get answers about event planning, pricing, timelines, vendor coordination, and booking with Sapphire Events in Tallinn.',
                'canonical' => route('/faqs'),
                'url' => route('/faqs'),
                'image' => 'assets/images/about-image-1.avif',
                'image_alt' => 'Frequently asked questions for Sapphire Events',
            ],
        ]);
    }
}
