<?php
ob_start();
$title = 'Dashboard';
$pageTitle = 'Dashboard';
?>

<div class="space-y-6">
    <?php if (($translationCacheStatus ?? null) === 'cleared'): ?>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
            Translation cache cleared successfully.
            <?php if (($translationCacheDeleted ?? null) !== null): ?>
                Removed <?php echo (int) $translationCacheDeleted; ?> cache file(s).
            <?php endif; ?>
        </div>
    <?php elseif (($translationCacheStatus ?? null) === 'error'): ?>
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm">
            Translation cache clear completed with errors.
            <?php if (($translationCacheDeleted ?? null) !== null): ?>
                Removed <?php echo (int) $translationCacheDeleted; ?> cache file(s) before failure.
            <?php endif; ?>
        </div>
    <?php elseif (($translationCacheStatus ?? null) === 'csrf_error'): ?>
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800 text-sm">
            Security token expired. Please retry clearing translation cache.
        </div>
    <?php endif; ?>

    <?php if (($siteCacheStatus ?? null) === 'cleared'): ?>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800 text-sm">
            Site-wide cache cleared successfully.
            <?php if (($siteCacheDeleted ?? null) !== null): ?>
                Removed <?php echo (int) $siteCacheDeleted; ?> cache file(s).
            <?php endif; ?>
        </div>
    <?php elseif (($siteCacheStatus ?? null) === 'error'): ?>
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-sm">
            Site-wide cache clear completed with errors.
            <?php if (($siteCacheDeleted ?? null) !== null): ?>
                Removed <?php echo (int) $siteCacheDeleted; ?> cache file(s) before failure.
            <?php endif; ?>
        </div>
    <?php elseif (($siteCacheStatus ?? null) === 'csrf_error'): ?>
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-amber-800 text-sm">
            Security token expired. Please retry clearing the site-wide cache.
        </div>
    <?php endif; ?>

    <section class="bg-gradient-to-r from-[#0F3D3E] to-[#17595A] rounded-2xl p-6 text-white">
        <p class="text-xs uppercase tracking-[0.2em] text-[#F5E8C3] mb-2">Sapphire Events Admin</p>
        <h2 class="text-2xl font-bold mb-2" style="font-family: 'Playfair Display';">Control Center</h2>
        <p class="text-sm text-slate-100">Manage content, service offerings, and incoming client messages from one place.</p>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-6 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                    <i class="fas fa-images"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800"><?php echo (int)($stats['gallery'] ?? 0); ?></p>
            <p class="text-sm text-slate-500 mt-0.5">Gallery Items</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-cyan-100 text-cyan-700 flex items-center justify-center">
                    <i class="fas fa-envelope"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800"><?php echo (int)($stats['inquiries'] ?? 0); ?></p>
            <p class="text-sm text-slate-500 mt-0.5">Inquiries</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800"><?php echo (int)($stats['newsletters'] ?? 0); ?></p>
            <p class="text-sm text-slate-500 mt-0.5">Newsletter Leads</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-violet-100 text-violet-700 flex items-center justify-center">
                    <i class="fas fa-star"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800"><?php echo (int)($stats['testimonials'] ?? 0); ?></p>
            <p class="text-sm text-slate-500 mt-0.5">Testimonials</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800"><?php echo (int)($stats['services'] ?? 0); ?></p>
            <p class="text-sm text-slate-500 mt-0.5">Services</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800"><?php echo (int)($stats['team'] ?? 0); ?></p>
            <p class="text-sm text-slate-500 mt-0.5">Team Members</p>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="xl:col-span-2 bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-800 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="<?php echo route('/admin/gallery/create'); ?>" class="group border border-slate-200 rounded-xl p-4 hover:border-[#C8A951] hover:bg-amber-50/40 transition-colors">
                    <p class="font-semibold text-slate-800 group-hover:text-[#0F3D3E]">Add Gallery Item</p>
                    <p class="text-sm text-slate-500 mt-1">Upload a new event photo.</p>
                </a>
                <a href="<?php echo route('/admin/services/create'); ?>" class="group border border-slate-200 rounded-xl p-4 hover:border-[#C8A951] hover:bg-amber-50/40 transition-colors">
                    <p class="font-semibold text-slate-800 group-hover:text-[#0F3D3E]">Add Service</p>
                    <p class="text-sm text-slate-500 mt-1">Publish a new service offering.</p>
                </a>
                <a href="<?php echo route('/admin/packages/create'); ?>" class="group border border-slate-200 rounded-xl p-4 hover:border-[#C8A951] hover:bg-amber-50/40 transition-colors">
                    <p class="font-semibold text-slate-800 group-hover:text-[#0F3D3E]">Create Package</p>
                    <p class="text-sm text-slate-500 mt-1">Build and price a new package.</p>
                </a>
                <a href="<?php echo route('/admin/testimonials/create'); ?>" class="group border border-slate-200 rounded-xl p-4 hover:border-[#C8A951] hover:bg-amber-50/40 transition-colors">
                    <p class="font-semibold text-slate-800 group-hover:text-[#0F3D3E]">Add Testimonial</p>
                    <p class="text-sm text-slate-500 mt-1">Highlight customer feedback.</p>
                </a>
                <a href="<?php echo route('/admin/team'); ?>" class="group border border-slate-200 rounded-xl p-4 hover:border-[#C8A951] hover:bg-amber-50/40 transition-colors">
                    <p class="font-semibold text-slate-800 group-hover:text-[#0F3D3E]">Manage Team</p>
                    <p class="text-sm text-slate-500 mt-1">Create, edit, and organize team profiles.</p>
                </a>
                <a href="<?php echo route('/admin/settings'); ?>" class="group border border-slate-200 rounded-xl p-4 hover:border-[#C8A951] hover:bg-amber-50/40 transition-colors">
                    <p class="font-semibold text-slate-800 group-hover:text-[#0F3D3E]">Global Site Settings</p>
                    <p class="text-sm text-slate-500 mt-1">Update logo, favicon, OG image, and contact details.</p>
                </a>
                <a href="<?php echo route('/admin/newsletters'); ?>" class="group border border-slate-200 rounded-xl p-4 hover:border-[#C8A951] hover:bg-amber-50/40 transition-colors">
                    <p class="font-semibold text-slate-800 group-hover:text-[#0F3D3E]">Newsletter Leads</p>
                    <p class="text-sm text-slate-500 mt-1">Review and segment subscriber contacts.</p>
                </a>
                <form method="POST" action="<?php echo route('/admin/translations/cache/clear'); ?>" class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                    <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <p class="font-semibold text-slate-800">Clear Translation Cache</p>
                    <p class="text-sm text-slate-500 mt-1 mb-3">Use after editing page or database content to refresh machine translations.</p>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#0F3D3E] px-4 py-2 text-sm font-medium text-white hover:bg-[#155255] transition-colors">
                        <i class="fas fa-language"></i>
                        <span>Clear Cache</span>
                    </button>
                </form>
                <form method="POST" action="<?php echo route('/admin/site-cache/clear'); ?>" class="border border-slate-200 rounded-xl p-4 bg-slate-50">
                    <input type="hidden" name="_csrf_token" value="<?php echo htmlspecialchars($csrf_token ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <p class="font-semibold text-slate-800">Clear Site-Wide Cache</p>
                    <p class="text-sm text-slate-500 mt-1 mb-3">Refresh cached frontend payloads after changing settings, hero backgrounds, or shared content.</p>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-[#C8A951] px-4 py-2 text-sm font-medium text-[#0F3D3E] hover:bg-[#d8bb6a] transition-colors">
                        <i class="fas fa-broom"></i>
                        <span>Clear Site Cache</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="text-base font-semibold text-slate-800 mb-3">Admin Notes</h3>
            <ul class="space-y-2 text-sm text-slate-600">
                <li class="flex gap-2"><i class="fas fa-circle text-[7px] mt-1.5 text-[#C8A951]"></i><span>Keep categories organized before adding new gallery items.</span></li>
                <li class="flex gap-2"><i class="fas fa-circle text-[7px] mt-1.5 text-[#C8A951]"></i><span>Review inquiries daily for timely client responses.</span></li>
                <li class="flex gap-2"><i class="fas fa-circle text-[7px] mt-1.5 text-[#C8A951]"></i><span>Use testimonials to strengthen package pages.</span></li>
            </ul>
            <a href="<?php echo route('/admin/inquiries'); ?>" class="mt-5 inline-flex items-center gap-2 rounded-lg bg-[#0F3D3E] px-4 py-2 text-sm font-medium text-white hover:bg-[#155255] transition-colors">
                <i class="fas fa-envelope-open-text"></i>
                <span>Review Inquiries</span>
            </a>
            <a href="<?php echo route('/admin/newsletters'); ?>" class="mt-2 inline-flex items-center gap-2 rounded-lg bg-[#C8A951] px-4 py-2 text-sm font-medium text-[#0F3D3E] hover:bg-[#d8bb6a] transition-colors">
                <i class="fas fa-users"></i>
                <span>Manage Newsletter Leads</span>
            </a>
        </div>
    </section>
</div>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
