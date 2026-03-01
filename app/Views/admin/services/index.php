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
        <h2 class="text-xl font-semibold text-slate-800">Services Management</h2>
        <a href="<?php echo route('/admin/services/create'); ?>" class="btn-primary">+ Add Service</a>
    </div>

    <div>
        <div class="bg-white rounded-lg luxury-shadow overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #0F3D3E; color: white;">
                    <tr>
                        <th class="px-6 py-4 text-left">Media</th>
                        <th class="px-6 py-4 text-left">Title</th>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <?php $mediaUrl = $resolveMediaUrl($service['image'] ?? null); ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-gray-100 border border-gray-200 flex items-center justify-center">
                                    <?php if (!empty($mediaUrl)): ?>
                                        <img src="<?php echo htmlspecialchars($mediaUrl); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">No image</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($service['title']); ?></td>
                            <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($service['created_at'])); ?></td>
                            <td class="px-6 py-4">
                                <a href="<?php echo route('/admin/services/edit?id=' . (int)$service['id']); ?>" class="text-blue-600 hover:text-blue-800 mr-4">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="text-red-600 hover:text-red-800 delete-btn" data-id="<?php echo $service['id']; ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($services)): ?>
                <div class="px-6 py-12 text-center text-gray-500">
                    No services yet. <a href="<?php echo route('/admin/services/create'); ?>" class="text-blue-600">Create one</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        const confirmed = await window.AdminToastConfirm.show({
            title: 'Delete Service',
            message: 'This service will be permanently deleted.',
            confirmText: 'Delete Service'
        });
        if (!confirmed) return;
        
        const id = btn.dataset.id;
        const formData = new FormData();
        formData.append('id', id);

        try {
            const response = await fetch('<?php echo route('/admin/services/delete'); ?>', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            alert('Error deleting service');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>


