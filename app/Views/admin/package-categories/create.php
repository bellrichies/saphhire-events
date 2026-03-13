<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Add Package Category</h2>
        <a href="<?php echo route('/admin/package-categories'); ?>" class="text-gray-600 hover:text-gray-900">&larr; Back</a>
    </div>

    <div>
        <div class="max-w-4xl bg-white rounded-lg luxury-shadow p-8">
            <form id="package-category-form" method="POST" action="<?php echo route('/admin/package-categories'); ?>" enctype="multipart/form-data">
                <?php echo \App\Core\CSRF::hidden(); ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="lg:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Category Name</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="e.g., Proposal Packages">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Display Order</label>
                        <input type="number" name="display_order" value="0" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Slug (Optional)</label>
                    <input type="text" name="slug" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="auto-generated if empty">
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="Category summary shown on the packages page."></textarea>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div class="lg:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Package Image URL</label>
                        <input type="text" id="create-category-image-url" name="image_url" placeholder="https://example.com/category-image.jpg or /assets/uploads/media/..." spellcheck="false" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3">

                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload Image</label>
                        <input type="file" id="create-category-image-file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Use either the media library/image URL or a direct upload. Selecting one clears the other.</p>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Preview</label>
                        <div id="create-category-image-preview-wrap" class="h-44 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500 bg-slate-50">
                            No image selected
                        </div>
                    </div>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex gap-4">
                    <button type="submit" class="btn-primary">Create Category</button>
                    <a href="<?php echo route('/admin/package-categories'); ?>" class="px-6 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const createCategoryForm = document.getElementById('package-category-form');
const createCategoryImageUrl = document.getElementById('create-category-image-url');
const createCategoryImageFile = document.getElementById('create-category-image-file');
const createCategoryImagePreviewWrap = document.getElementById('create-category-image-preview-wrap');
let createCategoryObjectUrl = null;

const clearCreateCategoryObjectUrl = () => {
    if (createCategoryObjectUrl) {
        URL.revokeObjectURL(createCategoryObjectUrl);
        createCategoryObjectUrl = null;
    }
};

const renderCreateCategoryPreview = (src) => {
    if (!src) {
        createCategoryImagePreviewWrap.className = 'h-44 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500 bg-slate-50';
        createCategoryImagePreviewWrap.textContent = 'No image selected';
        return;
    }

    createCategoryImagePreviewWrap.className = 'h-44 border-2 border-gray-200 rounded-lg overflow-hidden bg-slate-50';
    createCategoryImagePreviewWrap.innerHTML = '<img src="' + src + '" alt="Category preview" class="w-full h-full object-cover">';
};

createCategoryImageFile.addEventListener('change', () => {
    const file = createCategoryImageFile.files && createCategoryImageFile.files[0] ? createCategoryImageFile.files[0] : null;
    if (!file) {
        clearCreateCategoryObjectUrl();
        renderCreateCategoryPreview('');
        return;
    }

    createCategoryImageUrl.value = '';
    clearCreateCategoryObjectUrl();
    createCategoryObjectUrl = URL.createObjectURL(file);
    renderCreateCategoryPreview(createCategoryObjectUrl);
});

createCategoryImageUrl.addEventListener('input', () => {
    const url = createCategoryImageUrl.value.trim();
    if (!url) {
        clearCreateCategoryObjectUrl();
        renderCreateCategoryPreview('');
        return;
    }

    createCategoryImageFile.value = '';
    clearCreateCategoryObjectUrl();
    renderCreateCategoryPreview(url);
});

createCategoryForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(createCategoryForm);
    const messageDiv = document.getElementById('form-message');

    const response = await fetch('<?php echo route('/admin/package-categories'); ?>', {
        method: 'POST',
        body: formData
    });

    const data = await response.json();

    if (data.success) {
        messageDiv.className = 'mb-4 p-4 rounded-lg bg-green-100 text-green-700';
        messageDiv.textContent = data.message;
        messageDiv.classList.remove('hidden');
        setTimeout(() => window.location.href = '<?php echo route('/admin/package-categories'); ?>', 1200);
    } else {
        messageDiv.className = 'mb-4 p-4 rounded-lg bg-red-100 text-red-700';
        messageDiv.textContent = data.error || 'Failed to create category';
        messageDiv.classList.remove('hidden');
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>
