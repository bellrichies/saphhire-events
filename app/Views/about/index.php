<?php
$title = trans('content.about.page_title', 'About Us');
ob_start();
?>

<section class="relative py-14 md:py-16 px-4 overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    <div class="absolute top-8 right-16 w-24 h-24 rounded-full opacity-15 animate-float" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10" data-aos="fade-up">
        <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
            <?php echo htmlspecialchars(trans('content.about.hero.badge', 'About Sapphire Events')); ?>
        </span>
        <h1 class="text-4xl md:text-5xl font-light mb-4 leading-tight text-white" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.about.hero.title', 'Crafting Celebrations with Purpose')); ?>
        </h1>
        <p class="text-base md:text-lg text-gray-300 max-w-3xl mx-auto leading-relaxed" style="font-family: 'Montserrat', sans-serif;">
            <?php echo htmlspecialchars(trans('content.about.hero.description', 'We blend design excellence, operational precision, and human-centered service to deliver events that are beautiful, seamless, and memorable.')); ?>
        </p>
    </div>
</section>

<section class="pt-20 pb-10 px-4" style="background-color: #F8F5F2;">
    <div class="site-container">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.18em;">
                <?php echo htmlspecialchars(trans('content.about.team.badge', 'Our Team')); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.about.team.title', 'People Behind the Experience')); ?>
            </h2>
            <p class="text-base text-gray-600 max-w-2xl mx-auto">
                <?php echo htmlspecialchars(trans('content.about.team.description', 'A multidisciplinary team focused on design excellence, flawless coordination, and high-touch client support.')); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <div class="lg:col-span-7">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <?php if (!empty($teamMembers ?? [])): ?>
                        <?php foreach (($teamMembers ?? []) as $index => $member): ?>
                            <article class="team-card overflow-hidden" data-aos="fade-up" data-aos-delay="<?php echo $index * 90; ?>">
                                <div class="team-card-media">
                                    <img
                                        src="<?php echo htmlspecialchars(uploadedImageUrl($member['image'] ?? '')); ?>"
                                        alt="<?php echo htmlspecialchars('Portrait of ' . ($member['name'] ?? trans('content.about.team.default_member_name', 'Team Member')) . ', ' . ($member['role'] ?? trans('content.about.team.default_member_role', 'Team'))); ?>"
                                        loading="lazy"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="p-5">
                                    <h3 class="text-xl mb-1" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;"><?php echo htmlspecialchars($member['name'] ?? trans('content.about.team.default_member_name', 'Team Member')); ?></h3>
                                    <p class="text-xs uppercase tracking-widest mb-3" style="color: #C8A951; font-family: 'Montserrat', sans-serif; font-weight: 700;"><?php echo htmlspecialchars($member['role'] ?? trans('content.about.team.default_member_role', 'Team')); ?></p>
                                    <p class="text-sm text-gray-600 leading-relaxed">
                                        <?php echo htmlspecialchars($member['bio'] ?? ''); ?>
                                    </p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="sm:col-span-2 text-center py-8 text-gray-500">
                            <?php echo htmlspecialchars(trans('content.about.team.empty', 'Team members will appear here once available.')); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-5" data-aos="fade-left">
                <div class="rounded-2xl p-3 md:p-8 bg-[#f3eee8] luxury-shadow">
                    <div class="space-y-8">
                        <article class="about-feature-card mb-6">
                            <!-- <div class="about-icon-wrap"><i class="fas fa-bullseye"></i></div> -->
                            <h3 class="text-2xl mb-3" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;">
                                <?php echo htmlspecialchars(trans('content.about.mission_vision.mission_title', 'Our Mission')); ?>
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                <?php echo htmlspecialchars(trans('content.about.mission_vision.mission_desc', 'At Sapphire Events & Decorations, our mission is to transform your special occasions into unforgettable experiences. With meticulous attention to detail and a passion for creativity, we strive to exceed your expectations, delivering exceptional event planning and stunning decorations that bring your vision to life.')); ?>
                            </p>
                        </article>
                        <article class="about-feature-card">
                            <!-- <div class="about-icon-wrap"><i class="fas fa-star"></i></div> -->
                            <h3 class="text-2xl mb-3" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;">
                                <?php echo htmlspecialchars(trans('content.about.mission_vision.vision_title', 'Our Vision')); ?>
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                <?php echo htmlspecialchars(trans('content.about.mission_vision.vision_desc', 'Our vision at Sapphire Events & Decorations is to be the first choice for creating magical moments that last a lifetime. We aim to inspire and delight our clients with innovative designs, impeccable service, and a commitment to excellence in every event we undertake. With our expertise and dedication, we envision turning dreams into reality, one celebration at a time.')); ?>
                            </p>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="pb-20 px-4" style="background-color: #F8F5F2;">
    <div class="site-container">
        <!-- <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center mb-16">
            <div class="lg:col-span-7" data-aos="fade-right">
                <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.18em;">
                    <?php echo htmlspecialchars(trans('content.about.story.badge', 'Our Story')); ?>
                </span>
                <h2 class="text-4xl md:text-5xl font-light mb-5" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                    <?php echo htmlspecialchars(trans('content.about.story.title', 'From Passion Project to Trusted Partner')); ?>
                </h2>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    <?php echo htmlspecialchars(trans('content.about.story.paragraph_1', 'Sapphire Events & Decorations was founded on one principle: every event should feel intentionally designed and flawlessly delivered. What started as a small creative initiative has grown into a full-service event planning and decoration partner.')); ?>
                </p>
                <p class="text-gray-700 mb-4 leading-relaxed">
                    <?php echo htmlspecialchars(trans('content.about.story.paragraph_2', 'We support weddings, corporate functions, proposals, birthdays, and bespoke private experiences. Our process combines creative concepting, detailed logistics, and disciplined execution to ensure every touchpoint works together.')); ?>
                </p>
                <p class="text-gray-700 leading-relaxed">
                    <?php echo htmlspecialchars(trans('content.about.story.paragraph_3', 'Today, clients choose us for our reliability, calm communication, and consistent quality from the first consultation through the final setup.')); ?>
                </p>
            </div>

            <div class="lg:col-span-5 relative" data-aos="fade-left">
                <div class="h-[440px] rounded-2xl overflow-hidden luxury-shadow" style="background: linear-gradient(135deg, #0F3D3E 0%, #C8A951 100%);">
                    <img src="<?= route('assets/images/engagement.avif') ?>" alt="Sapphire Events story" class="w-full h-full object-cover object-center opacity-95">
                </div>
                <div class="hidden md:block absolute -bottom-6 -left-6 bg-white rounded-xl p-4 luxury-shadow">
                    <p class="text-xs uppercase tracking-widest mb-1" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.about.story.established', 'Established')); ?></p>
                    <p class="text-2xl" style="color: #C8A951; font-family: 'Cormorant Garamond', serif;"><?php echo htmlspecialchars(trans('content.about.story.established_value', 'With Vision & Craft')); ?></p>
                </div>
            </div>
        </div> -->

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">
            <div class="about-kpi-card">
                <p class="text-3xl mb-1" style="font-family: 'Cormorant Garamond', serif; color: #C8A951;">150+</p>
                <p class="text-sm text-gray-700 font-semibold"><?php echo htmlspecialchars(trans('content.about.kpi.events_delivered', 'Events Delivered')); ?></p>
            </div>
            <div class="about-kpi-card">
                <p class="text-3xl mb-1" style="font-family: 'Cormorant Garamond', serif; color: #C8A951;">2000+</p>
                <p class="text-sm text-gray-700 font-semibold"><?php echo htmlspecialchars(trans('content.about.kpi.guests_served', 'Guests Served')); ?></p>
            </div>
            <div class="about-kpi-card">
                <p class="text-3xl mb-1" style="font-family: 'Cormorant Garamond', serif; color: #C8A951;">98%</p>
                <p class="text-sm text-gray-700 font-semibold"><?php echo htmlspecialchars(trans('content.about.kpi.client_satisfaction', 'Client Satisfaction')); ?></p>
            </div>
            <div class="about-kpi-card">
                <p class="text-3xl mb-1" style="font-family: 'Cormorant Garamond', serif; color: #C8A951;">24/7</p>
                <p class="text-sm text-gray-700 font-semibold"><?php echo htmlspecialchars(trans('content.about.kpi.planning_support', 'Planning Support')); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="py-20 px-4">
    <div class="site-container">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.18em;">
                <?php echo htmlspecialchars(trans('content.about.capabilities.badge', 'What Sets Us Apart')); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.about.capabilities.title', 'Our Core Capabilities')); ?>
            </h2>
            <p class="text-base text-gray-600 max-w-2xl mx-auto">
                <?php echo htmlspecialchars(trans('content.about.capabilities.description', 'We operate at the intersection of creativity and execution so your event looks exceptional and runs effortlessly.')); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <article class="about-feature-card" data-aos="fade-up">
                <div class="about-icon-wrap"><i class="fas fa-palette"></i></div>
                <h3 class="text-xl mb-3" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;"><?php echo htmlspecialchars(trans('content.about.capabilities.creative_title', 'Creative Direction')); ?></h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    <?php echo htmlspecialchars(trans('content.about.capabilities.creative_desc', 'Distinct concept development, styling systems, and cohesive visual storytelling tailored to your event goals.')); ?>
                </p>
            </article>
            <article class="about-feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="about-icon-wrap"><i class="fas fa-clipboard-check"></i></div>
                <h3 class="text-xl mb-3" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;"><?php echo htmlspecialchars(trans('content.about.capabilities.operations_title', 'Operational Precision')); ?></h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    <?php echo htmlspecialchars(trans('content.about.capabilities.operations_desc', 'Structured planning frameworks, timeline control, and vendor coordination to reduce risk and maintain quality.')); ?>
                </p>
            </article>
            <article class="about-feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="about-icon-wrap"><i class="fas fa-heart"></i></div>
                <h3 class="text-xl mb-3" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;"><?php echo htmlspecialchars(trans('content.about.capabilities.service_title', 'Client-Centered Service')); ?></h3>
                <p class="text-gray-600 text-sm leading-relaxed">
                    <?php echo htmlspecialchars(trans('content.about.capabilities.service_desc', 'Transparent communication, responsive support, and tailored recommendations from consultation to event day.')); ?>
                </p>
            </article>
        </div>
    </div>
