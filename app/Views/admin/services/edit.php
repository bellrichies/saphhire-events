<?php
ob_start();

$currentImage = $service['image'] ?? null;
$currentImageUrl = uploadedImageUrl($currentImage);
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Edit Service</h2>
        <a href="<?php echo route('/admin/services'); ?>" class="text-gray-600 hover:text-gray-900">&larr; Back</a>
    </div>

    <div>
        <div class="max-w-3xl bg-white rounded-lg luxury-shadow p-8">
            <form id="service-edit-form" method="POST" action="<?php echo route('/admin/services/update'); ?>" enctype="multipart/form-data" novalidate>
                <?php echo \App\Core\CSRF::hidden(); ?>
                <input type="hidden" name="id" value="<?php echo (int)$service['id']; ?>">

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Service Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($service['title']); ?>" required maxlength="255" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="e.g., Wedding Planning">
                    <small class="text-red-500 error-title block mt-1"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Description</label>
                    <textarea name="description" rows="6" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="Describe the service in detail..."><?php echo htmlspecialchars($service['description']); ?></textarea>
                    <small class="text-red-500 error-description block mt-1"></small>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Image URL</label>
                        <input type="url" id="edit-image-url" name="image_url" placeholder="https://example.com/service-image.jpg or /assets/images/service.jpg" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3">

                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload New Image</label>
                        <input type="file" id="edit-image-file" name="image" accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Provide either an image URL or a local file upload. Leave both empty to keep the current image. Max 20MB. Allowed: JPEG, PNG, WEBP, AVIF.</p>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Current Image</label>
                        <div id="edit-image-preview-wrap" class="<?php echo $currentImageUrl ? 'border-2 border-gray-200 rounded-lg overflow-hidden' : 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500'; ?>">
                            <?php if ($currentImageUrl): ?>
                                <img id="edit-image-preview" src="<?php echo htmlspecialchars($currentImageUrl); ?>" alt="Current service image" class="w-full h-36 object-cover">
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

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                    <button id="update-service-btn" type="submit" class="px-8 py-3 rounded-xl font-bold text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-4" style="background: linear-gradient(135deg, #0F3D3E 0%, #1F5E60 55%, #C8A951 100%); --tw-ring-color: rgba(200, 169, 81, 0.35); box-shadow: 0 12px 28px rgba(15, 61, 62, 0.28); letter-spacing: 0.03em;">
                        <i class="fas fa-save mr-2"></i>Update Service
                    </button>
                    <a href="<?php echo route('/admin/services'); ?>" class="px-6 py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-50 text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const form = document.getElementById('service-edit-form');
const editImageUrl = document.getElementById('edit-image-url');
const editImageFile = document.getElementById('edit-image-file');
const editPreviewWrap = document.getElementById('edit-image-preview-wrap');
const editRemoveImage = document.getElementById('edit-remove-image');
const submitButton = document.getElementById('update-service-btn');
const existingImageSrc = '<?php echo htmlspecialchars($currentImageUrl, ENT_QUOTES); ?>';
const MAX_IMAGE_SIZE = 20 * 1024 * 1024;
const allowedMimeTypes = new Set(['image/jpeg', 'image/png', 'image/webp', 'image/avif']);
const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp', '.avif'];
let objectUrl = null;

const clearObjectUrl = () => {
    if (objectUrl) {
        URL.revokeObjectURL(objectUrl);
        objectUrl = null;
    }
};

const renderPreview = (src) => {
    if (!src) {
        editPreviewWrap.className = 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500';
        editPreviewWrap.textContent = 'No image selected';
        return;
    }
    editPreviewWrap.className = 'border-2 border-gray-200 rounded-lg overflow-hidden';
    editPreviewWrap.innerHTML = '<img src="' + src + '" alt="Service preview" class="w-full h-36 object-cover">';
};

const clearFieldErrors = () => {
    document.querySelectorAll('.error-title, .error-description').forEach((el) => {
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

editImageFile.addEventListener('change', () => {
    const file = editImageFile.files && editImageFile.files[0] ? editImageFile.files[0] : null;
    clearObjectUrl();

    if (!file) {
        renderPreview(existingImageSrc);
        return;
    }

    const fileName = (file.name || '').toLowerCase();
    const hasAllowedExtension = allowedExtensions.some((ext) => fileName.endsWith(ext));
    const hasAllowedMime = allowedMimeTypes.has((file.type || '').toLowerCase());

    if (!hasAllowedExtension && !hasAllowedMime) {
        editImageFile.value = '';
        renderPreview(existingImageSrc);
        showMessage('Unsupported image format. Allowed: JPEG, PNG, WEBP, AVIF.');
        return;
    }

    if ((file.size || 0) > MAX_IMAGE_SIZE) {
        editImageFile.value = '';
        renderPreview(existingImageSrc);
        showMessage('Image exceeds max upload size of 20MB.');
        return;
    }

    if (editRemoveImage) {
        editRemoveImage.checked = false;
    }
    editImageUrl.value = '';
    objectUrl = URL.createObjectURL(file);
    renderPreview(objectUrl);
});

editImageUrl.addEventListener('input', () => {
    const url = editImageUrl.value.trim();

    if (!url) {
        clearObjectUrl();
        renderPreview(existingImageSrc);
        return;
    }

    if (editRemoveImage) {
        editRemoveImage.checked = false;
    }
    editImageFile.value = '';
    clearObjectUrl();
    renderPreview(url);
});

if (editRemoveImage) {
    editRemoveImage.addEventListener('change', () => {
        if (!editRemoveImage.checked) {
            clearObjectUrl();
            renderPreview(existingImageSrc);
            return;
        }

        editImageUrl.value = '';
        editImageFile.value = '';
        clearObjectUrl();
        renderPreview('');
    });
}

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    clearFieldErrors();

    const imageUrl = editImageUrl.value.trim();
    const selectedFile = editImageFile.files && editImageFile.files[0] ? editImageFile.files[0] : null;
    const hasFile = !!selectedFile;

    if (imageUrl && hasFile) {
        showMessage('Please provide either an image URL or an uploaded file, not both.');
        return;
    }

    if (selectedFile && (selectedFile.size || 0) > MAX_IMAGE_SIZE) {
        showMessage('Image exceeds max upload size of 20MB.');
        return;
    }

    submitButton.disabled = true;
    submitButton.classList.add('opacity-70', 'cursor-not-allowed');

    try {
        const response = await fetch('<?php echo route('/admin/services/update'); ?>', {
            method: 'POST',
            body: new FormData(form)
        });

        const rawResponse = response.clone();
        let data = null;
        try {
            data = await response.json();
        } catch (parseError) {
            const text = await rawResponse.text();
            showMessage(text || 'Server returned an unexpected response.');
            return;
        }
        if (data.success) {
            showMessage(data.message, true);
            setTimeout(() => {
                window.location.href = '<?php echo route('/admin/services'); ?>';
            }, 1200);
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

        showMessage(data.error || 'An error occurred while updating the service.');
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

