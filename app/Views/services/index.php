<?php
$title = trans('content.services.page_title', 'Our Services');
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
?>

<!-- Compact Hero Section -->
<section class="relative py-16 md:py-20 px-4 overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    
    <!-- Floating Elements -->
    <div class="absolute top-10 right-20 w-24 h-24 rounded-full opacity-15 animate-float" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>
    <div class="absolute bottom-10 left-20 w-32 h-32 rounded-full opacity-10 animate-float-delayed" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>
    
    <div class="max-w-5xl mx-auto text-center relative z-10" data-aos="fade-up">
        <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
            <?php echo htmlspecialchars(trans('content.services.hero.badge', 'Professional Event Planning')); ?>
        </span>
        
        <h1 class="text-4xl md:text-5xl font-light mb-5 leading-tight" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em; color: white;">
            <?php echo htmlspecialchars(trans('content.services.hero.title_main', 'Comprehensive Event')); ?> <span class="italic" style="color: #C8A951;"><?php echo htmlspecialchars(trans('content.services.hero.title_highlight', 'Solutions')); ?></span>
        </h1>
        
        <p class="text-base md:text-lg text-gray-300 max-w-2xl mx-auto mb-8 leading-relaxed" style="font-family: 'Montserrat', sans-serif;">
            <?php echo htmlspecialchars(trans('content.services.hero.description', 'From intimate gatherings to grand celebrations, we transform your vision into spectacular, unforgettable experiences.')); ?>
        </p>
        
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="<?php echo route('/contact'); ?>" class="group px-8 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-xl" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.85rem;">
                <?php echo htmlspecialchars(trans('content.services.hero.primary', 'Schedule Consultation')); ?>
                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
            </a>
            <a href="#services" class="px-8 py-3 rounded-lg font-semibold border-2 border-white/30 text-white transition-all duration-300 hover:bg-white/10" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.85rem;">
                <?php echo htmlspecialchars(trans('content.services.hero.secondary', 'Explore Services')); ?>
            </a>
        </div>
    </div>
</section>

