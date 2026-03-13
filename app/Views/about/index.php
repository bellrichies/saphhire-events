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
        <h1 class="text-4xl md:text-5xl font-light mb-4 leading-tight text-white" style="font-family: 'Dancing Script', cursive; letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.about.hero.title', 'Crafting Celebrations with Purpose')); ?>
        </h1>
        <p class="text-base md:text-lg text-gray-300 max-w-3xl mx-auto leading-relaxed" style="font-family: 'Montserrat', sans-serif;">
            <?php echo htmlspecialchars(trans('content.about.hero.description', 'We blend design excellence, operational precision, and human-centered service to deliver events that are beautiful, seamless, and memorable.')); ?>
        </p>
    </div>
</section>

<section class="pt-20 pb-10 px-4" style="background-color: #F8F5F2;">
    <div class="w-full">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.18em;">
                <?php echo htmlspecialchars(trans('content.about.team.badge', 'The Full Story')); ?>
            </span>
            <p class="text-3xl md:text-3xl font-light mb-4" style="color: #0F3D3E;  letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.about.team.title', 'People Behind the Experience')); ?>
            </p>
            <p class="text-base text-gray-600 max-w-5xl mx-auto">
                <?php echo htmlspecialchars(trans('content.about.team.description', 'A multidisciplinary team focused on design excellence, flawless coordination, and high-touch client support.')); ?>
            </p>
        </div>
        <div class="space-y-8 lg:space-y-10">
            <div class="about-story-row" data-aos="fade-up">
                <figure class="about-story-media">
                    <video
                        class="about-story-image about-story-image--mission"
                        autoplay
                        muted
                        loop
                        playsinline
                        preload="auto"
                        aria-label="<?php echo htmlspecialchars(trans('content.about.mission_vision.mission_image_alt', 'Our mission and vision')); ?>">
                        <source src="/sapphireevents/assets/uploads/media/video/2026/03/46b910cd7e6c35205bcc990eed4d9662.mp4" type="video/mp4">
                    </video>
                </figure>
                <article class="about-feature-card about-story-card pt-10 md:pt-12">
                    <h3 class="text-center text-3xl font-bold mb-3" style="color: #0F3D3E;">
                        <?php echo htmlspecialchars(trans('content.about.mission_vision.mission_title', 'Mission')); ?>
                    </h3>
                    <p class="text-gray-600 text-lg leading-relaxed pt-8  md:px-8 line-spacing-1.6">
                        <?php echo htmlspecialchars(trans('content.about.mission_vision.mission_desc', 'At Sapphire Events & Decorations, our mission is to transform your special occasions into unforgettable experiences. With meticulous attention to detail and a passion for creativity, we strive to exceed your expectations, delivering exceptional event planning and stunning decorations that bring your vision to life.')); ?>
                    </p>
                    <p class="text-gray-600 text-lg leading-relaxed mt-4 md:px-8 line-spacing-1.6">Let us make your moments shine with elegance and sophistication.</p>
                </article>
            </div>
            <div class="about-story-row about-story-row--reverse" data-aos="fade-up" data-aos-delay="100">
                <figure class="about-story-media">
                    <img src="<?php echo asset('images/about-team.avif'); ?>" alt="<?php echo htmlspecialchars(trans('content.about.team.team_image_alt', 'Our team')); ?>" class="about-story-image about-story-image--top about-story-image--vision">
                </figure>
                <article class="about-feature-card about-story-card pt-10 md:pt-12">
                    <h3 class="text-center text-3xl font-bold mb-3" style="color: #0F3D3E;">
                        <?php echo htmlspecialchars(trans('content.about.mission_vision.vision_title', 'Vision')); ?>
                    </h3>
                    <p class="text-gray-600 text-lg leading-relaxed pt-8 md:px-8 line-spacing-1.6">
                        <?php echo htmlspecialchars(trans('content.about.mission_vision.vision_desc', 'Our vision at Sapphire Events & Decorations is to be the first choice for creating magical moments that last a lifetime. We aim to inspire and delight our clients with innovative designs, impeccable service, and a commitment to excellence in every event we undertake.')); ?>
                    </p>
                    <p class="text-gray-600 text-lg leading-relaxed mt-4 md:px-8"> With our expertise and dedication, we envision turning dreams into reality, one celebration at a time.</p>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="py-20 px-4" style="background-color: #fff;">
    <div class="w-full">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.18em;">
                <?php echo htmlspecialchars(trans('content.about.gallery.badge', 'Visual Highlights')); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                Our Signature Aesthetic
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-5">
            <?php 
            $getGalleryMediaUrl = static function (?string $media): string {
                if (!$media) {
                    return '';
                }
                if (preg_match('/^https?:\/\//', $media)) {
                    return $media;
                }
                return uploadedImageUrl($media);
            };

            $galleryItems = $highlightImages ?? [];
            
            if (!empty($galleryItems)):
                foreach ($galleryItems as $index => $item):
                    $mediaUrl = $getGalleryMediaUrl($item['image'] ?? null);
            ?>
                <div class="gallery-item rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4) * 50; ?>">
                    <div class="relative aspect-square overflow-hidden bg-gray-200">
                        <?php if (!empty($mediaUrl)): ?>
                            <img 
                                src="<?php echo htmlspecialchars($mediaUrl); ?>" 
                                alt="<?php echo htmlspecialchars($item['title'] ?? 'Gallery item'); ?>" 
                                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                loading="lazy"
                            >
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-300 to-gray-400">
                                <i class="fas fa-image text-gray-500 text-3xl"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black/0 hover:bg-black/20 transition-colors duration-300 flex items-center justify-center">
                            <a href="<?php echo route('/gallery'); ?>" class="opacity-0 hover:opacity-100 transition-opacity duration-300">
                                <i class="fas fa-expand text-white text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php 
                endforeach;
            else:
            ?>
                <p class="col-span-full text-center text-gray-500 py-12"><?php echo htmlspecialchars(trans('content.about.gallery.empty', 'Gallery images will appear here soon.')); ?></p>
            <?php 
            endif;
            ?>
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
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .about-feature-card:hover {
        transform: translateY(-6px);
    }

    .about-story-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 1.5rem;
        align-items: stretch;
    }

    .about-story-media {
        margin: 0;
        min-height: 100%;
        overflow: hidden;
    }

    .about-story-image {
        width: 100%;
        height: 100%;
        min-height: 250px;
        max-height: 440px;
        object-fit: cover;
        display: block;
    }

    .about-story-image--top {
        object-position: top center;
    }

    .about-story-image--vision {
        min-height: 400px;
        max-height: 620px;
    }

    .about-story-image--mission {
        min-height: 400px;
        max-height: 620px;
    }

    .about-story-card {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 100%;
        align-items: center;
        text-align: center;
        overflow: hidden;
    }

    .about-story-card > h3,
    .about-story-card > p {
        width: 100%;
        max-width: 34rem;
        overflow-wrap: anywhere;
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

    .gallery-item {
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    .gallery-item:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(15, 61, 62, 0.15);
    }

    .gallery-item img {
        transition: transform 0.35s ease;
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

    @media (min-width: 1024px) {
        .about-story-row {
            grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr);
            gap: 2rem;
        }

        .about-story-row--reverse .about-story-media {
            order: 2;
        }

        .about-story-row--reverse .about-story-card {
            order: 1;
        }

        .about-story-image {
            min-height: 280px;
            max-height: 340px;
        }

        .about-story-image--vision {
            min-height: 400px;
            max-height: 620px;
        }

        .about-story-image--mission {
            min-height: 400px;
            max-height: 620px;
        }
    }
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>
