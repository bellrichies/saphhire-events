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
    return uploadedImageUrl($image);
};

$getYoutubeEmbedUrl = static function (?string $value): string {
    if (!$value || !preg_match('/^https?:\/\//i', $value)) {
        return '';
    }

    $parts = parse_url($value);
    $host = strtolower((string)($parts['host'] ?? ''));
    $path = (string)($parts['path'] ?? '');
    parse_str((string)($parts['query'] ?? ''), $query);
    $videoId = '';

    if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
        $videoId = trim($path, '/');
    } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
        if ($path === '/watch') {
            $videoId = (string)($query['v'] ?? '');
        } elseif (str_starts_with($path, '/shorts/') || str_starts_with($path, '/embed/')) {
            $segments = explode('/', trim($path, '/'));
            $videoId = $segments[1] ?? '';
        }
    }

    $videoId = preg_replace('/[^a-zA-Z0-9_-]/', '', $videoId ?? '');
    return $videoId ? 'https://www.youtube.com/embed/' . $videoId : '';
};

$getYoutubeThumbnailUrl = static function (?string $value) use ($getYoutubeEmbedUrl): string {
    $embed = $getYoutubeEmbedUrl($value);
    if ($embed === '') {
        return '';
    }

    $videoId = basename((string)parse_url($embed, PHP_URL_PATH));
    return $videoId !== '' ? 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg' : '';
};

$getGalleryMediaType = static function ($media) use ($getYoutubeEmbedUrl) {
    if ($media && $getYoutubeEmbedUrl($media) !== '') {
        return 'youtube';
    }

    $path = strtolower((string)parse_url((string)$media, PHP_URL_PATH));
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (in_array($ext, ['mp4', 'webm', 'ogg', 'ogv', 'mov'], true)) {
        return 'video';
    }

    return 'image';
};

$heroPoster = route('/assets/images/about-home.avif');
$heroVideo = route('/assets/images/hero.mp4');
?>

<section
    class="home-hero relative min-h-[72vh] md:min-h-[82vh] flex items-center pt-24 pb-16 md:pt-28 md:pb-20 overflow-hidden"
    style="background: linear-gradient(140deg, #0F3D3E 0%, #1C1C1C 72%);"
    aria-labelledby="home-hero-title"
    aria-describedby="home-hero-description-primary home-hero-description-secondary"
>
    <img
        src="<?= htmlspecialchars($heroPoster); ?>"
        alt=""
        class="absolute inset-0 w-full h-full object-cover"
        fetchpriority="high"
        loading="eager"
        decoding="async"
        aria-hidden="true"
    >
    <video
        class="home-hero-video absolute inset-0 w-full h-full object-cover "
        muted
        loop
        playsinline
        preload="none"
        poster="<?= htmlspecialchars($heroPoster); ?>"
        data-src="<?= htmlspecialchars($heroVideo); ?>"
        aria-hidden="true"
    >
    </video>
    <div class="absolute inset-0 bg-black/30"></div>
    <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full blur-3xl opacity-30" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            <div class="lg:col-span-7">
                <span class="inline-block px-4 py-2 rounded-full mb-6 text-xs font-semibold tracking-[0.2em] uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.hero.badge', 'Premium Event Solutions')); ?>
                </span>
                <h1 id="home-hero-title" class="home-hero-title text-4xl sm:text-5xl lg:text-6xl font-light text-white leading-tight mb-5" style="font-family: 'Dancing Script', cursive; letter-spacing: -0.02em;">
                    <?php echo htmlspecialchars(trans('content.home.hero.title_main', 'Crafted Celebrations,')); ?>
                    <span style="color: #C8A951;"><?php echo htmlspecialchars(trans('content.home.hero.title_highlight', 'Flawless Execution')); ?></span>
                </h1>
                <p id="home-hero-description-primary" class="home-hero-copy text-base sm:text-lg text-gray-200 max-w-2xl mb-4" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.hero.description_1', 'Sapphire Events & Decorations plans and designs unforgettable moments, from private milestones to large-scale corporate occasions.')); ?>
                </p>
                <p id="home-hero-description-secondary" class="home-hero-copy text-sm sm:text-base text-gray-100 max-w-2xl mb-8" style="font-family: 'Montserrat', sans-serif;">
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

            <div class="hidden lg:block lg:col-span-5">
                <aside class="hero-panel rounded-2xl p-5 sm:p-6" aria-labelledby="home-hero-panel-title">
                    <p id="home-hero-panel-title" class="text-xs uppercase tracking-[0.18em] mb-3" style="color: #C8A951; font-family: 'Montserrat', sans-serif; font-weight: 700;"><?php echo htmlspecialchars(trans('content.home.hero_panel.title', 'Why Clients Choose Us')); ?></p>
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
                </aside>
            </div>
        </div>
    </div>
