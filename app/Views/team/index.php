<?php
$title = trans('content.team.page_title', 'Meet Our Team');
ob_start();
?>

<section class="relative py-14 md:py-16 px-4 overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    <div class="absolute top-8 right-16 w-24 h-24 rounded-full opacity-15 animate-float" style="background: radial-gradient(circle, #C8A951 0%, transparent 70%);"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10" data-aos="fade-up">
        <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
            <?php echo htmlspecialchars(trans('content.team.hero.badge', 'The Team')); ?>
        </span>
        <h1 class="text-4xl md:text-5xl font-light mb-4 leading-tight text-white" style="font-family: 'Dancing Script', cursive; letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.team.hero.title', 'Meet the Creative Minds Behind Sapphire Events')); ?>
        </h1>
        <p class="text-base md:text-lg text-gray-300 max-w-3xl mx-auto leading-relaxed" style="font-family: 'Montserrat', sans-serif;">
            <?php echo htmlspecialchars(trans('content.team.hero.description', 'Dedicated professionals committed to transforming your vision into unforgettable celebrations.')); ?>
        </p>
    </div>
</section>

<section class="page-deferred-section py-20 px-4" style="background-color: #F8F5F2;">
    <div class="w-full">
        <!-- Team Member 1: Racheal -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-4" data-aos="fade-up">
            <!-- Image Column (Left) -->
            <div class="flex items-center justify-center order-2 lg:order-1">
                <div class="bg-white  overflow-hidden shadow-lg hover:shadow-xl transition-shadow w-full" style="box-shadow: 0 4px 24px rgba(15, 61, 62, 0.08);">
                    <img src="<?= route('/assets/uploads/media/image/2026/03/ff4cf799393e6b1ba2bd4c2935e2f280.webp') ?>" alt="Racheal - CEO & Creative Director" class="w-full h-auto object-cover aspect-[3/4]" loading="lazy" decoding="async">
                </div>
            </div>
            
            <!-- Info Column (Right) -->
            <div class="md:px-[150px] md:py-[20px] flex flex-col order-1 lg:order-2">
                <h3 class="text-3xl font-bold mb-3" style="color: #0F3D3E; font-family: 'Dancing Script', cursive;">
                    <?php echo htmlspecialchars(trans('content.team.member1.name', 'Racheal')); ?>
                </h3>
                <p class="text-sm font-semibold mb-6 uppercase tracking-wider" style="color: #C8A951; font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.team.member1.role', 'CEO & Creative Director')); ?>
                </p>
                <p class="text-gray-600 leading-relaxed mb-4" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.team.member1.description_1', 'Meet Racheal, the brain, creative director & CEO of Sapphire events.')); ?>
                </p>
                <p class="text-gray-600 leading-relaxed mb-4" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.team.member1.description_2', 'With over 15 years experience in the event management business, Racheal has a passion for creating beautiful spaces, turning dreams into reality and giving you the events of your dreams.')); ?>
                </p>
                <p class="text-gray-600 leading-relaxed mb-8" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.team.member1.description_3', 'Racheal is based in Tallinn, Estonia, but available to travel anywhere within Estonia and Europe.')); ?>
                </p>
                <div class="pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-4" style="font-family: 'Montserrat', sans-serif;">
                        <?php echo htmlspecialchars(trans('content.team.member1.cta_text', 'Ready to start planning & decorating? Fill out our contact form.')); ?>
                    </p>
                    <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-md" style="background-color: #FFFFFF; color: #C8A951; border: 2px solid #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase; font-size: 0.75rem; box-shadow: 0 2px 8px rgba(200, 169, 81, 0.15);">
                        <?php echo htmlspecialchars(trans('content.team.member1.cta_button', 'Book an Appointment')); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Team Member 2: Israel -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center" data-aos="fade-up" data-aos-delay="100">
            <!-- Info Column (Left) -->
            <div class="md:px-[150px] md:py-[20px] flex flex-col justify-center">
                <h3 class="text-3xl font-bold mb-3" style="color: #0F3D3E; font-family: 'Dancing Script', cursive;">
                    <?php echo htmlspecialchars(trans('content.team.member2.name', 'Israel')); ?>
                </h3>
                <p class="text-sm font-semibold mb-6 uppercase tracking-wider" style="color: #C8A951; font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.team.member2.role', 'Co-Creative Director')); ?>
                </p>
                <p class="text-gray-600 leading-relaxed mb-4" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.team.member2.description_1', 'Meet Israel, the driving force behind our seamless event operations at Sapphire Events! As our Co-Creative Director, he expertly oversees staff, logistics, coordination, and management, ensuring every event unfolds with precision and flair.')); ?>
                </p>
                <p class="text-gray-600 leading-relaxed mb-4" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.team.member2.description_2', 'With a solid background in events and catering, Israel brings a wealth of experience, a sharp eye for detail, and a knack for turning chaos into calm. Whether it\'s crafting efficient workflows or solving last-minute surprises, he thrives on keeping everything and everyone in perfect sync.')); ?>
                </p>
                <p class="text-gray-600 leading-relaxed mb-4" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.team.member2.description_3', 'Israel\'s energy and professionalism are matched by his personable nature, making him a favorite among our clients and team alike. When he\'s not orchestrating picture-perfect events, you can find him brainstorming creative solutions or sharing his love for great food and flawless celebrations.')); ?>
                </p>
                <p class="text-gray-600 leading-relaxed mb-8" style="font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.team.member2.description_4', 'At Sapphire Events, Israel isn\'t just part of the team – he\'s the heart of the hustle, ensuring every event is an unforgettable experience!')); ?>
                </p>
                <div class="pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500 mb-4" style="font-family: 'Montserrat', sans-serif;">
                        <?php echo htmlspecialchars(trans('content.team.member2.cta_text', 'Ready to work with the perfect team? Get in touch today.')); ?>
                    </p>
                    <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 hover:shadow-md" style="background-color: #FFFFFF; color: #C8A951; border: 2px solid #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase; font-size: 0.75rem; box-shadow: 0 2px 8px rgba(200, 169, 81, 0.15);">
                        <?php echo htmlspecialchars(trans('content.team.member2.cta_button', 'Get in Touch')); ?>
                    </a>
                </div>
            </div>
            
            <!-- Image Column (Right) -->
            <div class="flex items-center justify-center">
                <div class="bg-white  overflow-hidden shadow-lg hover:shadow-xl transition-shadow w-full" style="box-shadow: 0 4px 24px rgba(15, 61, 62, 0.08);">
                    <img src="<?= route('/assets/uploads/media/image/2026/03/3ca78fbe9945a78d2eb8bad16230af52.avif') ?>" alt="Israel - Co-Creative Director" class="w-full h-auto object-cover aspect-[3/4]" loading="lazy" decoding="async">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="page-deferred-section py-10 md:py-8 px-4 text-center" style="background: linear-gradient(135deg, #F8F5F2 0%, #ffffff 100%);">
    <div class="max-w-4xl mx-auto" data-aos="fade-up">
        <h2 class="text-xl md:text-2xl font-semibold mb-3" style="font-family: 'Cormorant Garamond', serif; color: #0F3D3E; letter-spacing: -0.01em;">
            <?php echo htmlspecialchars(trans('content.team.cta.title', 'Ready to Collaborate with Our Team?')); ?>
        </h2>
        <p class="text-sm md:text-base text-gray-600 mb-5 leading-relaxed max-w-2xl mx-auto" style="font-family: 'Montserrat', sans-serif;">
            <?php echo htmlspecialchars(trans('content.team.cta.description', 'Let us bring our expertise and passion to your next event. We can\'t wait to create something magical together.')); ?>
        </p>
        <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center justify-center px-7 py-3 rounded-lg font-bold transition-all duration-300 hover:shadow-md" style="background-color: #FFFFFF; color: #C8A951; border: 2px solid #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase; font-size: 0.75rem; box-shadow: 0 2px 8px rgba(200, 169, 81, 0.15);">
            <?php echo htmlspecialchars(trans('content.team.cta.button', 'Schedule Your Consultation')); ?>
        </a>
    </div>
</section>

<style>
    .page-deferred-section {
        content-visibility: auto;
        contain-intrinsic-size: 1px 880px;
    }
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>
