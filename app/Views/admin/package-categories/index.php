<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Package Categories</h2>
        <a href="<?php echo route('/admin/package-categories/create'); ?>" class="btn-primary">+ Add Category</a>
    </div>

    <div>
        <div class="bg-white rounded-lg luxury-shadow overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #0F3D3E; color: white;">
                    <tr>
                        <th class="px-6 py-4 text-left">Image</th>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Slug</th>
                        <th class="px-6 py-4 text-left">Packages</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <?php if (!empty($cat['image'])): ?>
                                    <img src="<?php echo htmlspecialchars(uploadedImageUrl($cat['image'])); ?>" alt="<?php echo htmlspecialchars($cat['name']); ?>" class="w-16 h-12 rounded-lg object-cover border border-slate-200">
                                <?php else: ?>
                                    <div class="w-16 h-12 rounded-lg border border-dashed border-slate-300 flex items-center justify-center text-slate-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td class="px-6 py-4"><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                            <td class="px-6 py-4"><?php echo (int)$cat['package_count']; ?></td>
                            <td class="px-6 py-4 space-x-3">
                                <a href="<?php echo route('/admin/package-categories/edit') . '?id=' . $cat['id']; ?>" class="text-blue-600 hover:text-blue-800"><i class="fas fa-pen"></i> Edit</a>
                                <button class="text-red-600 hover:text-red-800 delete-btn" data-id="<?php echo $cat['id']; ?>"><i class="fas fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($categories)): ?>
                <div class="px-6 py-12 text-center text-gray-500">No package categories yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const confirmed = await window.AdminToastConfirm.show({
            title: 'Delete Package Category',
            message: 'This will delete the category and all packages under it.',
            confirmText: 'Delete Category'
        });
        if (!confirmed) return;

        const formData = new FormData();
        formData.append('id', btn.dataset.id);

        const response = await fetch('<?php echo route('/admin/package-categories/delete'); ?>', {
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


