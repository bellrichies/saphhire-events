<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Edit Package Category</h2>
        <a href="<?php echo route('/admin/package-categories'); ?>" class="text-gray-600 hover:text-gray-900">&larr; Back</a>
    </div>

    <div>
        <div class="max-w-3xl bg-white rounded-lg luxury-shadow p-8">
            <form id="package-category-edit-form" method="POST" action="<?php echo route('/admin/package-categories/update'); ?>" novalidate>
                <?php echo \App\Core\CSRF::hidden(); ?>
                <input type="hidden" name="id" value="<?php echo (int)$category['id']; ?>">

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Category Name</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($category['name']); ?>" required maxlength="255" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="e.g., Proposal Packages">
                    <small class="text-red-500 error-name block mt-1"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Slug (Optional)</label>
                    <input type="text" name="slug" value="<?php echo htmlspecialchars($category['slug']); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="auto-generated if empty">
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="Category summary shown on the packages page."><?php echo htmlspecialchars($category['description'] ?? ''); ?></textarea>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Display Order</label>
                    <input type="number" name="display_order" value="<?php echo (int)($category['display_order'] ?? 0); ?>" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
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
const form = document.getElementById('package-category-edit-form');
const submitButton = document.getElementById('update-category-btn');

const clearFieldErrors = () => {
    document.querySelectorAll('.error-name').forEach((el) => {
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

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    clearFieldErrors();
    const formData = new FormData(form);
    const name = String(formData.get('name') || '').trim();
    if (!name) {
        const target = document.querySelector('.error-name');
        if (target) {
            target.textContent = 'Name is required';
        }
        showMessage('Please fill in the required fields.');
        return;
    }

    submitButton.disabled = true;
    submitButton.classList.add('opacity-70', 'cursor-not-allowed');

    try {
        const response = await fetch('<?php echo route('/admin/package-categories/update'); ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            showMessage(data.message, true);
            setTimeout(() => {
                window.location.href = '<?php echo route('/admin/package-categories'); ?>';
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

        showMessage(data.error || 'Failed to update category');
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

