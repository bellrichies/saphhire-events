<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Add Category</h2>
        <a href="<?php echo route('/admin/categories'); ?>" class="text-gray-600 hover:text-gray-900">← Back</a>
    </div>

    <div>
        <div class="max-w-2xl bg-white rounded-lg luxury-shadow p-8">
            <form id="category-form" method="POST" action="<?php echo route('/admin/categories'); ?>">
                <?php echo \App\Core\CSRF::hidden(); ?>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Category Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="e.g., Weddings">
                    <small class="text-gray-500">Slug will be auto-generated</small>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex gap-4">
                    <button type="submit" class="btn-primary">Create Category</button>
                    <a href="<?php echo route('/admin/categories'); ?>" class="px-6 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('category-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const messageDiv = document.getElementById('form-message');

    try {
        const response = await fetch('<?php echo route('/admin/categories'); ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            messageDiv.className = 'mb-4 p-4 rounded-lg bg-green-100 text-green-700';
            messageDiv.textContent = data.message;
            
            setTimeout(() => {
                window.location.href = '<?php echo route('/admin/categories'); ?>';
            }, 1500);
        } else {
            throw data;
        }
    } catch (error) {
        messageDiv.className = 'mb-4 p-4 rounded-lg bg-red-100 text-red-700';
        messageDiv.textContent = error.error || 'An error occurred';
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>

