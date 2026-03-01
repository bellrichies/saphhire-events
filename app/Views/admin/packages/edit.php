<?php
ob_start();

$currentImage = $package['image'] ?? null;
$currentImageUrl = uploadedImageUrl($currentImage);
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Edit Package</h2>
        <a href="<?php echo route('/admin/packages'); ?>" class="text-gray-600 hover:text-gray-900">? Back</a>
    </div>

    <div>
        <div class="max-w-3xl bg-white rounded-lg luxury-shadow p-8">
            <form id="package-edit-form" method="POST" action="<?php echo route('/admin/packages/update'); ?>" enctype="multipart/form-data">
                <?php echo \App\Core\CSRF::hidden(); ?>
                <input type="hidden" name="id" value="<?php echo (int)$package['id']; ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Package Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($package['title']); ?>" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Category</label>
                        <select name="category_id" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo (int)$cat['id']; ?>" <?php echo (int)$cat['id'] === (int)$package['category_id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Description</label>
                    <textarea name="description" rows="4" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600"><?php echo htmlspecialchars($package['description']); ?></textarea>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Features (one per line)</label>
                    <textarea name="features" rows="5" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600"><?php echo htmlspecialchars($package['features'] ?? ''); ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Price Label</label>
                        <input type="text" name="price_label" value="<?php echo htmlspecialchars($package['price_label']); ?>" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Numeric Price (optional)</label>
                        <input type="number" step="0.01" min="0" name="price_amount" value="<?php echo htmlspecialchars((string)$package['price_amount']); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Currency</label>
                        <input type="text" name="currency" value="<?php echo htmlspecialchars($package['currency'] ?? 'EUR'); ?>" maxlength="10" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Image URL</label>
                        <input type="url" id="edit-image-url" name="image_url" placeholder="https://example.com/package-image.jpg or /assets/images/package.jpg" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3">

                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload New Image</label>
                        <input type="file" id="edit-image-file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Provide either an image URL or a local file upload. Leave both empty to keep the current image.</p>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Current Image</label>
                        <div id="edit-image-preview-wrap" class="<?php echo $currentImageUrl ? 'border-2 border-gray-200 rounded-lg overflow-hidden' : 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500'; ?>">
                            <?php if ($currentImageUrl): ?>
                                <img id="edit-image-preview" src="<?php echo htmlspecialchars($currentImageUrl); ?>" alt="Current package image" class="w-full h-36 object-cover">
                            <?php else: ?>
                                <span id="edit-image-placeholder">No image set</span>
                            <?php endif; ?>
                        </div>
                        <?php if ($currentImageUrl): ?>
                            <label class="inline-flex items-center gap-2 text-sm mt-3">
                                <input type="checkbox" id="edit-remove-image" name="remove_image" value="1" class="w-4 h-4">
                                Remove current image
                            </label>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Display Order</label>
                        <input type="number" name="display_order" value="<?php echo (int)($package['display_order'] ?? 0); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                    <div class="flex items-center pt-8">
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_featured" value="1" class="w-4 h-4" <?php echo ((int)($package['is_featured'] ?? 0) === 1) ? 'checked' : ''; ?>>
                            Mark as featured
                        </label>
                    </div>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex gap-4">
                    <button type="submit" class="px-6 py-2 rounded-lg font-semibold text-white shadow-md transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-4" style="background: linear-gradient(135deg, #0F3D3E 0%, #1F5E60 100%); box-shadow: 0 10px 24px rgba(15, 61, 62, 0.25); --tw-ring-color: rgba(200, 169, 81, 0.35);">Update Package</button>
                    <a href="<?php echo route('/admin/packages'); ?>" class="px-6 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const editForm = document.getElementById('package-edit-form');
const editImageUrl = document.getElementById('edit-image-url');
const editImageFile = document.getElementById('edit-image-file');
const editPreviewWrap = document.getElementById('edit-image-preview-wrap');
const editRemoveImage = document.getElementById('edit-remove-image');
const existingImageSrc = '<?php echo htmlspecialchars($currentImageUrl, ENT_QUOTES); ?>';
let editObjectUrl = null;

const clearEditObjectUrl = () => {
    if (editObjectUrl) {
        URL.revokeObjectURL(editObjectUrl);
        editObjectUrl = null;
    }
};

const renderEditPreview = (src) => {
    if (!src) {
        editPreviewWrap.className = 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500';
        editPreviewWrap.textContent = 'No image selected';
        return;
    }

    editPreviewWrap.className = 'border-2 border-gray-200 rounded-lg overflow-hidden';
    editPreviewWrap.innerHTML = '<img src="' + src + '" alt="Package preview" class="w-full h-36 object-cover">';
};

editImageFile.addEventListener('change', () => {
    const file = editImageFile.files && editImageFile.files[0] ? editImageFile.files[0] : null;
    if (!file) {
        clearEditObjectUrl();
        renderEditPreview(existingImageSrc);
        return;
    }

    if (editRemoveImage) {
        editRemoveImage.checked = false;
    }
    editImageUrl.value = '';
    clearEditObjectUrl();
    editObjectUrl = URL.createObjectURL(file);
    renderEditPreview(editObjectUrl);
});

editImageUrl.addEventListener('input', () => {
    const url = editImageUrl.value.trim();
    if (!url) {
        clearEditObjectUrl();
        renderEditPreview(existingImageSrc);
        return;
    }

    if (editRemoveImage) {
        editRemoveImage.checked = false;
    }
    editImageFile.value = '';
    clearEditObjectUrl();
    renderEditPreview(url);
});

if (editRemoveImage) {
    editRemoveImage.addEventListener('change', () => {
        if (!editRemoveImage.checked) {
            clearEditObjectUrl();
            renderEditPreview(existingImageSrc);
            return;
        }

        editImageUrl.value = '';
        editImageFile.value = '';
        clearEditObjectUrl();
        renderEditPreview('');
    });
}

editForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const messageDiv = document.getElementById('form-message');

    const response = await fetch('<?php echo route('/admin/packages/update'); ?>', {
        method: 'POST',
        body: formData
    });

    const data = await response.json();

    if (data.success) {
        messageDiv.className = 'mb-4 p-4 rounded-lg bg-green-100 text-green-700';
        messageDiv.textContent = data.message;
        messageDiv.classList.remove('hidden');
        setTimeout(() => window.location.href = '<?php echo route('/admin/packages'); ?>', 1200);
    } else {
        messageDiv.className = 'mb-4 p-4 rounded-lg bg-red-100 text-red-700';
        messageDiv.textContent = data.error || 'Failed to update package';
        messageDiv.classList.remove('hidden');
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>

