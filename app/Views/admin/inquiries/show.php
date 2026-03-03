<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Inquiry Details</h2>
        <a
            href="<?php echo route('/admin/inquiries'); ?>"
            class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E] focus:ring-offset-2"
            aria-label="Back to inquiries list">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            <span>Back to Inquiries</span>
        </a>
    </div>

    <div>
        <div class="max-w-3xl bg-white rounded-lg luxury-shadow p-8">
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

                <?php if (!empty($inspiration_image_url)): ?>
                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;">Inspiration Image</label>
                    <a href="<?php echo htmlspecialchars($inspiration_image_url); ?>" target="_blank" rel="noopener noreferrer" class="inline-block">
                        <img
                            src="<?php echo htmlspecialchars($inspiration_image_url); ?>"
                            alt="Inspiration image"
                            class="w-full max-w-md rounded-lg border border-slate-200 shadow-sm">
                    </a>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <a
                            href="<?php echo htmlspecialchars($inspiration_image_url); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0F3D3E] px-4 py-2 text-sm font-semibold text-white transition-colors hover:bg-[#155255] focus:outline-none focus:ring-2 focus:ring-[#0F3D3E] focus:ring-offset-2"
                            aria-label="Open inspiration image in a new tab">
                            <i class="fas fa-image" aria-hidden="true"></i>
                            <span>Open Full Image</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;">Message</label>
                    <p class="text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($inquiry['message']); ?></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2" style="color: #0F3D3E;">Received</label>
                    <p class="text-gray-700"><?php echo date('F d, Y \a\t g:i A', strtotime($inquiry['created_at'])); ?></p>
                </div>

                <div class="pt-6 border-t border-slate-200">
                    <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        <a
                            href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>"
                            class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-lg bg-[#0F3D3E] px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-[#155255] focus:outline-none focus:ring-2 focus:ring-[#0F3D3E] focus:ring-offset-2"
                            aria-label="Reply to inquiry via email">
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            <span>Reply via Email</span>
                        </a>
                        <a
                            href="tel:<?php echo htmlspecialchars($inquiry['phone']); ?>"
                            class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E] focus:ring-offset-2"
                            aria-label="Call inquiry contact phone number">
                            <i class="fas fa-phone" aria-hidden="true"></i>
                            <span>Call Client</span>
                        </a>
                        <a
                            href="<?php echo route('/admin/inquiries'); ?>"
                            class="inline-flex min-h-[44px] items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E] focus:ring-offset-2"
                            aria-label="Return to inquiries list">
                            <i class="fas fa-list" aria-hidden="true"></i>
                            <span>All Inquiries</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