<!-- Service Categories Section -->
<section id="services" class="py-20 px-4" style="background-color: #F8F5F2;">
    <div class="max-w-7xl mx-auto">
        <!-- Section Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                <?php echo htmlspecialchars(trans('content.services.details.badge', 'Detailed Services')); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.services.details.title', 'Service Category Details')); ?>
            </h2>
            <p class="text-base text-gray-600 max-w-xl mx-auto" style="font-family: 'Montserrat', sans-serif;">
                <?php echo htmlspecialchars(trans('content.services.details.description', 'Comprehensive support and expert guidance throughout your event journey.')); ?>
            </p>
        </div>

        <div class="space-y-12">
            <!-- Full Event Planning -->
            <div class="group bg-white rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-2xl" data-aos="fade-up" style="box-shadow: 0 4px 30px rgba(15, 61, 62, 0.08);">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <div class="relative h-64 lg:h-auto min-h-[320px] overflow-hidden">
                        <img
                            src="<?php echo asset('images/gallery_69a388fe98b78_1772325118.webp'); ?>"
                            alt="<?php echo htmlspecialchars(trans('content.services.details.card_1.image_alt', 'Full event planning service')); ?>"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            loading="lazy"
                        >
                        <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.72) 0%, rgba(26, 79, 80, 0.38) 55%, rgba(200, 169, 81, 0.42) 100%);"></div>
                        <div class="absolute top-4 left-4 px-3 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.9); color: #0F3D3E;">
                            <?php echo htmlspecialchars(trans('content.services.details.card_1.label', 'Full Service')); ?>
                        </div>
                    </div>
                    <div class="p-8 lg:p-10 flex flex-col justify-center">
                        <div class="w-12 h-1 rounded-full mb-6" style="background-color: #C8A951;"></div>
                        <h3 class="text-2xl font-semibold mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;"><?php echo htmlspecialchars(trans('content.services.details.card_1.title', 'Full Event Planning')); ?></h3>
                        <p class="text-gray-600 mb-6 leading-relaxed text-sm">
                            <?php echo htmlspecialchars(trans('content.services.details.card_1.description', 'Our complete planning service handles every aspect of your event from conception to execution, allowing you to sit back and enjoy your celebration.')); ?>
                        </p>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_1.feature_1', 'Venue selection & coordination')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_1.feature_2', 'Guest list management')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_1.feature_3', 'Catering & vendor selection')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_1.feature_4', 'Budget management')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_1.feature_5', 'Full day-of coordination')); ?>
                            </li>
                        </ul>
                        <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg w-fit text-sm" style="background-color: #0F3D3E; color: white; font-family: 'Montserrat', sans-serif; letter-spacing: 0.05em;">
                            <?php echo htmlspecialchars(trans('content.services.details.inquire', 'Inquire Now')); ?>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Decoration & Styling -->
            <div class="group bg-white rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-2xl" data-aos="fade-up" style="box-shadow: 0 4px 30px rgba(15, 61, 62, 0.08);">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <div class="p-8 lg:p-10 flex flex-col justify-center order-2 lg:order-1">
                        <div class="w-12 h-1 rounded-full mb-6" style="background-color: #C8A951;"></div>
                        <h3 class="text-2xl font-semibold mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;"><?php echo htmlspecialchars(trans('content.services.details.card_2.title', 'Decoration & Styling')); ?></h3>
                        <p class="text-gray-600 mb-6 leading-relaxed text-sm">
                            <?php echo htmlspecialchars(trans('content.services.details.card_2.description', 'Transform your venue with our expert decoration and styling services. From floral arrangements to lighting design, we create stunning visual experiences.')); ?>
                        </p>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_2.feature_1', 'Theme design & creation')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_2.feature_2', 'Floral arrangements')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_2.feature_3', 'Lighting design & effects')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_2.feature_4', 'Furniture & backdrop setup')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_2.feature_5', 'Professional installation')); ?>
                            </li>
                        </ul>
                        <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg w-fit text-sm" style="background-color: #0F3D3E; color: white; font-family: 'Montserrat', sans-serif; letter-spacing: 0.05em;">
                            <?php echo htmlspecialchars(trans('content.services.details.inquire', 'Inquire Now')); ?>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                    <div class="relative h-64 lg:h-auto min-h-[320px] overflow-hidden order-1 lg:order-2">
                        <img
                            src="<?php echo asset('images/gallery_69a38c6c7734d_17723259990.jpg'); ?>"
                            alt="<?php echo htmlspecialchars(trans('content.services.details.card_2.image_alt', 'Decoration and styling service')); ?>"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            loading="lazy"
                        >
                        <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(200, 169, 81, 0.58) 0%, rgba(168, 138, 61, 0.3) 45%, rgba(15, 61, 62, 0.62) 100%);"></div>
                        <div class="absolute top-4 right-4 px-3 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.9); color: white;">
                            <?php echo htmlspecialchars(trans('content.services.details.card_2.label', 'Creative')); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Day-of Coordination -->
            <div class="group bg-white rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-2xl" data-aos="fade-up" style="box-shadow: 0 4px 30px rgba(15, 61, 62, 0.08);">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <div class="relative h-64 lg:h-auto min-h-[320px] overflow-hidden">
                        <img
                            src="<?php echo asset('images/gallery_69a17ad3c3831_1772190419.jpg'); ?>"
                            alt="<?php echo htmlspecialchars(trans('content.services.details.card_3.image_alt', 'Day-of coordination service')); ?>"
                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            loading="lazy"
                        >
                        <div class="absolute inset-0" style="background: linear-gradient(135deg, rgba(28, 28, 28, 0.58) 0%, rgba(15, 61, 62, 0.45) 50%, rgba(200, 169, 81, 0.5) 100%);"></div>
                        <div class="absolute top-4 left-4 px-3 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.9); color: #0F3D3E;">
                            <?php echo htmlspecialchars(trans('content.services.details.card_3.label', 'On-Demand')); ?>
                        </div>
                    </div>
                    <div class="p-8 lg:p-10 flex flex-col justify-center">
                        <div class="w-12 h-1 rounded-full mb-6" style="background-color: #C8A951;"></div>
                        <h3 class="text-2xl font-semibold mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;"><?php echo htmlspecialchars(trans('content.services.details.card_3.title', 'Day-of Coordination')); ?></h3>
                        <p class="text-gray-600 mb-6 leading-relaxed text-sm">
                            <?php echo htmlspecialchars(trans('content.services.details.card_3.description', 'Already planned your event? Our day-of coordination service ensures flawless execution. We manage all logistics so you can focus on enjoying your celebration.')); ?>
                        </p>
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-8">
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_3.feature_1', 'Timeline management')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_3.feature_2', 'Vendor coordination')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_3.feature_3', 'Setup supervision')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_3.feature_4', 'Guest management')); ?>
                            </li>
                            <li class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-check-circle mr-2" style="color: #C8A951;"></i>
                                <?php echo htmlspecialchars(trans('content.services.details.card_3.feature_5', 'Problem resolution')); ?>
                            </li>
                        </ul>
                        <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-lg w-fit text-sm" style="background-color: #0F3D3E; color: white; font-family: 'Montserrat', sans-serif; letter-spacing: 0.05em;">
                            <?php echo htmlspecialchars(trans('content.services.details.inquire', 'Inquire Now')); ?>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Process Section -->
