<?php
$title = "Packages";
ob_start();

$formatPrice = static function ($pkg) {
    if (!empty($pkg['price_label'])) {
        return $pkg['price_label'];
    }

    if ($pkg['price_amount'] !== null && $pkg['price_amount'] !== '') {
        return ($pkg['currency'] ?? 'EUR') . ' ' . number_format((float)$pkg['price_amount'], 0);
    }

    return 'Custom Quote';
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
?>

<section class="relative py-16 md:py-20 px-4 overflow-hidden" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10" data-aos="fade-up">
        <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(200, 169, 81, 0.2); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
            Pricing & Packages
        </span>

        <h1 class="text-4xl md:text-5xl font-light mb-5 leading-tight" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em; color: white;">
            Tailored Event <span class="italic" style="color: #C8A951;">Packages</span>
        </h1>

        <p class="text-base md:text-lg text-gray-300 max-w-3xl mx-auto leading-relaxed" style="font-family: 'Montserrat', sans-serif;">
            Built from our public package references, including tablescape setups, proposal experiences, and consultation support. Choose a category to compare options and request booking.
        </p>
    </div>
</section>

<section class="py-20 px-4" style="background-color: #F8F5F2;">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                Package Categories
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                Explore by Category
            </h2>
            <p class="text-base text-gray-600 max-w-2xl mx-auto" style="font-family: 'Montserrat', sans-serif;">
                Each category groups packages with clear scope, pricing guidance, and inclusions so you can choose confidently.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($categories as $index => $category): ?>
                <article class="group bg-white rounded-2xl p-7 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="<?php echo $index * 70; ?>" style="box-shadow: 0 4px 24px rgba(15, 61, 62, 0.08);">
                    <div class="w-12 h-1 rounded-full mb-5" style="background-color: #C8A951;"></div>
                    <h3 class="text-2xl mb-3" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em; font-weight: 600;">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </h3>

                    <p class="text-gray-600 text-sm leading-relaxed mb-5">
                        <?php echo htmlspecialchars($category['description'] ?: 'Discover curated options designed for your event style and budget.'); ?>
                    </p>

                    <div class="space-y-2 mb-6 text-sm text-gray-700">
                        <p><strong>Packages:</strong> <?php echo (int)$category['package_count']; ?></p>
                        <p>
                            <strong>Price Range:</strong>
                            <?php if ($category['min_price'] !== null && $category['max_price'] !== null): ?>
                                EUR <?php echo number_format((float)$category['min_price'], 0); ?> - EUR <?php echo number_format((float)$category['max_price'], 0); ?>
                            <?php else: ?>
                                Customized pricing available
                            <?php endif; ?>
                        </p>
                    </div>

                    <a href="<?php echo route('/packages/' . $category['slug']); ?>" class="inline-flex items-center text-sm font-semibold" style="color: #0F3D3E; letter-spacing: 0.06em; text-transform: uppercase;">
                        View Category <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($categories)): ?>
            <div class="text-center py-10 text-gray-500">No package categories published yet.</div>
        <?php endif; ?>
    </div>
</section>

<section class="py-20 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-14" data-aos="fade-up">
            <span class="inline-block px-4 py-2 rounded-full mb-4 text-xs font-semibold tracking-widest uppercase" style="background-color: rgba(15, 61, 62, 0.1); color: #C8A951; font-family: 'Montserrat', sans-serif; letter-spacing: 0.2em;">
                Highlighted Offers
            </span>
            <h2 class="text-3xl md:text-4xl font-light mb-4" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
                Popular Package Picks
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
                        <h3 class="text-xl mb-2" style="color: #0F3D3E; font-family: 'Cormorant Garamond', serif; font-weight: 600; letter-spacing: -0.02em;">
                            <?php echo htmlspecialchars($package['title']); ?>
                        </h3>
                        <p class="text-2xl mb-4" style="color: #C8A951; font-family: 'Cormorant Garamond', serif; font-weight: 700;">
                            <?php echo htmlspecialchars($formatPrice($package)); ?>
                        </p>
                        <p class="text-sm text-gray-600 line-clamp-3 mb-5"><?php echo htmlspecialchars($package['description']); ?></p>
                        <a href="<?php echo route('/packages/' . $package['category_slug']); ?>" class="inline-flex items-center text-sm font-semibold" style="color: #0F3D3E; letter-spacing: 0.06em; text-transform: uppercase;">
                            Explore <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($featuredPackages)): ?>
            <div class="text-center py-10 text-gray-500">Featured packages are coming soon.</div>
        <?php endif; ?>
    </div>
</section>

<section class="py-16 px-4 text-center" style="background: linear-gradient(135deg, #0F3D3E 0%, #1C1C1C 100%); color: white;">
    <div class="max-w-3xl mx-auto" data-aos="fade-up">
        <h2 class="text-4xl md:text-5xl font-light mb-5" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
            Need a Custom Package?
        </h2>
        <p class="text-gray-300 mb-8">
            Share your guest count, venue, and style goals. We will prepare a tailored recommendation with transparent pricing.
        </p>
        <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center px-8 py-3.5 rounded-lg font-semibold transition-all duration-300 hover:shadow-xl" style="background-color: #C8A951; color: #0F3D3E; letter-spacing: 0.08em; text-transform: uppercase; font-size: 0.85rem;">
            Request Custom Quote
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

