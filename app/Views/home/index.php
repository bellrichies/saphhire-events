<?php
$title = trans('content.home.page_title', 'Home');
ob_start();

$formatPackagePrice = static function ($pkg) {
    if (!empty($pkg['price_label'])) {
        return $pkg['price_label'];
    }

    if (isset($pkg['price_amount']) && $pkg['price_amount'] !== null && $pkg['price_amount'] !== '') {
        return ($pkg['currency'] ?? 'EUR') . ' ' . number_format((float)$pkg['price_amount'], 0);
    }

    return 'Custom Quote';
};

$getPackageImageUrl = static function ($image) {
    if (empty($image)) {
        return '';
    }

    if (preg_match('/^https?:\/\//', $image) || strpos($image, '/') === 0) {
        return $image;
    }

    return uploadedImageUrl($image);
};

$getGalleryMediaType = static function ($media) {
    $path = strtolower((string)parse_url((string)$media, PHP_URL_PATH));
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (in_array($ext, ['mp4', 'webm', 'ogg', 'ogv', 'mov'], true)) {
        return 'video';
    }

    return 'image';
};
?>

<section class="home-hero relative min-h-[72vh] md:min-h-[82vh] flex items-center pt-24 pb-16 md:pt-28 md:pb-20 overflow-hidden" style="background: linear-gradient(140deg, #0F3D3E 0%, #1C1C1C 72%);">
    <video
        class="absolute inset-0 w-full h-full object-cover opacity-85"
        autoplay
        muted
        loop
        playsinline
    >
        <source src="<?= route('/assets/images/hero.mp4') ?>" type="video/mp4">
    </video>
    <div class="absolute inset-0 bg-black/45"></div>
    <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full blur-3xl opacity-30" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-7" data-aos="fade-up">
                <span class="inline-block px-4 py-2 rounded-full mb-6 text-xs font-semibold tracking-[0.2em] uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.hero.badge', 'Premium Event Solutions')); ?>
                </span>
                <h1 class="home-hero-title text-4xl sm:text-5xl lg:text-6xl font-light text-white leading-tight mb-5" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                    <?php echo htmlspecialchars(trans('content.home.hero.title_main', 'Crafted Celebrations,')); ?>
                    <span style="color: #C8A951;"><?php echo htmlspecialchars(trans('content.home.hero.title_highlight', 'Flawless Execution')); ?></span>
                </h1>
                <p class="home-hero-copy text-base sm:text-lg text-gray-200 max-w-2xl mb-4" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.hero.description_1', 'Sapphire Events & Decorations plans and designs unforgettable moments, from private milestones to large-scale corporate occasions.')); ?>
                </p>
                <p class="home-hero-copy text-sm sm:text-base text-gray-300 max-w-2xl mb-8" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.hero.description_2', 'Our team handles concept design, vendor coordination, styling, and on-site execution so every part of your celebration feels polished, cohesive, and stress-free.')); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="<?php echo route('/contact'); ?>" class="home-hero-btn inline-flex items-center justify-center px-8 py-3.5 rounded-lg font-semibold transition-all duration-300 hover:shadow-xl" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.82rem;">
                        <?php echo htmlspecialchars(trans('content.home.hero.cta_primary', 'Plan Your Event')); ?>
                    </a>
                    <a href="<?php echo route('/services'); ?>" class="home-hero-btn inline-flex items-center justify-center px-8 py-3.5 rounded-lg font-semibold border border-white/50 text-white transition-all duration-300 hover:bg-white/10" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.82rem;">
                        <?php echo htmlspecialchars(trans('content.home.hero.cta_secondary', 'Explore Services')); ?>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5" data-aos="fade-left">
                <div class="hero-panel rounded-2xl p-5 sm:p-6">
                    <p class="text-xs uppercase tracking-[0.18em] mb-3" style="color: #C8A951; font-family: 'Montserrat', sans-serif; font-weight: 700;"><?php echo htmlspecialchars(trans('content.home.hero_panel.title', 'Why Clients Choose Us')); ?></p>
                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                        <div class="home-kpi">
                            <p class="text-3xl mb-1" style="font-family: 'Cormorant Garamond', serif; color: #C8A951;">150+</p>
                            <p class="text-xs text-gray-200" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.home.hero_panel.events_delivered', 'Events Delivered')); ?></p>
                        </div>
                        <div class="home-kpi">
                            <p class="text-3xl mb-1" style="font-family: 'Cormorant Garamond', serif; color: #C8A951;">98%</p>
                            <p class="text-xs text-gray-200" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.home.hero_panel.client_satisfaction', 'Client Satisfaction')); ?></p>
                        </div>
                        <div class="home-kpi">
                            <p class="text-3xl mb-1" style="font-family: 'Cormorant Garamond', serif; color: #C8A951;">20+</p>
                            <p class="text-xs text-gray-200" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.home.hero_panel.theme_concepts', 'Theme Concepts')); ?></p>
                        </div>
                        <div class="home-kpi">
                            <p class="text-3xl mb-1" style="font-family: 'Cormorant Garamond', serif; color: #C8A951;">24/7</p>
                            <p class="text-xs text-gray-200" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.home.hero_panel.planning_support', 'Planning Support')); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 md:py-20 px-4 max-w-7xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
        <div data-aos="fade-right">
            <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-[0.18em] uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif;">
                <?php echo htmlspecialchars(trans('content.home.about_section.badge', 'About Us')); ?>
            </span>
            <h2 class="home-section-title text-4xl md:text-5xl font-light mb-5" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.home.about_section.title', 'Design-Led Event Planning')); ?>
            </h2>
            <p class="home-section-copy text-gray-700 mb-4" style="font-family: 'Montserrat', sans-serif;">
                Welcome to Sapphire Events&mdash;where your most meaningful occasions become lasting memories. Driven by creativity and defined by excellence, we design bespoke events and breathtaking d&eacute;cor that reflect your unique style and vision.
            </p>
            <p class="home-section-copy text-gray-700 mb-4" style="font-family: 'Montserrat', sans-serif;">
                From elegant weddings to polished corporate functions and every celebration in between, our experienced team is committed to delivering events that are seamless, sophisticated, and truly unforgettable.
            </p>
            <p class="home-section-copy text-gray-700 mb-7" style="font-family: 'Montserrat', sans-serif;">
                From the first idea to the final detail, Sapphire Events ensures every moment shines. Experience the art of exceptional event design with us.
            </p>
            <a href="<?php echo route('/about'); ?>" class="inline-flex items-center px-7 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg" style="background-color: #0F3D3E; color: #fff; font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.8rem;">
                <?php echo htmlspecialchars(trans('content.home.about_section.button', 'Learn Our Story')); ?>
            </a>
        </div>

        <div class="relative" data-aos="fade-left">
            <div class="rounded-2xl overflow-hidden luxury-shadow aspect-[3/4] md:aspect-[4/5] max-w-md mx-auto md:mx-0 md:ml-auto bg-[#f3eee8]">
                <img src="<?= route('/assets/images/about-home.avif') ?>" alt="Sapphire Events team at work" class="w-full h-full object-cover object-top">
            </div>
            <div class="hidden md:block absolute -bottom-8 -left-8 bg-white rounded-xl p-5 luxury-shadow">
                <p class="text-xs uppercase tracking-[0.16em] mb-1" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.home.about_section.trusted_label', 'Trusted by Clients')); ?></p>
                <p class="text-2xl" style="font-family: 'Cormorant Garamond', serif; color: #C8A951;"><?php echo htmlspecialchars(trans('content.home.about_section.trusted_value', 'Weddings, Corporate, Private')); ?></p>
            </div>
        </div>
    </div>
