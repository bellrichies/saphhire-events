<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$pathMatches = static function (string $needle) use ($currentPath): bool {
    return $currentPath === $needle
        || str_ends_with($currentPath, $needle)
        || str_contains($currentPath, $needle . '/');
};
$isActive = static function (string $path) use ($currentPath, $pathMatches): bool {
    if ($path === '/admin/dashboard') {
        return $currentPath === '/admin'
            || str_ends_with($currentPath, '/admin')
            || $currentPath === '/admin/'
            || str_ends_with($currentPath, '/admin/')
            || str_contains($currentPath, '/admin/dashboard');
    }
    return $pathMatches($path);
};

$itemClass = static function (string $path) use ($isActive): string {
    $base = 'group flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-all duration-150';
    if ($isActive($path)) {
        return $base . ' bg-[#C8A951]/20 text-white shadow-sm';
    }
    return $base . ' text-slate-300 hover:bg-white/10 hover:text-white';
};

$iconClass = static function (string $path) use ($isActive): string {
    if ($isActive($path)) {
        return 'w-5 text-white';
    }
    return 'w-5 text-slate-400 group-hover:text-white';
};
?>

<aside :class="[mobileOpen ? 'translate-x-0' : '-translate-x-full', 'lg:translate-x-0']" class="fixed lg:static inset-y-0 left-0 z-50 flex w-64 flex-col bg-[#0F3D3E] transition-transform duration-200 ease-in-out">
    <div class="h-16 shrink-0 border-b border-white/10 px-5 flex items-center">
        <div class="w-9 h-9 rounded-lg bg-[#C8A951]/20 border border-[#C8A951]/40 text-[#FDECB8] flex items-center justify-center">
            <i class="fas fa-gem text-sm"></i>
        </div>
        <div class="ml-3 min-w-0">
            <p class="text-white font-bold leading-tight" style="font-family: 'Playfair Display';">Sapphire</p>
            <p class="text-[10px] uppercase tracking-[0.18em] text-slate-300">Admin Panel</p>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto sidebar-scroll px-3 py-4 space-y-6">
        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Overview</p>
            <a href="<?php echo route('/admin/dashboard'); ?>" class="<?php echo $itemClass('/admin/dashboard'); ?>">
                <i class="fas fa-chart-line <?php echo $iconClass('/admin/dashboard'); ?>"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Content</p>
            <div class="space-y-0.5">
                <a href="<?php echo route('/admin/gallery'); ?>" class="<?php echo $itemClass('/admin/gallery'); ?>">
                    <i class="fas fa-images <?php echo $iconClass('/admin/gallery'); ?>"></i>
                    <span>Gallery</span>
                </a>
                <a href="<?php echo route('/admin/media'); ?>" class="<?php echo $itemClass('/admin/media'); ?>">
                    <i class="fas fa-photo-video <?php echo $iconClass('/admin/media'); ?>"></i>
                    <span>Media Library</span>
                </a>
                <a href="<?php echo route('/admin/categories'); ?>" class="<?php echo $itemClass('/admin/categories'); ?>">
                    <i class="fas fa-folder <?php echo $iconClass('/admin/categories'); ?>"></i>
                    <span>Categories</span>
                </a>
                <a href="<?php echo route('/admin/package-categories'); ?>" class="<?php echo $itemClass('/admin/package-categories'); ?>">
                    <i class="fas fa-layer-group <?php echo $iconClass('/admin/package-categories'); ?>"></i>
                    <span>Package Categories</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Offers</p>
            <div class="space-y-0.5">
                <a href="<?php echo route('/admin/services'); ?>" class="<?php echo $itemClass('/admin/services'); ?>">
                    <i class="fas fa-briefcase <?php echo $iconClass('/admin/services'); ?>"></i>
                    <span>Services</span>
                </a>
                <a href="<?php echo route('/admin/packages'); ?>" class="<?php echo $itemClass('/admin/packages'); ?>">
                    <i class="fas fa-box-open <?php echo $iconClass('/admin/packages'); ?>"></i>
                    <span>Packages</span>
                </a>
                <a href="<?php echo route('/admin/testimonials'); ?>" class="<?php echo $itemClass('/admin/testimonials'); ?>">
                    <i class="fas fa-star <?php echo $iconClass('/admin/testimonials'); ?>"></i>
                    <span>Testimonials</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Communication</p>
            <a href="<?php echo route('/admin/inquiries'); ?>" class="<?php echo $itemClass('/admin/inquiries'); ?>">
                <i class="fas fa-envelope <?php echo $iconClass('/admin/inquiries'); ?>"></i>
                <span>Inquiries</span>
            </a>
            <a href="<?php echo route('/admin/newsletters'); ?>" class="<?php echo $itemClass('/admin/newsletters'); ?>">
                <i class="fas fa-envelope-open-text <?php echo $iconClass('/admin/newsletters'); ?>"></i>
                <span>Newsletter Leads</span>
            </a>
        </div>

        <div>
            <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-widest text-slate-400">Company</p>
            <a href="<?php echo route('/admin/team'); ?>" class="<?php echo $itemClass('/admin/team'); ?>">
                <i class="fas fa-users <?php echo $iconClass('/admin/team'); ?>"></i>
                <span>Team</span>
            </a>
        </div>
    </nav>

    <div class="shrink-0 border-t border-white/10 p-3">
        <a href="<?php echo route('/admin/logout'); ?>" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-red-200 hover:bg-red-900/30 hover:text-red-100 transition-colors">
            <i class="fas fa-sign-out-alt w-5"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>
