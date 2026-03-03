<?php
ob_start();
$currentImage = $testimonial['image'] ?? null;
$currentImageUrl = uploadedImageUrl($currentImage);
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Edit Testimonial</h2>
        <a href="<?php echo route('/admin/testimonials'); ?>" class="text-gray-600 hover:text-gray-900">&larr; Back</a>
    </div>

    <div>
        <div class="max-w-2xl bg-white rounded-lg luxury-shadow p-8">
            <form id="testimonial-edit-form" method="POST" action="<?php echo route('/admin/testimonials/update'); ?>" enctype="multipart/form-data" novalidate>
                <?php echo \App\Core\CSRF::hidden(); ?>
                <input type="hidden" name="id" value="<?php echo (int)$testimonial['id']; ?>">

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Client Name</label>
                    <input type="text" name="name" required maxlength="150" value="<?php echo htmlspecialchars($testimonial['name']); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="e.g., John Doe">
                    <small class="text-red-500 error-name block mt-1"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Testimonial Content</label>
                    <textarea name="content" rows="6" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="What did the client love about your service?"><?php echo htmlspecialchars($testimonial['content']); ?></textarea>
                    <small class="text-red-500 error-content block mt-1"></small>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Client Image URL (optional)</label>
                        <input type="url" id="edit-image-url" name="image_url" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3" placeholder="https://example.com/client.jpg or /assets/uploads/media/image.jpg">

                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload New Image</label>
                        <input type="file" id="edit-image-file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Use either URL or upload. Leave both empty to keep current image.</p>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Current Image</label>
                        <div id="edit-image-preview-wrap" class="<?php echo $currentImageUrl ? 'border-2 border-gray-200 rounded-lg overflow-hidden' : 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500'; ?>">
                            <?php if ($currentImageUrl): ?>
                                <img src="<?php echo htmlspecialchars($currentImageUrl); ?>" alt="Current testimonial image" class="w-full h-36 object-cover">
                            <?php else: ?>
                                <span>No image set</span>
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
                    <button id="update-testimonial-btn" type="submit" class="px-8 py-3 rounded-xl font-bold text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-4" style="background: linear-gradient(135deg, #0F3D3E 0%, #1F5E60 55%, #C8A951 100%); --tw-ring-color: rgba(200, 169, 81, 0.35); box-shadow: 0 12px 28px rgba(15, 61, 62, 0.28); letter-spacing: 0.03em;">
                        <i class="fas fa-save mr-2"></i>Update Testimonial
                    </button>
                    <a href="<?php echo route('/admin/testimonials'); ?>" class="px-6 py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-50 text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const form = document.getElementById('testimonial-edit-form');
const submitButton = document.getElementById('update-testimonial-btn');
const messageBox = document.getElementById('form-message');
const csrfInput = form.querySelector('input[name="_csrf_token"]');
const editImageUrl = document.getElementById('edit-image-url');
const editImageFile = document.getElementById('edit-image-file');
const editPreviewWrap = document.getElementById('edit-image-preview-wrap');
const editRemoveImage = document.getElementById('edit-remove-image');
const existingImageSrc = '<?php echo htmlspecialchars($currentImageUrl, ENT_QUOTES); ?>';
let testimonialObjectUrl = null;

const clearObjectUrl = () => {
    if (testimonialObjectUrl) {
        URL.revokeObjectURL(testimonialObjectUrl);
        testimonialObjectUrl = null;
    }
};

const renderPreview = (src) => {
    if (!src) {
        editPreviewWrap.className = 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500';
        editPreviewWrap.textContent = 'No image selected';
        return;
    }

    editPreviewWrap.className = 'border-2 border-gray-200 rounded-lg overflow-hidden';
    editPreviewWrap.innerHTML = '<img src="' + src + '" alt="Testimonial preview" class="w-full h-36 object-cover">';
};

const clearFieldErrors = () => {
    document.querySelectorAll('.error-name, .error-content').forEach((el) => {
        el.textContent = '';
    });
};

const showMessage = (message, success = false) => {
    messageBox.className = success
        ? 'mb-4 p-4 rounded-lg bg-green-100 text-green-700'
        : 'mb-4 p-4 rounded-lg bg-red-100 text-red-700';
    messageBox.textContent = message;
    messageBox.classList.remove('hidden');
};

editImageFile.addEventListener('change', () => {
    const file = editImageFile.files && editImageFile.files[0] ? editImageFile.files[0] : null;
    clearObjectUrl();

    if (!file) {
        renderPreview(existingImageSrc);
        return;
    }

    if (editRemoveImage) {
        editRemoveImage.checked = false;
    }
    editImageUrl.value = '';
    testimonialObjectUrl = URL.createObjectURL(file);
    renderPreview(testimonialObjectUrl);
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

    submitButton.disabled = true;
    submitButton.classList.add('opacity-70', 'cursor-not-allowed');

    try {
        const formData = new FormData(form);
        if (csrfInput && csrfInput.value) {
            formData.set('_csrf_token', csrfInput.value);
        }

        const response = await fetch('<?php echo route('/admin/testimonials/update'); ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showMessage(data.message, true);
            setTimeout(() => {
                window.location.href = '<?php echo route('/admin/testimonials'); ?>';
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

        showMessage(data.error || 'Failed to update testimonial.');
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