</section>

<section id="core-services" class="py-16 md:py-20 px-4 overflow-hidden" style="background-color: #F8F5F2;">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-10" data-aos="fade-up">
            <div>
                <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-[0.18em] uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.core_services.badge', 'Core Services')); ?>
                </span>
                <h2 class="home-section-title text-4xl md:text-5xl font-light" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                    <?php echo htmlspecialchars(trans('content.home.core_services.title', 'End-to-End Event Expertise')); ?>
                </h2>
                <p class="home-section-copy text-gray-600 mt-3 max-w-2xl" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.core_services.description', 'Strategic planning, creative styling, and execution support tailored to your venue, audience, and event goals.')); ?>
                </p>
            </div>
            <a href="<?php echo route('/services'); ?>" class="inline-flex items-center text-sm font-semibold" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;">
                <?php echo htmlspecialchars(trans('content.home.core_services.view_all', 'View All Services')); ?> <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="relative" data-aos="fade-up" data-aos-delay="100">
            <button type="button" class="home-service-swiper-btn home-service-swiper-prev" aria-label="Previous service">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button type="button" class="home-service-swiper-btn home-service-swiper-next" aria-label="Next service">
                <i class="fas fa-chevron-right"></i>
            </button>

            <div class="swiper homeServicesSwiper">
                <div class="swiper-wrapper">
                    <?php foreach (array_slice($services ?? [], 0, 6) as $index => $service): ?>
                        <div class="swiper-slide">
                            <article class="service-scroll-card h-full rounded-2xl overflow-hidden bg-white" data-aos="fade-up" data-aos-delay="<?php echo $index * 80; ?>">
                                <div class="service-scroll-card-inner">
                                    <div class="service-scroll-image-wrap">
                                        <?php if (!empty($service['image'])): ?>
                                            <img src="<?php echo htmlspecialchars(uploadedImageUrl($service['image'])); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" class="service-scroll-image">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                                                <i class="fas fa-image text-white text-5xl opacity-30"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="service-scroll-content">
                                        <div>
                                            <h3 class="text-2xl mb-3" style="font-family: 'Cormorant Garamond', serif; color: #0F3D3E; letter-spacing: -0.02em; font-weight: 600;">
                                                <?php echo htmlspecialchars($service['title']); ?>
                                            </h3>
                                            <p class="service-card-description text-sm text-gray-600 leading-relaxed mb-4 line-clamp-4">
                                                <?php echo htmlspecialchars($service['description']); ?>
                                            </p>
                                            <div class="space-y-2 mb-5">
                                                <p class="text-xs text-gray-700 flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                                                    <i class="fas fa-check-circle" style="color: #C8A951;"></i>
                                                    <?php echo htmlspecialchars(trans('content.home.core_services.bullet_1', 'Personalized concept and event styling')); ?>
                                                </p>
                                                <p class="text-xs text-gray-700 flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                                                    <i class="fas fa-check-circle" style="color: #C8A951;"></i>
                                                    <?php echo htmlspecialchars(trans('content.home.core_services.bullet_2', 'Timeline, logistics, and vendor support')); ?>
                                                </p>
                                            </div>
                                        </div>

                                        <a href="<?php echo route('/services/' . $service['id']); ?>" class="inline-flex items-center text-xs font-semibold transition-all duration-300 hover:opacity-80" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase;">
                                            <?php echo htmlspecialchars(trans('content.home.core_services.explore', 'Explore')); ?>
                                            <i class="fas fa-arrow-right ml-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</section>

