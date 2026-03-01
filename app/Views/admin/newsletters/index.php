<?php
ob_start();
$statusValue = (string)($status ?? 'all');
$searchValue = (string)($search ?? '');
$filterQuery = http_build_query([
    'q' => $searchValue,
    'status' => $statusValue,
]);
$csvExportUrl = route('/admin/newsletters/export/csv') . ($filterQuery !== '' ? '?' . $filterQuery : '');
$txtExportUrl = route('/admin/newsletters/export/txt') . ($filterQuery !== '' ? '?' . $filterQuery : '');
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Newsletter Leads</h2>
        <div class="flex items-center gap-2">
            <a href="<?php echo htmlspecialchars($csvExportUrl, ENT_QUOTES, 'UTF-8'); ?>" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="fas fa-file-csv"></i>
                <span>Download CSV</span>
            </a>
            <a href="<?php echo htmlspecialchars($txtExportUrl, ENT_QUOTES, 'UTF-8'); ?>" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <i class="fas fa-file-lines"></i>
                <span>Download TXT</span>
            </a>
        </div>
    </div>

    <form method="GET" action="<?php echo route('/admin/newsletters'); ?>" class="bg-white rounded-lg border border-slate-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="md:col-span-2">
                <label for="newsletter-search" class="block text-sm font-medium text-slate-700 mb-1">Search</label>
                <input
                    id="newsletter-search"
                    type="text"
                    name="q"
                    value="<?php echo htmlspecialchars($searchValue, ENT_QUOTES, 'UTF-8'); ?>"
                    placeholder="Search by email, source, or IP address"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]"
                >
            </div>
            <div>
                <label for="newsletter-status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select
                    id="newsletter-status"
                    name="status"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]"
                >
                    <option value="all" <?php echo $statusValue === 'all' ? 'selected' : ''; ?>>All</option>
                    <option value="active" <?php echo $statusValue === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="unsubscribed" <?php echo $statusValue === 'unsubscribed' ? 'selected' : ''; ?>>Unsubscribed</option>
                    <option value="bounced" <?php echo $statusValue === 'bounced' ? 'selected' : ''; ?>>Bounced</option>
                </select>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#0F3D3E] px-4 py-2 text-sm font-medium text-white hover:bg-[#155255] transition-colors">
                <i class="fas fa-search"></i>
                <span>Filter Leads</span>
            </button>
        </div>
    </form>

    <div class="bg-white rounded-lg luxury-shadow overflow-x-auto">
        <table class="w-full">
            <thead style="background-color: #0F3D3E; color: white;">
                <tr>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-left">Source</th>
                    <th class="px-6 py-4 text-left">Locale</th>
                    <th class="px-6 py-4 text-left">IP Address</th>
                    <th class="px-6 py-4 text-left">Subscribed</th>
                    <th class="px-6 py-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscriptions as $subscription): ?>
                    <?php
                    $currentStatus = (string)($subscription['status'] ?? 'active');
                    $canActivate = $currentStatus !== 'active';
                    $canUnsubscribe = $currentStatus !== 'unsubscribed';
                    $canBounce = $currentStatus !== 'bounced';
                    ?>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-slate-800"><?php echo htmlspecialchars($subscription['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="px-6 py-4">
                            <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold <?php echo $currentStatus === 'active' ? 'bg-emerald-100 text-emerald-800' : ($currentStatus === 'unsubscribed' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800'); ?>">
                                <?php echo htmlspecialchars(ucfirst($currentStatus), ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-700"><?php echo htmlspecialchars($subscription['source'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="px-6 py-4 text-slate-700"><?php echo htmlspecialchars(strtoupper((string)($subscription['locale'] ?? 'en')), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="px-6 py-4 text-slate-700"><?php echo htmlspecialchars($subscription['ip_address'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
                        <td class="px-6 py-4 text-slate-700">
                            <?php echo !empty($subscription['subscribed_at']) ? date('M d, Y g:i A', strtotime((string)$subscription['subscribed_at'])) : '-'; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap items-center gap-2">
                                <?php if ($canActivate): ?>
                                    <button class="text-emerald-700 hover:text-emerald-900 status-btn" data-id="<?php echo (int)$subscription['id']; ?>" data-status="active">
                                        Activate
                                    </button>
                                <?php endif; ?>
                                <?php if ($canUnsubscribe): ?>
                                    <button class="text-amber-700 hover:text-amber-900 status-btn" data-id="<?php echo (int)$subscription['id']; ?>" data-status="unsubscribed">
                                        Unsubscribe
                                    </button>
                                <?php endif; ?>
                                <?php if ($canBounce): ?>
                                    <button class="text-rose-700 hover:text-rose-900 status-btn" data-id="<?php echo (int)$subscription['id']; ?>" data-status="bounced">
                                        Mark Bounced
                                    </button>
                                <?php endif; ?>
                                <button class="text-red-600 hover:text-red-800 delete-btn" data-id="<?php echo (int)$subscription['id']; ?>">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($subscriptions)): ?>
            <div class="px-6 py-12 text-center text-gray-500">
                No newsletter leads found.
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
const newsletterCsrfToken = '<?php echo htmlspecialchars($csrf_token ?? \App\Core\CSRF::getToken(), ENT_QUOTES, 'UTF-8'); ?>';

document.querySelectorAll('.status-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const formData = new FormData();
        formData.append('id', btn.dataset.id || '');
        formData.append('status', btn.dataset.status || '');
        formData.append('_csrf_token', newsletterCsrfToken);

        try {
            const response = await fetch('<?php echo route('/admin/newsletters/status'); ?>', {
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

            alert(data.error || 'Unable to update status.');
        } catch (error) {
            alert('Unable to update status.');
        }
    });
});

document.querySelectorAll('.delete-btn').forEach((btn) => {
    btn.addEventListener('click', async () => {
        const confirmed = await window.AdminToastConfirm.show({
            title: 'Delete Newsletter Lead',
            message: 'This lead will be permanently deleted.',
            confirmText: 'Delete Lead'
        });
        if (!confirmed) {
            return;
        }

        const formData = new FormData();
        formData.append('id', btn.dataset.id || '');
        formData.append('_csrf_token', newsletterCsrfToken);

        try {
            const response = await fetch('<?php echo route('/admin/newsletters/delete'); ?>', {
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

            alert(data.error || 'Unable to delete lead.');
        } catch (error) {
            alert('Unable to delete lead.');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
