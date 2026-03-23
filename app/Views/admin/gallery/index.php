<?php
ob_start();
$currentPage = max(1, (int)($page ?? 1));
$currentPerPage = max(1, (int)($perPage ?? 10));
$allItemsCount = max(0, (int)($totalItems ?? count($items ?? [])));
$pagesCount = max(1, (int)($totalPages ?? 1));
$rangeStart = $allItemsCount > 0 ? (($currentPage - 1) * $currentPerPage) + 1 : 0;
$rangeEnd = min($allItemsCount, $currentPage * $currentPerPage);
$paginationBase = route('/admin/gallery');
$reorderPageUrl = route('/admin/gallery/reorder');
$videoCount = 0;
$featuredCount = 0;
$imageCount = 0;

$resolveMediaUrl = static function (?string $media): string {
    if (!$media) {
        return '';
    }

    if (preg_match('/^https?:\/\//', $media)) {
        return $media;
    }

    return uploadedImageUrl($media);
};

$extractYoutubeVideoId = static function (?string $value): string {
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
            $videoId = (string)($query['v'] ?? ($query['vi'] ?? ''));
        } elseif (str_starts_with($path, '/shorts/') || str_starts_with($path, '/embed/') || str_starts_with($path, '/live/') || str_starts_with($path, '/v/')) {
            $segments = explode('/', trim($path, '/'));
            $videoId = $segments[1] ?? '';
        }
    }

    $videoId = preg_replace('/[^a-zA-Z0-9_-]/', '', $videoId ?? '');
    if ($videoId === '' || strlen($videoId) < 6 || strlen($videoId) > 15) {
        return '';
    }

    return $videoId;
};

$getYoutubeEmbedUrl = static function (?string $value) use ($extractYoutubeVideoId): string {
    $videoId = $extractYoutubeVideoId($value);
    return $videoId !== '' ? 'https://www.youtube.com/embed/' . $videoId : '';
};

$getYoutubeThumbnailUrl = static function (?string $value) use ($extractYoutubeVideoId): string {
    $videoId = $extractYoutubeVideoId($value);
    return $videoId !== '' ? 'https://i.ytimg.com/vi/' . $videoId . '/hqdefault.jpg' : '';
};