<section class="py-16 md:py-20 px-4 max-w-7xl mx-auto">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-10" data-aos="fade-up">
        <div>
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-[0.18em] uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif;">
                <?php echo htmlspecialchars(trans('content.home.portfolio.badge', 'Portfolio')); ?>
            </span>
            <h2 class="home-section-title text-4xl md:text-5xl font-light" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.home.portfolio.title', 'Featured Gallery')); ?>
            </h2>
            <p class="home-section-copy text-gray-600 mt-3 max-w-2xl" style="font-family: 'Montserrat', sans-serif;">
                <?php echo htmlspecialchars(trans('content.home.portfolio.description', 'A bold visual snapshot of our latest decor transformations, creative styling, and signature event setups.')); ?>
            </p>
        </div>
        <a href="<?php echo route('/gallery'); ?>" class="inline-flex items-center text-sm font-semibold" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;">
            <?php echo htmlspecialchars(trans('content.home.portfolio.view_all', 'View Full Gallery')); ?> <i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-10" id="gallery-grid">
        <?php foreach ($featuredGallery as $index => $item): ?>
            <?php
                $mediaPath = uploadedImageUrl($item['image'] ?? '');
                $mediaType = $getGalleryMediaType($item['image'] ?? '');
            ?>
            <article class="gallery-item group relative overflow-hidden rounded-xl luxury-shadow aspect-[3/4] cursor-pointer"
                     data-aos="zoom-in" data-aos-delay="<?php echo $index * 50; ?>"
                     data-media="<?php echo htmlspecialchars($mediaPath); ?>"
                     data-media-type="<?php echo htmlspecialchars($mediaType); ?>"
                     data-title="<?php echo htmlspecialchars($item['title']); ?>"
                     data-category="<?php echo htmlspecialchars($item['category_name'] ?? ''); ?>">
                <?php if (!empty($item['image']) && $mediaType === 'video'): ?>
                    <video
                        src="<?php echo htmlspecialchars($mediaPath); ?>"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        autoplay
                        muted
                        loop
                        playsinline
                        preload="metadata"
                    ></video>
                <?php elseif (!empty($item['image'])): ?>
                    <img src="<?php echo htmlspecialchars($mediaPath); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #0F3D3E 0%, #C8A951 100%);">
                        <i class="fas fa-image text-white text-5xl opacity-30"></i>
                    </div>
                <?php endif; ?>

                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-5 text-white">
                    <h3 class="text-2xl mb-1" style="font-family: 'Cormorant Garamond', serif; font-weight: 600;"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p class="text-xs uppercase tracking-[0.14em] text-gray-200" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars($item['category_name'] ?? ''); ?></p>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <div id="lightbox-modal" class="fixed inset-0 z-50 hidden bg-black/95 flex items-center justify-center" style="backdrop-filter: blur(4px);">
        <div class="relative w-full h-full flex items-center justify-center p-4">
            <button id="lightbox-close" class="absolute top-6 right-6 z-60 w-12 h-12 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white/10" style="color: #C8A951; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>

            <div class="relative w-full max-w-5xl mx-auto">
                <img id="lightbox-image" src="" alt="Gallery Item" class="w-full h-auto rounded-lg max-h-[80vh] object-contain">
                <video id="lightbox-video" class="hidden w-full h-auto rounded-lg max-h-[80vh] object-contain" controls playsinline></video>
                <button id="lightbox-prev" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white/10" style="color: #C8A951; font-size: 1.5rem;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="lightbox-next" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white/10" style="color: #C8A951; font-size: 1.5rem;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8 rounded-b-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 id="lightbox-title" class="text-2xl font-bold mb-2" style="color: #C8A951; font-family: 'Cormorant Garamond', serif;"></h3>
                        <p id="lightbox-category" class="text-sm text-gray-300 uppercase tracking-wider" style="font-family: 'Montserrat', sans-serif;"></p>
                    </div>
                    <div id="lightbox-counter" class="text-lg font-semibold text-gray-400" style="color: #C8A951; font-family: 'Montserrat', sans-serif;"></div>
                </div>
            </div>
        </div>
    </div>

