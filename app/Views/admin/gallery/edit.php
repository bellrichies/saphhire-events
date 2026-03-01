<?php
ob_start();

$currentImage = $item['image'] ?? null;
$currentImageUrl = uploadedImageUrl($currentImage);
$isCurrentMediaVideo = preg_match('/\.(mp4|webm|ogg|ogv|mov)(\?.*)?$/i', (string)$currentImageUrl) === 1;
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Edit Gallery Item</h2>
        <a href="<?php echo route('/admin/gallery'); ?>" class="text-gray-600 hover:text-gray-900">&larr; Back</a>
    </div>

    <div>
        <div class="max-w-3xl bg-white rounded-lg luxury-shadow p-8">
            <form id="gallery-edit-form" method="POST" action="<?php echo route('/admin/gallery/update'); ?>" enctype="multipart/form-data" novalidate>
                <?php echo \App\Core\CSRF::hidden(); ?>
                <input type="hidden" name="id" value="<?php echo (int)$item['id']; ?>">

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required maxlength="255" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    <small class="text-red-500 error-title block mt-1"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Category</label>
                    <select name="category_id" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo (int)$cat['id']; ?>" <?php echo (int)$item['category_id'] === (int)$cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-red-500 error-category_id block mt-1"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Description</label>
                    <textarea name="description" rows="5" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600"><?php echo htmlspecialchars($item['description']); ?></textarea>
                    <small class="text-red-500 error-description block mt-1"></small>
                </div>

                <div class="mb-6">
                    <label class="inline-flex items-center gap-3 text-sm font-semibold" style="color: #0F3D3E;">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4" <?php echo ((int)($item['is_featured'] ?? 0) === 1) ? 'checked' : ''; ?>>
                        Set as Featured Item
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Featured items can appear in highlight sections across the website.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Media URL (Image or Video)</label>
                        <input type="url" id="edit-media-url" name="media_url" placeholder="https://example.com/gallery-item.jpg or .mp4" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3">

                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload New Media</label>
                        <input type="file" id="edit-media-file" name="media" accept="image/*,video/mp4,video/webm,video/ogg,video/quicktime,.mov,.ogv" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Supported formats: JPG, PNG, WEBP, AVIF, MP4, WEBM, OGV, MOV. Leave both empty to keep current media.</p>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Current Media</label>
                        <div id="edit-image-preview-wrap" class="<?php echo $currentImageUrl ? 'border-2 border-gray-200 rounded-lg overflow-hidden' : 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500'; ?>">
                            <?php if ($currentImageUrl): ?>
                                <?php if ($isCurrentMediaVideo): ?>
                                    <video src="<?php echo htmlspecialchars($currentImageUrl); ?>" class="w-full h-36 object-cover" muted playsinline controls></video>
                                <?php else: ?>
                                    <img src="<?php echo htmlspecialchars($currentImageUrl); ?>" alt="Current gallery image" class="w-full h-36 object-cover">
                                <?php endif; ?>
                            <?php else: ?>
                                <span>No media set</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($currentImageUrl): ?>
                            <label class="inline-flex items-center gap-2 text-sm mt-3">
                                <input type="checkbox" id="edit-remove-media" name="remove_media" value="1" class="w-4 h-4">
                                Remove current media
                            </label>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                    <button id="update-gallery-btn" type="submit" class="px-8 py-3 rounded-xl font-bold text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-4" style="background: linear-gradient(135deg, #0F3D3E 0%, #1F5E60 55%, #C8A951 100%); --tw-ring-color: rgba(200, 169, 81, 0.35); box-shadow: 0 12px 28px rgba(15, 61, 62, 0.28); letter-spacing: 0.03em;">
                        <i class="fas fa-save mr-2"></i>Update Gallery Item
                    </button>
                    <a href="<?php echo route('/admin/gallery'); ?>" class="px-6 py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-50 text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const form = document.getElementById('gallery-edit-form');
const editMediaUrl = document.getElementById('edit-media-url');
const editMediaFile = document.getElementById('edit-media-file');
const editPreviewWrap = document.getElementById('edit-image-preview-wrap');
const editRemoveMedia = document.getElementById('edit-remove-media');
const submitButton = document.getElementById('update-gallery-btn');
const existingImageSrc = '<?php echo htmlspecialchars($currentImageUrl, ENT_QUOTES); ?>';
let objectUrl = null;

const clearObjectUrl = () => {
    if (objectUrl) {
        URL.revokeObjectURL(objectUrl);
        objectUrl = null;
    }
};

