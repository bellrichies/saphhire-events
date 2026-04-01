<?php
$title = sprintf(
    trans('content.package_category_page.page_title', '%s Packages'),
    $category['name']
);
ob_start();

$formatPrice = static function ($pkg) {
    if (!empty($pkg['price_label'])) {
        return $pkg['price_label'];
    }

    if ($pkg['price_amount'] !== null && $pkg['price_amount'] !== '') {
        return ($pkg['currency'] ?? 'EUR') . ' ' . number_format((float)$pkg['price_amount'], 0);
    }

    return trans('content.packages_page.labels.custom_quote', 'Custom Quote');
};

$getImageUrl = static function (?string $image): string {
    if (!$image) {
        return '';
    }

    if (preg_match('/^https?:\/\//', $image) || str_starts_with($image, '/')) {
        return $image;
    }

    return uploadedImageUrl($image);
};

$categoryImageUrl = $getImageUrl($category['image'] ?? null);
?>

<section class="relative py-16 md:py-20 px-4 overflow-hidden" style="<?php echo innerHeroBackgroundStyle(); ?>">
    <?php if ($categoryImageUrl): ?>
        <img src="<?php echo htmlspecialchars($categoryImageUrl); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" class="absolute inset-0 w-full h-full object-cover opacity-20">
    <?php endif; ?>
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    <div class="absolute inset-0" style="background-color: color-mix(in srgb, var(--theme-primary) 80%, transparent);"></div>

    <div class="site-container relative z-10 text-center" data-aos="fade-up">
        <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-widest uppercase" style="background-color: color-mix(in srgb, var(--theme-accent) 20%, transparent); color: var(--theme-accent); letter-spacing: 0.2em; font-family: var(--font-ui);">
            <?php echo htmlspecialchars(trans('content.package_category_page.hero.badge', 'Package Category')); ?>
        </span>
        <h1 class="text-4xl md:text-5xl font-light mb-4 text-white" style="font-family: var(--font-display); letter-spacing: -0.02em;">
            <?php echo htmlspecialchars($category['name']); ?> <?php echo htmlspecialchars(trans('content.package_category_page.hero.title_suffix', 'Packages')); ?>
        </h1>
        <p class="text-gray-300 max-w-3xl mx-auto" style="font-family: var(--font-ui);">
            <?php echo htmlspecialchars($category['description'] ?: trans('content.package_category_page.hero.description_fallback', 'Compare package options and submit your preferred booking request.')); ?>
        </p>
    </div>
</section>

<section class="py-16 md:py-20 px-4" style="background-color: #F8F5F2;">
    <div class="site-container">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10" data-aos="fade-up">
            <a href="<?php echo route('/packages'); ?>" class="inline-flex items-center text-sm font-semibold" style="color: #0F3D3E; letter-spacing: 0.06em; text-transform: uppercase;">
                <i class="fas fa-arrow-left mr-2"></i> <?php echo htmlspecialchars(trans('content.package_category_page.actions.back_to_all', 'All Categories')); ?>
            </a>
            <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center px-5 py-2.5 rounded-lg text-xs font-semibold" style="background-color: #0F3D3E; color: white; letter-spacing: 0.08em; text-transform: uppercase;">
                <?php echo htmlspecialchars(trans('content.package_category_page.actions.need_guidance', 'Need Guidance?')); ?>
            </a>
        </div>

        <?php if ($booked): ?>
            <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border border-green-200" data-aos="fade-up">
                <?php echo htmlspecialchars(trans('content.package_category_page.feedback.booked', 'Package booking request sent. The admin team has been notified and will contact you shortly.')); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($bookingError)): ?>
            <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 border border-red-200" data-aos="fade-up">
                <?php echo htmlspecialchars(trans('content.package_category_page.feedback.error', 'Unable to submit booking. Please review your details and try again.')); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <?php foreach ($packages as $index => $package): ?>
                <article class="bg-white rounded-2xl overflow-hidden transition-all duration-500 hover:-translate-y-1 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="<?php echo $index * 80; ?>" style="box-shadow: 0 4px 24px rgba(15, 61, 62, 0.08);">
                    <div class="grid grid-cols-1 sm:grid-cols-2 min-h-[390px]">
                        <div class="relative h-64 sm:h-auto overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                            <?php $imageUrl = $getImageUrl($package['image'] ?? null); ?>
                            <?php if ($imageUrl): ?>
                                <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center"><i class="fas fa-gem text-white text-5xl opacity-30"></i></div>
                            <?php endif; ?>
                        </div>

                        <div class="p-6 flex flex-col justify-between">
                            <div>
                                <h3 class="text-2xl mb-2" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; font-weight: 600; letter-spacing: -0.02em;">
                                    <?php echo htmlspecialchars($package['title']); ?>
                                </h3>
                                <p class="text-2xl mb-4" style="color: #C8A951; font-family: 'Cormorant Garamond', serif; font-weight: 700;">
                                    <?php echo htmlspecialchars($formatPrice($package)); ?>
                                </p>
                                <p class="text-sm text-gray-700 leading-relaxed mb-4 line-clamp-4"><?php echo htmlspecialchars($package['description']); ?></p>

                                <?php if (!empty($package['features'])): ?>
                                    <ul class="space-y-2 text-sm text-gray-700 mb-5">
                                        <?php foreach (preg_split('/\r\n|\r|\n/', $package['features']) as $feature): ?>
                                            <?php if (trim($feature) !== ''): ?>
                                                <li class="flex items-start gap-2"><i class="fas fa-check-circle mt-1" style="color: #C8A951;"></i><span><?php echo htmlspecialchars(trim($feature)); ?></span></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>

                            <button class="book-package-trigger inline-flex items-center text-xs font-semibold" data-package-id="<?php echo (int)$package['id']; ?>" data-package-title="<?php echo htmlspecialchars($package['title']); ?>" style="color: #0F3D3E; letter-spacing: 0.08em; text-transform: uppercase;">
                                <?php echo htmlspecialchars(trans('content.package_category_page.actions.select_package', 'Select This Package')); ?> <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($packages)): ?>
            <div class="text-center py-10 text-gray-500"><?php echo htmlspecialchars(trans('content.package_category_page.feedback.empty', 'No packages are currently available in this category.')); ?></div>
        <?php endif; ?>
    </div>