</section>

<section class="py-20 px-4" style="background-color: #F8F5F2;">
    <div class="site-container">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.18em;">
                <?php echo htmlspecialchars(trans('content.about.process.badge', 'Our Process')); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.about.process.title', 'How We Deliver Excellence')); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="about-step-card" data-aos="fade-up">
                <div class="about-step-num">01</div>
                <h4 class="font-bold mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.about.process.step_1_title', 'Discovery')); ?></h4>
                <p class="text-sm text-gray-600"><?php echo htmlspecialchars(trans('content.about.process.step_1_desc', 'Understand objectives, audience, venue constraints, and priorities.')); ?></p>
            </div>
            <div class="about-step-card" data-aos="fade-up" data-aos-delay="100">
                <div class="about-step-num">02</div>
                <h4 class="font-bold mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.about.process.step_2_title', 'Concept & Plan')); ?></h4>
                <p class="text-sm text-gray-600"><?php echo htmlspecialchars(trans('content.about.process.step_2_desc', 'Build a clear concept, layout direction, and execution roadmap.')); ?></p>
            </div>
            <div class="about-step-card" data-aos="fade-up" data-aos-delay="200">
                <div class="about-step-num">03</div>
                <h4 class="font-bold mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.about.process.step_3_title', 'Coordination')); ?></h4>
                <p class="text-sm text-gray-600"><?php echo htmlspecialchars(trans('content.about.process.step_3_desc', 'Align vendors, timelines, materials, and setup logistics.')); ?></p>
            </div>
            <div class="about-step-card" data-aos="fade-up" data-aos-delay="300">
                <div class="about-step-num">04</div>
                <h4 class="font-bold mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.about.process.step_4_title', 'Execution')); ?></h4>
                <p class="text-sm text-gray-600"><?php echo htmlspecialchars(trans('content.about.process.step_4_desc', 'Deliver a polished event experience with on-site quality control.')); ?></p>
            </div>
        </div>
    </div>
