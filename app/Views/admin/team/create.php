<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Add Team Member</h2>
        <a href="<?php echo route('/admin/team'); ?>" class="text-gray-600 hover:text-gray-900">&larr; Back</a>
    </div>

    <div>
        <div class="max-w-3xl bg-white rounded-lg luxury-shadow p-8">
            <form id="team-create-form" method="POST" action="<?php echo route('/admin/team'); ?>" enctype="multipart/form-data" novalidate>
                <?php echo \App\Core\CSRF::hidden(); ?>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Name</label>
                    <input type="text" name="name" required maxlength="150" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="e.g., Kristina">
                    <small class="text-red-500 error-name block mt-1"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Role</label>
                    <input type="text" name="role" required maxlength="150" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="e.g., Founder & Director">
                    <small class="text-red-500 error-role block mt-1"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Bio</label>
                    <textarea name="bio" rows="5" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="Brief profile and responsibilities..."></textarea>
                    <small class="text-red-500 error-bio block mt-1"></small>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Image URL</label>
                        <input type="url" id="create-image-url" name="image_url" placeholder="https://example.com/team-member.jpg or /assets/images/founder-ceo.avif" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3">

                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload Image</label>
                        <input type="file" id="create-image-file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Provide either an image URL or a local file upload. Selecting one will clear the other.</p>
                        <small class="text-red-500 error-image block mt-1"></small>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Preview</label>
                        <div id="create-image-preview-wrap" class="h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500">
                            No image selected
                        </div>
                    </div>
                </div>

                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Display Order</label>
                        <input type="number" name="display_order" value="0" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-3 text-sm font-semibold" style="color: #0F3D3E;">
                            <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4">
                            Visible on frontend
                        </label>
                    </div>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                    <button id="create-team-btn" type="submit" class="px-8 py-3 rounded-xl font-bold text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-4" style="background: linear-gradient(135deg, #0F3D3E 0%, #1F5E60 55%, #C8A951 100%); --tw-ring-color: rgba(200, 169, 81, 0.35); box-shadow: 0 12px 28px rgba(15, 61, 62, 0.28); letter-spacing: 0.03em;">
                        <i class="fas fa-save mr-2"></i>Create Team Member
                    </button>
                    <a href="<?php echo route('/admin/team'); ?>" class="px-6 py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-50 text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const form = document.getElementById('team-create-form');
const submitButton = document.getElementById('create-team-btn');
const createImageUrl = document.getElementById('create-image-url');
const createImageFile = document.getElementById('create-image-file');
const createPreviewWrap = document.getElementById('create-image-preview-wrap');
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
        createPreviewWrap.className = 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500';
        createPreviewWrap.textContent = 'No image selected';
        return;
    }

    createPreviewWrap.className = 'h-36 border-2 border-gray-200 rounded-lg overflow-hidden';
    createPreviewWrap.innerHTML = '<img src="' + src + '" alt="Team preview" class="w-full h-full object-cover">';
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

createImageFile.addEventListener('change', () => {
    const file = createImageFile.files && createImageFile.files[0] ? createImageFile.files[0] : null;
    clearObjectUrl();
    showImageFieldError('');

    if (!file) {
        renderPreview('');
        return;
    }

    if (!validateImageFile(file)) {
        createImageFile.value = '';
        renderPreview('');
        return;
    }

    createImageUrl.value = '';
    objectUrl = URL.createObjectURL(file);
    renderPreview(objectUrl);
});

createImageUrl.addEventListener('input', () => {
    const url = createImageUrl.value.trim();
    if (!url) {
        clearObjectUrl();
        renderPreview('');
        return;
    }

    createImageFile.value = '';
    clearObjectUrl();
    renderPreview(url);
});

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

    const imageUrl = createImageUrl.value.trim();
    const hasFile = !!(createImageFile.files && createImageFile.files[0]);
    const file = hasFile ? createImageFile.files[0] : null;

    if (imageUrl && hasFile) {
        showMessage('Please provide either an image URL or an uploaded file, not both.');
        return;
    }

    if (hasFile && !validateImageFile(file)) {
        return;
    }

    if (!imageUrl && !hasFile) {
        const target = document.querySelector('.error-image');
        if (target) {
            target.textContent = 'Image is required';
        }
        showMessage('Please provide an image URL or upload an image.');
        return;
    }

    submitButton.disabled = true;
    submitButton.classList.add('opacity-70', 'cursor-not-allowed');

    try {
        const response = await fetch('<?php echo route('/admin/team'); ?>', {
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

        showMessage(data.error || 'Failed to create team member.');
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
