<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sapphire Events</title>
    <meta name="admin-csrf-token" content="<?php echo htmlspecialchars(\App\Core\CSRF::getToken()); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=League+Spartan:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&family=Syncopate:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --emerald: #0F3D3E;
            --gold: #C8A951;
            --off-white: #F8F5F2;
            --charcoal: #1C1C1C;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--charcoal);
            background-color: #eef2f7;
            overflow-x: hidden;
        }

        h4,
        p {
            font-family: 'League Spartan', 'Inter', sans-serif;
        }

        h1,
        h2 {
            font-family: 'Syncopate', 'Inter', sans-serif;
        }

        [x-cloak] { display: none !important; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.18); border-radius: 8px; }
        .sidebar-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.28); }
        .admin-fade-in { animation: admin-fade-in .2s ease-out; }
        @keyframes admin-fade-in {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .confirm-toast {
            animation: confirm-toast-in 180ms ease-out;
        }

        @keyframes confirm-toast-in {
            from {
                opacity: 0;
                transform: scale(0.96);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        main table {
            min-width: 640px;
        }
    </style>
</head>
<body class="antialiased">
    <?php
    $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
    $pathMatches = static function (string $needle) use ($currentPath): bool {
        return $currentPath === $needle
            || str_ends_with($currentPath, $needle)
            || str_contains($currentPath, $needle . '/');
    };
    $isLoginPage = $pathMatches('/admin/login');
    $pageTitleMap = [
        '/admin/dashboard' => 'Dashboard',
        '/admin/gallery' => 'Gallery',
        '/admin/categories' => 'Categories',
        '/admin/package-categories' => 'Package Categories',
        '/admin/services' => 'Services',
        '/admin/packages' => 'Packages',
        '/admin/testimonials' => 'Testimonials',
        '/admin/inquiries' => 'Inquiries',
        '/admin/newsletters' => 'Newsletter Leads',
        '/admin/settings' => 'Global Site Settings',
        '/admin/team' => 'Team',
        '/admin/media' => 'Media Library',
    ];
    $resolvedPageTitle = $pageTitle ?? $title ?? 'Admin';
    foreach ($pageTitleMap as $prefix => $label) {
        if ($pathMatches($prefix)) {
            $resolvedPageTitle = $label;
            break;
        }
    }
    ?>

    <?php if ($isLoginPage): ?>
        <?php echo $content ?? ''; ?>
    <?php else: ?>
    <div x-data="{ mobileOpen: false }" class="flex min-h-screen min-h-[100dvh] overflow-hidden">
        <div x-show="mobileOpen" x-cloak @click="mobileOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden" x-transition.opacity></div>

        <?php include VIEW_PATH . '/partials/admin-sidebar.php'; ?>

        <div class="flex-1 flex min-w-0 flex-col overflow-hidden">
            <header class="h-16 shrink-0 border-b border-slate-200 bg-white px-4 lg:px-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 -ml-2 rounded-lg text-slate-600 hover:bg-slate-100" type="button" aria-label="Open sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-lg lg:text-xl font-semibold text-slate-800"><?php echo htmlspecialchars($resolvedPageTitle); ?></h1>
                </div>
                <div class="flex items-center gap-3 text-sm">
                    <span class="hidden md:inline text-slate-500"><?php echo htmlspecialchars($_SESSION['admin_email'] ?? 'Admin'); ?></span>
                    <a href="<?php echo route('/admin/logout'); ?>" class="inline-flex items-center gap-2 rounded-lg border border-red-200 px-3 py-1.5 text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto overflow-x-auto p-4 lg:p-6 admin-fade-in">
                <?php echo $content ?? ''; ?>
            </main>
        </div>
    </div>
    <?php endif; ?>

    <script>
        window.AdminToastConfirm = (() => {
            let activeElement = null;
            let keyHandler = null;

            const removeActive = (result = false) => {
                if (!activeElement) {
                    return;
                }

                if (keyHandler) {
                    document.removeEventListener('keydown', keyHandler);
                    keyHandler = null;
                }

                const element = activeElement;
                activeElement = null;
                element.remove();
                return result;
            };

            const show = (options = {}) => {
                if (activeElement) {
                    removeActive(false);
                }

                const title = options.title || 'Confirm Deletion';
                const message = options.message || 'Are you sure you want to delete this item?';
                const confirmText = options.confirmText || 'Delete';
                const cancelText = options.cancelText || 'Cancel';

                return new Promise((resolve) => {
                    const host = document.createElement('div');
                    host.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4';
                    host.innerHTML = `
                        <div class="absolute inset-0 bg-black/35" data-confirm-overlay></div>
                        <div class="confirm-toast relative w-full max-w-sm rounded-2xl border border-red-100 bg-white shadow-2xl">
                            <div class="flex items-start gap-3 p-4">
                                <div class="mt-0.5 h-9 w-9 rounded-full bg-red-50 text-red-600 flex items-center justify-center">
                                    <i class="fas fa-trash"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-gray-900">${title}</p>
                                    <p class="mt-1 text-sm text-gray-600">${message}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-2 px-4 pb-4">
                                <button type="button" data-confirm-cancel class="px-3 py-2 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">${cancelText}</button>
                                <button type="button" data-confirm-yes class="px-3 py-2 rounded-lg text-sm font-semibold text-white bg-red-600 hover:bg-red-700">${confirmText}</button>
                            </div>
                        </div>
                    `;

                    const cancelBtn = host.querySelector('[data-confirm-cancel]');
                    const confirmBtn = host.querySelector('[data-confirm-yes]');
                    const overlay = host.querySelector('[data-confirm-overlay]');

                    cancelBtn.addEventListener('click', () => {
                        removeActive(false);
                        resolve(false);
                    });

                    confirmBtn.addEventListener('click', () => {
                        removeActive(true);
                        resolve(true);
                    });

                    overlay.addEventListener('click', () => {
                        removeActive(false);
                        resolve(false);
                    });

                    keyHandler = (event) => {
                        if (event.key === 'Escape') {
                            removeActive(false);
                            resolve(false);
                        }
                    };
                    document.addEventListener('keydown', keyHandler);

                    activeElement = host;
                    document.body.appendChild(host);
                    confirmBtn.focus();
                });
            };

            return { show };
        })();
    </script>
    <script>
        window.AdminMediaConfig = {
            list: '<?php echo route('/admin/media/list'); ?>',
            upload: '<?php echo route('/admin/media/upload'); ?>',
            delete: '<?php echo route('/admin/media/delete'); ?>'
        };
    </script>
    <script src="<?php echo asset('js/admin-media-library.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
