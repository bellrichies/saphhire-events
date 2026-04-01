<?php
$title = trans('content.packages_page.page_title', 'Packages');
ob_start();

$categoryCountLabel = trans('content.packages_page.categories.labels.packages', 'Packages:');
$priceRangeLabel = trans('content.packages_page.categories.labels.price_range', 'Price Range:');
$customizedPricingLabel = trans('content.packages_page.categories.labels.customized_pricing', 'Customized pricing available');
$currencyCode = trans('content.packages_page.categories.labels.currency_code', 'EUR');

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
    return uploadedImageUrl($image);
};
?>

<section class="relative py-16 md:py-20 px-4 overflow-hidden" style="<?php echo innerHeroBackgroundStyle(); ?>">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10" data-aos="fade-up">
        <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-widest uppercase" style="background-color: color-mix(in srgb, var(--theme-accent) 20%, transparent); color: var(--theme-accent); font-family: var(--font-ui); letter-spacing: 0.2em;">
            <?php echo htmlspecialchars(trans('content.packages_page.hero.badge', 'Pricing & Packages')); ?>
        </span>

        <h1 class="text-4xl md:text-5xl font-light mb-5 leading-tight" style="font-family: var(--font-display); letter-spacing: -0.02em; color: white;">
            <?php echo htmlspecialchars(trans('content.packages_page.hero.title_main', 'Tailored Event')); ?> <span class="italic" style="color: var(--theme-accent);"><?php echo htmlspecialchars(trans('content.packages_page.hero.title_highlight', 'Packages')); ?></span>
        </h1>

        <p class="text-base md:text-lg text-gray-300 max-w-3xl mx-auto leading-relaxed" style="font-family: var(--font-ui);">
            <?php echo htmlspecialchars(trans('content.packages_page.hero.description', 'Built from our public package references, including tablescape setups, proposal experiences, and consultation support. Choose a category to compare options and request booking.')); ?>
        </p>
    </div>
</section>

<section class="py-20 px-4" style="background-color: #F8F5F2;">
    <div class="site-container">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                <?php echo htmlspecialchars(trans('content.packages_page.categories.badge', 'Package Categories')); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Dancing Script', cursive; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.packages_page.categories.title', 'Explore by Category')); ?>
            </h2>
            <p class="text-base text-gray-600 max-w-2xl mx-auto" style="font-family: 'Montserrat', sans-serif;">
                <?php echo htmlspecialchars(trans('content.packages_page.categories.description', 'Each category groups packages with clear scope, pricing guidance, and inclusions so you can choose confidently.')); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($categories as $index => $category): ?>
                <?php $categoryImageUrl = $getImageUrl($category['image'] ?? null); ?>
                <article class="group bg-white rounded-2xl overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="<?php echo $index * 70; ?>" style="box-shadow: 0 4px 24px rgba(15, 61, 62, 0.08);">
                    <div class="relative aspect-[4/3] overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                        <?php if ($categoryImageUrl): ?>
                            <img src="<?php echo htmlspecialchars($categoryImageUrl); ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-images text-white text-5xl opacity-30"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/45 to-transparent"></div>
                    </div>

                    <div class="p-7">
                        <div class="w-12 h-1 rounded-full mb-5" style="background-color: #C8A951;"></div>
                        <h3 class="text-2xl mb-3" style="color: #0F3D3E; font-family: 'Dancing Script', cursive; letter-spacing: -0.02em; font-weight: 600;">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </h3>

                        <p class="text-gray-600 text-sm leading-relaxed mb-5">
                            <?php echo htmlspecialchars($category['description'] ?: trans('content.packages_page.categories.card_fallback_description', 'Discover curated options designed for your event style and budget.')); ?>
                        </p>

                        <div class="space-y-2 mb-6 text-sm text-gray-700">
                            <p><strong><?php echo htmlspecialchars($categoryCountLabel); ?></strong> <?php echo (int)$category['package_count']; ?></p>
                            <p>
                                <strong><?php echo htmlspecialchars($priceRangeLabel); ?></strong>
                                <?php if ($category['min_price'] !== null && $category['max_price'] !== null): ?>
                                    <?php echo htmlspecialchars($currencyCode); ?> <?php echo number_format((float)$category['min_price'], 0); ?> - <?php echo htmlspecialchars($currencyCode); ?> <?php echo number_format((float)$category['max_price'], 0); ?>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($customizedPricingLabel); ?>
                                <?php endif; ?>
                            </p>
                        </div>

                        <a href="<?php echo route('/packages/' . $category['slug']); ?>" class="inline-flex items-center text-sm font-semibold" style="color: #0F3D3E; letter-spacing: 0.06em; text-transform: uppercase;">
                            <?php echo htmlspecialchars(trans('content.packages_page.categories.view_category', 'View Category')); ?> <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($categories)): ?>
            <div class="text-center py-10 text-gray-500"><?php echo htmlspecialchars(trans('content.packages_page.categories.empty', 'No package categories published yet.')); ?></div>
        <?php endif; ?>
    </div>