$resolveMediaType = static function (?string $media) use ($getYoutubeEmbedUrl): string {
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

foreach (($items ?? []) as $galleryItemSummary) {
    $summaryType = $resolveMediaType($galleryItemSummary['image'] ?? null);
    if ($summaryType === 'video' || $summaryType === 'youtube') {
        $videoCount++;
    } else {
        $imageCount++;
    }

    if ((int)($galleryItemSummary['is_featured'] ?? 0) === 1) {
        $featuredCount++;
    }
}

$buildPageUrl = static function (int $targetPage, int $targetPerPage) use ($paginationBase): string {
    return $paginationBase . '?' . http_build_query([
        'page' => max(1, $targetPage),
        'per_page' => max(1, $targetPerPage),
    ]);
};
?>

<div class="space-y-5">
    <section class="rounded-[28px] border border-slate-200 bg-[radial-gradient(circle_at_top_left,_rgba(200,169,81,0.16),_transparent_30%),linear-gradient(135deg,_#0f3d3e_0%,_#153f49_48%,_#1b2431_100%)] px-5 py-5 text-white shadow-[0_24px_80px_rgba(15,61,62,0.22)] lg:px-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-white/80">
                    <i class="fas fa-photo-video text-[10px] text-[#C8A951]"></i>
                    Gallery Admin
                </span>
                <h2 class="mt-4 text-2xl font-semibold tracking-tight text-white">Media library overview</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-200">
                    Review gallery assets, promote standout media to featured placement, and manage edits from one compact workspace.
                </p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <form method="GET" action="<?php echo route('/admin/gallery'); ?>" class="inline-flex items-center gap-2 rounded-2xl border border-white/10 bg-white/10 px-3 py-2 text-sm text-slate-100 backdrop-blur">
                    <label for="per-page" class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-300">Rows</label>
                    <input id="per-page" type="number" name="per_page" min="1" max="100" value="<?php echo $currentPerPage; ?>" class="w-16 rounded-xl border border-white/10 bg-white/95 px-2 py-1.5 text-center text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-[#C8A951]">
                    <input type="hidden" name="page" value="1">
                    <button type="submit" class="rounded-xl border border-white/10 bg-white/15 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.12em] text-white transition hover:bg-white/20">Apply</button>
                </form>
                <a href="<?php echo $reorderPageUrl; ?>" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/15">
                    <i class="fas fa-grip-vertical text-xs"></i>
                    Reorder media
                </a>
                <a href="<?php echo route('/admin/gallery/create'); ?>" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[#C8A951] px-4 py-3 text-sm font-semibold text-[#0F3D3E] transition hover:brightness-105">
                    <i class="fas fa-plus text-xs"></i>
                    Add media
                </a>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3 lg:grid-cols-4">
            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-300">Total Items</p>
                <p class="mt-2 text-2xl font-semibold text-white"><?php echo $allItemsCount; ?></p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-300">Featured</p>
                <p class="mt-2 text-2xl font-semibold text-white"><?php echo $featuredCount; ?></p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-300">Images</p>
                <p class="mt-2 text-2xl font-semibold text-white"><?php echo $imageCount; ?></p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-300">Videos</p>
                <p class="mt-2 text-2xl font-semibold text-white"><?php echo $videoCount; ?></p>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_18px_60px_rgba(15,23,42,0.08)]">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 lg:flex-row lg:items-center lg:justify-between lg:px-6">
            <div>
                <h3 class="text-base font-semibold text-slate-900">Gallery items</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Showing <?php echo $rangeStart; ?>-<?php echo $rangeEnd; ?> of <?php echo $allItemsCount; ?> items
                </p>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-600">
                <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                Featured media appears on public highlight sections
            </div>
        </div>

        <?php if (!empty($items)): ?>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px]">
                    <thead class="bg-slate-50/90 text-left">
                        <tr class="text-[11px] uppercase tracking-[0.16em] text-slate-500">
                            <th class="px-5 py-3 font-semibold lg:px-6">Media</th>
                            <th class="px-5 py-3 font-semibold">Order</th>
                            <th class="px-5 py-3 font-semibold">Details</th>
                            <th class="px-5 py-3 font-semibold">Category</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold">Published</th>
                            <th class="px-5 py-3 font-semibold lg:px-6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($items as $item): ?>
                            <?php
                                $mediaUrl = $resolveMediaUrl($item['image'] ?? null);
                                $mediaType = $resolveMediaType($item['image'] ?? null);
                                $youtubeThumbUrl = $mediaType === 'youtube' ? $getYoutubeThumbnailUrl($item['image'] ?? null) : '';
                                $isFeatured = (int)($item['is_featured'] ?? 0) === 1;
                                $itemTitle = trim((string)($item['title'] ?? '')) ?: 'Untitled media';
                                $itemDescription = trim(strip_tags((string)($item['description'] ?? '')));
                                $itemSummary = $itemDescription !== '' ? mb_strimwidth($itemDescription, 0, 90, '...') : 'No description provided yet.';
                            ?>
                            <tr class="gallery-row align-top text-sm text-slate-700">
                                <td class="px-5 py-3.5 lg:px-6">
                                    <div class="flex items-start gap-3">
                                        <div class="relative h-16 w-16 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm">
                                            <?php if ($mediaType === 'youtube' && $youtubeThumbUrl !== ''): ?>
                                                <img src="<?php echo htmlspecialchars($youtubeThumbUrl); ?>" alt="<?php echo htmlspecialchars($itemTitle); ?>" class="h-full w-full object-cover" loading="lazy" referrerpolicy="origin">
                                                <span class="absolute bottom-1.5 right-1.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-[10px] text-white">
                                                    <i class="fab fa-youtube"></i>
                                                </span>
                                            <?php elseif (!empty($mediaUrl) && $mediaType === 'video'): ?>
                                                <video src="<?php echo htmlspecialchars($mediaUrl); ?>" class="h-full w-full object-cover" muted playsinline preload="metadata"></video>
                                                <span class="absolute bottom-1.5 right-1.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-black/60 text-[10px] text-white">
                                                    <i class="fas fa-play"></i>
                                                </span>
                                            <?php elseif (!empty($mediaUrl)): ?>
                                                <img src="<?php echo htmlspecialchars($mediaUrl); ?>" alt="<?php echo htmlspecialchars($itemTitle); ?>" class="h-full w-full object-cover">
                                            <?php else: ?>
                                                <span class="flex h-full w-full items-center justify-center text-xs font-medium text-slate-400">No media</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-slate-900">#<?php echo (int)$item['id']; ?></p>
                                            <p class="mt-1 text-xs uppercase tracking-[0.16em] text-slate-400"><?php echo $mediaType === 'youtube' ? 'YouTube video' : ($mediaType === 'video' ? 'Video asset' : 'Image asset'); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700 shadow-sm">
                                        <?php echo (int)($item['display_order'] ?? 0); ?>
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="min-w-0">
                                        <p class="truncate text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($itemTitle); ?></p>
                                        <p class="mt-1 max-w-xs text-sm leading-5 text-slate-500"><?php echo htmlspecialchars($itemSummary); ?></p>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        <?php echo htmlspecialchars($item['category_name'] ?? 'Uncategorized'); ?>
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="space-y-2">
                                        <span data-featured-badge class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold <?php echo $isFeatured ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'; ?>">
                                            <span class="h-2 w-2 rounded-full <?php echo $isFeatured ? 'bg-emerald-500' : 'bg-slate-400'; ?>"></span>
                                            <?php echo $isFeatured ? 'Featured' : 'Standard'; ?>
                                        </span>
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold <?php echo $mediaType === 'youtube' ? 'bg-rose-50 text-rose-700' : ($mediaType === 'video' ? 'bg-amber-50 text-amber-700' : 'bg-sky-50 text-sky-700'); ?>">
                                            <i class="<?php echo $mediaType === 'youtube' ? 'fab fa-youtube' : ($mediaType === 'video' ? 'fas fa-film' : 'fas fa-image'); ?> text-[10px]"></i>
                                            <?php echo $mediaType === 'youtube' ? 'YouTube' : ucfirst($mediaType); ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-sm text-slate-500">
                                    <p class="font-medium text-slate-700"><?php echo date('M d, Y', strtotime($item['created_at'])); ?></p>
                                    <p class="mt-1 text-xs text-slate-400"><?php echo date('g:i A', strtotime($item['created_at'])); ?></p>
                                </td>
                                <td class="px-5 py-3.5 lg:px-6">
                                    <div class="flex min-w-[220px] flex-col gap-3">
                                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5">
                                            <div>
                                                <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-slate-500">Featured</p>
                                                <p data-featured-copy class="mt-0.5 text-xs text-slate-500"><?php echo $isFeatured ? 'Shown in highlights' : 'Hidden from highlights'; ?></p>
                                            </div>
                                            <button
                                                type="button"
                                                class="featured-toggle relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-200 <?php echo $isFeatured ? 'bg-emerald-500' : 'bg-slate-300'; ?>"
                                                role="switch"
                                                aria-checked="<?php echo $isFeatured ? 'true' : 'false'; ?>"
                                                data-id="<?php echo (int)$item['id']; ?>"
                                                data-featured="<?php echo $isFeatured ? '1' : '0'; ?>"
                                            >
                                                <span class="sr-only">Toggle featured status</span>
                                                <span class="featured-toggle-knob inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200 <?php echo $isFeatured ? 'translate-x-6' : 'translate-x-1'; ?>"></span>
                                            </button>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="<?php echo route('/admin/gallery/edit') . '?id=' . (int)$item['id']; ?>" class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-sky-200 hover:bg-sky-50 hover:text-sky-700">
                                                <i class="fas fa-pen text-xs"></i>
                                                Edit
                                            </a>
                                            <button class="delete-btn inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-red-100 bg-red-50 px-3 py-2 text-sm font-medium text-red-600 transition hover:bg-red-100" data-id="<?php echo $item['id']; ?>">
                                                <i class="fas fa-trash text-xs"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col gap-3 border-t border-slate-200 px-5 py-4 lg:flex-row lg:items-center lg:justify-between lg:px-6">
                <p class="text-sm text-slate-500">
                    Page <?php echo $currentPage; ?> of <?php echo $pagesCount; ?>
                </p>
                <div class="flex items-center gap-2">
                    <a href="<?php echo $buildPageUrl(max(1, $currentPage - 1), $currentPerPage); ?>" class="inline-flex items-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition <?php echo $currentPage <= 1 ? 'pointer-events-none opacity-40' : 'hover:bg-slate-50'; ?>">
                        Previous
                    </a>
                    <a href="<?php echo $buildPageUrl(min($pagesCount, $currentPage + 1), $currentPerPage); ?>" class="inline-flex items-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition <?php echo $currentPage >= $pagesCount ? 'pointer-events-none opacity-40' : 'hover:bg-slate-50'; ?>">
                        Next
                    </a>
                </div>
            </div>

        <?php else: ?>
            <div class="px-6 py-16 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                    <i class="fas fa-images text-xl"></i>
                </div>
                <h3 class="mt-5 text-lg font-semibold text-slate-900">No gallery items yet</h3>
                <p class="mt-2 text-sm text-slate-500">Start by uploading your first image or video to build the portfolio.</p>
                <a href="<?php echo route('/admin/gallery/create'); ?>" class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-[#0F3D3E] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#124c4e]">
                    <i class="fas fa-plus text-xs"></i>
                    Create first item
                </a>
            </div>
        <?php endif; ?>
    </section>
</div>

<style>
    .gallery-row {
        transition: background-color 160ms ease, transform 160ms ease;
    }

    .gallery-row:hover {
        background: #fbfdff;
    }
</style>

<script>
const featuredToggleUrl = '<?php echo route('/admin/gallery/featured'); ?>';
const galleryCsrfToken = <?php echo json_encode($csrf_token ?? ''); ?>;

const applyFeaturedToggleState = (toggle, isFeatured) => {
    toggle.dataset.featured = isFeatured ? '1' : '0';
    toggle.setAttribute('aria-checked', isFeatured ? 'true' : 'false');
    toggle.classList.toggle('bg-emerald-500', isFeatured);
    toggle.classList.toggle('bg-slate-300', !isFeatured);

    const knob = toggle.querySelector('.featured-toggle-knob');
    if (knob) {
        knob.classList.toggle('translate-x-6', isFeatured);
        knob.classList.toggle('translate-x-1', !isFeatured);
    }

    const featuredPanel = toggle.closest('.rounded-2xl');
    if (featuredPanel) {
        const statusText = featuredPanel.querySelector('[data-featured-copy]');
        if (statusText) {
            statusText.textContent = isFeatured ? 'Shown in highlights' : 'Hidden from highlights';
        }
    }

    const row = toggle.closest('tr');
    const statusBadge = row ? row.querySelector('[data-featured-badge]') : null;
    if (statusBadge) {
        statusBadge.className = `inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold ${isFeatured ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500'}`;
        statusBadge.innerHTML = `<span class="h-2 w-2 rounded-full ${isFeatured ? 'bg-emerald-500' : 'bg-slate-400'}"></span>${isFeatured ? 'Featured' : 'Standard'}`;
    }
};

document.querySelectorAll('.featured-toggle').forEach(toggle => {
    toggle.addEventListener('click', async () => {
        if (toggle.dataset.loading === 'true') {
            return;
        }

        const currentState = toggle.dataset.featured === '1';
        const nextState = currentState ? 0 : 1;
        const formData = new FormData();
        formData.append('id', toggle.dataset.id || '');
        formData.append('is_featured', String(nextState));
        formData.append('_csrf_token', galleryCsrfToken);

        toggle.dataset.loading = 'true';
        toggle.disabled = true;

        try {
            const response = await fetch(featuredToggleUrl, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (!response.ok || !data.success) {
                throw new Error(data.error || 'Unable to update featured status');
            }

            applyFeaturedToggleState(toggle, nextState === 1);
        } catch (error) {
            alert(error.message || 'Error updating featured status');
        } finally {
            toggle.disabled = false;
            toggle.dataset.loading = 'false';
        }
    });
});

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const confirmed = await window.AdminToastConfirm.show({
            title: 'Delete Gallery Item',
            message: 'This gallery item will be permanently deleted.',
            confirmText: 'Delete Item'
        });
        if (!confirmed) return;

        const id = btn.dataset.id;
        const formData = new FormData();
        formData.append('id', id);

        try {
            const response = await fetch('<?php echo route('/admin/gallery/delete'); ?>', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            alert('Error deleting item');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
