<!DOCTYPE html>
<html lang="<?php echo getCurrentLanguage(); ?>">
<head>
    <?php
    $seoOverrides = isset($seo) && is_array($seo) ? $seo : [];
    if (!isset($seoOverrides['title']) && isset($title)) {
        $seoOverrides['title'] = $title;
    }
    $seoMeta = buildSeoMeta($seoOverrides);
    $faviconUrl = toAbsoluteUrl('assets/images/favicon.png');
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($seoMeta['title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($seoMeta['description']); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($seoMeta['keywords']); ?>">
    <meta name="robots" content="<?php echo htmlspecialchars($seoMeta['robots']); ?>">
    <link rel="canonical" href="<?php echo htmlspecialchars($seoMeta['canonical']); ?>">

    <meta property="og:title" content="<?php echo htmlspecialchars($seoMeta['title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seoMeta['description']); ?>">
    <meta property="og:type" content="<?php echo htmlspecialchars($seoMeta['type']); ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($seoMeta['url']); ?>">
    <meta property="og:site_name" content="<?php echo htmlspecialchars($seoMeta['site_name']); ?>">
    <meta property="og:locale" content="<?php echo htmlspecialchars($seoMeta['locale']); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($seoMeta['image']); ?>">
    <meta property="og:image:alt" content="<?php echo htmlspecialchars($seoMeta['image_alt']); ?>">

    <meta name="twitter:card" content="<?php echo htmlspecialchars($seoMeta['twitter_card']); ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($seoMeta['title']); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($seoMeta['description']); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($seoMeta['image']); ?>">
    <meta name="twitter:image:alt" content="<?php echo htmlspecialchars($seoMeta['image_alt']); ?>">
    <?php if (!empty($seoMeta['twitter_site'])): ?>
        <meta name="twitter:site" content="<?php echo htmlspecialchars($seoMeta['twitter_site']); ?>">
    <?php endif; ?>

    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo htmlspecialchars($faviconUrl); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo htmlspecialchars($faviconUrl); ?>">
    <link rel="shortcut icon" href="<?php echo htmlspecialchars($faviconUrl); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo htmlspecialchars($faviconUrl); ?>">
    <meta name="theme-color" content="#0F3D3E">

    <script type="application/ld+json">
        <?php echo json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => appConfig('site.name', 'Sapphire Events & Decorations'),
            'url' => baseUrl('/'),
            'logo' => toAbsoluteUrl('assets/images/favicon.png'),
            'description' => appConfig('site.description', ''),
            'email' => appConfig('site.email', ''),
            'telephone' => appConfig('site.phone', ''),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => appConfig('site.address', ''),
                'addressLocality' => 'Tallinn',
                'addressCountry' => 'EE',
            ],
            'sameAs' => array_values(array_filter([
                appConfig('social.instagram', ''),
                appConfig('social.facebook', ''),
                appConfig('social.tiktok', ''),
            ])),
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Lora:wght@400;500;600;700&family=Manrope:wght@300;400;500;600;700;800&family=Montserrat:wght@100;200;300;400;500;600;700;800&family=Parisienne&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="<?= asset('css/language-selector.css'); ?>">
    
    <style>
        :root {
            --emerald: #7D3171;
            --gold: #F6CCF0;
            --off-white: #FBECF9;
            --blush: #F6CCF0;
            --blush-soft: #FBECF9;
            --blush-mist: #FFF8FE;
            --accent-strong: #7D3171;
            --base-light: #FFFFFF;
            --base-dark: #000000;
            --rose-shadow: rgba(126, 78, 118, 0.14);
            --charcoal: #000000;
        }

        html {
            -webkit-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Lora', serif;
            color: var(--charcoal);
            background: radial-gradient(circle at 8% 8%, rgba(246, 204, 240, 0.62) 0%, rgba(246, 204, 240, 0) 28%),
                        radial-gradient(circle at 92% 8%, rgba(251, 236, 249, 0.9) 0%, rgba(251, 236, 249, 0) 28%),
                        linear-gradient(180deg, #ffffff 0%, #fff8fe 55%, #fbecf9 100%);
            font-weight: 400;
            line-height: 1.6;
            letter-spacing: 0.2px;
            overflow-x: hidden;
        }

        .site-container {
            width: 100%;
            max-width: none;
            margin-inline: 0;
        }

        .site-gutter {
            padding-inline: clamp(1rem, 2.5vw, 2.5rem);
        }

        main {
            overflow-x: clip;
        }

        img,
        video,
        svg,
        canvas {
            display: block;
            max-width: 100%;
        }

        img {
            height: auto;
        }

        iframe {
            max-width: 100%;
        }

        img[loading="lazy"] {
            content-visibility: auto;
            contain-intrinsic-size: 320px 220px;
        }

        a,
        button,
        input,
        select,
        textarea {
            touch-action: manipulation;
        }

        h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3.5rem;
            font-weight: 600;
            letter-spacing: -0.5px;
            line-height: 1.14;
        }

        h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            font-weight: 600;
            letter-spacing: -0.45px;
            line-height: 1.18;
        }

        h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.01em;
        }

        h4 {
            font-family: 'Lora', serif;
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        h5, h6 {
            font-family: 'Lora', serif;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        p {
            font-family: 'Lora', serif;
            font-weight: 400;
            letter-spacing: 0.25px;
        }

        a {
            font-family: 'Lora', serif;
            font-weight: 500;
        }

        span, small {
            font-family: 'Lora', serif;
        }

        .btn-primary {
            background-color: var(--emerald);
            color: var(--base-light);
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 2px solid var(--accent-strong);
            font-family: 'Manrope', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.875rem;
        }

        .btn-primary:hover {
            background-color: var(--base-light);
            color: var(--accent-strong);
        }

        .btn-secondary {
            background-color: var(--blush);
            color: var(--accent-strong);
            padding: 12px 24px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: 2px solid var(--blush);
            font-family: 'Manrope', sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.875rem;
        }

        .btn-secondary:hover {
            background-color: var(--base-light);
            color: var(--accent-strong);
            border-color: var(--accent-strong);
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--accent-strong) 0%, var(--blush) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .luxury-shadow {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .hover-zoom {
            transition: transform 0.3s ease;
        }

        .hover-zoom:hover {
            transform: scale(1.05);
        }

        /* Typography Enhancements */
        .subtitle {
            font-family: 'Lora', serif;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-weight: 600;
        }

        .accent-text {
            font-family: 'Parisienne', cursive;
            font-style: normal;
            font-weight: 400;
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.8rem;
            font-weight: 600;
            letter-spacing: -0.3px;
        }

        small {
            font-family: 'Lora', serif;
            font-weight: 400;
            letter-spacing: 0.25px;
        }

        .text-label {
            font-family: 'Lora', serif;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-weight: 700;
        }

        .elegant-heading {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 300;
            letter-spacing: 0.05em;
        }

        /* Elegant Service Card - Home Page */
        .service-card-home {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92) 0%, rgba(255, 248, 252, 0.92) 100%);
            box-shadow: 0 10px 32px var(--rose-shadow);
            border: 1px solid rgba(246, 204, 240, 0.55);
            backdrop-filter: blur(1.5px);
        }

        .service-card-home:hover {
            box-shadow: 0 20px 60px rgba(126, 78, 118, 0.26);
            transform: translateY(-6px);
        }

        .service-card-home img {
            will-change: transform;
        }

        .service-card-home h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.75rem;
            color: #0F3D3E;
            letter-spacing: -0.02em;
        }

        .service-card-home:hover h3 {
            color: #C8A951;
        }

        .service-card-home .w-1 {
            transition: all 0.3s ease;
        }

        .service-card-home:hover .w-1 {
            background-color: #0F3D3E;
        }

        .site-nav-link:hover {
            color: var(--accent-strong) !important;
        }

        section[style*="background-color: #F8F5F2"] {
            background-color: var(--blush-soft) !important;
        }

        .site-topbar {
            background-color: var(--accent-strong);
            color: var(--base-light);
            border-bottom: 1px solid rgba(255, 255, 255, 0.18);
        }

        .site-topbar-inner {
            min-height: 42px;
        }

        .site-topbar a {
            color: var(--base-light);
            text-decoration: none;
            transition: opacity 0.25s ease;
        }

        .site-topbar a:hover {
            opacity: 0.82;
        }

        .topbar-chip {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            padding: 6px 12px;
            font-family: 'Manrope', sans-serif;
            font-size: 0.75rem;
            line-height: 1;
            letter-spacing: 0.04em;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .topbar-cta {
            background: var(--blush);
            color: var(--accent-strong) !important;
            font-weight: 700;
            border-color: transparent;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.35rem;
                line-height: 1.15;
            }

            h2 {
                font-size: 2rem;
                line-height: 1.2;
            }

            .section-title {
                font-size: 2.1rem;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
                text-align: center;
            }

            .service-card-home h3 {
                font-size: 1.5rem;
            }

            .service-card-home {
                flex-direction: column;
            }

            .service-card-home > div:first-child {
                width: 100% !important;
                height: 250px !important;
            }

            .service-card-home > div:last-child {
                width: 100% !important;
            }

            .site-topbar-inner {
                min-height: 38px;
            }

            .topbar-chip {
                padding: 4px 10px;
                font-size: 0.68rem;
            }

            .site-gutter {
                padding-inline: clamp(0.875rem, 4vw, 1.25rem);
            }
        }

        /* Animation */
        @keyframes cardSlideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .service-card-home {
            animation: cardSlideIn 0.6s ease-out;
        }

        /* Custom AJAX Lightbox Styling */
        #lightbox-modal {
            animation: fadeInLightbox 0.3s ease-out;
        }

        #lightbox-modal.hidden {
            animator: fadeOutLightbox 0.3s ease-out;
        }

        @keyframes fadeInLightbox {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeOutLightbox {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }

        #lightbox-image {
            animation: zoomInImage 0.3s ease-out;
        }

        @keyframes zoomInImage {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .gallery-item {
            cursor: pointer;
        }

        #lightbox-prev:hover,
        #lightbox-next:hover {
            background-color: rgba(200, 169, 81, 0.2) !important;
            transform: scale(1.1);
        }

        #lightbox-close:hover {
            background-color: rgba(200, 169, 81, 0.2) !important;
            transform: scale(1.1);
        }
    </style>
