<?php
ob_start();
$reorderCollection = $reorderItems ?? [];
$allItemsCount = max(0, (int)($totalItems ?? count($reorderCollection)));

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

$getYoutubeThumbnailUrl = static function (?string $value) use ($extractYoutubeVideoId): string {
    $videoId = $extractYoutubeVideoId($value);
    return $videoId !== '' ? 'https://i.ytimg.com/vi/' . $videoId . '/hqdefault.jpg' : '';
};

$resolveMediaType = static function (?string $media) use ($extractYoutubeVideoId): string {
    if ($media && $extractYoutubeVideoId($media) !== '') {
        return 'youtube';
    }

    $path = strtolower((string)parse_url((string)$media, PHP_URL_PATH));
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (in_array($ext, ['mp4', 'webm', 'ogg', 'ogv', 'mov'], true)) {
        return 'video';
    }

    return 'image';
};
?>

<div class="space-y-5">
    <section class="rounded-[28px] border border-slate-200 bg-[radial-gradient(circle_at_top_left,_rgba(200,169,81,0.16),_transparent_30%),linear-gradient(135deg,_#0f3d3e_0%,_#153f49_48%,_#1b2431_100%)] px-5 py-5 text-white shadow-[0_24px_80px_rgba(15,61,62,0.22)] lg:px-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <a href="<?php echo route('/admin/gallery'); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-slate-200 transition hover:text-white">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Back to gallery list
                </a>
                <span class="mt-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] text-white/80">
                    <i class="fas fa-grip-vertical text-[10px] text-[#C8A951]"></i>
                    Reorder Workspace
                </span>
                <h2 class="mt-4 text-2xl font-semibold tracking-tight text-white">Reorder gallery media</h2>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-200">
                    Drag cards into the exact sequence you want for public display, then save once you are happy with the new order.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-3 sm:min-w-[240px]">
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-300">Total Media</p>
                    <p class="mt-2 text-2xl font-semibold text-white"><?php echo $allItemsCount; ?></p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-300">Save Flow</p>
                    <p class="mt-2 text-sm font-semibold text-white">Drag, review, save</p>
                </div>
            </div>
        </div>
    </section>

    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_18px_60px_rgba(15,23,42,0.08)]">
        <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 lg:flex-row lg:items-center lg:justify-between lg:px-6">
            <div>
                <h3 class="text-base font-semibold text-slate-900">Media order</h3>
                <p class="mt-1 text-sm text-slate-500">Use drag and drop to change the frontend sequence for images, uploaded videos, and YouTube items.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <button type="button" id="gallery-order-reset" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
                    Reset
                </button>
                <button type="button" id="gallery-order-save" class="inline-flex items-center justify-center rounded-xl bg-[#0F3D3E] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#124c4e] disabled:cursor-not-allowed disabled:opacity-60" disabled>
                    Save order
                </button>
            </div>
        </div>

        <div class="px-5 py-5 lg:px-6">
            <?php if (!empty($reorderCollection)): ?>
                <div id="gallery-reorder-status" class="mb-4 hidden rounded-2xl px-4 py-3 text-sm font-medium"></div>
                <div id="gallery-reorder-list" class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    <?php foreach ($reorderCollection as $reorderIndex => $reorderItem): ?>
                        <?php
                            $reorderMediaUrl = $resolveMediaUrl($reorderItem['image'] ?? null);
                            $reorderMediaType = $resolveMediaType($reorderItem['image'] ?? null);
                            $reorderYoutubeThumbUrl = $reorderMediaType === 'youtube' ? $getYoutubeThumbnailUrl($reorderItem['image'] ?? null) : '';
                            $reorderTitle = trim((string)($reorderItem['title'] ?? '')) ?: 'Untitled media';
                            $reorderCategory = trim((string)($reorderItem['category_name'] ?? '')) ?: 'Uncategorized';
                        ?>
                        <article class="gallery-order-card flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-3 shadow-sm" draggable="true" data-order-id="<?php echo (int)$reorderItem['id']; ?>">
                            <button type="button" class="gallery-order-handle inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-400 transition hover:text-slate-600" aria-label="Drag to reorder">
                                <i class="fas fa-grip-vertical"></i>
                            </button>
                            <div class="relative h-16 w-16 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-white">
                                <?php if ($reorderMediaType === 'youtube' && $reorderYoutubeThumbUrl !== ''): ?>
                                    <img src="<?php echo htmlspecialchars($reorderYoutubeThumbUrl); ?>" alt="<?php echo htmlspecialchars($reorderTitle); ?>" class="h-full w-full object-cover" loading="lazy" referrerpolicy="origin">
                                <?php elseif ($reorderMediaType === 'video' && $reorderMediaUrl !== ''): ?>
                                    <video src="<?php echo htmlspecialchars($reorderMediaUrl); ?>" class="h-full w-full object-cover" muted playsinline preload="metadata"></video>
                                <?php elseif ($reorderMediaUrl !== ''): ?>
                                    <img src="<?php echo htmlspecialchars($reorderMediaUrl); ?>" alt="<?php echo htmlspecialchars($reorderTitle); ?>" class="h-full w-full object-cover" loading="lazy">
                                <?php else: ?>
                                    <span class="flex h-full w-full items-center justify-center text-xs font-medium text-slate-400">No media</span>
                                <?php endif; ?>
                                <?php if ($reorderMediaType === 'youtube'): ?>
                                    <span class="absolute bottom-1 right-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-600 text-[9px] text-white">
                                        <i class="fab fa-youtube"></i>
                                    </span>
                                <?php elseif ($reorderMediaType === 'video'): ?>
                                    <span class="absolute bottom-1 right-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-black/70 text-[9px] text-white">
                                        <i class="fas fa-play"></i>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="gallery-order-number inline-flex min-w-[2rem] items-center justify-center rounded-full bg-white px-2 py-1 text-[11px] font-semibold text-slate-500">
                                        <?php echo $reorderIndex + 1; ?>
                                    </span>
                                    <p class="truncate text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($reorderTitle); ?></p>
                                </div>
                                <p class="mt-1 text-xs uppercase tracking-[0.14em] text-slate-400"><?php echo htmlspecialchars($reorderCategory); ?></p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-slate-400 shadow-sm">
                        <i class="fas fa-images text-lg"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">No gallery items available</h3>
                    <p class="mt-2 text-sm text-slate-500">Add media first, then return here to manage the display order.</p>
                    <a href="<?php echo route('/admin/gallery/create'); ?>" class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-[#0F3D3E] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#124c4e]">
                        <i class="fas fa-plus text-xs"></i>
                        Add media
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<style>
    .gallery-order-card {
        cursor: grab;
        transition: transform 160ms ease, box-shadow 160ms ease, border-color 160ms ease, background-color 160ms ease;
    }

    .gallery-order-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.08);
        transform: translateY(-1px);
    }

    .gallery-order-card.is-dragging {
        opacity: 0.55;
        cursor: grabbing;
        box-shadow: 0 20px 42px rgba(15, 23, 42, 0.16);
    }
