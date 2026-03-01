<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Team Management</h2>
        <a href="<?php echo route('/admin/team/create'); ?>" class="btn-primary">+ Add Team Member</a>
    </div>

    <div>
        <div class="bg-white rounded-lg luxury-shadow overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #0F3D3E; color: white;">
                    <tr>
                        <th class="px-6 py-4 text-left">Member</th>
                        <th class="px-6 py-4 text-left">Role</th>
                        <th class="px-6 py-4 text-left">Order</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="<?php echo htmlspecialchars(uploadedImageUrl($member['image'] ?? '')); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                    <div>
                                        <p class="font-semibold text-slate-800"><?php echo htmlspecialchars($member['name']); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars(strlen((string)$member['bio']) > 75 ? substr((string)$member['bio'], 0, 75) . '...' : (string)$member['bio']); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($member['role']); ?></td>
                            <td class="px-6 py-4"><?php echo (int)($member['display_order'] ?? 0); ?></td>
                            <td class="px-6 py-4">
                                <?php if ((int)($member['is_active'] ?? 0) === 1): ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Active</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Hidden</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 space-x-3">
                                <a href="<?php echo route('/admin/team/edit?id=' . (int)$member['id']); ?>" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="text-red-600 hover:text-red-800 delete-btn" data-id="<?php echo (int)$member['id']; ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($members)): ?>
                <div class="px-6 py-12 text-center text-gray-500">
                    No team members yet. <a href="<?php echo route('/admin/team/create'); ?>" class="text-blue-600">Create one</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
const teamCsrfToken = '<?php echo htmlspecialchars($csrf_token ?? \App\Core\CSRF::getToken(), ENT_QUOTES, 'UTF-8'); ?>';

document.querySelectorAll('.delete-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const confirmed = await window.AdminToastConfirm.show({
            title: 'Delete Team Member',
            message: 'This team member will be permanently deleted.',
            confirmText: 'Delete Member'
        });
        if (!confirmed) return;

        const formData = new FormData();
        formData.append('id', btn.dataset.id);
        formData.append('_csrf_token', teamCsrfToken);

        try {
            const response = await fetch('<?php echo route('/admin/team/delete'); ?>', {
                method: 'POST',
                body: formData
            });
            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            alert('Error deleting team member');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>

