<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Testimonials Management</h2>
        <a href="<?php echo route('/admin/testimonials/create'); ?>" class="btn-primary">+ Add Testimonial</a>
    </div>

    <div>
        <div class="bg-white rounded-lg luxury-shadow overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #0F3D3E; color: white;">
                    <tr>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Content</th>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($testimonials as $testimonial): ?>
                        <?php
                        $content = (string)$testimonial['content'];
                        $excerpt = strlen($content) > 50 ? substr($content, 0, 50) . '...' : $content;
                        ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($testimonial['name']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($excerpt); ?></td>
                            <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($testimonial['created_at'])); ?></td>
                            <td class="px-6 py-4 space-x-3">
                                <a href="<?php echo route('/admin/testimonials/edit?id=' . (int)$testimonial['id']); ?>" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="text-red-600 hover:text-red-800 delete-btn" data-id="<?php echo $testimonial['id']; ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($testimonials)): ?>
                <div class="px-6 py-12 text-center text-gray-500">
                    No testimonials yet. <a href="<?php echo route('/admin/testimonials/create'); ?>" class="text-blue-600">Create one</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const testimonialCsrfToken = '<?php echo htmlspecialchars($csrf_token ?? \App\Core\CSRF::getToken(), ENT_QUOTES, 'UTF-8'); ?>';

document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        const confirmed = await window.AdminToastConfirm.show({
            title: 'Delete Testimonial',
            message: 'This testimonial will be permanently deleted.',
            confirmText: 'Delete Testimonial'
        });
        if (!confirmed) return;
        
        const id = btn.dataset.id;
        const formData = new FormData();
        formData.append('id', id);
        formData.append('_csrf_token', testimonialCsrfToken);

        try {
            const response = await fetch('<?php echo route('/admin/testimonials/delete'); ?>', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                location.reload();
                return;
            }

            if (data.error === 'CSRF token invalid') {
                alert('Your session token expired. Refresh the page and try again.');
                return;
            }
        } catch (error) {
            alert('Error deleting testimonial');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>


