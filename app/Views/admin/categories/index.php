<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Categories Management</h2>
        <a href="<?php echo route('/admin/categories/create'); ?>" class="btn-primary">+ Add Category</a>
    </div>

    <div>
        <div class="bg-white rounded-lg luxury-shadow overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #0F3D3E; color: white;">
                    <tr>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Slug</th>
                        <th class="px-6 py-4 text-left">Items</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($cat['name']); ?></td>
                            <td class="px-6 py-4"><code><?php echo htmlspecialchars($cat['slug']); ?></code></td>
                            <td class="px-6 py-4"><?php echo $cat['item_count']; ?></td>
                            <td class="px-6 py-4">
                                <button class="text-red-600 hover:text-red-800 delete-btn" data-id="<?php echo $cat['id']; ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($categories)): ?>
                <div class="px-6 py-12 text-center text-gray-500">
                    No categories yet. <a href="<?php echo route('/admin/categories/create'); ?>" class="text-blue-600">Create one</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        const confirmed = await window.AdminToastConfirm.show({
            title: 'Delete Category',
            message: 'This will delete the category and all items inside it.',
            confirmText: 'Delete Category'
        });
        if (!confirmed) return;
        
        const id = btn.dataset.id;
        const formData = new FormData();
        formData.append('id', id);

        try {
            const response = await fetch('<?php echo route('/admin/categories/delete'); ?>', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            alert('Error deleting category');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>


