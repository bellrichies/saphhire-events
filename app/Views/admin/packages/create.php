<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Add Package</h2>
        <a href="<?php echo route('/admin/packages'); ?>" class="text-gray-600 hover:text-gray-900">? Back</a>
    </div>

    <div>
        <div class="max-w-3xl bg-white rounded-lg luxury-shadow p-8">
            <form id="package-form" method="POST" action="<?php echo route('/admin/packages'); ?>" enctype="multipart/form-data">
                <?php echo \App\Core\CSRF::hidden(); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Package Title</label>
                        <input type="text" name="title" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Category</label>
                        <select name="category_id" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                            <option value="">Select category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo (int)$cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Description</label>
                    <textarea name="description" rows="4" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Features (one per line)</label>
                    <textarea name="features" rows="5" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="Includes basic setup&#10;Candles and rose petals&#10;30-minute styling session"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Price Label</label>
                        <input type="text" name="price_label" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="EUR 125 | starting from 8 guests">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Numeric Price (optional)</label>
                        <input type="number" step="0.01" min="0" name="price_amount" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="125">
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Currency</label>
                        <input type="text" name="currency" value="EUR" maxlength="10" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Image URL</label>
                        <input type="url" id="create-image-url" name="image_url" placeholder="https://example.com/package-image.jpg or /assets/images/package.jpg" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3">

                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload Image</label>
                        <input type="file" id="create-image-file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Use either URL or upload. Selecting one will clear the other.</p>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Preview</label>
                        <div id="create-image-preview-wrap" class="h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500">
                            No image selected
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Display Order</label>
                        <input type="number" name="display_order" value="0" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    </div>
                    <div class="flex items-center pt-8">
                        <label class="inline-flex items-center gap-2 text-sm">
                            <input type="checkbox" name="is_featured" value="1" class="w-4 h-4">
                            Mark as featured
                        </label>
                    </div>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex gap-4">
                    <button type="submit" class="btn-primary">Create Package</button>
                    <a href="<?php echo route('/admin/packages'); ?>" class="px-6 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const createForm = document.getElementById('package-form');
const createImageUrl = document.getElementById('create-image-url');
const createImageFile = document.getElementById('create-image-file');
const createPreviewWrap = document.getElementById('create-image-preview-wrap');
let createObjectUrl = null;

const clearCreateObjectUrl = () => {
    if (createObjectUrl) {
        URL.revokeObjectURL(createObjectUrl);
        createObjectUrl = null;
    }
};

const renderCreatePreview = (src) => {
    if (!src) {
        createPreviewWrap.className = 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500';
        createPreviewWrap.textContent = 'No image selected';
        return;
    }

    createPreviewWrap.className = 'h-36 border-2 border-gray-200 rounded-lg overflow-hidden';
    createPreviewWrap.innerHTML = '<img src="' + src + '" alt="Package preview" class="w-full h-full object-cover">';
};

createImageFile.addEventListener('change', () => {
    const file = createImageFile.files && createImageFile.files[0] ? createImageFile.files[0] : null;
    if (!file) {
        clearCreateObjectUrl();
        renderCreatePreview('');
        return;
    }

    createImageUrl.value = '';
    clearCreateObjectUrl();
    createObjectUrl = URL.createObjectURL(file);
    renderCreatePreview(createObjectUrl);
});

createImageUrl.addEventListener('input', () => {
    const url = createImageUrl.value.trim();
    if (!url) {
        clearCreateObjectUrl();
        renderCreatePreview('');
        return;
    }

    createImageFile.value = '';
    clearCreateObjectUrl();
    renderCreatePreview(url);
});

createForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const messageDiv = document.getElementById('form-message');

    const response = await fetch('<?php echo route('/admin/packages'); ?>', {
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
        messageDiv.textContent = data.error || 'Failed to create package';
        messageDiv.classList.remove('hidden');
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>

