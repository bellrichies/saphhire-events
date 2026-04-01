<?php
$title = trans('content.gallery_page.page_title', 'Gallery');
ob_start();

$paginationPreviousAria = trans('content.gallery_page.pagination.previous_aria', 'Previous page');
$paginationNextAria = trans('content.gallery_page.pagination.next_aria', 'Next page');
$galleryItemFallbackTitle = trans('content.gallery_page.labels.item_fallback_title', 'Gallery item');
$zoomAria = trans('content.gallery_page.lightbox.zoom_aria', 'Open media');
$closeLightboxAria = trans('content.gallery_page.lightbox.close_aria', 'Close gallery');
$previousLightboxAria = trans('content.gallery_page.lightbox.previous_aria', 'Previous item');
$nextLightboxAria = trans('content.gallery_page.lightbox.next_aria', 'Next item');

$getGalleryMediaUrl = static function (?string $media): string {
    if (!$media) {
        return '';
    }

    if (preg_match('/^https?:\/\//', $media)) {
        return $media;
    }

    return uploadedImageUrl($media);
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

$getGalleryMediaType = static function (?string $media) use ($getYoutubeEmbedUrl): string {
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

$lightboxItems = array_map(static function (array $item) use ($getGalleryMediaUrl, $getGalleryMediaType, $getYoutubeEmbedUrl): array {
    $item['media_type'] = $getGalleryMediaType($item['image'] ?? null);
    $item['media_url'] = $item['media_type'] === 'youtube'
        ? $getYoutubeEmbedUrl($item['image'] ?? null)
        : $getGalleryMediaUrl($item['image'] ?? null);
    return $item;
}, $items ?? []);
?>

<!-- Hero Section -->
<section class="relative py-14 md:py-16 px-4 overflow-hidden" style="<?php echo innerHeroBackgroundStyle(); ?>">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2260%22 height=%2260%22><circle cx=%2230%22 cy=%2230%22 r=%222%22 fill=%22%23C8A951%22/></svg>');"></div>
    <div class="absolute top-8 right-16 w-24 h-24 rounded-full opacity-15 animate-float" style="background: radial-gradient(circle, var(--theme-accent) 0%, transparent 70%);"></div>

    <div class="max-w-5xl mx-auto text-center relative z-10" data-aos="fade-up">
        <span class="inline-block px-4 py-2 rounded-full mb-5 text-xs font-semibold tracking-widest uppercase" style="background-color: color-mix(in srgb, var(--theme-accent) 20%, transparent); color: var(--theme-accent); font-family: var(--font-ui); letter-spacing: 0.2em;">
            <?php echo htmlspecialchars(trans('content.gallery_page.hero.badge', 'Our Portfolio')); ?>
        </span>
        <h1 class="text-4xl md:text-5xl font-light mb-4 leading-tight text-white" style="font-family: var(--font-display); letter-spacing: -0.02em;">
            <?php echo htmlspecialchars(trans('content.gallery_page.hero.title', 'Gallery of Elegant Celebrations')); ?>
        </h1>
        <p class="text-base md:text-lg text-gray-300 max-w-3xl mx-auto leading-relaxed" style="font-family: var(--font-ui);">
            <?php echo htmlspecialchars(trans('content.gallery_page.hero.description', 'Discover curated highlights from weddings, proposals, birthdays, and corporate events crafted with precision and style.')); ?>
        </p>
    </div>
</section>

<!-- Gallery Section with Filter Tabs -->
<section class="py-20 bg-[#FAFAFA]">
    <div class="px-4 md:px-8">
        <div class="w-full ">
            <!-- <div class="text-center mb-8" data-aos="fade-up">
                <h2 class="text-4xl md:text-5xl mb-3 text-[#0F3D3E]" style="font-family: 'Dancing Script', cursive; font-weight: 600;">Featured Events</h2>
                <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto">
                    Filter by event type and explore curated visual highlights from our latest productions.
                </p>
            </div> -->

            <!-- Category Filter Tabs - Modern Pill Style -->
            <div class="flex flex-wrap justify-center gap-3 mb-16" id="category-tabs">
                <button 
                    class="filter-tab active px-6 py-3 rounded-full font-medium text-sm transition-all duration-300 ease-out"
                    data-category="all"
                    type="button"
                    aria-pressed="true"
                    onclick="filterGallery('all')">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                        <?php echo htmlspecialchars(trans('content.gallery_page.filters.all_events', 'All Events')); ?>
                    </span>
                </button>
                <?php foreach ($categories as $cat): ?>
                    <button 
                        class="filter-tab px-6 py-3 rounded-full font-medium text-sm transition-all duration-300 ease-out"
                        data-category="<?php echo $cat['id']; ?>"
                        type="button"
                        aria-pressed="false"
                        onclick="filterGallery('<?php echo $cat['id']; ?>')">
                        <?php echo htmlspecialchars($cat['name']); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="flex justify-center mb-10">
                <p id="gallery-results-text" class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-semibold tracking-wider uppercase" style="background-color: rgba(15, 61, 62, 0.08); color: #0F3D3E; font-family: 'Montserrat', sans-serif;">
                    <?php echo htmlspecialchars(trans('content.gallery_page.results.showing_prefix', 'Showing')); ?> <?php echo count($items ?? []); ?> <?php echo htmlspecialchars(trans('content.gallery_page.results.events_plural', 'Events')); ?>
                </p>
            </div>

            <!-- Gallery Grid - Bold Postcard Style -->
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4" id="gallery-grid">
                <?php foreach ($items as $index => $item): ?>
                    <article 
                        class="gallery-card group relative bg-white overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer"
                        data-category="<?php echo $item['category_id']; ?>"
                        data-index="<?php echo $index; ?>"
                        data-aos="fade-up"
                        data-aos-delay="<?php echo ($index % 4) * 50; ?>"
                        onclick="openLightbox(<?php echo $index; ?>)">
                        
                        <!-- Image Container with Portrait Aspect Ratio -->
                        <div class="relative aspect-[3/4] overflow-hidden bg-gray-900">
                            
                            <?php $mediaUrl = $getGalleryMediaUrl($item['image'] ?? null); ?>
                            <?php $mediaType = $getGalleryMediaType($item['image'] ?? null); ?>
                            <?php if (!empty($mediaUrl) && $mediaType === 'youtube'): ?>
                                <img
                                    src="<?php echo htmlspecialchars($getYoutubeThumbnailUrl($item['image'] ?? null)); ?>"
                                    alt="<?php echo htmlspecialchars($item['title'] ?: $galleryItemFallbackTitle); ?>"
                                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    loading="lazy">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition-all duration-300 z-20">
                                    <div class="w-16 h-16 rounded-full bg-red-600/90 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110">
                                        <i class="fab fa-youtube text-white text-3xl"></i>
                                    </div>
                                </div>
                            <?php elseif (!empty($mediaUrl) && $mediaType === 'video'): ?>
                                <video
                                    src="<?php echo htmlspecialchars($mediaUrl); ?>"
                                    class="gallery-video absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    muted
                                    loop
                                    playsinline
                                    preload="metadata"
                                    data-playing="false">
                                </video>
                                
                                <!-- Play Button Overlay for Video -->
                                <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition-all duration-300 z-20">
                                    <div class="w-16 h-16 rounded-full bg-white/90 flex items-center justify-center transform transition-transform duration-300 group-hover:scale-110 group-hover:bg-[#C8A951]">
                                        <svg class="w-8 h-8 text-[#0F3D3E] ml-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"></path>
                                        </svg>
                                    </div>
                                </div>
                            <?php elseif (!empty($mediaUrl)): ?>
                                <img 
                                    src="<?php echo htmlspecialchars($mediaUrl); ?>" 
                                    alt="<?php echo htmlspecialchars($item['title'] ?: $galleryItemFallbackTitle); ?>"
                                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    loading="lazy">
                                
                                <!-- Overlay gradient for images -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <?php else: ?>
                                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-700 to-gray-900">
                                    <svg class="w-16 h-16 text-white/30" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/></svg>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Zoom Icon -->
                            <div class="absolute top-4 right-4 z-30 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                <button type="button" aria-label="<?php echo htmlspecialchars($zoomAria); ?>" class="w-10 h-10 bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-[#C8A951] hover:text-[#0F3D3E] transition-colors" onclick="event.stopPropagation(); openLightbox(<?php echo $index; ?>)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path></svg>
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <div id="filtered-empty-state" class="hidden text-center py-20">
                <div class="w-20 h-20 mx-auto mb-5 rounded-full bg-[#F8F5F2] flex items-center justify-center">
                    <svg class="w-10 h-10 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                </div>
                <h3 class="text-2xl font-bold text-[#0F3D3E] mb-2" style="font-family: 'Cormorant Garamond', serif;"><?php echo htmlspecialchars(trans('content.gallery_page.empty_filtered.title', 'No Matching Events')); ?></h3>
                <p class="text-gray-600 mb-6"><?php echo htmlspecialchars(trans('content.gallery_page.empty_filtered.description', 'Try another category or reset the filter.')); ?></p>
                <button type="button" onclick="filterGallery('all')" class="px-6 py-3 rounded-full bg-[#0F3D3E] text-white font-medium hover:bg-[#C8A951] hover:text-[#0F3D3E] transition-all">
                    <?php echo htmlspecialchars(trans('content.gallery_page.empty_filtered.button', 'Show All Events')); ?>
                </button>
            </div>

            <!-- Empty State -->
            <?php if (empty($items)): ?>
                <div class="text-center py-20">
                    <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-[#F8F5F2] flex items-center justify-center">
                        <svg class="w-12 h-12 text-[#C8A951]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-[#0F3D3E] mb-2" style="font-family: 'Cormorant Garamond', serif;"><?php echo htmlspecialchars(trans('content.gallery_page.empty.title', 'No Events Found')); ?></h3>
                    <p class="text-gray-600 mb-6"><?php echo htmlspecialchars(trans('content.gallery_page.empty.description', 'There are no events in this category yet. Check back soon!')); ?></p>
                    <button onclick="filterGallery('all')" class="px-6 py-3 rounded-full bg-[#0F3D3E] text-white font-medium hover:bg-[#C8A951] hover:text-[#0F3D3E] transition-all">
                        <?php echo htmlspecialchars(trans('content.gallery_page.empty.button', 'View All Events')); ?>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Pagination - Modern Style -->
            <?php if ($totalPages > 1): ?>
                <div class="flex justify-center items-center gap-2 mt-16">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?php echo $currentPage - 1; ?><?php echo $currentCategory ? "&category=$currentCategory" : ''; ?>" 
                        aria-label="<?php echo htmlspecialchars($paginationPreviousAria); ?>"
                        class="w-10 h-10 rounded-full flex items-center justify-center bg-white border border-gray-200 text-[#0F3D3E] hover:bg-[#0F3D3E] hover:text-white hover:border-[#0F3D3E] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo $currentCategory ? "&category=$currentCategory" : ''; ?>" 
                        class="w-10 h-10 rounded-full flex items-center justify-center font-medium transition-all <?php echo $i === $currentPage ? 'bg-[#C8A951] text-[#0F3D3E]' : 'bg-white border border-gray-200 text-[#0F3D3E] hover:bg-[#0F3D3E] hover:text-white hover:border-[#0F3D3E]'; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?><?php echo $currentCategory ? "&category=$currentCategory" : ''; ?>" 
                        aria-label="<?php echo htmlspecialchars($paginationNextAria); ?>"
                        class="w-10 h-10 rounded-full flex items-center justify-center bg-white border border-gray-200 text-[#0F3D3E] hover:bg-[#0F3D3E] hover:text-white hover:border-[#0F3D3E] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        </div>
    </div>
</section>

<!-- Event Categories Showcase -->
<!-- <section class="py-20 px-4 bg-white">
    <div class="site-container">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-2 rounded-full text-xs font-semibold uppercase tracking-wider mb-4" style="background: rgba(200, 169, 81, 0.15); color: #C8A951;">What We Do</span>
            <h2 class="text-4xl md:text-5xl mb-4 text-[#0F3D3E]" style="font-family: 'Cormorant Garamond', serif; font-weight: 600;">Event Categories</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                From intimate gatherings to grand celebrations, we specialize in creating unforgettable experiences.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="group relative bg-gradient-to-br from-[#0F3D3E] to-[#1a5f60] rounded-2xl p-8 text-white overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-2" data-aos="fade-up">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#C8A951]/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-6 group-hover:bg-[#C8A951] group-hover:text-[#0F3D3E] transition-all duration-300">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z" clip-rule="evenodd"/><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="font-family: 'Cormorant Garamond', serif;">Weddings</h3>
                    <p class="text-gray-300 text-sm leading-relaxed mb-4">Elegant ceremonies and receptions tailored to your love story.</p>
                    <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center gap-2 text-[#C8A951] text-sm font-semibold hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

            <div class="group relative bg-gradient-to-br from-[#1C1C1C] to-[#2d2d2d] rounded-2xl p-8 text-white overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#C8A951]/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-6 group-hover:bg-[#C8A951] group-hover:text-[#0F3D3E] transition-all duration-300">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="font-family: 'Cormorant Garamond', serif;">Corporate</h3>
                    <p class="text-gray-300 text-sm leading-relaxed mb-4">Professional events that elevate your brand and impress guests.</p>
                    <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center gap-2 text-[#C8A951] text-sm font-semibold hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

            <div class="group relative bg-gradient-to-br from-[#C8A951] to-[#d4b86a] rounded-2xl p-8 text-[#0F3D3E] overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-xl bg-white/30 flex items-center justify-center mb-6 group-hover:bg-[#0F3D3E] group-hover:text-white transition-all duration-300">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z"/><path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="font-family: 'Cormorant Garamond', serif;">Birthdays</h3>
                    <p class="text-[#0F3D3E]/70 text-sm leading-relaxed mb-4">Creative celebrations for milestones of all ages.</p>
                    <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center gap-2 text-[#0F3D3E] text-sm font-semibold hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>

            <div class="group relative bg-gradient-to-br from-[#0F3D3E] to-[#C8A951] rounded-2xl p-8 text-white overflow-hidden hover:shadow-2xl transition-all duration-500 hover:-translate-y-2" data-aos="fade-up" data-aos-delay="300">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <div class="w-14 h-14 rounded-xl bg-white/10 flex items-center justify-center mb-6 group-hover:bg-white group-hover:text-[#0F3D3E] transition-all duration-300">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3" style="font-family: 'Cormorant Garamond', serif;">Special Events</h3>
                    <p class="text-gray-200 text-sm leading-relaxed mb-4">Proposals, anniversaries, baby showers, and more.</p>
                    <a href="<?php echo route('/contact'); ?>" class="inline-flex items-center gap-2 text-[#C8A951] text-sm font-semibold hover:gap-3 transition-all">
                        Learn More
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section> -->


<!-- CTA Section -->
<!-- <section class="relative py-14 md:py-16 px-4 overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#0F3D3E] via-[#1a5f60] to-[#0F3D3E]"></div>
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\'40\' height=\'40\' viewBox=\'0 0 40 40\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23C8A951\' fill-opacity=\'0.4\'%3E%3Cpath fill-rule=\'evenodd\' d=\'M0 40L40 0H20L0 20M40 40V20L20 40\'/%3E%3C/g%3E%3C/svg%3E');"></div>
    
    <div class="absolute top-0 left-0 w-64 h-64 bg-[#C8A951]/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-[#C8A951]/5 rounded-full translate-x-1/2 translate-y-1/2"></div>
    
    <div class="relative z-10 max-w-3xl mx-auto text-center">
        <h2 class="text-3xl md:text-4xl font-light mb-4 text-white" style="font-family: 'Cormorant Garamond', serif; letter-spacing: -0.02em;">
            Ready to Create Your <span class="italic text-[#C8A951]">Perfect Event?</span>
        </h2>
        <p class="text-base md:text-lg mb-7 text-gray-300 leading-relaxed max-w-2xl mx-auto">
            Inspired by our portfolio? Let's discuss your vision and create something beautiful together.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="<?php echo route('/contact'); ?>" class="group px-8 py-3.5 rounded-full font-semibold transition-all duration-300 inline-flex items-center justify-center gap-2" style="background-color: #C8A951; color: #0F3D3E; font-family: 'Montserrat', sans-serif; letter-spacing: 0.05em; text-transform: uppercase; font-size: 0.8rem;">
                Start Your Event
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
            <a href="<?php echo route('/services'); ?>" class="px-8 py-3.5 rounded-full font-semibold border-2 border-white/30 text-white transition-all duration-300 hover:bg-white hover:text-[#0F3D3E] inline-flex items-center justify-center gap-2" style="font-family: 'Montserrat', sans-serif; letter-spacing: 0.05em; text-transform: uppercase; font-size: 0.8rem;">
                Explore Services
            </a>
        </div>
    </div>
</section> -->

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/95 backdrop-blur-md" onclick="closeLightbox()"></div>
    
    <!-- Content -->
    <div class="relative w-full h-full flex items-center justify-center p-4 md:p-8">
        <!-- Close Button -->
        <button aria-label="<?php echo htmlspecialchars($closeLightboxAria); ?>" onclick="closeLightbox()" class="absolute top-4 right-4 md:top-6 md:right-6 z-50 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-[#C8A951] hover:text-[#0F3D3E] transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <!-- Navigation -->
        <button aria-label="<?php echo htmlspecialchars($previousLightboxAria); ?>" onclick="navigateLightbox(-1)" class="absolute left-2 md:left-6 top-1/2 -translate-y-1/2 z-50 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-[#C8A951] hover:text-[#0F3D3E] transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        
        <button aria-label="<?php echo htmlspecialchars($nextLightboxAria); ?>" onclick="navigateLightbox(1)" class="absolute right-2 md:right-6 top-1/2 -translate-y-1/2 z-50 w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-[#C8A951] hover:text-[#0F3D3E] transition-all">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        </button>
        
        <!-- Image Container -->
        <div class="relative max-w-5xl w-full mx-auto">
            <img id="lightbox-image" src="" alt="" class="w-full h-auto max-h-[75vh] object-contain rounded-lg shadow-2xl">
            <video id="lightbox-video" src="" class="hidden w-full h-auto max-h-[75vh] object-contain rounded-lg shadow-2xl" controls playsinline></video>
            <iframe id="lightbox-youtube" src="" class="hidden w-full h-[75vh] rounded-lg shadow-2xl bg-black" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen title="YouTube video"></iframe>
            
            <!-- Info Panel -->
            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/90 via-black/60 to-transparent p-6 rounded-b-lg">
                <div class="flex items-end justify-between">
                    <div>
                        <h3 id="lightbox-title" class="text-2xl font-bold text-white mb-1" style="font-family: 'Cormorant Garamond', serif;"></h3>
                        <p id="lightbox-category" class="text-sm text-[#C8A951] uppercase tracking-wider font-semibold"></p>
                    </div>
                    <div id="lightbox-counter" class="text-sm text-gray-400 font-medium"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Filter Tab Styles */
    .filter-tab {
        background: white;
        color: #0F3D3E;
        border: 2px solid transparent;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .filter-tab:hover {
        border-color: #C8A951;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(200, 169, 81, 0.2);
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, #0F3D3E 0%, #1a5f60 100%);
        color: white;
        border-color: transparent;
        box-shadow: 0 4px 16px rgba(15, 61, 62, 0.3);
    }
    
    /* Gallery Card Animation */
    .gallery-card {
        opacity: 1;
        transform: translateY(0);
    }
    
    .gallery-card.hidden {
        opacity: 0;
        transform: scale(0.8);
        pointer-events: none;
        position: absolute;
    }
    
    /* Line Clamp */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Lightbox Animation */
    #lightbox-modal {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    #lightbox-modal.show {
        opacity: 1;
    }
    
    #lightbox-modal img {
        opacity: 0;
        transform: scale(0.95);
        transition: all 0.3s ease;
    }
    
    #lightbox-modal.show img {
        opacity: 1;
        transform: scale(1);
    }
    
    /* Gallery Card Enhanced Styles */
    .gallery-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .gallery-card > div:first-child {
        flex: 1;
    }
    
    /* Video Play Button Animation */
    .gallery-card .group-hover\:scale-110 {
        transition: transform 0.3s ease;
    }
    
    /* Ensure portrait cards maintain aspect ratio */
    @media (min-width: 640px) {
        .gallery-card {
            height: auto;
        }
        
        .gallery-card > div:first-child {
            height: auto;
        }
    }
</style>

<script>
// Gallery Data for Lightbox
const galleryItems = <?php echo json_encode($lightboxItems); ?>;
let currentIndex = 0;

// Filter Gallery by Category
function filterGallery(category) {
    const tabs = document.querySelectorAll('.filter-tab');
    const cards = document.querySelectorAll('.gallery-card');
    const resultsText = document.getElementById('gallery-results-text');
    const filteredEmptyState = document.getElementById('filtered-empty-state');
    let visibleCount = 0;
    
    // Update active tab
    tabs.forEach(tab => {
        tab.classList.remove('active');
        tab.setAttribute('aria-pressed', 'false');
        if (tab.dataset.category === String(category)) {
            tab.classList.add('active');
            tab.setAttribute('aria-pressed', 'true');
        }
    });
    
    // Filter cards with animation
    cards.forEach((card, index) => {
        const cardCategory = card.dataset.category;
        const shouldShow = category === 'all' || cardCategory === String(category);
        
        if (shouldShow) {
            card.classList.remove('hidden');
            card.style.display = 'block';
            visibleCount += 1;
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        } else {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.8)';
            setTimeout(() => {
                card.classList.add('hidden');
                card.style.display = 'none';
            }, 300);
        }
    });

    if (resultsText) {
        const showingPrefix = <?php echo json_encode(trans('content.gallery_page.results.showing_prefix', 'Showing')); ?>;
        const singularLabel = <?php echo json_encode(trans('content.gallery_page.results.event_singular', 'Event')); ?>;
        const pluralLabel = <?php echo json_encode(trans('content.gallery_page.results.events_plural', 'Events')); ?>;
        resultsText.textContent = `${showingPrefix} ${visibleCount} ${visibleCount === 1 ? singularLabel : pluralLabel}`;
    }

    if (filteredEmptyState) {
        filteredEmptyState.classList.toggle('hidden', visibleCount > 0);
    }
    
    // Update URL without reload
    const url = new URL(window.location);
    if (category === 'all') {
        url.searchParams.delete('category');
    } else {
        url.searchParams.set('category', category);
    }
    window.history.pushState({}, '', url);
}

// Lightbox Functions
function openLightbox(index) {
    currentIndex = index;
    const item = galleryItems[index];
    const modal = document.getElementById('lightbox-modal');
    const image = document.getElementById('lightbox-image');
    const video = document.getElementById('lightbox-video');
    const youtube = document.getElementById('lightbox-youtube');
    const title = document.getElementById('lightbox-title');
    const category = document.getElementById('lightbox-category');
    const counter = document.getElementById('lightbox-counter');

    if (item.media_type === 'youtube') {
        image.classList.add('hidden');
        image.removeAttribute('src');
        if (video) {
            video.pause();
            video.classList.add('hidden');
            video.removeAttribute('src');
            video.load();
        }
        if (youtube) {
            youtube.classList.remove('hidden');
            youtube.src = item.media_url || '';
        }
    } else if (item.media_type === 'video') {
        image.classList.add('hidden');
        image.removeAttribute('src');
        if (youtube) {
            youtube.classList.add('hidden');
            youtube.removeAttribute('src');
        }
        if (video) {
            video.classList.remove('hidden');
            video.src = item.media_url || '';
            video.muted = false;
            video.currentTime = 0;
            video.play().catch(() => {});
        }
    } else {
        if (video) {
            video.pause();
            video.classList.add('hidden');
            video.removeAttribute('src');
            video.load();
        }
        if (youtube) {
            youtube.classList.add('hidden');
            youtube.removeAttribute('src');
        }
        image.classList.remove('hidden');
        image.src = item.media_url || '';
        image.alt = item.title || <?php echo json_encode($galleryItemFallbackTitle); ?>;
    }

    title.textContent = item.title;
    category.textContent = item.category_name || <?php echo json_encode(trans('content.gallery_page.lightbox.category_fallback', 'Event')); ?>;
    counter.textContent = `${index + 1} / ${galleryItems.length}`;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => modal.classList.add('show'), 10);
}

function closeLightbox() {
    const modal = document.getElementById('lightbox-modal');
    const video = document.getElementById('lightbox-video');
    const youtube = document.getElementById('lightbox-youtube');
    if (video) {
        video.pause();
        video.removeAttribute('src');
        video.load();
    }
    if (youtube) {
        youtube.removeAttribute('src');
        youtube.classList.add('hidden');
    }
    modal.classList.remove('show');
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

function navigateLightbox(direction) {
    currentIndex = (currentIndex + direction + galleryItems.length) % galleryItems.length;
    openLightbox(currentIndex);
}

// Keyboard Navigation
document.addEventListener('keydown', (e) => {
    const modal = document.getElementById('lightbox-modal');
    if (!modal.classList.contains('hidden')) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') navigateLightbox(-1);
        if (e.key === 'ArrowRight') navigateLightbox(1);
    }
});

// Video Hover Auto-play
document.addEventListener('DOMContentLoaded', () => {
    const galleryCards = document.querySelectorAll('.gallery-card');
    
    galleryCards.forEach(card => {
        const video = card.querySelector('.gallery-video');

        if (video) {
            // Auto-play on hover
            card.addEventListener('mouseenter', () => {
                video.play().catch(err => {
                    console.log('Auto-play prevented:', err);
                });
            });
            
            // Pause and reset on mouse leave
            card.addEventListener('mouseleave', () => {
                video.pause();
                video.currentTime = 0;
            });
        }
    });
    
    // Initialize from URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');
    if (category) {
        filterGallery(category);
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/app.php';
?>

