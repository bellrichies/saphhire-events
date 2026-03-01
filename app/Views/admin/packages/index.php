<?php
ob_start();
$resolveMediaUrl = static function (?string $media): string {
    if (!$media) {
        return '';
    }

    if (preg_match('/^https?:\/\//', $media)) {
        return $media;
    }

    return uploadedImageUrl($media);
};
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Packages Management</h2>
        <a href="<?php echo route('/admin/packages/create'); ?>" class="btn-primary">+ Add Package</a>
    </div>

    <div>
        <div class="bg-white rounded-lg luxury-shadow overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #0F3D3E; color: white;">
                    <tr>
                        <th class="px-6 py-4 text-left">Media</th>
                        <th class="px-6 py-4 text-left">Title</th>
                        <th class="px-6 py-4 text-left">Category</th>
                        <th class="px-6 py-4 text-left">Price</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($packages as $package): ?>
                        <?php $mediaUrl = $resolveMediaUrl($package['image'] ?? null); ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 flex items-center justify-center">
                                    <?php if (!empty($mediaUrl)): ?>
                                        <img src="<?php echo htmlspecialchars($mediaUrl); ?>" alt="<?php echo htmlspecialchars($package['title']); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">No image</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($package['title']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($package['category_name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($package['price_label']); ?></td>
                            <td class="px-6 py-4 space-x-3">
                                <a href="<?php echo route('/admin/packages/edit') . '?id=' . $package['id']; ?>" class="text-blue-600 hover:text-blue-800"><i class="fas fa-pen"></i> Edit</a>
                                <button class="text-red-600 hover:text-red-800 delete-btn" data-id="<?php echo $package['id']; ?>"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($packages)): ?>
                <div class="px-6 py-12 text-center text-gray-500">No packages yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const confirmed = await window.AdminToastConfirm.show({
            title: 'Delete Package',
            message: 'This package will be permanently deleted.',
            confirmText: 'Delete Package'
        });
        if (!confirmed) return;

        const formData = new FormData();
        formData.append('id', btn.dataset.id);

        const response = await fetch('<?php echo route('/admin/packages/delete'); ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Delete failed');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>