</section>

<section id="home-packages" class="py-16 md:py-20 px-4" style="background: linear-gradient(135deg, #F8F5F2 0%, #ffffff 100%);">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-10" data-aos="fade-up">
            <div>
                <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-[0.18em] uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.packages.badge', 'Packages')); ?>
                </span>
                <h2 class="home-section-title text-4xl md:text-5xl font-light" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                    <?php echo htmlspecialchars(trans('content.home.packages.title', 'New Package Collections')); ?>
                </h2>
                <p class="home-section-copy text-gray-600 mt-3 max-w-2xl" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.packages.description', 'Explore the new package concept with clear inclusions and transparent pricing across tablescapes, proposals, and consultations.')); ?>
                </p>
            </div>
            <a href="<?php echo route('/packages'); ?>" class="inline-flex items-center text-sm font-semibold" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;">
                <?php echo htmlspecialchars(trans('content.home.packages.explore', 'Explore Packages')); ?> <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="home-package-categories flex flex-wrap gap-3 mb-8" data-aos="fade-up" data-aos-delay="80">
            <?php foreach (($packageCategories ?? []) as $category): ?>
                <a href="<?php echo route('/packages/' . $category['slug']); ?>" class="inline-flex items-center px-4 py-2 rounded-full text-xs font-semibold transition-all duration-300 hover:shadow-md" style="background-color: rgba(15, 61, 62, 0.08); color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;">
                    <?php echo htmlspecialchars($category['name']); ?> (<?php echo (int)($category['package_count'] ?? 0); ?>)
                </a>
            <?php endforeach; ?>
        </div>

        <div class="swiper homePackagesSwiper" data-aos="fade-up" data-aos-delay="120">
            <div class="swiper-wrapper">
                <?php foreach (($featuredPackages ?? []) as $package): ?>
                    <div class="swiper-slide">
                        <article class="home-package-card h-full rounded-2xl overflow-hidden bg-white">
                            <div class="relative aspect-[3/4] overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                                <?php $packageImage = $getPackageImageUrl($package['image'] ?? null); ?>
                                <?php if (!empty($packageImage)): ?>
                                    <img src="<?php echo htmlspecialchars($packageImage); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-gem text-white text-5xl opacity-30"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(200, 169, 81, 0.95); color: #0F3D3E;">
                                    <?php echo htmlspecialchars($package['category_name'] ?? trans('content.home.packages.category_fallback', 'Package')); ?>
                                </span>
                            </div>
                            <div class="p-5 sm:p-6">
                                <h3 class="text-xl mb-2" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; font-weight: 600; letter-spacing: -0.02em;">
                                    <?php echo htmlspecialchars($package['title']); ?>
                                </h3>
                                <p class="text-2xl mb-4" style="color: #C8A951; font-family: 'Cormorant Garamond', serif; font-weight: 700;">
                                    <?php echo htmlspecialchars($formatPackagePrice($package)); ?>
                                </p>
                                <p class="text-sm text-gray-600 line-clamp-3 mb-5"><?php echo htmlspecialchars($package['description']); ?></p>
                                <a href="<?php echo route('/packages/' . ($package['category_slug'] ?? '')); ?>" class="inline-flex items-center text-sm font-semibold" style="color: #0F3D3E; letter-spacing: 0.06em; text-transform: uppercase;">
                                    <?php echo htmlspecialchars(trans('content.home.packages.card_explore', 'Explore')); ?> <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (empty($featuredPackages ?? [])): ?>
            <div class="text-center py-8 text-gray-500"><?php echo htmlspecialchars(trans('content.home.packages.empty', 'Package highlights will appear here once published.')); ?></div>
        <?php endif; ?>
    </div>
