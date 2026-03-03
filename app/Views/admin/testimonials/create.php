<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Add Testimonial</h2>
        <a href="<?php echo route('/admin/testimonials'); ?>" class="text-gray-600 hover:text-gray-900">← Back</a>
    </div>

    <div>
        <div class="max-w-2xl bg-white rounded-lg luxury-shadow p-8">
            <form id="testimonial-form" method="POST" action="<?php echo route('/admin/testimonials'); ?>" enctype="multipart/form-data">
                <?php echo \App\Core\CSRF::hidden(); ?>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Client Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="e.g., John Doe">
                    <small class="text-red-500 error-name"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Testimonial Content</label>
                    <textarea name="content" rows="6" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600" placeholder="What did the client love about your service?"></textarea>
                    <small class="text-red-500 error-content"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Client Image URL (optional)</label>
                    <input type="url" id="testimonial-image-url" name="image_url" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3" placeholder="https://example.com/client.jpg or /assets/uploads/media/image.jpg">

                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload Image (optional)</label>
                    <input type="file" id="testimonial-image-file" name="image" accept="image/*" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-2">Use either URL or upload. Leave both empty to keep initials-only display.</p>
                    <div id="testimonial-image-preview" class="mt-3 hidden border border-slate-200 rounded-lg overflow-hidden max-w-xs">
                        <img id="testimonial-image-preview-img" src="" alt="Client image preview" class="w-full h-40 object-cover">
                    </div>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex gap-4">
                    <button type="submit" class="btn-primary">Create Testimonial</button>
                    <a href="<?php echo route('/admin/testimonials'); ?>" class="px-6 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const testimonialImageUrl = document.getElementById('testimonial-image-url');
const testimonialImageFile = document.getElementById('testimonial-image-file');
const testimonialImagePreview = document.getElementById('testimonial-image-preview');
const testimonialImagePreviewImg = document.getElementById('testimonial-image-preview-img');
let testimonialObjectUrl = null;

const clearTestimonialObjectUrl = () => {
    if (testimonialObjectUrl) {
        URL.revokeObjectURL(testimonialObjectUrl);
        testimonialObjectUrl = null;
    }
};

const renderTestimonialPreview = (src) => {
    if (!src) {
        testimonialImagePreview.classList.add('hidden');
        testimonialImagePreviewImg.src = '';
        return;
    }

    testimonialImagePreviewImg.src = src;
    testimonialImagePreview.classList.remove('hidden');
};

testimonialImageFile.addEventListener('change', () => {
    const file = testimonialImageFile.files && testimonialImageFile.files[0] ? testimonialImageFile.files[0] : null;
    clearTestimonialObjectUrl();
    if (!file) {
        renderTestimonialPreview('');
        return;
    }

    testimonialImageUrl.value = '';
    testimonialObjectUrl = URL.createObjectURL(file);
    renderTestimonialPreview(testimonialObjectUrl);
});

testimonialImageUrl.addEventListener('input', () => {
    const url = testimonialImageUrl.value.trim();
    if (!url) {
        renderTestimonialPreview('');
        return;
    }

    testimonialImageFile.value = '';
    clearTestimonialObjectUrl();
    renderTestimonialPreview(url);
});

document.getElementById('testimonial-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const form = e.target;
    const csrfInput = form.querySelector('input[name="_csrf_token"]');
    const formData = new FormData(form);
    if (csrfInput && csrfInput.value) {
        formData.set('_csrf_token', csrfInput.value);
    }
    const messageDiv = document.getElementById('form-message');

    try {
        const response = await fetch('<?php echo route('/admin/testimonials'); ?>', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            messageDiv.className = 'mb-4 p-4 rounded-lg bg-green-100 text-green-700';
            messageDiv.textContent = data.message;
            
            setTimeout(() => {
                window.location.href = '<?php echo route('/admin/testimonials'); ?>';
            }, 1500);
        } else if (data.error === 'CSRF token invalid' && data.csrf_token) {
            if (csrfInput) {
                csrfInput.value = data.csrf_token;
            }
            messageDiv.className = 'mb-4 p-4 rounded-lg bg-yellow-100 text-yellow-800';
            messageDiv.textContent = 'Session token refreshed. Please submit again.';
            messageDiv.classList.remove('hidden');
        } else {
            throw data;
        }
    } catch (error) {
        messageDiv.className = 'mb-4 p-4 rounded-lg bg-red-100 text-red-700';
        
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

