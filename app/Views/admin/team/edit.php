<?php
ob_start();

$currentImage = $member['image'] ?? null;
$currentImageUrl = uploadedImageUrl($currentImage);
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Edit Team Member</h2>
        <a href="<?php echo route('/admin/team'); ?>" class="text-gray-600 hover:text-gray-900">&larr; Back</a>
    </div>

    <div>
        <div class="max-w-3xl bg-white rounded-lg luxury-shadow p-8">
            <form id="team-edit-form" method="POST" action="<?php echo route('/admin/team/update'); ?>" enctype="multipart/form-data" novalidate>
                <?php echo \App\Core\CSRF::hidden(); ?>
                <input type="hidden" name="id" value="<?php echo (int)$member['id']; ?>">

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Name</label>
                    <input type="text" name="name" required maxlength="150" value="<?php echo htmlspecialchars($member['name']); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    <small class="text-red-500 error-name block mt-1"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Role</label>
                    <input type="text" name="role" required maxlength="150" value="<?php echo htmlspecialchars($member['role']); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    <small class="text-red-500 error-role block mt-1"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Bio</label>
                    <textarea name="bio" rows="5" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600"><?php echo htmlspecialchars($member['bio']); ?></textarea>
                    <small class="text-red-500 error-bio block mt-1"></small>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Image URL</label>
                        <input type="url" id="edit-image-url" name="image_url" placeholder="https://example.com/team-member.jpg or /assets/images/founder-ceo.avif" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3">

                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload New Image</label>
                        <input type="file" id="edit-image-file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Provide either an image URL or a local file upload. Leave both empty to keep the current image.</p>
                        <small class="text-red-500 error-image block mt-1"></small>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Current Image</label>
                        <div id="edit-image-preview-wrap" class="<?php echo $currentImageUrl ? 'border-2 border-gray-200 rounded-lg overflow-hidden' : 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500'; ?>">
                            <?php if ($currentImageUrl): ?>
                                <img src="<?php echo htmlspecialchars($currentImageUrl); ?>" alt="Current team image" class="w-full h-36 object-cover">
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

                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Display Order</label>
                        <input type="number" name="display_order" value="<?php echo (int)($member['display_order'] ?? 0); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-3 text-sm font-semibold" style="color: #0F3D3E;">
                            <input type="checkbox" name="is_active" value="1" <?php echo ((int)($member['is_active'] ?? 0) === 1) ? 'checked' : ''; ?> class="w-4 h-4">
                            Visible on frontend
                        </label>
                    </div>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                    <button id="update-team-btn" type="submit" class="px-8 py-3 rounded-xl font-bold text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-4" style="background: linear-gradient(135deg, #0F3D3E 0%, #1F5E60 55%, #C8A951 100%); --tw-ring-color: rgba(200, 169, 81, 0.35); box-shadow: 0 12px 28px rgba(15, 61, 62, 0.28); letter-spacing: 0.03em;">
                        <i class="fas fa-save mr-2"></i>Update Team Member
                    </button>
                    <a href="<?php echo route('/admin/team'); ?>" class="px-6 py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-50 text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const form = document.getElementById('team-edit-form');
const submitButton = document.getElementById('update-team-btn');
const editImageUrl = document.getElementById('edit-image-url');
const editImageFile = document.getElementById('edit-image-file');
const editPreviewWrap = document.getElementById('edit-image-preview-wrap');
const editRemoveImage = document.getElementById('edit-remove-image');
const existingImageSrc = '<?php echo htmlspecialchars($currentImageUrl, ENT_QUOTES); ?>';
const allowedImageMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
const allowedImageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
const maxImageSizeBytes = 20 * 1024 * 1024;
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
    editPreviewWrap.innerHTML = '<img src="' + src + '" alt="Team preview" class="w-full h-36 object-cover">';
};

const clearFieldErrors = () => {
    document.querySelectorAll('.error-name, .error-role, .error-bio, .error-image').forEach((el) => {
        el.textContent = '';
    });
};

const showImageFieldError = (message) => {
    const target = document.querySelector('.error-image');
    if (target) {
        target.textContent = message;
    }
};

const validateImageFile = (file) => {
    if (!file) {
        return true;
    }

    if (file.size > maxImageSizeBytes) {
        showImageFieldError('Image must be 20MB or less.');
        showMessage('Image must be 20MB or less.');
        return false;
    }

    const mime = (file.type || '').toLowerCase();
    if (mime && allowedImageMimeTypes.includes(mime)) {
        return true;
    }

    const ext = ((file.name || '').split('.').pop() || '').toLowerCase();
    if (allowedImageExtensions.includes(ext)) {
        return true;
    }

    showImageFieldError('Allowed formats: JPG, PNG, WEBP, AVIF.');
    showMessage('Allowed formats: JPG, PNG, WEBP, AVIF.');
    return false;
};

editImageFile.addEventListener('change', () => {
    const file = editImageFile.files && editImageFile.files[0] ? editImageFile.files[0] : null;
    clearObjectUrl();
    showImageFieldError('');

    if (!file) {
        renderPreview(existingImageSrc);
        return;
    }

    if (!validateImageFile(file)) {
        editImageFile.value = '';
        renderPreview(existingImageSrc);
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

const showMessage = (message, success = false) => {
    const messageDiv = document.getElementById('form-message');
    messageDiv.className = success
        ? 'mb-4 p-4 rounded-lg bg-green-100 text-green-700'
        : 'mb-4 p-4 rounded-lg bg-red-100 text-red-700';
    messageDiv.textContent = message;
    messageDiv.classList.remove('hidden');
};

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearFieldErrors();

    const imageUrl = editImageUrl.value.trim();
    const hasFile = !!(editImageFile.files && editImageFile.files[0]);
    const file = hasFile ? editImageFile.files[0] : null;

    if (imageUrl && hasFile) {
        showMessage('Please provide either an image URL or an uploaded file, not both.');
        return;
    }

    if (hasFile && !validateImageFile(file)) {
        return;
    }

    submitButton.disabled = true;
    submitButton.classList.add('opacity-70', 'cursor-not-allowed');

    try {
        const response = await fetch('<?php echo route('/admin/team/update'); ?>', {
            method: 'POST',
            body: new FormData(form)
        });
        const data = await response.json();

        if (data.success) {
            showMessage(data.message, true);
            setTimeout(() => {
                window.location.href = '<?php echo route('/admin/team'); ?>';
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

        showMessage(data.error || 'Failed to update team member.');
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
