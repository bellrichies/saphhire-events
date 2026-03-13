<?php
ob_start();

$currentImage = $category['image'] ?? null;
$currentImageUrl = uploadedImageUrl($currentImage);
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Edit Package Category</h2>
        <a href="<?php echo route('/admin/package-categories'); ?>" class="text-gray-600 hover:text-gray-900">&larr; Back</a>
    </div>

    <div>
        <div class="max-w-4xl bg-white rounded-lg luxury-shadow p-8">
            <form id="package-category-edit-form" method="POST" action="<?php echo route('/admin/package-categories/update'); ?>" enctype="multipart/form-data" novalidate>
                <?php echo \App\Core\CSRF::hidden(); ?>
                <input type="hidden" name="id" value="<?php echo (int)$category['id']; ?>">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="lg:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Category Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required maxlength="255" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="e.g., Proposal Packages">
                        <small class="text-red-500 error-name block mt-1"></small>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Display Order</label>
                        <input type="number" name="display_order" value="<?php echo (int)($category['display_order'] ?? 0); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Slug (Optional)</label>
                    <input type="text" name="slug" value="<?php echo htmlspecialchars($category['slug']); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="auto-generated if empty">
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="Category summary shown on the packages page."><?php echo htmlspecialchars($category['description'] ?? ''); ?></textarea>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="lg:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Package Image URL</label>
                        <input type="text" id="edit-category-image-url" name="image_url" placeholder="https://example.com/category-image.jpg or /assets/uploads/media/..." spellcheck="false" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3">

                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload New Image</label>
                        <input type="file" id="edit-category-image-file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Use the media library/image URL, upload a replacement, or remove the current image.</p>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Current Image</label>
                        <div id="edit-category-image-preview-wrap" class="<?php echo $currentImageUrl ? 'h-44 border-2 border-gray-200 rounded-lg overflow-hidden bg-slate-50' : 'h-44 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500 bg-slate-50'; ?>">
                            <?php if ($currentImageUrl): ?>
                                <img src="<?php echo htmlspecialchars($currentImageUrl); ?>" alt="Current category image" class="w-full h-full object-cover">
                            <?php else: ?>
                                No image set
                            <?php endif; ?>
                        </div>
                        <label class="inline-flex items-center gap-2 text-sm mt-3">
                            <input type="checkbox" id="edit-remove-category-image" name="remove_image" value="1" class="w-4 h-4">
                            Remove current image
                        </label>
                    </div>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex flex-col sm:flex-row gap-4 items-stretch sm:items-center">
                    <button id="update-category-btn" type="submit" class="px-8 py-3 rounded-xl font-bold text-white shadow-lg transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 focus:outline-none focus:ring-4" style="background: linear-gradient(135deg, #0F3D3E 0%, #1F5E60 55%, #C8A951 100%); --tw-ring-color: rgba(200, 169, 81, 0.35); box-shadow: 0 12px 28px rgba(15, 61, 62, 0.28); letter-spacing: 0.03em;">
                        <i class="fas fa-save mr-2"></i>Update Category
                    </button>
                    <a href="<?php echo route('/admin/package-categories'); ?>" class="px-6 py-3 border-2 border-gray-300 rounded-lg hover:bg-gray-50 text-center">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const categoryEditForm = document.getElementById('package-category-edit-form');
const updateCategoryButton = document.getElementById('update-category-btn');
const editCategoryImageUrl = document.getElementById('edit-category-image-url');
const editCategoryImageFile = document.getElementById('edit-category-image-file');
const editCategoryImagePreviewWrap = document.getElementById('edit-category-image-preview-wrap');
const editRemoveCategoryImage = document.getElementById('edit-remove-category-image');
const existingCategoryImageSrc = '<?php echo htmlspecialchars($currentImageUrl, ENT_QUOTES); ?>';
let editCategoryObjectUrl = null;

const clearCategoryFieldErrors = () => {
    document.querySelectorAll('.error-name').forEach((el) => {
        el.textContent = '';
    });
};

const showCategoryMessage = (message, success = false) => {
    const messageDiv = document.getElementById('form-message');
    messageDiv.className = success
        ? 'mb-4 p-4 rounded-lg bg-green-100 text-green-700'
        : 'mb-4 p-4 rounded-lg bg-red-100 text-red-700';
    messageDiv.textContent = message;
    messageDiv.classList.remove('hidden');
};

const clearEditCategoryObjectUrl = () => {
    if (editCategoryObjectUrl) {
        URL.revokeObjectURL(editCategoryObjectUrl);
        editCategoryObjectUrl = null;
    }
};

const renderEditCategoryPreview = (src) => {
    if (!src) {
        editCategoryImagePreviewWrap.className = 'h-44 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500 bg-slate-50';
        editCategoryImagePreviewWrap.textContent = 'No image selected';
        return;
    }

    editCategoryImagePreviewWrap.className = 'h-44 border-2 border-gray-200 rounded-lg overflow-hidden bg-slate-50';
    editCategoryImagePreviewWrap.innerHTML = '<img src="' + src + '" alt="Category preview" class="w-full h-full object-cover">';
};

editCategoryImageFile.addEventListener('change', () => {
    const file = editCategoryImageFile.files && editCategoryImageFile.files[0] ? editCategoryImageFile.files[0] : null;
    if (!file) {
        clearEditCategoryObjectUrl();
        renderEditCategoryPreview(existingCategoryImageSrc);
        return;
    }

    editCategoryImageUrl.value = '';
    editRemoveCategoryImage.checked = false;
    clearEditCategoryObjectUrl();
    editCategoryObjectUrl = URL.createObjectURL(file);
    renderEditCategoryPreview(editCategoryObjectUrl);
});

editCategoryImageUrl.addEventListener('input', () => {
    const url = editCategoryImageUrl.value.trim();
    if (!url) {
        clearEditCategoryObjectUrl();
        renderEditCategoryPreview(existingCategoryImageSrc);
        return;
    }

    editCategoryImageFile.value = '';
    editRemoveCategoryImage.checked = false;
    clearEditCategoryObjectUrl();
    renderEditCategoryPreview(url);
});

editRemoveCategoryImage.addEventListener('change', () => {
    if (!editRemoveCategoryImage.checked) {
        clearEditCategoryObjectUrl();
        renderEditCategoryPreview(existingCategoryImageSrc);
        return;
    }

    editCategoryImageUrl.value = '';
    editCategoryImageFile.value = '';
    clearEditCategoryObjectUrl();
    renderEditCategoryPreview('');
});

categoryEditForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    clearCategoryFieldErrors();
    const formData = new FormData(categoryEditForm);
    const name = String(formData.get('name') || '').trim();
    if (!name) {
        const target = document.querySelector('.error-name');
        if (target) {
            target.textContent = 'Name is required';
        }
        showCategoryMessage('Please fill in the required fields.');
        return;
    }

    updateCategoryButton.disabled = true;
    updateCategoryButton.classList.add('opacity-70', 'cursor-not-allowed');

    try {
        const response = await fetch('<?php echo route('/admin/package-categories/update'); ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showCategoryMessage(data.message, true);
            setTimeout(() => {
                window.location.href = '<?php echo route('/admin/package-categories'); ?>';
            }, 1200);
            return;
        }

        showCategoryMessage(data.error || 'Failed to update category');
    } catch (error) {
        showCategoryMessage('A network error occurred. Please try again.');
    } finally {
        updateCategoryButton.disabled = false;
        updateCategoryButton.classList.remove('opacity-70', 'cursor-not-allowed');
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
