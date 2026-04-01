<?php
ob_start();
$pageTitle = 'Global Site Settings';
$title = 'Global Site Settings';

$settings = is_array($settings ?? null) ? $settings : [];
$siteLogo = uploadedImageUrl($settings['site_logo'] ?? '');
$siteFavicon = uploadedImageUrl($settings['site_favicon'] ?? '');
$siteOgImage = uploadedImageUrl($settings['site_og_image'] ?? '');
$innerHeroBackgroundImage = uploadedImageUrl($settings['inner_hero_background_image'] ?? '');
$fontOptions = themeFontOptions();
?>

<div class="space-y-6">
    <section class="bg-gradient-to-r from-[#0F3D3E] to-[#17595A] rounded-2xl p-6 text-white">
        <p class="text-xs uppercase tracking-[0.2em] text-[#F5E8C3] mb-2">Brand & Contact</p>
        <h2 class="text-2xl font-bold mb-2" style="font-family: 'Playfair Display';">Global Site Settings</h2>
        <p class="text-sm text-slate-100">Update the branding assets and contact details that your public pages consume.</p>
    </section>

    <div id="settings-message" class="hidden rounded-xl px-4 py-3 text-sm"></div>

    <form id="site-settings-form" method="POST" action="<?php echo route('/admin/settings'); ?>" enctype="multipart/form-data" class="space-y-6">
        <?php echo \App\Core\CSRF::hidden(); ?>

        <section class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Core Details</h3>
                <p class="text-sm text-slate-500 mt-1">These values feed the public header, footer, SEO metadata, and contact page.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="site-name">Site Name</label>
                    <input id="site-name" type="text" name="site_name" value="<?php echo htmlspecialchars((string)($settings['site_name'] ?? '')); ?>" maxlength="150" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-site_name block mt-1"></small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="site-tagline">Tagline</label>
                    <input id="site-tagline" type="text" name="site_tagline" value="<?php echo htmlspecialchars((string)($settings['site_tagline'] ?? '')); ?>" maxlength="255" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-site_tagline block mt-1"></small>
                </div>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2" for="site-description">Site Description</label>
                <textarea id="site-description" name="site_description" rows="3" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"><?php echo htmlspecialchars((string)($settings['site_description'] ?? '')); ?></textarea>
                <small class="text-red-500 error-site_description block mt-1"></small>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="site-email">Contact Email</label>
                    <input id="site-email" type="email" name="site_email" value="<?php echo htmlspecialchars((string)($settings['site_email'] ?? '')); ?>" maxlength="255" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-site_email block mt-1"></small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="site-phone">Phone Number</label>
                    <input id="site-phone" type="text" name="site_phone" value="<?php echo htmlspecialchars((string)($settings['site_phone'] ?? '')); ?>" maxlength="60" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-site_phone block mt-1"></small>
                </div>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2" for="site-address">Office Address</label>
                <textarea id="site-address" name="site_address" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"><?php echo htmlspecialchars((string)($settings['site_address'] ?? '')); ?></textarea>
                <small class="text-red-500 error-site_address block mt-1"></small>
            </div>

            <div class="mt-5">
                <label class="block text-sm font-semibold text-slate-700 mb-2" for="site-registration-code">Registration Code</label>
                <input id="site-registration-code" type="text" name="site_registration_code" value="<?php echo htmlspecialchars((string)($settings['site_registration_code'] ?? '')); ?>" maxlength="80" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                <small class="text-red-500 error-site_registration_code block mt-1"></small>
            </div>
        </section>

        <section class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Theme & Typography</h3>
                <p class="text-sm text-slate-500 mt-1">Control the shared colors, font pairings, and default text scale used across the frontend.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="theme-primary-color">Primary Color</label>
                    <input id="theme-primary-color" type="color" name="theme_primary_color" value="<?php echo htmlspecialchars((string)($settings['theme_primary_color'] ?? '#0F3D3E')); ?>" class="h-12 w-full rounded-lg border border-slate-300 p-1">
                    <small class="text-red-500 error-theme_primary_color block mt-1"></small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="theme-accent-color">Accent Color</label>
                    <input id="theme-accent-color" type="color" name="theme_accent_color" value="<?php echo htmlspecialchars((string)($settings['theme_accent_color'] ?? '#C8A951')); ?>" class="h-12 w-full rounded-lg border border-slate-300 p-1">
                    <small class="text-red-500 error-theme_accent_color block mt-1"></small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="theme-light-color">Light Surface Color</label>
                    <input id="theme-light-color" type="color" name="theme_light_color" value="<?php echo htmlspecialchars((string)($settings['theme_light_color'] ?? '#F8F5F2')); ?>" class="h-12 w-full rounded-lg border border-slate-300 p-1">
                    <small class="text-red-500 error-theme_light_color block mt-1"></small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="theme-dark-color">Dark Color</label>
                    <input id="theme-dark-color" type="color" name="theme_dark_color" value="<?php echo htmlspecialchars((string)($settings['theme_dark_color'] ?? '#1C1C1C')); ?>" class="h-12 w-full rounded-lg border border-slate-300 p-1">
                    <small class="text-red-500 error-theme_dark_color block mt-1"></small>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mt-6">
                <?php
                $fontFields = [
                    'theme_body_font' => 'Body Font',
                    'theme_heading_font' => 'Heading Font',
                    'theme_display_font' => 'Display Font',
                    'theme_ui_font' => 'UI Font',
                ];
                ?>
                <?php foreach ($fontFields as $field => $label): ?>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2" for="<?php echo htmlspecialchars($field); ?>"><?php echo htmlspecialchars($label); ?></label>
                        <select id="<?php echo htmlspecialchars($field); ?>" name="<?php echo htmlspecialchars($field); ?>" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                            <?php foreach ($fontOptions as $fontKey => $font): ?>
                                <option value="<?php echo htmlspecialchars($fontKey); ?>" <?php echo (($settings[$field] ?? '') === $fontKey) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($font['label']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-red-500 error-<?php echo htmlspecialchars($field); ?> block mt-1"></small>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mt-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="theme-body-size">Body Size</label>
                    <input id="theme-body-size" type="text" name="theme_body_size" value="<?php echo htmlspecialchars((string)($settings['theme_body_size'] ?? '1rem')); ?>" placeholder="1rem" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-theme_body_size block mt-1"></small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="theme-h1-size">H1 Size</label>
                    <input id="theme-h1-size" type="text" name="theme_h1_size" value="<?php echo htmlspecialchars((string)($settings['theme_h1_size'] ?? '3.5rem')); ?>" placeholder="3.5rem" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-theme_h1_size block mt-1"></small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="theme-h2-size">H2 Size</label>
                    <input id="theme-h2-size" type="text" name="theme_h2_size" value="<?php echo htmlspecialchars((string)($settings['theme_h2_size'] ?? '2.5rem')); ?>" placeholder="2.5rem" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-theme_h2_size block mt-1"></small>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Brand Assets & Inner Hero Background</h3>
                <p class="text-sm text-slate-500 mt-1">Each asset can be set with a media URL, the media library, or a fresh upload.</p>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <?php
                $assetFields = [
                    [
                        'key' => 'site_logo',
                        'label' => 'Site Logo',
                        'url_name' => 'site_logo_url',
                        'file_name' => 'site_logo_file',
                        'preview' => $siteLogo,
                        'error_class' => 'site_logo',
                    ],
                    [
                        'key' => 'site_favicon',
                        'label' => 'Favicon',
                        'url_name' => 'site_favicon_url',
                        'file_name' => 'site_favicon_file',
                        'preview' => $siteFavicon,
                        'error_class' => 'site_favicon',
                    ],
                    [
                        'key' => 'site_og_image',
                        'label' => 'Open Graph Image',
                        'url_name' => 'site_og_image_url',
                        'file_name' => 'site_og_image_file',
                        'preview' => $siteOgImage,
                        'error_class' => 'site_og_image',
                    ],
                ];
                ?>
                <?php foreach ($assetFields as $asset): ?>
                    <article class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <h4 class="text-base font-semibold text-slate-800"><?php echo htmlspecialchars($asset['label']); ?></h4>
                                <p class="text-xs text-slate-500 mt-1">Current asset preview.</p>
                            </div>
                            <div class="h-20 w-20 rounded-xl border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center" data-preview-wrap="<?php echo htmlspecialchars($asset['key']); ?>">
                                <?php if ($asset['preview'] !== ''): ?>
                                    <img src="<?php echo htmlspecialchars($asset['preview']); ?>" alt="<?php echo htmlspecialchars($asset['label']); ?> preview" class="h-full w-full object-contain bg-white">
                                <?php else: ?>
                                    <span class="text-[11px] text-slate-400 text-center px-2">No image</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2" for="<?php echo htmlspecialchars($asset['url_name']); ?>"><?php echo htmlspecialchars($asset['label']); ?> URL or Path</label>
                        <input
                            id="<?php echo htmlspecialchars($asset['url_name']); ?>"
                            type="text"
                            name="<?php echo htmlspecialchars($asset['url_name']); ?>"
                            value=""
                            placeholder="https://example.com/image.png or /assets/images/file.png"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"
                            data-settings-url-input="<?php echo htmlspecialchars($asset['key']); ?>"
                        >

                        <div class="mt-3 flex flex-wrap gap-2">
                            <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50" data-media-target="<?php echo htmlspecialchars($asset['url_name']); ?>">
                                <i class="fas fa-photo-video"></i>
                                <span>Choose from Media Library</span>
                            </button>
                        </div>

                        <label class="block text-sm font-semibold text-slate-700 mt-4 mb-2" for="<?php echo htmlspecialchars($asset['file_name']); ?>">Or Upload New Image</label>
                        <input
                            id="<?php echo htmlspecialchars($asset['file_name']); ?>"
                            type="file"
                            name="<?php echo htmlspecialchars($asset['file_name']); ?>"
                            accept=".jpg,.jpeg,.png,.webp,.avif,.svg,.ico,image/jpeg,image/png,image/webp,image/avif,image/svg+xml,image/x-icon"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                            data-settings-file-input="<?php echo htmlspecialchars($asset['key']); ?>"
                        >
                        <p class="mt-2 text-xs text-slate-500">Leave both fields empty to keep the current asset. Use one source only.</p>
                        <small class="text-red-500 error-<?php echo htmlspecialchars($asset['error_class']); ?> block mt-1"></small>
                    </article>
                <?php endforeach; ?>
            </div>

            <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 sm:p-5">
                <div class="flex flex-col gap-1 mb-5">
                    <h4 class="text-base font-semibold text-slate-800">Inner Page Hero Background</h4>
                    <p class="text-xs text-slate-500">Keep the image upload, render mode, and overlay controls together so admins can tune the result in one place.</p>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-[1.2fr_0.8fr] gap-5">
                    <article class="rounded-xl border border-slate-200 bg-white p-4">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <h5 class="text-sm font-semibold text-slate-800">Background Image</h5>
                                <p class="text-xs text-slate-500 mt-1">Used by non-home hero sections when image mode is enabled.</p>
                            </div>
                            <div class="h-20 w-20 rounded-xl border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center" data-preview-wrap="inner_hero_background_image">
                                <?php if ($innerHeroBackgroundImage !== ''): ?>
                                    <img src="<?php echo htmlspecialchars($innerHeroBackgroundImage); ?>" alt="Inner page hero background preview" class="h-full w-full object-contain bg-white">
                                <?php else: ?>
                                    <span class="text-[11px] text-slate-400 text-center px-2">No image</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2" for="inner_hero_background_image_url">Background URL or Path</label>
                        <input
                            id="inner_hero_background_image_url"
                            type="text"
                            name="inner_hero_background_image_url"
                            value=""
                            placeholder="https://example.com/hero.jpg or /assets/uploads/..."
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20"
                            data-settings-url-input="inner_hero_background_image"
                        >

                        <div class="mt-3 flex flex-wrap gap-2">
                            <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-slate-300 px-3 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50" data-media-target="inner_hero_background_image_url">
                                <i class="fas fa-photo-video"></i>
                                <span>Choose from Media Library</span>
                            </button>
                        </div>

                        <label class="block text-sm font-semibold text-slate-700 mt-4 mb-2" for="inner_hero_background_image_file">Or Upload New Image</label>
                        <input
                            id="inner_hero_background_image_file"
                            type="file"
                            name="inner_hero_background_image_file"
                            accept=".jpg,.jpeg,.png,.webp,.avif,.svg,.ico,image/jpeg,image/png,image/webp,image/avif,image/svg+xml,image/x-icon"
                            class="w-full rounded-lg border border-slate-300 px-4 py-2.5"
                            data-settings-file-input="inner_hero_background_image"
                        >
                        <p class="mt-2 text-xs text-slate-500">Leave both fields empty to keep the current image. Use one source only.</p>
                        <small class="text-red-500 error-inner_hero_background_image block mt-1"></small>
                    </article>

                    <article class="rounded-xl border border-slate-200 bg-white p-4">
                        <div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2" for="inner-hero-render-mode">Render Mode</label>
                                <div id="inner-hero-render-mode" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <label class="flex items-start gap-3 rounded-lg border border-slate-300 px-3 py-3 transition-colors hover:border-[#0F3D3E]/40 hover:bg-slate-50">
                                        <input
                                            type="radio"
                                            name="inner_hero_render_mode"
                                            value="gradient_only"
                                            class="mt-1 h-4 w-4 accent-[#0F3D3E]"
                                            <?php echo (($settings['inner_hero_render_mode'] ?? 'gradient_only') === 'gradient_only') ? 'checked' : ''; ?>
                                        >
                                        <span class="block">
                                            <span class="block text-sm font-semibold text-slate-700">Gradient Only</span>
                                            <span class="block text-xs text-slate-500 mt-1">Show only the configured gradient colors.</span>
                                        </span>
                                    </label>
                                    <label class="flex items-start gap-3 rounded-lg border border-slate-300 px-3 py-3 transition-colors hover:border-[#0F3D3E]/40 hover:bg-slate-50">
                                        <input
                                            type="radio"
                                            name="inner_hero_render_mode"
                                            value="image_overlay"
                                            class="mt-1 h-4 w-4 accent-[#0F3D3E]"
                                            <?php echo (($settings['inner_hero_render_mode'] ?? '') === 'image_overlay') ? 'checked' : ''; ?>
                                        >
                                        <span class="block">
                                            <span class="block text-sm font-semibold text-slate-700">Image with Overlay</span>
                                            <span class="block text-xs text-slate-500 mt-1">Show the uploaded hero image beneath the overlay.</span>
                                        </span>
                                    </label>
                                </div>
                                <small class="text-red-500 error-inner_hero_render_mode block mt-1"></small>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <label class="block text-sm font-semibold text-slate-700" for="inner-hero-overlay-opacity">Overlay Opacity</label>
                                <span id="inner-hero-overlay-opacity-value" class="text-xs font-semibold text-slate-500"><?php echo htmlspecialchars((string)($settings['inner_hero_overlay_opacity'] ?? '65')); ?>%</span>
                            </div>
                            <input id="inner-hero-overlay-opacity" type="range" name="inner_hero_overlay_opacity" min="0" max="100" step="1" value="<?php echo htmlspecialchars((string)($settings['inner_hero_overlay_opacity'] ?? '65')); ?>" class="mt-2 w-full accent-[#0F3D3E]">
                            <div class="mt-2 flex items-center justify-between text-[11px] text-slate-400">
                                <span>0%</span>
                                <span>100%</span>
                            </div>
                            <small class="text-red-500 error-inner_hero_overlay_opacity block mt-1"></small>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2" for="inner-hero-overlay-start">Overlay Start</label>
                                <input id="inner-hero-overlay-start" type="color" name="inner_hero_overlay_start" value="<?php echo htmlspecialchars((string)($settings['inner_hero_overlay_start'] ?? '#0F3D3E')); ?>" class="h-12 w-full rounded-lg border border-slate-300 p-1">
                                <small class="text-red-500 error-inner_hero_overlay_start block mt-1"></small>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2" for="inner-hero-overlay-end">Overlay End</label>
                                <input id="inner-hero-overlay-end" type="color" name="inner_hero_overlay_end" value="<?php echo htmlspecialchars((string)($settings['inner_hero_overlay_end'] ?? '#1C1C1C')); ?>" class="h-12 w-full rounded-lg border border-slate-300 p-1">
                                <small class="text-red-500 error-inner_hero_overlay_end block mt-1"></small>
                            </div>
                        </div>

                        <div class="mt-4 rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs text-slate-600">Use `Gradient Only` for color-only heroes, or `Image with Overlay` to show the selected image beneath the adjustable overlay.</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-xl border border-slate-200 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-800">Social Links</h3>
                <p class="text-sm text-slate-500 mt-1">These are used in the public footer and contact page social actions.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="social-instagram">Instagram URL</label>
                    <input id="social-instagram" type="url" name="social_instagram" value="<?php echo htmlspecialchars((string)($settings['social_instagram'] ?? '')); ?>" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-social_instagram block mt-1"></small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="social-facebook">Facebook URL</label>
                    <input id="social-facebook" type="url" name="social_facebook" value="<?php echo htmlspecialchars((string)($settings['social_facebook'] ?? '')); ?>" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-social_facebook block mt-1"></small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="social-tiktok">TikTok URL</label>
                    <input id="social-tiktok" type="url" name="social_tiktok" value="<?php echo htmlspecialchars((string)($settings['social_tiktok'] ?? '')); ?>" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-social_tiktok block mt-1"></small>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2" for="social-whatsapp">WhatsApp URL</label>
                    <input id="social-whatsapp" type="url" name="social_whatsapp" value="<?php echo htmlspecialchars((string)($settings['social_whatsapp'] ?? '')); ?>" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                    <small class="text-red-500 error-social_whatsapp block mt-1"></small>
                </div>
            </div>
        </section>

        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit" id="save-settings-btn" class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#0F3D3E] px-5 py-3 text-sm font-semibold text-white hover:bg-[#155255] transition-colors">
                <i class="fas fa-save"></i>
                <span>Save Settings</span>
            </button>
            <a href="<?php echo route('/admin/dashboard'); ?>" class="inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                <span>Back to Dashboard</span>
            </a>
        </div>
    </form>
</div>

<script>
(() => {
    const form = document.getElementById('site-settings-form');
    const messageBox = document.getElementById('settings-message');
    const submitButton = document.getElementById('save-settings-btn');
    const overlayOpacityInput = document.getElementById('inner-hero-overlay-opacity');
    const overlayOpacityValue = document.getElementById('inner-hero-overlay-opacity-value');
    const MAX_IMAGE_SIZE = 20 * 1024 * 1024;

    if (!form || !messageBox || !submitButton) {
        return;
    }

    if (overlayOpacityInput && overlayOpacityValue) {
        const syncOverlayOpacity = () => {
            overlayOpacityValue.textContent = overlayOpacityInput.value + '%';
        };
        overlayOpacityInput.addEventListener('input', syncOverlayOpacity);
        syncOverlayOpacity();
    }

    const clearErrors = () => {
        form.querySelectorAll('small[class*="error-"]').forEach((element) => {
            element.textContent = '';
        });
    };

    const showMessage = (message, isSuccess) => {
        messageBox.textContent = message;
        messageBox.className = isSuccess
            ? 'rounded-xl px-4 py-3 text-sm bg-emerald-50 border border-emerald-200 text-emerald-800'
            : 'rounded-xl px-4 py-3 text-sm bg-red-50 border border-red-200 text-red-800';
        messageBox.classList.remove('hidden');
    };

    const previewAsset = (key, source) => {
        const previewWrap = document.querySelector('[data-preview-wrap="' + key + '"]');
        if (!previewWrap) {
            return;
        }

        if (!source) {
            previewWrap.innerHTML = '<span class="text-[11px] text-slate-400 text-center px-2">No image</span>';
            return;
        }

        previewWrap.innerHTML = '<img src="' + source + '" alt="Preview" class="h-full w-full object-contain bg-white">';
    };

    form.querySelectorAll('[data-settings-url-input]').forEach((input) => {
        input.addEventListener('input', () => {
            const key = input.getAttribute('data-settings-url-input');
            const fileInput = form.querySelector('[data-settings-file-input="' + key + '"]');
            if (input.value.trim() !== '' && fileInput) {
                fileInput.value = '';
            }
            previewAsset(key, input.value.trim());
        });
    });

    form.querySelectorAll('[data-settings-file-input]').forEach((input) => {
        input.addEventListener('change', () => {
            const key = input.getAttribute('data-settings-file-input');
            const file = input.files && input.files[0] ? input.files[0] : null;
            const urlInput = form.querySelector('[data-settings-url-input="' + key + '"]');

            if (!file) {
                if (urlInput && urlInput.value.trim() !== '') {
                    previewAsset(key, urlInput.value.trim());
                }
                return;
            }

            if ((file.size || 0) > MAX_IMAGE_SIZE) {
                input.value = '';
                showMessage('Image exceeds max upload size of 20MB.', false);
                return;
            }

            if (urlInput) {
                urlInput.value = '';
            }

            previewAsset(key, URL.createObjectURL(file));
        });
    });

    form.querySelectorAll('[data-media-target]').forEach((button) => {
        button.addEventListener('click', () => {
            const targetName = button.getAttribute('data-media-target');
            const targetInput = form.querySelector('[name="' + targetName + '"]');
            if (!targetInput || !window.AdminMediaPicker) {
                return;
            }

            window.AdminMediaPicker.open({
                targetInput,
                allowedTypes: ['image'],
                allowSelection: true,
            });
        });
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearErrors();
        messageBox.classList.add('hidden');

        submitButton.disabled = true;
        submitButton.classList.add('opacity-70', 'cursor-not-allowed');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
            });

            const data = await response.json();

            if (data.csrf_token) {
                const csrfInput = form.querySelector('input[name="_csrf_token"]');
                if (csrfInput) {
                    csrfInput.value = data.csrf_token;
                }
            }

            if (data.success) {
                let successMessage = data.message || 'Settings saved successfully.';
                if (data.cache && typeof data.cache.deleted !== 'undefined') {
                    successMessage += ' Cleared ' + data.cache.deleted + ' cache file(s).';
                }
                showMessage(successMessage, true);
                window.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            if (data.errors) {
                Object.entries(data.errors).forEach(([field, message]) => {
                    const target = form.querySelector('.error-' + field);
                    if (target) {
                        target.textContent = String(message);
                    }
                });
                showMessage('Please fix the highlighted fields and try again.', false);
                return;
            }

            showMessage(data.error || 'Unable to save settings right now.', false);
        } catch (error) {
            showMessage('A network error occurred. Please try again.', false);
        } finally {
            submitButton.disabled = false;
            submitButton.classList.remove('opacity-70', 'cursor-not-allowed');
        }
    });
})();
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
