<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Add Gallery Item</h2>
        <a href="<?php echo route('/admin/gallery'); ?>" class="text-gray-600 hover:text-gray-900">&larr; Back</a>
    </div>

    <div>
        <div class="max-w-3xl bg-white rounded-lg luxury-shadow p-8">
            <form id="gallery-form" method="POST" action="<?php echo route('/admin/gallery'); ?>" enctype="multipart/form-data">
                <?php echo \App\Core\CSRF::hidden(); ?>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Title</label>
                    <input type="text" name="title" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                    <small class="text-red-500 error-title"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Category</label>
                    <select name="category_id" required class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600">
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="text-red-500 error-category_id"></small>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Description</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600"></textarea>
                    <small class="text-red-500 error-description"></small>
                </div>

                <div class="mb-6">
                    <label class="inline-flex items-center gap-3 text-sm font-semibold" style="color: #0F3D3E;">
                        <input type="checkbox" name="is_featured" value="1" class="w-4 h-4">
                        Set as Featured Item
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Featured items can appear in highlight sections across the website.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Media URL or YouTube Link</label>
                        <input type="url" id="create-media-url" name="media_url" placeholder="https://example.com/gallery-item.jpg or https://www.youtube.com/watch?v=KWPOAt0GhmM" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-yellow-600 mb-3">

                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Or Upload Media</label>
                        <input type="file" id="create-media-file" name="media" accept="image/*,video/mp4,video/webm,video/ogg,video/quicktime,.mov,.ogv" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg">
                        <p class="text-xs text-gray-500 mt-2">Supported formats: JPG, PNG, WEBP, AVIF, MP4, WEBM, OGV, MOV, and YouTube links. Selecting one input clears the other.</p>
                    </div>
                    <div>
                        <label class="block mb-2 font-semibold" style="color: #0F3D3E;">Preview</label>
                        <div id="create-image-preview-wrap" class="h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500">
                            No media selected
                        </div>
                    </div>
                </div>

                <div id="form-message" class="mb-4 p-4 rounded-lg hidden"></div>

                <div class="flex gap-4">
                    <button type="submit" class="px-6 py-2 rounded-lg font-semibold text-white shadow-md transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 focus:outline-none focus:ring-4" style="background: linear-gradient(135deg, #0F3D3E 0%, #1F5E60 100%); box-shadow: 0 10px 24px rgba(15, 61, 62, 0.25); --tw-ring-color: rgba(200, 169, 81, 0.35);">Create Item</button>
                    <a href="<?php echo route('/admin/gallery'); ?>" class="px-6 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const galleryForm = document.getElementById('gallery-form');
const createMediaUrl = document.getElementById('create-media-url');
const createMediaFile = document.getElementById('create-media-file');
const createPreviewWrap = document.getElementById('create-image-preview-wrap');
let createObjectUrl = null;

const getYoutubeEmbedUrl = (value) => {
    if (!value) return '';

    let url;
    try {
        url = new URL(value);
    } catch (error) {
        return '';
    }

    const host = url.hostname.replace(/^www\./, '').toLowerCase();
    let videoId = '';

    if (host === 'youtu.be') {
        videoId = url.pathname.replace(/^\/+/, '').split('/')[0] || '';
    } else if (host === 'youtube.com' || host === 'm.youtube.com') {
        if (url.pathname === '/watch') {
            videoId = url.searchParams.get('v') || '';
        } else if (url.pathname.startsWith('/shorts/')) {
            videoId = url.pathname.split('/')[2] || '';
        } else if (url.pathname.startsWith('/embed/')) {
            videoId = url.pathname.split('/')[2] || '';
        }
    }

    videoId = videoId.replace(/[^a-zA-Z0-9_-]/g, '');
    return videoId ? 'https://www.youtube.com/embed/' + videoId : '';
};

const clearCreateObjectUrl = () => {
    if (createObjectUrl) {
        URL.revokeObjectURL(createObjectUrl);
        createObjectUrl = null;
    }
};