</head>
<body>
    <?php ob_start(); ?>
    <?php
    $topBarPhone = appConfig('site.phone', '+372-5160427');
    $topBarEmail = appConfig('site.email', 'Sapphireeventsglitz@gmail.com');
    ?>
    <div class="site-topbar">
        <div class="site-topbar-inner site-container site-gutter flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 sm:gap-3 overflow-x-auto no-scrollbar">
                <a class="topbar-chip" href="tel:<?php echo htmlspecialchars(preg_replace('/\s+/', '', $topBarPhone)); ?>" aria-label="Call us">
                    <i class="fas fa-phone-alt text-[0.65rem]" aria-hidden="true"></i>
                    <span><?php echo htmlspecialchars($topBarPhone); ?></span>
                </a>
                <a class="topbar-chip hidden sm:inline-flex" href="mailto:<?php echo htmlspecialchars($topBarEmail); ?>" aria-label="Email us">
                    <i class="fas fa-envelope text-[0.65rem]" aria-hidden="true"></i>
                    <span><?php echo htmlspecialchars($topBarEmail); ?></span>
                </a>
                <span class="topbar-chip hidden md:inline-flex" aria-hidden="true">
                    <i class="fas fa-star text-[0.65rem]"></i>
                    <span>Elegant Event Styling</span>
                </span>
            </div>
            <a href="<?php echo route('/contact'); ?>" class="topbar-chip topbar-cta hidden sm:inline-flex">
                Book Consultation
            </a>
        </div>
    </div>
    <?php include VIEW_PATH . '/partials/header.php'; ?>

    <main>
        <?php echo $content ?? ''; ?>
    </main>

    <?php include VIEW_PATH . '/partials/footer.php'; ?>

    <script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script>
        AOS.init({
            duration: 800,
            offset: 100,
            once: true,
        });

        // AJAX Lightbox Implementation
        (function() {
            const modal = document.getElementById('lightbox-modal');
            if (!modal) return; // Skip if not on a page with lightbox
            
            const lightboxImage = document.getElementById('lightbox-image');
            const lightboxVideo = document.getElementById('lightbox-video');
            const lightboxTitle = document.getElementById('lightbox-title');
            const lightboxCategory = document.getElementById('lightbox-category');
            const lightboxCounter = document.getElementById('lightbox-counter');
            const closeBtn = document.getElementById('lightbox-close');
            const prevBtn = document.getElementById('lightbox-prev');
            const nextBtn = document.getElementById('lightbox-next');
            const galleryItems = document.querySelectorAll('.gallery-item');
            
            let currentIndex = 0;
            const items = Array.from(galleryItems);

            function showLightbox(index) {
                if (items.length === 0) return;
                
                if (index < 0) {
                    currentIndex = items.length - 1;
                } else if (index >= items.length) {
                    currentIndex = 0;
                } else {
                    currentIndex = index;
                }

                const item = items[currentIndex];
                const media = item.getAttribute('data-media') || item.getAttribute('data-image');
                const mediaType = (item.getAttribute('data-media-type') || 'image').toLowerCase();
                const title = item.getAttribute('data-title');
                const category = item.getAttribute('data-category');

                if (lightboxVideo && mediaType === 'video') {
                    if (lightboxImage) {
                        lightboxImage.classList.add('hidden');
                        lightboxImage.removeAttribute('src');
                    }
                    lightboxVideo.classList.remove('hidden');
                    lightboxVideo.src = media || '';
                    lightboxVideo.muted = false;
                    lightboxVideo.currentTime = 0;
                    lightboxVideo.play().catch(() => {});
                } else {
                    if (lightboxVideo) {
                        lightboxVideo.pause();
                        lightboxVideo.classList.add('hidden');
                        lightboxVideo.removeAttribute('src');
                        lightboxVideo.load();
                    }
                    if (lightboxImage) {
                        lightboxImage.classList.remove('hidden');
                        lightboxImage.src = media || '';
                        lightboxImage.alt = title;
                    }
                }
                lightboxTitle.textContent = title;
                lightboxCategory.textContent = category;
                lightboxCounter.textContent = currentIndex + 1 + ' / ' + items.length;

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeLightbox() {
                if (lightboxVideo) {
                    lightboxVideo.pause();
                    lightboxVideo.removeAttribute('src');
                    lightboxVideo.load();
                }
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Gallery item click handlers
            galleryItems.forEach((item, index) => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    showLightbox(index);
                });
            });

            // Navigation buttons
            if (prevBtn) {
                prevBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showLightbox(currentIndex - 1);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showLightbox(currentIndex + 1);
                });
            }

            // Close button
            if (closeBtn) {
                closeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    closeLightbox();
                });
            }

            // Close on background click
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeLightbox();
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', (e) => {
                if (modal.classList.contains('hidden')) return;

                switch(e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowLeft':
                        showLightbox(currentIndex - 1);
                        break;
                    case 'ArrowRight':
                        showLightbox(currentIndex + 1);
                        break;
                }
            });
        })();
    </script>
    <?php
    $renderedBodyHtml = ob_get_clean();
    echo \App\Core\HtmlContentTranslator::translateForCurrentLanguage($renderedBodyHtml);
    ?>
</body>
</html>