</section>

<section class="py-20 px-4">
    <div class="site-container">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                <?php echo htmlspecialchars(trans('content.packages_page.featured.badge', 'Highlighted Offers')); ?>
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Dancing Script', cursive; letter-spacing: -0.02em;">
                <?php echo htmlspecialchars(trans('content.packages_page.featured.title', 'Popular Package Picks')); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($featuredPackages as $index => $package): ?>
                <article class="bg-white rounded-2xl overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl" data-aos="fade-up" data-aos-delay="<?php echo $index * 70; ?>" style="box-shadow: 0 4px 24px rgba(15, 61, 62, 0.08);">
                    <div class="relative aspect-[3/4] overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #2d5a5b 100%);">
                        <?php $imageUrl = $getImageUrl($package['image'] ?? null); ?>
                        <?php if ($imageUrl): ?>
                            <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-gem text-white text-5xl opacity-30"></i></div>
                        <?php endif; ?>
                        <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold" style="background: rgba(200, 169, 81, 0.95); color: #0F3D3E;">
                            <?php echo htmlspecialchars($package['category_name']); ?>
                        </span>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl mb-2" style="color: #0F3D3E; font-family: 'Dancing Script', cursive; font-weight: 600; letter-spacing: -0.02em;">
                            <?php echo htmlspecialchars($package['title']); ?>
                        </h3>
                        <p class="text-2xl mb-5" style="color: #C8A951; font-family: 'Cormorant Garamond', serif; font-weight: 700;">
                            <?php echo htmlspecialchars($formatPrice($package)); ?>
                        </p>
                        <a href="<?php echo route('/contact?package=' . urlencode($package['id'])); ?>" class="inline-flex items-center text-sm font-semibold" style="color: #fff; background: #0F3D3E; padding: 0.75rem 1.5rem; border-radius: 0.5rem; letter-spacing: 0.06em; text-transform: uppercase; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#C8A951'; this.style.color='#0F3D3E';" onmouseout="this.style.backgroundColor='#0F3D3E'; this.style.color='#fff';">
                            <?php echo htmlspecialchars(trans('content.packages_page.featured.book_now', 'Book Now')); ?> <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($featuredPackages)): ?>
            <div class="text-center py-10 text-gray-500"><?php echo htmlspecialchars(trans('content.packages_page.featured.empty', 'Featured packages are coming soon.')); ?></div>
        <?php endif; ?>
    </div>
</section>

<section class="py-10 md:py-8 px-4 text-center" style="background: linear-gradient(135deg, #F8F5F2 0%, #ffffff 100%);">
    <div class="max-w-3xl mx-auto" data-aos="fade-up">
        <h2 class="text-2xl md:text-3xl font-semibold mb-3" style="font-family: 'Cormorant Garamond', serif;  letter-spacing: -0.01em;">
            <?php echo htmlspecialchars(trans('content.packages_page.cta.title', 'Need a Custom Package?')); ?>
        </h2>
        <p class="text-sm md:text-base text-gray-500 mb-5 max-w-2xl mx-auto leading-relaxed">
            <?php echo htmlspecialchars(trans('content.packages_page.cta.description', 'Share your guest count, venue, and style goals. We will prepare a tailored recommendation with transparent pricing.')); ?>
        </p>
        <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center justify-center px-7 py-3 rounded-lg font-bold transition-all duration-300 hover:shadow-md" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.06em; text-transform: uppercase; font-size: 0.75rem; box-shadow: 0 2px 8px rgba(200, 169, 81, 0.25);">
            <?php echo htmlspecialchars(trans('content.packages_page.cta.button', 'Request Custom Quote')); ?>
        </a>
    </div>
</section>

<style>
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>