</section>

<div id="package-booking-modal" class="fixed inset-0 z-50 hidden bg-black/70 px-4">
    <div class="min-h-full flex items-center justify-center py-8">
        <div class="w-full max-w-xl bg-white rounded-2xl p-7 relative">
            <button id="package-modal-close" type="button" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>

            <h2 class="text-3xl mb-2" style="font-family: 'Cormorant Garamond', serif; color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.package_category_page.modal.title', 'Book Package')); ?></h2>
            <p class="text-sm text-gray-600 mb-6"><?php echo htmlspecialchars(trans('content.package_category_page.modal.selected_prefix', 'Selected:')); ?> <strong id="selected-package-name"></strong></p>

            <form method="POST" action="<?php echo route('/packages/book'); ?>" class="space-y-4">
                <?php echo \App\Core\CSRF::hidden(); ?>
                <input type="hidden" name="package_id" id="selected-package-id" value="">
                <input type="hidden" name="category_slug" value="<?php echo htmlspecialchars($category['slug']); ?>">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.package_category_page.modal.fields.name', 'Full Name')); ?></label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.package_category_page.modal.fields.phone', 'Phone')); ?></label>
                        <input type="text" name="phone" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.package_category_page.modal.fields.email', 'Email')); ?></label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.package_category_page.modal.fields.event_date', 'Preferred Date')); ?></label>
                        <input type="date" name="event_date" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;"><?php echo htmlspecialchars(trans('content.package_category_page.modal.fields.message', 'Details')); ?></label>
                    <textarea name="message" rows="4" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="<?php echo htmlspecialchars(trans('content.package_category_page.modal.fields.message_placeholder', 'Share your event location, guest count, timing, and any special requests.')); ?>"></textarea>
                </div>

                <button type="submit" class="w-full py-3 rounded-lg font-semibold" style="background-color: #0F3D3E; color: white; letter-spacing: 0.08em; text-transform: uppercase;">
                    <?php echo htmlspecialchars(trans('content.package_category_page.modal.submit', 'Send Booking Request')); ?>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('package-booking-modal');
        const closeBtn = document.getElementById('package-modal-close');
        const selectedId = document.getElementById('selected-package-id');
        const selectedName = document.getElementById('selected-package-name');

        document.querySelectorAll('.book-package-trigger').forEach((btn) => {
            btn.addEventListener('click', function () {
                selectedId.value = this.getAttribute('data-package-id');
                selectedName.textContent = this.getAttribute('data-package-title');
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        });

        const closeModal = () => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        };

        closeBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    })();
</script>

<style>
    .line-clamp-4 {
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>

