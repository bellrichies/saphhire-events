<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Inquiry Details</h2>
        <a href="<?php echo route('/admin/inquiries'); ?>" class="text-gray-600 hover:text-gray-900">← Back</a>
    </div>

    <div>
        <div class="max-w-2xl bg-white rounded-lg luxury-shadow p-8">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;">Name</label>
                    <p class="text-gray-700"><?php echo htmlspecialchars($inquiry['name']); ?></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;">Email</label>
                    <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>" class="text-blue-600 hover:underline">
                        <?php echo htmlspecialchars($inquiry['email']); ?>
                    </a>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;">Phone</label>
                    <a href="tel:<?php echo htmlspecialchars($inquiry['phone']); ?>" class="text-blue-600 hover:underline">
                        <?php echo htmlspecialchars($inquiry['phone']); ?>
                    </a>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;">Event Type</label>
                    <p class="text-gray-700"><?php echo htmlspecialchars($inquiry['event_type']); ?></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;">Event Date</label>
                    <p class="text-gray-700"><?php echo !empty($inquiry['event_date']) ? date('F d, Y', strtotime($inquiry['event_date'])) : 'Not specified'; ?></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;">Message</label>
                    <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($inquiry['message']); ?></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;">Received</label>
                    <p class="text-gray-700"><?php echo date('F d, Y \a\t g:i A', strtotime($inquiry['created_at'])); ?></p>
                </div>

                <div class="pt-6 border-t">
                    <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>" class="btn-primary">Reply via Email</a>
                    <a href="tel:<?php echo htmlspecialchars($inquiry['phone']); ?>" class="btn-secondary ml-4">Call</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>

