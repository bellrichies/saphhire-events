<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Inquiries</h2>
        <div class="flex items-center gap-2">
            <a href="<?php echo route('/admin/inquiries/export/csv'); ?>" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="fas fa-file-csv"></i>
                <span>Download Sender Emails CSV</span>
            </a>
            <a href="<?php echo route('/admin/inquiries/export/txt'); ?>" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="fas fa-file-lines"></i>
                <span>Download Sender Emails TXT</span>
            </a>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-lg luxury-shadow overflow-x-auto">
            <table class="w-full">
                <thead style="background-color: #0F3D3E; color: white;">
                    <tr>
                        <th class="px-6 py-4 text-left">Name</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Event Type</th>
                        <th class="px-6 py-4 text-left">Event Date</th>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inquiries as $inquiry): ?>
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4"><?php echo htmlspecialchars($inquiry['name']); ?></td>
                            <td class="px-6 py-4">
                                <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>" class="text-blue-600 hover:underline">
                                    <?php echo htmlspecialchars($inquiry['email']); ?>
                                </a>
                            </td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($inquiry['event_type']); ?></td>
                            <td class="px-6 py-4"><?php echo !empty($inquiry['event_date']) ? date('M d, Y', strtotime($inquiry['event_date'])) : '-'; ?></td>
                            <td class="px-6 py-4"><?php echo date('M d, Y', strtotime($inquiry['created_at'])); ?></td>
                            <td class="px-6 py-4 space-x-2">
                                <a href="<?php echo route('/admin/inquiries/show') . '?id=' . $inquiry['id']; ?>" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <button class="text-red-600 hover:text-red-800 delete-btn" data-id="<?php echo $inquiry['id']; ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php if (empty($inquiries)): ?>
                <div class="px-6 py-12 text-center text-gray-500">
                    No inquiries yet.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.delete-btn').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        const confirmed = await window.AdminToastConfirm.show({
            title: 'Delete Inquiry',
            message: 'This inquiry will be permanently deleted.',
            confirmText: 'Delete Inquiry'
        });
        if (!confirmed) return;
        
        const id = btn.dataset.id;
        const formData = new FormData();
        formData.append('id', id);

        try {
            const response = await fetch('<?php echo route('/admin/inquiries/delete'); ?>', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                location.reload();
            }
        } catch (error) {
            alert('Error deleting inquiry');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>