const clearFieldErrors = () => {
    document.querySelectorAll('.error-title, .error-category_id, .error-description').forEach((el) => {
        el.textContent = '';
    });
};

const showMessage = (message, success = false) => {
    const messageDiv = document.getElementById('form-message');
    messageDiv.className = success
        ? 'mb-4 p-4 rounded-lg bg-green-100 text-green-700'
        : 'mb-4 p-4 rounded-lg bg-red-100 text-red-700';
    messageDiv.textContent = message;
    messageDiv.classList.remove('hidden');
};

const isVideoSource = (src) => {
    if (!src) return false;
    return /\.(mp4|webm|ogg|ogv|mov)(\?.*)?$/i.test(src);
};

const renderPreview = (src, type = '') => {
    if (!src) {
        editPreviewWrap.className = 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500';
        editPreviewWrap.textContent = 'No media selected';
        return;
    }
    editPreviewWrap.className = 'border-2 border-gray-200 rounded-lg overflow-hidden';
    const mediaType = type || (isVideoSource(src) ? 'video' : 'image');
    if (mediaType === 'video') {
        editPreviewWrap.innerHTML = '<video src="' + src + '" class="w-full h-36 object-cover" muted playsinline controls></video>';
    } else {
        editPreviewWrap.innerHTML = '<img src="' + src + '" alt="Gallery preview" class="w-full h-36 object-cover">';
    }
};

editMediaFile.addEventListener('change', () => {
    const file = editMediaFile.files && editMediaFile.files[0] ? editMediaFile.files[0] : null;
    clearObjectUrl();

    if (!file) {
        renderPreview(existingImageSrc);
        return;
    }

    if (editRemoveMedia) {
        editRemoveMedia.checked = false;
    }
    editMediaUrl.value = '';
    objectUrl = URL.createObjectURL(file);
    renderPreview(objectUrl, (file.type || '').startsWith('video/') ? 'video' : 'image');
});

editMediaUrl.addEventListener('input', () => {
    const url = editMediaUrl.value.trim();
    if (!url) {
        clearObjectUrl();
        renderPreview(existingImageSrc);
        return;
    }

    if (editRemoveMedia) {
        editRemoveMedia.checked = false;
    }
    editMediaFile.value = '';
    clearObjectUrl();
    renderPreview(url);
});

if (editRemoveMedia) {
    editRemoveMedia.addEventListener('change', () => {
        if (!editRemoveMedia.checked) {
            clearObjectUrl();
            renderPreview(existingImageSrc);
            return;
        }

        editMediaUrl.value = '';
        editMediaFile.value = '';
        clearObjectUrl();
        renderPreview('');
    });
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearFieldErrors();
    const csrfInput = form.querySelector('input[name="_csrf_token"]');

    submitButton.disabled = true;
    submitButton.classList.add('opacity-70', 'cursor-not-allowed');

    try {
        const formData = new FormData(form);
        if (csrfInput && csrfInput.value) {
            formData.set('_csrf_token', csrfInput.value);
        }

        const response = await fetch('<?php echo route('/admin/gallery/update'); ?>', {
            method: 'POST',
            body: formData
        });

        const raw = await response.text();
        let data = null;
        try {
            data = JSON.parse(raw);
        } catch (parseError) {
            data = {
                error: `Request failed with status ${response.status}`,
                details: raw ? raw.slice(0, 400) : 'Empty response body'
            };
        }

        if (!response.ok && !data.error && !data.errors) {
            data.error = `Request failed with status ${response.status}`;
        }

        if (data.success) {
            showMessage(data.message, true);
            setTimeout(() => {
                window.location.href = '<?php echo route('/admin/gallery'); ?>';
            }, 1200);
            return;
        }

        if (data.error === 'CSRF token invalid' && data.csrf_token) {
            if (csrfInput) {
                csrfInput.value = data.csrf_token;
            }
            showMessage('Session token refreshed. Please submit again.');
            return;
        }

        if (data.errors) {
            Object.keys(data.errors).forEach((field) => {
                const target = document.querySelector('.error-' + field);
                if (target) {
                    target.textContent = data.errors[field];
                }
            });
            showMessage('Please fix the highlighted fields.');
            return;
        }

        const parts = [data.error || 'An error occurred while updating the gallery item.'];
        if (data.details) {
            parts.push('Details: ' + data.details);
        }
        if (data.error_id) {
            parts.push('Error ID: ' + data.error_id);
        }
        showMessage(parts.join(' | '));
    } catch (error) {
        showMessage('A network error occurred. Please try again.');
    } finally {
        submitButton.disabled = false;
        submitButton.classList.remove('opacity-70', 'cursor-not-allowed');
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>