</section>

<section class="py-16 md:py-20 px-4" style="background-color: #F8F5F2;">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12 md:mb-16" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold uppercase tracking-wider mb-4" style="background: rgba(200, 169, 81, 0.15); color: #C8A951; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.home.testimonials.badge', 'Testimonials')); ?></span>
            <h2 class="home-section-title text-4xl md:text-5xl mb-4 text-[#0F3D3E]" style="font-family: 'Cormorant Garamond', serif; font-weight: 600;"><?php echo htmlspecialchars(trans('content.home.testimonials.title', 'Client Stories')); ?></h2>
            <p class="home-section-copy text-base sm:text-lg text-gray-600 max-w-2xl mx-auto" style="font-family: 'Montserrat', sans-serif;">
                <?php echo htmlspecialchars(trans('content.home.testimonials.description', 'Hear from clients who trusted us to bring their vision to life.')); ?>
            </p>
        </div>

        <?php
            $featuredTestimonials = array_slice($testimonials ?? [], 0, 3);
        ?>

        <?php if (!empty($featuredTestimonials)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                <?php foreach ($featuredTestimonials as $index => $testimonial): ?>
                    <?php
                        $name = trim((string)($testimonial['name'] ?? trans('content.home.testimonials.default_name', 'Client')));
                        $initials = '';
                        foreach (preg_split('/\s+/', $name) as $part) {
                            if ($part !== '') {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }
                            if (strlen($initials) >= 2) {
                                break;
                            }
                        }
                        if ($initials === '') {
                            $initials = 'CL';
                        }
                    ?>
                    <article class="bg-white rounded-2xl p-6 sm:p-8 shadow-lg hover:shadow-xl transition-shadow relative" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="absolute -top-4 left-8 text-6xl text-[#C8A951]/20" style="font-family: 'Cormorant Garamond', serif;">"</div>
                        <div class="flex gap-1 mb-4" aria-label="Five-star rating">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <svg class="w-5 h-5 text-[#C8A951]" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <?php endfor; ?>
                        </div>
                        <p class="text-gray-700 mb-6 leading-relaxed italic line-clamp-4" style="font-family: 'Montserrat', sans-serif;">
                            "<?php echo htmlspecialchars($testimonial['content'] ?? trans('content.home.testimonials.default_content', 'Sapphire Events made our celebration seamless, elegant, and unforgettable.')); ?>"
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#0F3D3E] to-[#C8A951] flex items-center justify-center text-white font-bold"><?php echo htmlspecialchars($initials); ?></div>
                            <div>
                                <p class="font-bold text-[#0F3D3E]" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars($name); ?></p>
                                <p class="text-sm text-gray-500" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.home.testimonials.verified_client', 'Verified Client')); ?></p>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <p class="text-gray-600" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.home.testimonials.empty', 'Client testimonials will appear here soon.')); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="py-16 md:py-20 px-4 text-center" style="background: linear-gradient(135deg, #F8F5F2 0%, #ffffff 100%);">
    <div class="max-w-3xl mx-auto" data-aos="fade-up">
        <h2 class="home-section-title text-4xl md:text-5xl font-light mb-5" style="font-family: 'Cormorant Garamond', serif; color: #0F3D3E; letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.home.cta.title', 'Ready to Plan Your Next Event?')); ?>
        </h2>
        <p class="home-section-copy text-gray-700 mb-8" style="font-family: 'Montserrat', sans-serif;">
            <?php echo htmlspecialchars(trans('content.home.cta.description', 'Tell us your goals, timeline, and style preferences. We will turn them into an event experience your guests remember.')); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center justify-center px-8 py-3.5 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg" style="background-color: #0F3D3E; color: white; font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.82rem;">
                <?php echo htmlspecialchars(trans('content.home.cta.primary', 'Book Consultation')); ?>
            </a>
            <a href="<?php echo route('/services'); ?>" class="inline-flex items-center justify-center px-8 py-3.5 rounded-lg font-semibold border border-[#0F3D3E] transition-all duration-300 hover:bg-[#0F3D3E] hover:text-white" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.82rem;">
                <?php echo htmlspecialchars(trans('content.home.cta.secondary', 'View Service Plans')); ?>
            </a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (document.querySelector('.homeServicesSwiper')) {
            new Swiper('.homeServicesSwiper', {
                slidesPerView: 1.12,
                spaceBetween: 16,
                speed: 900,
                loop: true,
                allowTouchMove: true,
                keyboard: {
                    enabled: true,
                    onlyInViewport: true
                },
                navigation: {
                    nextEl: '.home-service-swiper-next',
                    prevEl: '.home-service-swiper-prev'
                },
                autoplay: reduceMotion ? false : {
                    delay: 2800,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1.3,
                        spaceBetween: 18
                    },
                    768: {
                        slidesPerView: 1.5,
                        spaceBetween: 20
                    },
                    1024: {
                        slidesPerView: 1.85,
                        spaceBetween: 22
                    },
                    1280: {
                        slidesPerView: 2.1,
                        spaceBetween: 24
                    }
                }
            });
        }

        new Swiper('.homePackagesSwiper', {
            slidesPerView: 1.08,
            spaceBetween: 16,
            speed: 850,
            loop: true,
            autoplay: reduceMotion ? false : {
                delay: 3200,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },
            breakpoints: {
                640: {
                    slidesPerView: 1.35,
                    spaceBetween: 16
                },
                900: {
                    slidesPerView: 1.8,
                    spaceBetween: 16
                },
                1200: {
                    slidesPerView: 4,
                    spaceBetween: 16
                }
            }
        });
    });