</section>
<section class="home-deferred-section py-16 md:py-12 overflow-hidden" aria-labelledby="home-about-title">
    <div class="w-full" style="background-color: #F6CCF0;">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-stretch">
            <div class="relative" data-aos="fade-right">
                <div class="about-feature-image overflow-hidden luxury-shadow bg-[#f3eee8]">
                    <img src="<?= route('/assets/images/about-home.avif') ?>" alt="Sapphire Events decor setup" class="w-full h-full object-cover object-center" loading="lazy" decoding="async">
                </div>
                <div class="hidden lg:block absolute -bottom-6 -right-6 bg-white rounded-xl p-5 luxury-shadow">
                    <p class="text-xs text-gray-700 font-bold uppercase tracking-[0.16em] mb-1" style=""><?php echo htmlspecialchars(trans('content.home.about_section.trusted_label', 'Trusted by Clients')); ?></p>
                    <p class="text-2xl text-gray-700" style=""><?php echo htmlspecialchars(trans('content.home.about_section.trusted_value', 'Weddings, Corporate, Private')); ?></p>
                </div>
            </div>

            <div class="py-2 lg:py-6 px-4 sm:px-6 lg:px-8" data-aos="fade-left">
                <div class="about-content-panel h-full flex flex-col justify-center">
                    <h2 id="home-about-title" class="home-section-title text-4xl md:text-5xl font-light mb-8 justify-center" style="color: #0F3D3E;  letter-spacing: -0.02em;">
                        <?php echo htmlspecialchars(trans('content.home.about_section.title', 'Let Us Plan & <br> Decorate Your Next Event')); ?>
                    </h2>

                    <div class="about-content-grid grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 items-stretch">
                        <div class="about-column-card about-copy-card">
                            <div class="about-copy max-w-xl">
                                <p class="home-section-copy text-gray-700 mb-4" style="font-family: 'Montserrat', sans-serif;">
                                    <?php echo htmlspecialchars(trans('content.home.about_section.paragraph_1', 'We combine creative direction, meticulous planning, and production precision to create experiences that feel elegant and effortless.')); ?>
                                </p>
                                <p class="home-section-copy text-gray-700 mb-4" style="font-family: 'Montserrat', sans-serif;">
                                    <?php echo htmlspecialchars(trans('content.home.about_section.paragraph_2', 'Every event is customized to your audience, space, and goals, with a dedicated team from initial concept through day-of execution.')); ?>
                                </p>
                                <p class="home-section-copy text-gray-700 mb-0" style="font-family: 'Montserrat', sans-serif;">
                                    <?php echo htmlspecialchars(trans('content.home.about_section.paragraph_3', 'From planning to decorating, we deliver polished design, reliable coordination, and the elevated finish your day deserves.')); ?>
                                </p>
                            </div>
                        </div>

                        <div class="about-column-card about-ceo-card">
                            <div class="about-ceo-media overflow-hidden">
                                <img src="<?= route('/assets/images/founder-ceo.avif') ?>" alt="CEO of Sapphire Events & Decorations" class="w-full h-full object-cover object-center lg:object-left" loading="lazy" decoding="async">
                            </div>
                        </div>
                    </div>
                    <div class="about-cta-group flex flex-col sm:flex-row gap-3 pt-6 text-center">
                        <a href="<?php echo route('/packages'); ?>" class="inline-flex items-center justify-center px-7 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg" style="background-color: #0F3D3E; color: #fff; font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.8rem;">
                            <?php echo htmlspecialchars(trans('content.home.about_section.button_packages', 'Select Our Package')); ?>
                        </a>
                        <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center justify-center px-7 py-3 rounded-lg font-semibold border border-[#0F3D3E] transition-all duration-300 hover:bg-[#0F3D3E] hover:text-white" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.8rem;">
                            <?php echo htmlspecialchars(trans('content.home.about_section.button_contact', 'Contact Us')); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="core-services" class="home-deferred-section py-16 md:py-20 px-4 overflow-hidden" style="background-color: #F8F5F2;" aria-labelledby="home-services-title">
    <div class="w-full">
            <div class="md:max-w-7xl mx-auto flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-10" data-aos="fade-up">
                <div>
                    <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-[0.18em] uppercase" style="background-color: rgba(15, 61, 62, 0.1); font-family: 'Montserrat', sans-serif;">
                        <?php echo htmlspecialchars(trans('content.home.core_services.badge', 'What we do')); ?>
                    </span>
                    <h2 id="home-services-title" class="home-section-title text-4xl md:text-5xl font-light" style="color: #0F3D3E; font-family: 'Dancing Script', cursive; letter-spacing: -0.02em;">
                        <?php echo htmlspecialchars(trans('content.home.core_services.title', 'Services')); ?>
                    </h2>
                    <p class="home-section-copy text-gray-600 mt-3 max-w-2xl" style="font-family: 'Montserrat', sans-serif;">
                        <?php echo htmlspecialchars(trans('content.home.core_services.description', 'Strategic planning, creative styling, and execution support tailored to your venue, audience, and event goals.')); ?>
                    </p>
                </div>
                <a href="<?php echo route('/services'); ?>" class="inline-flex min-h-[48px] items-center rounded-lg px-3 py-3 text-sm font-semibold" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;">
                    <?php echo htmlspecialchars(trans('content.home.core_services.view_all', 'View All Services')); ?> <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
                </a>
            </div>

            <div class="relative" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper homeServicesSwiper" role="region" aria-roledescription="carousel" aria-label="<?php echo htmlspecialchars(trans('content.home.core_services.carousel_label', 'Featured services')); ?>" aria-live="off">
                    <div class="swiper-wrapper">
                        <?php $featuredServices = array_slice($services ?? [], 0, 6); ?>
                        <?php $featuredServiceCount = count($featuredServices); ?>
                        <?php foreach ($featuredServices as $index => $service): ?>
                            <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="<?php echo htmlspecialchars(($index + 1) . ' of ' . $featuredServiceCount); ?>">
                                <article class="service-scroll-card h-full  overflow-hidden bg-white" data-aos="fade-up" data-aos-delay="<?php echo $index * 80; ?>">
                                    <div class="service-scroll-card-inner">
                                        <div class="service-scroll-image-wrap">
                                            <?php if (!empty($service['image'])): ?>
                                                <img src="<?php echo htmlspecialchars(uploadedImageUrl($service['image'])); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" class="service-scroll-image" loading="lazy" decoding="async">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                                                    <i class="fas fa-image text-white text-5xl opacity-30" aria-hidden="true"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="service-scroll-content">
                                            <div>
                                                <h3 class="text-2xl mb-2" style="font-family: 'Dancing Script', cursive; color: #0F3D3E; letter-spacing: -0.02em; font-weight: 600;">
                                                    <?php echo htmlspecialchars($service['title']); ?>
                                                </h3>
                                                <p class="service-card-description text-sm text-gray-600"><?php echo nl2br(htmlspecialchars(trim((string)$service['description']))); ?></p>
                                            </div>

                                            <a href="<?php echo route('/services/' . $service['id']); ?>" class="inline-flex min-h-[48px] items-center rounded-lg px-2 py-3 text-xs font-semibold transition-all duration-300 hover:opacity-80" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.08em; text-transform: uppercase;" aria-label="<?php echo htmlspecialchars(trans('content.home.core_services.explore_service', 'Explore service') . ': ' . $service['title']); ?>">
                                                <?php echo htmlspecialchars(trans('content.home.core_services.explore', 'Explore')); ?>
                                                <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
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
    </div>
</section>
<section class="home-deferred-section py-16 md:py-20" aria-labelledby="home-portfolio-title">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="md:max-w-7xl mx-auto flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-10" data-aos="fade-up">
            <div>
                <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-[0.18em] uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.portfolio.badge', 'Portfolio')); ?>
                </span>
                <h2 id="home-portfolio-title" class="home-section-title text-4xl md:text-5xl font-light" style="color: #0F3D3E; font-family: 'Dancing Script', cursive; letter-spacing: -0.02em;">
                    <?php echo htmlspecialchars(trans('content.home.portfolio.title', 'Featured Gallery')); ?>
                </h2>
                <p class="home-section-copy text-gray-600 mt-3 max-w-2xl" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.portfolio.description', 'A bold visual snapshot of our latest decor transformations, creative styling, and signature event setups.')); ?>
                </p>
            </div>
            <a href="<?php echo route('/gallery'); ?>" class="inline-flex min-h-[48px] items-center rounded-lg px-3 py-3 text-sm font-semibold" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;">
                <?php echo htmlspecialchars(trans('content.home.portfolio.view_all', 'View Full Gallery')); ?> <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
            </a>
        </div>

        <p id="gallery-grid-instructions" class="sr-only">
            <?php echo htmlspecialchars(trans('content.home.portfolio.instructions', 'Open a gallery item to view it in a dialog. Press Enter or Space on a focused item to open it.')); ?>
        </p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-5 mb-10" id="gallery-grid" aria-describedby="gallery-grid-instructions">
        <?php foreach ($featuredGallery as $index => $item): ?>
            <?php
                $mediaPath = $getGalleryMediaType($item['image'] ?? '') === 'youtube'
                    ? $getYoutubeEmbedUrl($item['image'] ?? '')
                    : uploadedImageUrl($item['image'] ?? '');
                $mediaType = $getGalleryMediaType($item['image'] ?? '');
            ?>
            <article class="gallery-item group relative overflow-hidden luxury-shadow aspect-[3/4] cursor-pointer"
                     data-aos="zoom-in" data-aos-delay="<?php echo $index * 50; ?>"
                     data-media="<?php echo htmlspecialchars($mediaPath); ?>"
                     data-media-type="<?php echo htmlspecialchars($mediaType); ?>"
                     data-title="<?php echo htmlspecialchars($item['title']); ?>"
                     data-category="<?php echo htmlspecialchars($item['category_name'] ?? ''); ?>"
                     tabindex="0"
                     role="button"
                     aria-haspopup="dialog"
                     aria-controls="lightbox-modal"
                     aria-describedby="gallery-grid-instructions"
                     aria-label="<?php echo htmlspecialchars(trans('content.home.portfolio.open_item', 'Open gallery item') . ': ' . $item['title']); ?>">
                <?php if (!empty($item['image']) && $mediaType === 'youtube'): ?>
                    <img src="<?php echo htmlspecialchars($getYoutubeThumbnailUrl($item['image'] ?? '')); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                    <div class="gallery-video-overlay pointer-events-none absolute inset-0 flex items-center justify-center bg-gradient-to-t from-black/28 via-black/6 to-transparent transition-opacity duration-300">
                        <span class="gallery-play-button flex items-center justify-center rounded-full bg-red-600/90 border-red-500">
                            <i class="fab fa-youtube text-white" aria-hidden="true"></i>
                        </span>
                    </div>
                <?php elseif (!empty($item['image']) && $mediaType === 'video'): ?>
                    <video
                        src="<?php echo htmlspecialchars($mediaPath); ?>"
                        class="gallery-card-video absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        muted
                        playsinline
                        preload="metadata"
                        loop
                        data-playing="false"
                        aria-hidden="true"
                    ></video>
                    <div class="gallery-video-overlay pointer-events-none absolute inset-0 flex items-center justify-center bg-gradient-to-t from-black/28 via-black/6 to-transparent transition-opacity duration-300">
                        <span class="gallery-play-button flex items-center justify-center rounded-full">
                            <i class="fas fa-play" aria-hidden="true"></i>
                        </span>
                    </div>
                <?php elseif (!empty($item['image'])): ?>
                    <img src="<?php echo htmlspecialchars($mediaPath); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async">
                <?php else: ?>
                    <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #0F3D3E 0%, #C8A951 100%);">
                        <i class="fas fa-image text-white text-5xl opacity-30" aria-hidden="true"></i>
                    </div>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
        </div>
    </div>
</section>
<div id="lightbox-modal" class="fixed inset-0 z-50 hidden bg-black/95 items-center justify-center" style="backdrop-filter: blur(4px);" role="dialog" aria-modal="true" aria-hidden="true" aria-labelledby="lightbox-title" aria-describedby="lightbox-category" data-lightbox-managed="page">
    <div class="relative w-full h-full flex items-center justify-center p-4">
        <button id="lightbox-close" type="button" class="absolute top-6 right-6 z-50 w-12 h-12 min-w-[48px] min-h-[48px] rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white/10" style="color: #C8A951; font-size: 1.5rem;" aria-label="<?php echo htmlspecialchars(trans('content.home.portfolio.close_dialog', 'Close gallery dialog')); ?>">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
        <div class="relative w-full max-w-5xl mx-auto">
            <img id="lightbox-image" alt="" class="hidden w-full h-auto rounded-lg max-h-[80vh] object-contain">
            <video id="lightbox-video" class="hidden w-full h-auto rounded-lg max-h-[80vh] object-contain" controls playsinline></video>
            <iframe id="lightbox-youtube" src="" class="hidden w-full h-[80vh] rounded-lg max-h-[80vh] bg-black" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen title="YouTube video"></iframe>
            <button id="lightbox-prev" type="button" class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-12 w-12 h-12 min-w-[48px] min-h-[48px] rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white/10" style="color: #C8A951; font-size: 1.5rem;" aria-label="<?php echo htmlspecialchars(trans('content.home.portfolio.previous_item', 'Previous gallery item')); ?>">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <button id="lightbox-next" type="button" class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-12 w-12 h-12 min-w-[48px] min-h-[48px] rounded-full flex items-center justify-center transition-all duration-300 hover:bg-white/10" style="color: #C8A951; font-size: 1.5rem;" aria-label="<?php echo htmlspecialchars(trans('content.home.portfolio.next_item', 'Next gallery item')); ?>">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8 rounded-b-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h3 id="lightbox-title" class="text-2xl font-bold mb-2" style="color: #C8A951; font-family: 'Dancing Script', cursive;"></h3>
                    <p id="lightbox-category" class="text-sm text-gray-300 uppercase tracking-wider" style="font-family: 'Montserrat', sans-serif;"></p>
                </div>
                <div id="lightbox-counter" class="text-lg font-semibold text-gray-400" style="color: #C8A951; font-family: 'Montserrat', sans-serif;"></div>
            </div>
        </div>
    </div>
</div>
<section id="home-packages" class="home-deferred-section py-16 md:py-20 px-4" style="background: linear-gradient(135deg, #F8F5F2 0%, #ffffff 100%);" aria-labelledby="home-packages-title">
    <div class="w-full ">
        <div class="md:max-w-7xl mx-auto flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-10" data-aos="fade-up">
            <div>
                <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-[0.18em] uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.packages.badge', 'Packages')); ?>
                </span>
                <h2 id="home-packages-title" class="home-section-title text-4xl md:text-5xl font-light" style="color: #0F3D3E; font-family: 'Dancing Script', cursive; letter-spacing: -0.02em;">
                    <?php echo htmlspecialchars(trans('content.home.packages.title', 'New Package Collections')); ?>
                </h2>
                <p class="home-section-copy text-gray-600 mt-3 max-w-2xl" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.home.packages.description', 'Explore the new package concept with clear inclusions and transparent pricing across tablescapes, proposals, and consultations.')); ?>
                </p>
            </div>
            <a href="<?php echo route('/packages'); ?>" class="inline-flex min-h-[48px] items-center rounded-lg px-3 py-3 text-sm font-semibold" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;">
                <?php echo htmlspecialchars(trans('content.home.packages.explore', 'Explore Packages')); ?> <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
            </a>
        </div>

        <div class="md:max-w-7xl mx-auto home-package-categories flex flex-wrap gap-4 mb-10" data-aos="fade-up" data-aos-delay="80">
            <?php foreach (($packageCategories ?? []) as $category): ?>
                <?php $packageCategoryImage = $getPackageImageUrl($category['image'] ?? null); ?>
                <a href="<?php echo route('/packages/' . $category['slug']); ?>" class="inline-flex min-h-[48px] items-center gap-3 px-5 py-3 rounded-full text-xs font-semibold transition-all duration-300 hover:shadow-md" style="background-color: rgba(15, 61, 62, 0.12); color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;">
                    <?php if ($packageCategoryImage): ?>
                        <img src="<?php echo htmlspecialchars($packageCategoryImage); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" class="w-9 h-9 rounded-full object-cover border border-white/70" loading="lazy" decoding="async">
                    <?php else: ?>
                        <span class="w-9 h-9 rounded-full flex items-center justify-center text-[11px] text-white" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                            <i class="fas fa-image" aria-hidden="true"></i>
                        </span>
                    <?php endif; ?>
                    <span><?php echo htmlspecialchars($category['name']); ?> (<?php echo (int)($category['package_count'] ?? 0); ?>)</span>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="swiper homePackagesSwiper" data-aos="fade-up" data-aos-delay="120" role="region" aria-roledescription="carousel" aria-label="<?php echo htmlspecialchars(trans('content.home.packages.carousel_label', 'Featured packages')); ?>" aria-live="off">
            <div class="swiper-wrapper">
                <?php $featuredPackageCount = count($featuredPackages ?? []); ?>
                <?php foreach (($featuredPackages ?? []) as $index => $package): ?>
                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="<?php echo htmlspecialchars(($index + 1) . ' of ' . $featuredPackageCount); ?>">
                        <article class="home-package-card h-full rounded-2xl overflow-hidden bg-white">
                            <div class="relative aspect-[3/4] overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                                <?php $packageImage = $getPackageImageUrl($package['image'] ?? null); ?>
                                <?php if (!empty($packageImage)): ?>
                                    <img src="<?php echo htmlspecialchars($packageImage); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>" class="w-full h-full object-cover" loading="lazy" decoding="async">
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-gem text-white text-5xl opacity-30" aria-hidden="true"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(200, 169, 81, 0.95); color: #0F3D3E;">
                                    <?php echo htmlspecialchars($package['category_name'] ?? trans('content.home.packages.category_fallback', 'Package')); ?>
                                </span>
                            </div>
                            <div class="p-5 sm:p-6">
                                <h3 class="text-xl font-bold mb-2" style="color: #0F3D3E; font-family: 'Dancing Script', cursive; font-weight: 600; letter-spacing: -0.02em;">
                                    <?php echo htmlspecialchars($package['title']); ?>
                                </h3>
                                <p class="text-xl mb-4" style="color: #C8A951; font-family: 'Cormorant Garamond', serif; font-weight: 700;">
                                    <?php echo htmlspecialchars($formatPackagePrice($package)); ?>
                                </p>
                                <a href="<?php echo route('/packages/' . ($package['category_slug'] ?? '')); ?>" class="inline-flex min-h-[48px] items-center rounded-lg px-2 py-3 text-sm font-semibold" style="color: #0F3D3E; letter-spacing: 0.06em; text-transform: uppercase;" aria-label="<?php echo htmlspecialchars(trans('content.home.packages.open_package_category', 'Explore package category') . ': ' . ($package['title'] ?? '')); ?>">
                                    <?php echo htmlspecialchars(trans('content.home.packages.card_explore', 'Explore')); ?> <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
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
<section class="home-deferred-section py-8 md:py-10 px-4 md:px-12" style="background-color: #F8F5F2;" aria-labelledby="home-testimonials-title">
    <div class="site-container">
        <div class="text-center mb-6 md:mb-8" data-aos="fade-up">
            <h2 id="home-testimonials-title" class="home-section-title text-2xl md:text-3xl mb-2 text-[#0F3D3E]" style="font-family: 'Dancing Script', cursive; font-weight: 600;"><?php echo htmlspecialchars(trans('content.home.testimonials.title', 'Client Stories')); ?></h2>
            <p class="home-section-copy text-sm text-gray-600 max-w-xl mx-auto" style="font-family: 'Montserrat', sans-serif;">
                <?php echo htmlspecialchars(trans('content.home.testimonials.description', 'Hear from clients who trusted us to bring their vision to life.')); ?>
            </p>
        </div>

        <?php
            $featuredTestimonials = array_slice($testimonials ?? [], 0, 3);
        ?>

        <?php if (!empty($featuredTestimonials)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-5">
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
                    <article class="bg-white rounded-2xl p-4 sm:p-5 shadow-md hover:shadow-lg transition-shadow relative" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                        <div class="absolute -top-3 left-6 text-5xl text-[#C8A951]/20" style="font-family: 'Cormorant Garamond', serif;">"</div>
                        <div class="flex gap-1 mb-2" role="img" aria-label="<?php echo htmlspecialchars(trans('content.home.testimonials.rating_label', '5 out of 5 stars')); ?>">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <svg class="w-4 h-4 text-[#C8A951]" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <?php endfor; ?>
                        </div>
                        <p class="text-gray-700 mb-3 leading-relaxed italic line-clamp-4 text-xs" style="font-family: 'Montserrat', sans-serif;">
                            "<?php echo htmlspecialchars($testimonial['content'] ?? trans('content.home.testimonials.default_content', 'Sapphire Events made our celebration seamless, elegant, and unforgettable.')); ?>"
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#0F3D3E] to-[#C8A951] flex items-center justify-center text-white font-bold text-xs"><?php echo htmlspecialchars($initials); ?></div>
                            <div>
                                <p class="font-bold text-[#0F3D3E] text-xs" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars($name); ?></p>
                                <p class="text-xs text-gray-700" style="font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.home.testimonials.verified_client', 'Verified Client')); ?></p>
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
<section class="home-deferred-section py-16 md:py-12 px-4 text-center" style="background: linear-gradient(135deg, #F8F5F2 0%, #ffffff 100%);" aria-labelledby="home-cta-title">
    <div class="md:max-w-4xl mx-auto" data-aos="fade-up">
        <h2 id="home-cta-title" class="home-section-title text-3xl md:text-4xl font-light mb-5" style="font-family: 'Cormorant Garamond', serif; color: #0F3D3E; letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.home.cta.title', 'Ready to Plan Your Next Event?')); ?>
        </h2>
        <p class="max-w-3xl mx-auto text-gray-700 mb-8" style="font-family: 'Montserrat', sans-serif;">
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
        const heroVideo = document.querySelector('.home-hero-video');

        if (heroVideo && !reduceMotion) {
            const activateHeroVideo = () => {
                if (heroVideo.dataset.loaded === 'true') {
                    return;
                }

                const source = heroVideo.dataset.src;
                if (!source) {
                    return;
                }

                heroVideo.dataset.loaded = 'true';
                heroVideo.innerHTML = '<source src="' + source + '" type="video/mp4">';
                heroVideo.load();
                window.setTimeout(() => {
                    heroVideo.play().catch(() => {});
                }, 1200);
            };

            if ('IntersectionObserver' in window) {
                const heroObserver = new IntersectionObserver((entries) => {
                    if (!entries[0] || !entries[0].isIntersecting) {
                        return;
                    }

                    activateHeroVideo();
                    heroObserver.disconnect();
                }, { rootMargin: '200px 0px' });

                heroObserver.observe(heroVideo);
            } else {
                activateHeroVideo();
            }
        }

        const bootSwiper = (selector, config) => {
            const element = document.querySelector(selector);
            if (!element) {
                return;
            }

            const init = () => {
                if (element.dataset.swiperReady === 'true' || typeof window.Swiper !== 'function') {
                    return;
                }

                element.dataset.swiperReady = 'true';
                new Swiper(selector, config);
            };

            if ('IntersectionObserver' in window) {
                const swiperObserver = new IntersectionObserver((entries) => {
                    if (!entries[0] || !entries[0].isIntersecting) {
                        return;
                    }

                    init();
                    swiperObserver.disconnect();
                }, { rootMargin: '180px 0px' });

                swiperObserver.observe(element);
                return;
            }

            init();
        };

        bootSwiper('.homeServicesSwiper', {
            slidesPerView: 1.12,
            spaceBetween: 16,
            speed: 900,
            loop: true,
            allowTouchMove: true,
            keyboard: {
                enabled: true,
                onlyInViewport: true
            },
            autoplay: reduceMotion ? false : {
                delay: 2800,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },
            breakpoints: {
                640: {
                    slidesPerView: 1.45,
                    spaceBetween: 18
                },
                768: {
                    slidesPerView: 2.15,
                    spaceBetween: 20
                },
                1024: {
                    slidesPerView: 3.1,
                    spaceBetween: 22
                },
                1280: {
                    slidesPerView: 4.05,
                    spaceBetween: 24
                }
            }
        });

        bootSwiper('.homePackagesSwiper', {
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

        const galleryItems = Array.from(document.querySelectorAll('#gallery-grid .gallery-item'));
        const lightboxModal = document.getElementById('lightbox-modal');
        const lightboxImage = document.getElementById('lightbox-image');
        const lightboxVideo = document.getElementById('lightbox-video');
        const lightboxYoutube = document.getElementById('lightbox-youtube');
        const lightboxTitle = document.getElementById('lightbox-title');
        const lightboxCategory = document.getElementById('lightbox-category');
        const lightboxCounter = document.getElementById('lightbox-counter');
        const lightboxClose = document.getElementById('lightbox-close');
        const lightboxPrev = document.getElementById('lightbox-prev');
        const lightboxNext = document.getElementById('lightbox-next');
        const canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
        let activeGalleryIndex = -1;
        let lastFocusedElement = null;

        const getLightboxFocusableElements = () => {
            if (!lightboxModal) {
                return [];
            }

            return Array.from(lightboxModal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'))
                .filter((element) => !element.hasAttribute('disabled') && !element.getAttribute('aria-hidden'));
        };

        const stopCardPreview = (card) => {
            const video = card ? card.querySelector('.gallery-card-video') : null;
            if (!video) {
                return;
            }

            video.pause();
            video.currentTime = 0;
            card.classList.remove('is-previewing');
        };

        const startCardPreview = (card) => {
            if (!canHover || reduceMotion || !card) {
                return;
            }

            const video = card.querySelector('.gallery-card-video');
            if (!video) {
                return;
            }

            galleryItems.forEach((item) => {
                if (item !== card) {
                    stopCardPreview(item);
                }
            });

            card.classList.add('is-previewing');
            video.play().catch(() => {
                card.classList.remove('is-previewing');
            });
        };

        const stopLightboxVideo = () => {
            if (!lightboxVideo) {
            } else {
                lightboxVideo.pause();
                lightboxVideo.removeAttribute('src');
                lightboxVideo.load();
                lightboxVideo.classList.add('hidden');
            }

            if (lightboxYoutube) {
                lightboxYoutube.removeAttribute('src');
                lightboxYoutube.classList.add('hidden');
            }
        };

        const renderLightboxItem = (index) => {
            const card = galleryItems[index];
            if (!card) {
                return;
            }

            const media = card.dataset.media || '';
            const mediaType = card.dataset.mediaType || 'image';
            const title = card.dataset.title || '';
            const category = card.dataset.category || '';

            activeGalleryIndex = index;
            lightboxTitle.textContent = title;
            lightboxCategory.textContent = category;
            lightboxCounter.textContent = galleryItems.length ? (index + 1) + ' / ' + galleryItems.length : '';

            if (mediaType === 'youtube') {
                lightboxImage.classList.add('hidden');
                lightboxImage.removeAttribute('src');
                stopLightboxVideo();
                if (lightboxYoutube) {
                    lightboxYoutube.classList.remove('hidden');
                    lightboxYoutube.src = media;
                }
            } else if (mediaType === 'video') {
                lightboxImage.classList.add('hidden');
                lightboxImage.removeAttribute('src');
                if (lightboxYoutube) {
                    lightboxYoutube.classList.add('hidden');
                    lightboxYoutube.removeAttribute('src');
                }
                lightboxVideo.classList.remove('hidden');
                lightboxVideo.src = media;
                lightboxVideo.load();
                lightboxVideo.currentTime = 0;
                lightboxVideo.play().catch(() => {});
            } else {
                stopLightboxVideo();
                lightboxImage.classList.remove('hidden');
                lightboxImage.src = media;
                lightboxImage.alt = title;
            }
        };

        const openLightbox = (index) => {
            if (!lightboxModal || !galleryItems[index]) {
                return;
            }

            lastFocusedElement = document.activeElement;
            galleryItems.forEach(stopCardPreview);
            renderLightboxItem(index);
            lightboxModal.classList.remove('hidden');
            lightboxModal.classList.add('flex');
            lightboxModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
            lightboxClose?.focus();
        };

        const closeLightbox = () => {
            if (!lightboxModal) {
                return;
            }

            lightboxModal.classList.add('hidden');
            lightboxModal.classList.remove('flex');
            lightboxModal.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('overflow-hidden');
            lightboxImage.classList.add('hidden');
            lightboxImage.removeAttribute('src');
            stopLightboxVideo();
            activeGalleryIndex = -1;
            if (lastFocusedElement instanceof HTMLElement) {
                lastFocusedElement.focus();
            }
        };

        const showAdjacentLightboxItem = (direction) => {
            if (!galleryItems.length) {
                return;
            }

            const nextIndex = activeGalleryIndex < 0
                ? 0
                : (activeGalleryIndex + direction + galleryItems.length) % galleryItems.length;

            renderLightboxItem(nextIndex);
        };

        galleryItems.forEach((card, index) => {
            card.addEventListener('click', () => openLightbox(index));
            card.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    openLightbox(index);
                }
            });

            if (!card.querySelector('.gallery-card-video')) {
                return;
            }

            card.addEventListener('mouseenter', () => startCardPreview(card));
            card.addEventListener('mouseleave', () => stopCardPreview(card));
        });

        lightboxClose?.addEventListener('click', closeLightbox);
        lightboxPrev?.addEventListener('click', () => showAdjacentLightboxItem(-1));
        lightboxNext?.addEventListener('click', () => showAdjacentLightboxItem(1));
        lightboxModal?.addEventListener('click', (event) => {
            if (event.target === lightboxModal) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (!lightboxModal || lightboxModal.classList.contains('hidden')) {
                return;
            }

            if (event.key === 'Escape') {
                closeLightbox();
            } else if (event.key === 'ArrowLeft') {
                showAdjacentLightboxItem(-1);
            } else if (event.key === 'ArrowRight') {
                showAdjacentLightboxItem(1);
            } else if (event.key === 'Tab') {
                const focusableElements = getLightboxFocusableElements();
                if (!focusableElements.length) {
                    event.preventDefault();
                    return;
                }

                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];

                if (event.shiftKey && document.activeElement === firstElement) {
                    event.preventDefault();
                    lastElement.focus();
                } else if (!event.shiftKey && document.activeElement === lastElement) {
                    event.preventDefault();
                    firstElement.focus();
                }
            }
        });
    });
