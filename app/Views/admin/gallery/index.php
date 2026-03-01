<?php
ob_start();
$currentPage = max(1, (int)($page ?? 1));
$currentPerPage = max(1, (int)($perPage ?? 10));
$allItemsCount = max(0, (int)($totalItems ?? count($items ?? [])));
$pagesCount = max(1, (int)($totalPages ?? 1));
$rangeStart = $allItemsCount > 0 ? (($currentPage - 1) * $currentPerPage) + 1 : 0;
$rangeEnd = min($allItemsCount, $currentPage * $currentPerPage);
$paginationBase = route('/admin/gallery');
$resolveMediaUrl = static function (?string $media): string {
    if (!$media) {
        return '';
    }

    if (preg_match('/^https?:\/\//', $media)) {
        return $media;
    }

    return uploadedImageUrl($media);
};
$resolveMediaType = static function (?string $media): string {
    $path = strtolower((string)parse_url((string)$media, PHP_URL_PATH));
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (in_array($ext, ['mp4', 'webm', 'ogg', 'ogv', 'mov'], true)) {
        return 'video';
    }

    return 'image';
};

$buildPageUrl = static function (int $targetPage, int $targetPerPage) use ($paginationBase): string {
    return $paginationBase . '?' . http_build_query([
        'page' => max(1, $targetPage),
        'per_page' => max(1, $targetPerPage),
    ]);
};
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Gallery Management</h2>
        <a href="<?php echo route('/admin/gallery/create'); ?>" class="btn-primary">+ Add Item</a>
    </div>

    <div>
        <div class="mb-4 flex justify-end">
            <form method="GET" action="<?php echo route('/admin/gallery'); ?>" class="flex items-center gap-2 text-sm text-gray-700">
                <label for="per-page" class="font-medium">Rows per page</label>
                <input id="per-page" type="number" name="per_page" min="1" max="100" value="<?php echo $currentPerPage; ?>" class="w-24 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                <input type="hidden" name="page" value="1">
                <button type="submit" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Apply</button>
            </form>
        </div>

        <div class="bg-white rounded-lg luxury-shadow overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #0F3D3E; color: white;">
                    <tr>
                        <th class="px-6 py-4 text-left">Media</th>
                        <th class="px-6 py-4 text-left">Title</th>
                        <th class="px-6 py-4 text-left">Category</th>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <?php
                            $mediaUrl = $resolveMediaUrl($item['image'] ?? null);
                            $mediaType = $resolveMediaType($item['image'] ?? null);
                        ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 flex items-center justify-center">
                                    <?php if (!empty($mediaUrl) && $mediaType === 'video'): ?>
                                        <video src="<?php echo htmlspecialchars($mediaUrl); ?>" class="w-full h-full object-cover" muted playsinline preload="metadata"></video>
                                    <?php elseif (!empty($mediaUrl)): ?>
                                        <img src="<?php echo htmlspecialchars($mediaUrl); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">No media</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($item['title']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($item['category_name'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="<?php echo route('/admin/gallery/edit') . '?id=' . (int)$item['id']; ?>" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                    <button class="text-red-600 hover:text-red-800 delete-btn" data-id="<?php echo $item['id']; ?>">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($items)): ?>
                <div class="px-6 py-12 text-center text-gray-500">
                    No gallery items yet. <a href="<?php echo route('/admin/gallery/create'); ?>" class="text-blue-600">Create one</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($items)): ?>
                <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <p class="text-sm text-gray-600">
                        Showing <?php echo $rangeStart; ?>-<?php echo $rangeEnd; ?> of <?php echo $allItemsCount; ?>
                    </p>
                    <div class="flex items-center gap-2">
                        <a href="<?php echo $buildPageUrl(max(1, $currentPage - 1), $currentPerPage); ?>" class="px-3 py-2 border rounded-lg text-sm <?php echo $currentPage <= 1 ? 'pointer-events-none opacity-50' : 'hover:bg-gray-50'; ?>">
                            Previous
                        </a>
                        <span class="px-3 py-2 text-sm text-gray-700">
                            Page <?php echo $currentPage; ?> of <?php echo $pagesCount; ?>
                        </span>
                        <a href="<?php echo $buildPageUrl(min($pagesCount, $currentPage + 1), $currentPerPage); ?>" class="px-3 py-2 border rounded-lg text-sm <?php echo $currentPage >= $pagesCount ? 'pointer-events-none opacity-50' : 'hover:bg-gray-50'; ?>">
                            Next
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
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