</section>

<section class="py-20 px-4" style="background-color: #F8F5F2;">
    <div class="site-container">
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.18em;">
                <?php echo htmlspecialchars(trans('content.about.testimonials.badge', 'Testimonials')); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.about.testimonials.title', 'What Clients Say About Working With Us')); ?>
            </h2>
            <p class="text-base text-gray-600 max-w-2xl mx-auto">
                <?php echo htmlspecialchars(trans('content.about.testimonials.description', 'Feedback from clients who trusted Sapphire Events with milestone celebrations and business events.')); ?>
            </p>
        </div>

        <div class="relative" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper aboutTestimonialSwiper px-2 md:px-12">
                <div class="swiper-wrapper">
                    <?php foreach (($testimonials ?? []) as $item): ?>
                        <div class="swiper-slide h-auto">
                            <article class="testimonial-card h-full">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="testimonial-avatar">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; font-weight: 600;">
                                            <?php echo htmlspecialchars($item['name']); ?>
                                        </h3>
                                        <div class="flex gap-1 mt-1" aria-label="5 star rating">
                                            <?php for ($i = 0; $i < 5; $i++): ?>
                                                <i class="fas fa-star text-xs" style="color: #C8A951;"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed line-clamp-5">
                                    "<?php echo htmlspecialchars($item['content']); ?>"
                                </p>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- <button class="about-testimonial-btn about-testimonial-prev" aria-label="<?php echo htmlspecialchars(trans('content.about.testimonials.prev_aria', 'Previous testimonial')); ?>">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="about-testimonial-btn about-testimonial-next" aria-label="<?php echo htmlspecialchars(trans('content.about.testimonials.next_aria', 'Next testimonial')); ?>">
                <i class="fas fa-chevron-right"></i>
            </button> -->
        </div>

        <?php if (empty($testimonials ?? [])): ?>
            <p class="text-center text-gray-500 mt-8"><?php echo htmlspecialchars(trans('content.about.testimonials.empty', 'Testimonials will appear here once available.')); ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="py-16 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22><circle cx=%2250%22 cy=%2250%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    <div class="max-w-3xl mx-auto text-center relative z-10" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-light mb-5 text-white" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.about.cta.title', "Let's Build Your Next Event")); ?>
        </h2>
        <p class="text-gray-300 mb-8 max-w-2xl mx-auto">
            <?php echo htmlspecialchars(trans('content.about.cta.description', "Share your vision and requirements. We'll recommend the best service path and package direction for your event.")); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="<?php echo route('/contact'); ?>" class="group px-10 py-4 rounded-xl font-semibold transition-all duration-300 hover:shadow-2xl hover:scale-105" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.85rem;">
                <?php echo htmlspecialchars(trans('content.about.cta.primary', 'Start Planning')); ?>
                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
            </a>
            <a href="<?php echo route('/services'); ?>" class="px-10 py-4 rounded-xl font-semibold border-2 border-white/30 text-white transition-all duration-300 hover:bg-white/10" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.85rem;">
                <?php echo htmlspecialchars(trans('content.about.cta.secondary', 'Explore Services')); ?>
            </a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        new Swiper('.aboutTestimonialSwiper', {
            slidesPerView: 1,
            spaceBetween: 16,
            speed: 700,
            loop: true,
            keyboard: {
                enabled: true,
                onlyInViewport: true
            },
            navigation: {
                nextEl: '.about-testimonial-next',
                prevEl: '.about-testimonial-prev'
            },
            autoplay: reduceMotion ? false : {
                delay: 4500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                1200: {
                    slidesPerView: 3,
                    spaceBetween: 24
                }
            }
        });
    });