</script>
<style>
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .home-deferred-section {
        content-visibility: auto;
        contain-intrinsic-size: 1px 960px;
    }

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

    .about-feature-image {
        min-height: 100%;
        height: 100%;
    }

    .about-feature-image img {
        min-height: 420px;
    }

    .about-content-panel {
        border-radius: 1.75rem;
        padding: 1.5rem;
        max-width: 860px;
        margin: 0 auto;
    }

    .about-content-panel .home-section-title,
    .about-content-panel .home-section-copy {
        color: #161616 !important;
    }

    .about-content-grid {
        grid-auto-rows: 1fr;
    }

    .about-column-card {
        min-height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 0;
        border-radius: 0;
        background: transparent;
        box-shadow: none;
    }

    .about-copy-card {
        align-items: stretch;
        justify-content: center;
    }

    .about-copy {
        margin-left: auto;
        width: 100%;
        text-align: right;
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 100%;
        max-width: 100%;
    }

    .about-copy .home-section-copy {
        font-size: 1rem;
        line-height: 1.95;
        letter-spacing: 0.01em;
        color: #2b2b2b !important;
    }

    .about-copy .home-section-copy + .home-section-copy {
        margin-top: 0.5rem;
    }

    .about-ceo-card {
        align-items: stretch;
        justify-content: stretch;
    }

    .about-ceo-media {
        flex: 1 1 auto;
        min-height: 100%;
        border-radius: 0;
        background: transparent;
        overflow: hidden;
    }

    .about-ceo-media img {
        width: 100%;
        height: 100%;
        min-height: 100%;
        min-height: 520px;
        object-position: center top;
    }

    .about-cta-group {
        margin-top: auto;
        justify-content: flex-end;
    }

    .service-scroll-card {
        box-shadow: 0 8px 28px rgba(15, 61, 62, 0.12);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
        height: auto;
        min-height: 100%;
        display: flex;
    }

    .service-scroll-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 44px rgba(15, 61, 62, 0.18);
    }

    .service-scroll-card-inner {
        height: 100%;
        display: grid;
        grid-template-rows: 250px minmax(0, 1fr);
        width: 100%;
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
        padding: 1rem 1rem 1.1rem;
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
        background: #ffffff;
        height: 100%;
    }

    .service-card-description {
        overflow: visible;
        display: block;
        white-space: pre-wrap;
    }

    .service-scroll-content > a {
        margin-top: auto;
        padding-top: 1rem;
    }

    .homeServicesSwiper .swiper-slide {
        height: auto;
        display: flex;
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .gallery-item {
        border: 1px solid rgba(15, 61, 62, 0.08);
        box-shadow: 0 12px 30px rgba(15, 61, 62, 0.14);
        min-height: 16rem;
    }

    .gallery-item:hover {
        box-shadow: 0 18px 42px rgba(15, 61, 62, 0.22);
    }

    .gallery-item:focus-visible {
        outline: 3px solid rgba(200, 169, 81, 0.9);
        outline-offset: 3px;
    }

    .gallery-card-video {
        background: #0f3d3e;
        transition: opacity 0.25s ease, transform 0.5s ease;
    }

    .gallery-video-overlay {
        opacity: 1;
    }

    .gallery-play-button {
        width: 4.25rem;
        height: 4.25rem;
        background: rgba(15, 61, 62, 0.72);
        color: #ffffff;
        border: 1px solid rgba(255, 255, 255, 0.35);
        backdrop-filter: blur(6px);
        font-size: 1.05rem;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.22);
        transition: transform 0.28s ease, opacity 0.28s ease, background-color 0.28s ease;
    }

    .gallery-item:hover .gallery-play-button,
    .gallery-item:focus-visible .gallery-play-button {
        transform: scale(1.06);
        background: rgba(200, 169, 81, 0.92);
    }

    .gallery-item.is-previewing .gallery-video-overlay,
    .gallery-item:hover .gallery-video-overlay,
    .gallery-item:focus-visible .gallery-video-overlay {
        opacity: 0;
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
        -webkit-line-clamp: 3;
        line-clamp: 3;
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

        .about-feature-image img,
        .about-ceo-card img {
            min-height: 320px;
        }

        .about-ceo-card {
            width: 100vw;
            margin-inline: calc(50% - 50vw);
        }

        .about-ceo-media img {
            min-height: 420px;
            object-position: center top;
        }

        .about-content-panel {
            padding: 1.25rem;
        }

        .service-scroll-card {
            height: auto;
            min-height: 100%;
        }

        .service-scroll-card-inner {
            height: auto;
            grid-template-rows: 210px minmax(0, 1fr);
        }

        .service-scroll-image-wrap {
            height: 210px;
        }

        .service-scroll-content {
            padding: 0.95rem;
            gap: 0.65rem;
        }

        .home-package-categories {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding-bottom: 0.5rem;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            gap: 0.875rem;
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
            min-height: 18rem;
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
            line-clamp: 3;
        }

        #lightbox-prev,
        #lightbox-next {
            display: none;
        }
    }

    @media (min-width: 1024px) {
        .about-content-grid {
            grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.35fr);
            gap: 2rem;
        }

        .about-feature-image img {
            min-height: 620px;
        }

        .about-content-panel {
            padding: 2rem;
        }

        .about-column-card {
            min-height: 540px;
        }

        .about-copy {
            max-width: 13rem;
        }

        .about-copy .home-section-copy {
            line-height: 2.05;
        }

        .about-ceo-media img {
            min-height: 680px;
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