</script>

<style>
    .hero-panel {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.03) 100%);
        border: 1px solid rgba(255, 255, 255, 0.14);
        backdrop-filter: blur(6px);
    }

    .home-kpi {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 0.75rem;
        padding: 0.85rem;
    }

    .service-scroll-card {
        box-shadow: 0 8px 28px rgba(15, 61, 62, 0.12);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        height: 372px;
    }

    .service-scroll-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 44px rgba(15, 61, 62, 0.18);
    }

    .service-scroll-card-inner {
        height: 100%;
        display: grid;
        grid-template-columns: 52% 48%;
    }

    .service-scroll-image-wrap {
        overflow: hidden;
        height: 100%;
    }

    .service-scroll-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .service-scroll-card:hover .service-scroll-image {
        transform: scale(1.06);
    }

    .service-scroll-content {
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 0.9rem;
    }

    .home-service-swiper-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
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

    .home-service-swiper-btn:hover {
        background: #C8A951;
        color: #0F3D3E;
        box-shadow: 0 10px 22px rgba(15, 61, 62, 0.22);
    }

    .home-service-swiper-prev {
        left: -6px;
    }

    .home-service-swiper-next {
        right: -6px;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .gallery-item {
        border: 1px solid rgba(15, 61, 62, 0.08);
        box-shadow: 0 12px 30px rgba(15, 61, 62, 0.14);
    }

    .gallery-item:hover {
        box-shadow: 0 18px 42px rgba(15, 61, 62, 0.22);
    }

    .home-package-card {
        box-shadow: 0 4px 24px rgba(15, 61, 62, 0.08);
        transition: transform 0.5s ease, box-shadow 0.5s ease;
    }

    .home-package-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 24px 46px rgba(15, 61, 62, 0.16);
    }

    .line-clamp-4 {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    @media (max-width: 640px) {
        .home-hero {
            min-height: auto;
            padding-top: 6rem;
            padding-bottom: 2.75rem;
        }

        .home-hero-title,
        .home-section-title {
            font-size: 2rem;
            line-height: 1.1;
        }

        .home-hero-copy,
        .home-section-copy {
            font-size: 0.95rem;
            line-height: 1.65;
        }

        .home-hero-btn {
            width: 100%;
            justify-content: center;
        }

        .home-kpi {
            padding: 0.7rem;
        }

        .home-kpi p.text-3xl {
            font-size: 1.65rem;
            line-height: 1;
        }

        .service-scroll-card {
            height: auto;
            min-height: 370px;
        }

        .service-scroll-card-inner {
            grid-template-columns: 1fr;
            grid-template-rows: auto 1fr;
            height: auto;
        }

        .service-scroll-image-wrap {
            height: 180px;
        }

        .service-scroll-content {
            padding: 1rem;
            gap: 0.65rem;
            justify-content: flex-start;
        }

        .service-card-description {
            display: block;
            -webkit-line-clamp: unset;
            -webkit-box-orient: unset;
            overflow: visible;
        }

        .home-service-swiper-btn {
            width: 38px;
            height: 38px;
        }

        .home-package-categories {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 0.35rem;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }

        .home-package-categories::-webkit-scrollbar {
            display: none;
        }

        .home-package-categories > a {
            flex: 0 0 auto;
            white-space: nowrap;
        }

        #gallery-grid .gallery-item {
            aspect-ratio: 4 / 5;
        }

        #lightbox-modal .absolute.bottom-0 {
            padding: 1rem 1rem 1.25rem;
        }

        #lightbox-title {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
        }

        #lightbox-category {
            font-size: 0.72rem;
        }

        #lightbox-counter {
            font-size: 0.9rem;
        }

        .line-clamp-4 {
            -webkit-line-clamp: 3;
        }

        #lightbox-prev,
        #lightbox-next {
            display: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .service-scroll-card,
        .service-scroll-image,
        .home-package-card {
            transition: none;
        }
    }
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>
