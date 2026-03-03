<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Add Service</h2>
        <a href="<?php echo route('/admin/services'); ?>" class="text-gray-600 hover:text-gray-900">← Back</a>
    </div>

    <div>
        <div class="max-w-2xl bg-white rounded-lg luxury-shadow p-8">
            <form id="service-form" method="POST" action="<?php echo route('/admin/services'); ?>" enctype="multipart/form-data">
                <?php echo \App\Core\CSRF::hidden(); ?>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Service Title</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="e.g., Wedding Planning">
                    <small class="text-red-500 error-title"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Description</label>
                    <textarea name="description" rows="6" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="Describe the service in detail..."></textarea>
                    <small class="text-red-500 error-description"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Image URL</label>
                    <input type="url" id="image-url-input" name="image_url" placeholder="https://example.com/service-image.jpg or /assets/uploads/media/image.jpg" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3">

                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload Image</label>
                    <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg" id="image-input">
                    <small class="text-gray-500 block mt-2">Use either URL or upload. Max 20MB. Formats: JPEG, PNG, WebP, AVIF.</small>
                    <div id="image-preview" class="mt-4 hidden">
                        <img id="preview-img" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 8px; max-height: 250px;">
                    </div>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex gap-4">
                    <button type="submit" class="btn-primary">Create Service</button>
                    <a href="<?php echo route('/admin/services'); ?>" class="px-6 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Image preview
const imageInput = document.getElementById('image-input');
const imageUrlInput = document.getElementById('image-url-input');
const imagePreview = document.getElementById('image-preview');
const previewImg = document.getElementById('preview-img');

if (imageInput) {
    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            if (imageUrlInput) {
                imageUrlInput.value = '';
            }
            const reader = new FileReader();
            reader.onload = (event) => {
                previewImg.src = event.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.classList.add('hidden');
        }
    });
}

if (imageUrlInput) {
    imageUrlInput.addEventListener('input', () => {
        const url = imageUrlInput.value.trim();
        if (!url) {
            imagePreview.classList.add('hidden');
            previewImg.src = '';
            return;
        }

        if (imageInput) {
            imageInput.value = '';
        }
        previewImg.src = url;
        imagePreview.classList.remove('hidden');
    });
}

// Form submission
document.getElementById('service-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const messageDiv = document.getElementById('form-message');

    try {
        const response = await fetch('<?php echo route('/admin/services'); ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            messageDiv.className = 'mb-4 p-4 rounded-lg bg-green-100 text-green-700';
            messageDiv.textContent = data.message;
            messageDiv.classList.remove('hidden');
            
            setTimeout(() => {
                window.location.href = '<?php echo route('/admin/services'); ?>';
            }, 1500);
        } else {
            throw data;
        }
    } catch (error) {
        messageDiv.className = 'mb-4 p-4 rounded-lg bg-red-100 text-red-700';
        messageDiv.classList.remove('hidden');
        
        if (error.errors) {
            Object.keys(error.errors).forEach(field => {
                const errorSpan = document.querySelector(`.error-${field}`);
                if (errorSpan) {
                    errorSpan.textContent = error.errors[field];
                }
            });
        } else {
            messageDiv.textContent = error.error || 'An error occurred';
        }
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>