</script>

<style>
    .about-kpi-card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.2rem;
        text-align: center;
        box-shadow: 0 6px 24px rgba(15, 61, 62, 0.08);
        border: 1px solid rgba(200, 169, 81, 0.12);
    }

    .about-feature-card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.6rem;
        box-shadow: 0 6px 24px rgba(15, 61, 62, 0.08);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        border: 1px solid rgba(200, 169, 81, 0.12);
    }

    .about-feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 36px rgba(15, 61, 62, 0.16);
    }

    .about-icon-wrap {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.2rem;
        color: #C8A951;
        background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.14));
    }

    .about-step-card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 6px 24px rgba(15, 61, 62, 0.08);
        border: 1px solid rgba(200, 169, 81, 0.12);
    }

    .about-step-num {
        width: 3rem;
        height: 3rem;
        border-radius: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.9rem;
        color: #fff;
        font-weight: 700;
        background: linear-gradient(135deg, #0F3D3E 0%, #C8A951 100%);
    }

    .team-card {
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 6px 24px rgba(15, 61, 62, 0.08);
        border: 1px solid rgba(200, 169, 81, 0.12);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .team-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 36px rgba(15, 61, 62, 0.16);
    }

    .team-card-media {
        aspect-ratio: 4 / 5;
        overflow: hidden;
        border-bottom: 1px solid rgba(200, 169, 81, 0.16);
    }

    .testimonial-card {
        background: #fff;
        border-radius: 1rem;
        padding: 1.4rem;
        box-shadow: 0 6px 24px rgba(15, 61, 62, 0.08);
        border: 1px solid rgba(200, 169, 81, 0.12);
        display: flex;
        flex-direction: column;
        height: 100%;
        min-height: 260px;
    }

    .aboutTestimonialSwiper .swiper-wrapper {
        align-items: stretch;
    }

    .aboutTestimonialSwiper .swiper-slide {
        height: auto;
        display: flex;
    }

    .testimonial-avatar {
        width: 2.9rem;
        height: 2.9rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #0F3D3E 0%, #C8A951 100%);
    }

    .about-testimonial-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 42px;
        height: 42px;
        border-radius: 9999px;
        border: 1px solid rgba(200, 169, 81, 0.6);
        background: rgba(15, 61, 62, 0.92);
        color: #C8A951;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 20;
        transition: all 0.3s ease;
    }

    .about-testimonial-btn:hover {
        background: #C8A951;
        color: #0F3D3E;
        box-shadow: 0 10px 22px rgba(15, 61, 62, 0.22);
    }

    .about-testimonial-prev {
        left: -4px;
    }

    .about-testimonial-next {
        right: -4px;
    }

    .line-clamp-5 {
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-15px) rotate(3deg); }
    }

    @keyframes float-delayed {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(-3deg); }
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    .animate-float-delayed {
        animation: float-delayed 8s ease-in-out infinite;
        animation-delay: 2s;
    }

    @media (prefers-reduced-motion: reduce) {
        .about-feature-card,
        .team-card {
            transition: none;
        }

        .animate-float,
        .animate-float-delayed {
            animation: none;
        }
    }

    @media (max-width: 768px) {
        .about-testimonial-btn {
            width: 36px;
            height: 36px;
        }
    }
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>