const isVideoSource = (src) => {
    if (!src) return false;
    return /\.(mp4|webm|ogg|ogv|mov)(\?.*)?$/i.test(src);
};

const renderCreatePreview = (src, type = '') => {
    if (!src) {
        createPreviewWrap.className = 'h-36 border-2 border-dashed border-gray-300 rounded-lg overflow-hidden flex items-center justify-center text-sm text-gray-500';
        createPreviewWrap.textContent = 'No media selected';
        return;
    }

    createPreviewWrap.className = 'h-36 border-2 border-gray-200 rounded-lg overflow-hidden';
    const mediaType = type || (getYoutubeEmbedUrl(src) ? 'youtube' : (isVideoSource(src) ? 'video' : 'image'));
    if (mediaType === 'youtube') {
        const embedUrl = getYoutubeEmbedUrl(src);
        createPreviewWrap.innerHTML = '<iframe src="' + embedUrl + '" class="w-full h-full" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen title="YouTube preview"></iframe>';
    } else if (mediaType === 'video') {
        createPreviewWrap.innerHTML = '<video src="' + src + '" class="w-full h-full object-cover" muted playsinline controls></video>';
    } else {
        createPreviewWrap.innerHTML = '<img src="' + src + '" alt="Gallery preview" class="w-full h-full object-cover">';
    }
};

createMediaFile.addEventListener('change', () => {
    const file = createMediaFile.files && createMediaFile.files[0] ? createMediaFile.files[0] : null;
    if (!file) {
        clearCreateObjectUrl();
        renderCreatePreview('');
        return;
    }

    createMediaUrl.value = '';
    clearCreateObjectUrl();
    createObjectUrl = URL.createObjectURL(file);
    renderCreatePreview(createObjectUrl, (file.type || '').startsWith('video/') ? 'video' : 'image');
});

createMediaUrl.addEventListener('input', () => {
    const url = createMediaUrl.value.trim();
    if (!url) {
        clearCreateObjectUrl();
        renderCreatePreview('');
        return;
    }

    createMediaFile.value = '';
    clearCreateObjectUrl();
    renderCreatePreview(url, getYoutubeEmbedUrl(url) ? 'youtube' : '');
});

galleryForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const csrfInput = galleryForm.querySelector('input[name="_csrf_token"]');
    const formData = new FormData(e.target);
    if (csrfInput && csrfInput.value) {
        formData.set('_csrf_token', csrfInput.value);
    }
    const messageDiv = document.getElementById('form-message');

    try {
        const response = await fetch('<?php echo route('/admin/gallery'); ?>', {
            method: 'POST',
            body: formData
        });
        const raw = await response.text();
        let data = null;
        try {
            data = JSON.parse(raw);
        } catch (parseError) {
            data = {
                error: `Request failed with status ${response.status}`,
                details: raw ? raw.slice(0, 400) : 'Empty response body'
            };
        }

        if (!response.ok && !data.error && !data.errors) {
            data.error = `Request failed with status ${response.status}`;
        }

        if (data.success) {
            messageDiv.className = 'mb-4 p-4 rounded-lg bg-green-100 text-green-700';
            messageDiv.textContent = data.message;
            messageDiv.classList.remove('hidden');

            setTimeout(() => {
                window.location.href = '<?php echo route('/admin/gallery'); ?>';
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
        messageDiv.classList.remove('hidden');

        document.querySelectorAll('[class^="error-"]').forEach((el) => {
            el.textContent = '';
        });

        if (error.errors) {
            Object.keys(error.errors).forEach(field => {
                const errorSpan = document.querySelector(`.error-${field}`);
                if (errorSpan) {
                    errorSpan.textContent = error.errors[field];
                }
            });
            messageDiv.textContent = 'Please fix the highlighted fields.';
        } else {
            const parts = [error.error || 'An unexpected error occurred'];
            if (error.details) {
                parts.push('Details: ' + error.details);
            }
            if (error.error_id) {
                parts.push('Error ID: ' + error.error_id);
            }
            messageDiv.textContent = parts.join(' | ');
        }
    }
});
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>