</style>

<script>
const galleryReorderUrl = '<?php echo route('/admin/gallery/reorder'); ?>';
const galleryCsrfToken = <?php echo json_encode($csrf_token ?? ''); ?>;
const reorderList = document.getElementById('gallery-reorder-list');
const reorderSaveButton = document.getElementById('gallery-order-save');
const reorderResetButton = document.getElementById('gallery-order-reset');
const reorderStatus = document.getElementById('gallery-reorder-status');
let draggedOrderCard = null;
let initialOrderIds = [];

const getCurrentOrderIds = () => {
    if (!reorderList) {
        return [];
    }

    return Array.from(reorderList.querySelectorAll('[data-order-id]')).map((card) => card.dataset.orderId || '');
};

const refreshOrderNumbers = () => {
    if (!reorderList) {
        return;
    }

    reorderList.querySelectorAll('.gallery-order-card').forEach((card, index) => {
        const number = card.querySelector('.gallery-order-number');
        if (number) {
            number.textContent = String(index + 1);
        }
    });
};

const setReorderDirtyState = () => {
    if (!reorderSaveButton) {
        return;
    }

    const isDirty = JSON.stringify(getCurrentOrderIds()) !== JSON.stringify(initialOrderIds);
    reorderSaveButton.disabled = !isDirty;
};

const showReorderStatus = (message, kind) => {
    if (!reorderStatus) {
        return;
    }

    reorderStatus.className = 'mb-4 rounded-2xl px-4 py-3 text-sm font-medium';
    if (kind === 'success') {
        reorderStatus.classList.add('bg-emerald-50', 'text-emerald-700');
    } else if (kind === 'error') {
        reorderStatus.classList.add('bg-red-50', 'text-red-700');
    } else {
        reorderStatus.classList.add('bg-slate-100', 'text-slate-600');
    }

    reorderStatus.textContent = message;
    reorderStatus.classList.remove('hidden');
};

if (reorderList) {
    initialOrderIds = getCurrentOrderIds();
    refreshOrderNumbers();

    reorderList.querySelectorAll('.gallery-order-card').forEach((card) => {
        card.addEventListener('dragstart', () => {
            draggedOrderCard = card;
            card.classList.add('is-dragging');
        });

        card.addEventListener('dragend', () => {
            card.classList.remove('is-dragging');
            draggedOrderCard = null;
            refreshOrderNumbers();
            setReorderDirtyState();
        });

        card.addEventListener('dragover', (event) => {
            event.preventDefault();
            if (!draggedOrderCard || draggedOrderCard === card) {
                return;
            }

            const rect = card.getBoundingClientRect();
            const shouldInsertAfter = event.clientY > rect.top + (rect.height / 2);
            if (shouldInsertAfter) {
                card.after(draggedOrderCard);
            } else {
                card.before(draggedOrderCard);
            }
        });
    });
}

reorderResetButton?.addEventListener('click', () => {
    if (!reorderList || initialOrderIds.length === 0) {
        return;
    }

    const cardsById = new Map(Array.from(reorderList.querySelectorAll('[data-order-id]')).map((card) => [card.dataset.orderId || '', card]));
    initialOrderIds.forEach((id) => {
        const card = cardsById.get(id);
        if (card) {
            reorderList.appendChild(card);
        }
    });

    refreshOrderNumbers();
    setReorderDirtyState();
    if (reorderStatus) {
        reorderStatus.classList.add('hidden');
    }
});

reorderSaveButton?.addEventListener('click', async () => {
    const orderedIds = getCurrentOrderIds();
    if (orderedIds.length === 0) {
        return;
    }

    reorderSaveButton.disabled = true;

    try {
        const formData = new FormData();
        orderedIds.forEach((id) => formData.append('ordered_ids[]', id));
        formData.append('_csrf_token', galleryCsrfToken);

        const response = await fetch(galleryReorderUrl, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (!response.ok || !data.success) {
            throw new Error(data.error || 'Unable to save gallery order');
        }

        initialOrderIds = orderedIds.slice();
        refreshOrderNumbers();
        setReorderDirtyState();
        showReorderStatus(data.message || 'Gallery order updated successfully.', 'success');
    } catch (error) {
        showReorderStatus(error.message || 'Unable to save gallery order.', 'error');
        setReorderDirtyState();
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