<section class="py-20 px-4 relative overflow-hidden">
    <!-- Background Decoration -->
    <div class="absolute top-0 left-0 w-full h-24" style="background: linear-gradient(to bottom, #F8F5F2, transparent);"></div>
    <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full opacity-5" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>
    
    <div class="max-w-7xl mx-auto relative">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
            <div class="lg:col-span-7">
                <div class="mb-10" data-aos="fade-up">
                    <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                        <?php echo htmlspecialchars(trans('content.services.process.badge', 'How We Work')); ?>
                    </span>
                    <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                        <?php echo htmlspecialchars(trans('content.services.process.title', 'Our Event Planning Process')); ?>
                    </h2>
                    <p class="text-base text-gray-600 max-w-2xl" style="font-family: 'Montserrat', sans-serif;">
                        <?php echo htmlspecialchars(trans('content.services.process.description', 'A proven methodology to ensure every detail is perfect.')); ?>
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative" data-aos="fade-up">
                        <div class="bg-white rounded-2xl p-6 relative z-10 transition-all duration-300 hover:shadow-xl" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                            <div class="w-16 h-16 rounded-xl mb-5 flex items-center justify-center text-white text-xl font-bold transform transition-transform duration-300 hover:scale-110" style="background: linear-gradient(135deg, #0F3D3E 0%, #C8A951 100%);">01</div>
                            <h4 class="text-lg font-bold mb-3" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.services.process.step_1_title', 'Consultation')); ?></h4>
                            <p class="text-gray-600 leading-relaxed text-sm"><?php echo htmlspecialchars(trans('content.services.process.step_1_desc', 'In-depth discovery call to understand your vision and requirements.')); ?></p>
                        </div>
                    </div>

                    <div class="relative" data-aos="fade-up" data-aos-delay="100">
                        <div class="bg-white rounded-2xl p-6 relative z-10 transition-all duration-300 hover:shadow-xl" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                            <div class="w-16 h-16 rounded-xl mb-5 flex items-center justify-center text-white text-xl font-bold transform transition-transform duration-300 hover:scale-110" style="background: linear-gradient(135deg, #0F3D3E 0%, #C8A951 100%);">02</div>
                            <h4 class="text-lg font-bold mb-3" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.services.process.step_2_title', 'Concept Design')); ?></h4>
                            <p class="text-gray-600 leading-relaxed text-sm"><?php echo htmlspecialchars(trans('content.services.process.step_2_desc', 'Detailed event concept with themes, palettes, and layouts.')); ?></p>
                        </div>
                    </div>

                    <div class="relative" data-aos="fade-up" data-aos-delay="200">
                        <div class="bg-white rounded-2xl p-6 relative z-10 transition-all duration-300 hover:shadow-xl" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                            <div class="w-16 h-16 rounded-xl mb-5 flex items-center justify-center text-white text-xl font-bold transform transition-transform duration-300 hover:scale-110" style="background: linear-gradient(135deg, #0F3D3E 0%, #C8A951 100%);">03</div>
                            <h4 class="text-lg font-bold mb-3" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.services.process.step_3_title', 'Planning')); ?></h4>
                            <p class="text-gray-600 leading-relaxed text-sm"><?php echo htmlspecialchars(trans('content.services.process.step_3_desc', 'Timelines, vendor relationships, budgets, and logistics.')); ?></p>
                        </div>
                    </div>

                    <div class="relative" data-aos="fade-up" data-aos-delay="300">
                        <div class="bg-white rounded-2xl p-6 relative z-10 transition-all duration-300 hover:shadow-xl" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                            <div class="w-16 h-16 rounded-xl mb-5 flex items-center justify-center text-white text-xl font-bold transform transition-transform duration-300 hover:scale-110" style="background: linear-gradient(135deg, #0F3D3E 0%, #C8A951 100%);">04</div>
                            <h4 class="text-lg font-bold mb-3" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.services.process.step_4_title', 'Execution')); ?></h4>
                            <p class="text-gray-600 leading-relaxed text-sm"><?php echo htmlspecialchars(trans('content.services.process.step_4_desc', 'Full setup, decoration, and day-of coordination.')); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5" data-aos="fade-left">
                <div class="bg-white rounded-2xl p-4 md:p-5" style="box-shadow: 0 6px 26px rgba(15, 61, 62, 0.1); border: 1px solid rgba(200, 169, 81, 0.14);">
                    <div class="rounded-xl overflow-hidden aspect-[4/5] bg-[#F8F5F2] flex items-center justify-center">
                        <img src="<?= route('/assets/images/gallery_69a38c007ee41_1772325800.jpg') ?>" alt="Founder of Sapphire Events" class="w-full h-full object-contain">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="py-20 px-4 relative overflow-hidden">
    <div class="max-w-7xl mx-auto relative">
        <!-- Section Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                <?php echo htmlspecialchars(trans('content.services.why.badge', 'Why Us')); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.services.why.title', 'Why Choose Sapphire Events')); ?>
            </h2>
            <p class="text-base text-gray-600 max-w-xl mx-auto" style="font-family: 'Montserrat', sans-serif;">
                <?php echo htmlspecialchars(trans('content.services.why.description', 'Expertise, creativity, and unwavering attention to detail.')); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Feature 1 -->
            <div class="group relative bg-white rounded-2xl p-6 text-center transition-all duration-500 hover:shadow-xl hover:-translate-y-2" data-aos="fade-up" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500" style="background: linear-gradient(90deg, #0F3D3E, #C8A951);"></div>
                <div class="w-14 h-14 rounded-xl mx-auto mb-5 flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                    <i class="fas fa-medal text-xl" style="color: #C8A951;"></i>
                </div>
                <h4 class="font-bold mb-3" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.services.why.feature_1_title', 'Proven Experience')); ?></h4>
                <p class="text-gray-600 text-sm leading-relaxed">
                    <?php echo htmlspecialchars(trans('content.services.why.feature_1_desc', 'Hundreds of successful events spanning weddings and corporate functions.')); ?>
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="group relative bg-white rounded-2xl p-6 text-center transition-all duration-500 hover:shadow-xl hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500" style="background: linear-gradient(90deg, #0F3D3E, #C8A951);"></div>
                <div class="w-14 h-14 rounded-xl mx-auto mb-5 flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                    <i class="fas fa-lightbulb text-xl" style="color: #C8A951;"></i>
                </div>
                <h4 class="font-bold mb-3" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.services.why.feature_2_title', 'Creative Vision')); ?></h4>
                <p class="text-gray-600 text-sm leading-relaxed">
                    <?php echo htmlspecialchars(trans('content.services.why.feature_2_desc', 'Fresh ideas and innovative solutions for unique events.')); ?>
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="group relative bg-white rounded-2xl p-6 text-center transition-all duration-500 hover:shadow-xl hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500" style="background: linear-gradient(90deg, #0F3D3E, #C8A951);"></div>
                <div class="w-14 h-14 rounded-xl mx-auto mb-5 flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                    <i class="fas fa-handshake text-xl" style="color: #C8A951;"></i>
                </div>
                <h4 class="font-bold mb-3" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.services.why.feature_3_title', 'Trusted Partners')); ?></h4>
                <p class="text-gray-600 text-sm leading-relaxed">
                    <?php echo htmlspecialchars(trans('content.services.why.feature_3_desc', 'Relationships with the best vendors and suppliers.')); ?>
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="group relative bg-white rounded-2xl p-6 text-center transition-all duration-500 hover:shadow-xl hover:-translate-y-2" data-aos="fade-up" data-aos-delay="300" style="box-shadow: 0 4px 20px rgba(15, 61, 62, 0.08);">
                <div class="absolute top-0 left-0 right-0 h-1 rounded-t-2xl transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500" style="background: linear-gradient(90deg, #0F3D3E, #C8A951);"></div>
                <div class="w-14 h-14 rounded-xl mx-auto mb-5 flex items-center justify-center transition-all duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, rgba(15, 61, 62, 0.1), rgba(200, 169, 81, 0.1));">
                    <i class="fas fa-star text-xl" style="color: #C8A951;"></i>
                </div>
                <h4 class="font-bold mb-3" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars(trans('content.services.why.feature_4_title', 'Stress-Free Planning')); ?></h4>
                <p class="text-gray-600 text-sm leading-relaxed">
                    <?php echo htmlspecialchars(trans('content.services.why.feature_4_desc', 'We handle details so you can enjoy your event.')); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Packages Section -->
<section class="py-20 px-4" style="background-color: #F8F5F2;">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-10" data-aos="fade-up">
            <div>
                <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                    <?php echo htmlspecialchars(trans('content.services.packages.badge', 'Pricing')); ?>
                </span>
                <h2 class="text-3xl md:text-4xl font-light mb-3" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                    <?php echo htmlspecialchars(trans('content.services.packages.title', 'New Package Concept')); ?>
                </h2>
                <p class="text-base text-gray-600 max-w-2xl" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.services.packages.description', 'Our packages are now structured by category with transparent pricing, clear inclusions, and streamlined booking actions.')); ?>
                </p>
            </div>
            <a href="<?php echo route('/packages'); ?>" class="inline-flex items-center text-sm font-semibold" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;">
                <?php echo htmlspecialchars(trans('content.services.packages.view_all', 'View All Packages')); ?> <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="flex flex-wrap gap-3 mb-8" data-aos="fade-up" data-aos-delay="80">
            <?php foreach (($packageCategories ?? []) as $category): ?>
                <a href="<?php echo route('/packages/' . $category['slug']); ?>" class="inline-flex items-center px-4 py-2 rounded-full text-xs font-semibold transition-all duration-300 hover:shadow-md" style="background-color: rgba(15, 61, 62, 0.08); color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;">
                    <?php echo htmlspecialchars($category['name']); ?> (<?php echo (int)($category['package_count'] ?? 0); ?>)
                </a>
            <?php endforeach; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 items-stretch">
            <?php foreach (($featuredPackages ?? []) as $index => $package): ?>
                <article class="service-package-card group relative bg-white rounded-2xl overflow-hidden transition-all duration-500 hover:shadow-2xl flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo $index * 80; ?>">
                    <div class="h-1.5" style="background: linear-gradient(90deg, #0F3D3E 0%, #C8A951 100%);"></div>

                    <div class="relative h-48 overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                        <?php $packageImage = $getPackageImageUrl($package['image'] ?? ''); ?>
                        <?php if (!empty($packageImage)): ?>
                            <img src="<?php echo htmlspecialchars($packageImage); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-gem text-white text-5xl opacity-30"></i>
                            </div>
                        <?php endif; ?>
                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider" style="background-color: rgba(200, 169, 81, 0.95); color: #0F3D3E;">
                            <?php echo htmlspecialchars($package['category_name'] ?? trans('content.services.packages.category_fallback', 'Package')); ?>
                        </span>
                    </div>

                    <div class="p-6 flex flex-col flex-grow">
                        <h3 class="text-2xl font-semibold mb-2" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif;">
                            <?php echo htmlspecialchars($package['title']); ?>
                        </h3>
                        <p class="text-2xl font-bold mb-3" style="color: #C8A951; font-family: 'Cormorant Garamond', serif;">
                            <?php echo htmlspecialchars($formatPackagePrice($package)); ?>
                        </p>
                        <p class="text-gray-600 mb-4 leading-relaxed text-sm line-clamp-3">
                            <?php echo htmlspecialchars($package['description']); ?>
                        </p>

                        <?php if (!empty($package['features'])): ?>
                            <ul class="space-y-2 mb-5 text-sm text-gray-700">
                                <?php foreach (preg_split('/\r\n|\r|\n/', $package['features']) as $feature): ?>
                                    <?php if (trim($feature) !== ''): ?>
                                        <li class="flex items-start">
                                            <i class="fas fa-check-circle mr-2 mt-0.5" style="color: #C8A951;"></i>
                                            <span><?php echo htmlspecialchars(trim($feature)); ?></span>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <div class="mt-auto pt-2 flex items-center justify-between gap-3">
                            <a href="<?php echo route('/packages/' . ($package['category_slug'] ?? '')); ?>" class="inline-flex items-center text-xs font-semibold uppercase tracking-wider" style="color: #0F3D3E; font-family: 'Montserrat', sans-serif;">
                                <?php echo htmlspecialchars(trans('content.services.packages.view_details', 'View Details')); ?> <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                            <a href="<?php echo route('/packages/' . ($package['category_slug'] ?? '')); ?>" class="px-4 py-2 rounded-lg text-xs font-semibold uppercase tracking-wider transition-all duration-300 hover:shadow-lg" style="background-color: #0F3D3E; color: white; font-family: 'Montserrat', sans-serif;">
                                <?php echo htmlspecialchars(trans('content.services.packages.book_package', 'Book Package')); ?>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($featuredPackages ?? [])): ?>
            <div class="text-center py-10 text-gray-500" style="font-family: 'Montserrat', sans-serif;">
                <?php echo htmlspecialchars(trans('content.services.packages.empty', 'Packages will appear here once they are published in the admin panel.')); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <!-- Animated Background -->
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22100%22 height=%22100%22><circle cx=%2250%22 cy=%2250%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    
    <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-32 h-32 rounded-full opacity-10 animate-float" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>
    <div class="absolute bottom-10 right-10 w-40 h-40 rounded-full opacity-10 animate-float-delayed" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>
    
    <div class="max-w-3xl mx-auto text-center relative z-10" data-aos="fade-up">
        <h2 class="text-3xl md:text-4xl font-light mb-6 text-white" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.services.cta.title_main', 'Ready to Create Something')); ?> <span class="italic" style="color: #C8A951;"><?php echo htmlspecialchars(trans('content.services.cta.title_highlight', 'Beautiful?')); ?></span>
        </h2>
        <p class="text-lg mb-10 text-gray-300 leading-relaxed max-w-xl mx-auto" style="font-family: 'Montserrat', sans-serif;">
            <?php echo htmlspecialchars(trans('content.services.cta.description', "Let's discuss your event vision and create a customized plan.")); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="<?php echo route('/contact'); ?>" class="group px-10 py-4 rounded-xl font-semibold transition-all duration-300 hover:shadow-2xl hover:scale-105" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.85rem;">
                <?php echo htmlspecialchars(trans('content.services.cta.primary', 'Get Started')); ?>
                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-1 transition-transform"></i>
            </a>
            <a href="<?php echo route('/faqs'); ?>" class="px-10 py-4 rounded-xl font-semibold border-2 border-white/30 text-white transition-all duration-300 hover:bg-white/10" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.1em; text-transform: uppercase; font-size: 0.85rem;">
                <?php echo htmlspecialchars(trans('content.services.cta.secondary', 'Learn More')); ?>
            </a>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        new Swiper('.servicesPageSwiper', {
            slidesPerView: 1.08,
            spaceBetween: 16,
            speed: 800,
            loop: true,
            watchSlidesProgress: true,
            keyboard: {
                enabled: true,
                onlyInViewport: true
            },
            navigation: {
                nextEl: '.service-swiper-next',
                prevEl: '.service-swiper-prev'
            },
            autoplay: reduceMotion ? false : {
                delay: 3500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true
            },
            breakpoints: {
                640: {
                    slidesPerView: 1.4,
                    spaceBetween: 18
                },
                900: {
                    slidesPerView: 2,
                    spaceBetween: 22
                },
                1280: {
                    slidesPerView: 3,
                    spaceBetween: 24
                }
            }
        });
    });
</script>

<style>
    /* Line Clamp Utilities */
.line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.service-package-card {
    box-shadow: 0 8px 28px rgba(15, 61, 62, 0.1);
    border: 1px solid rgba(200, 169, 81, 0.12);
}
    
    /* Floating Animations */
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
    
    .service-swiper-btn {
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

    .service-swiper-btn:hover {
        background: #C8A951;
        color: #0F3D3E;
        box-shadow: 0 10px 22px rgba(15, 61, 62, 0.22);
    }

    .service-swiper-prev {
        left: -4px;
    }

    .service-swiper-next {
        right: -4px;
    }
    
    /* Responsive Carousel */
    @media (max-width: 768px) {
        .service-swiper-btn {
            width: 38px;
            height: 38px;
        }
    }
    
    /* Smooth Scroll */
    html {
        scroll-behavior: smooth;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #F8F5F2;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #0F3D3E, #C8A951);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #0F3D3E;
    }
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>


