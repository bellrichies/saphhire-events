<?php
ob_start();
?>

<div class="space-y-4">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold text-slate-800">Media Library</h2>
        <button type="button" id="media-upload-trigger" class="btn-primary">Upload Media</button>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between mb-4">
            <div class="flex gap-2">
                <button type="button" data-media-filter="" class="media-filter-btn px-3 py-1.5 rounded-lg text-sm border border-slate-300 bg-slate-900 text-white">All</button>
                <button type="button" data-media-filter="image" class="media-filter-btn px-3 py-1.5 rounded-lg text-sm border border-slate-300 bg-white text-slate-700">Images</button>
                <button type="button" data-media-filter="video" class="media-filter-btn px-3 py-1.5 rounded-lg text-sm border border-slate-300 bg-white text-slate-700">Videos</button>
                <button type="button" data-media-filter="file" class="media-filter-btn px-3 py-1.5 rounded-lg text-sm border border-slate-300 bg-white text-slate-700">Files</button>
            </div>
            <input id="media-search-input" type="search" placeholder="Search media..." class="w-full md:w-72 px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
        </div>

        <div id="media-library-grid" class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4"></div>
        <p id="media-library-empty" class="hidden text-sm text-slate-500 py-8 text-center">No media found.</p>

        <div class="flex items-center justify-between mt-5">
            <button type="button" id="media-prev-page" class="px-3 py-1.5 rounded-lg border border-slate-300 text-sm disabled:opacity-50">Previous</button>
            <span id="media-page-label" class="text-sm text-slate-600">Page 1</span>
            <button type="button" id="media-next-page" class="px-3 py-1.5 rounded-lg border border-slate-300 text-sm disabled:opacity-50">Next</button>
        </div>
    </div>
</div>

<script>
(() => {
    const grid = document.getElementById('media-library-grid');
    const empty = document.getElementById('media-library-empty');
    const searchInput = document.getElementById('media-search-input');
    const pageLabel = document.getElementById('media-page-label');
    const prevBtn = document.getElementById('media-prev-page');
    const nextBtn = document.getElementById('media-next-page');
    const uploadBtn = document.getElementById('media-upload-trigger');
    const filterButtons = Array.from(document.querySelectorAll('.media-filter-btn'));

    const state = {
        page: 1,
        totalPages: 1,
        type: '',
        search: '',
    };

    const formatSize = (bytes) => {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        if (bytes < 1024 * 1024 * 1024) return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        return (bytes / (1024 * 1024 * 1024)).toFixed(1) + ' GB';
    };

    const renderItem = (item) => {
        const safeUrl = item.public_url || '';
        let preview = '<div class="h-32 bg-slate-100 flex items-center justify-center text-slate-400 text-xs uppercase">File</div>';
        if (item.media_type === 'image') {
            preview = `<img src="${safeUrl}" alt="${item.original_name || 'Media'}" class="h-32 w-full object-cover">`;
        } else if (item.media_type === 'video') {
            preview = `<video src="${safeUrl}" class="h-32 w-full object-cover" muted playsinline></video>`;
        }

        const escapedName = (item.original_name || '').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return `
            <article class="rounded-lg border border-slate-200 overflow-hidden bg-white shadow-sm">
                ${preview}
                <div class="p-3 space-y-2">
                    <p class="text-xs font-medium text-slate-800 line-clamp-2" title="${escapedName}">${escapedName || 'Untitled'}</p>
                    <p class="text-[11px] text-slate-500">${item.media_type.toUpperCase()} · ${formatSize(item.size_bytes || 0)}</p>
                    <div class="flex items-center gap-2">
                        <button type="button" data-copy="${safeUrl}" class="px-2 py-1 text-xs rounded border border-slate-300 hover:bg-slate-50">Copy URL</button>
                        <button type="button" data-delete="${item.id}" class="px-2 py-1 text-xs rounded border border-red-200 text-red-600 hover:bg-red-50">Delete</button>
                    </div>
                </div>
            </article>
        `;
    };

    const listMedia = async () => {
        const params = new URLSearchParams({
            page: String(state.page),
            per_page: '24',
        });
        if (state.type) params.set('type', state.type);
        if (state.search) params.set('search', state.search);

        const response = await fetch(`<?php echo route('/admin/media/list'); ?>?${params.toString()}`);
        const data = await response.json();

        const items = Array.isArray(data.items) ? data.items : [];
        state.totalPages = Number(data.pagination?.total_pages || 1);

        grid.innerHTML = items.map(renderItem).join('');
        empty.classList.toggle('hidden', items.length > 0);
        pageLabel.textContent = `Page ${state.page} of ${state.totalPages}`;
        prevBtn.disabled = state.page <= 1;
        nextBtn.disabled = state.page >= state.totalPages;
    };

    const debouncedSearch = (() => {
        let timer = null;
        return () => {
            if (timer) clearTimeout(timer);
            timer = setTimeout(() => {
                state.search = searchInput.value.trim();
                state.page = 1;
                listMedia().catch(() => {});
            }, 250);
        };
    })();

    grid.addEventListener('click', async (event) => {
        const copyBtn = event.target.closest('[data-copy]');
        const deleteBtn = event.target.closest('[data-delete]');

        if (copyBtn) {
            const value = copyBtn.getAttribute('data-copy') || '';
            if (value) {
                await navigator.clipboard.writeText(value);
                copyBtn.textContent = 'Copied';
                setTimeout(() => {
                    copyBtn.textContent = 'Copy URL';
                }, 900);
            }
        }

        if (deleteBtn) {
            const id = deleteBtn.getAttribute('data-delete');
            const confirmed = await window.AdminToastConfirm.show({
                title: 'Delete Media',
                message: 'Delete this media item? This action cannot be undone.',
                confirmText: 'Delete',
            });
            if (!confirmed) {
                return;
            }

            const fd = new FormData();
            fd.set('id', id || '');
            fd.set('_csrf_token', document.querySelector('meta[name="admin-csrf-token"]')?.content || '');

            const response = await fetch('<?php echo route('/admin/media/delete'); ?>', {
                method: 'POST',
                body: fd,
            });
            const data = await response.json();
            if (data.csrf_token) {
                const tokenMeta = document.querySelector('meta[name="admin-csrf-token"]');
                if (tokenMeta) tokenMeta.content = data.csrf_token;
            }

            if (!response.ok && data.error) {
                alert(data.error);
                return;
            }

            await listMedia();
        }
    });

    filterButtons.forEach((button) => {
        button.addEventListener('click', () => {
            filterButtons.forEach((el) => {
                el.classList.remove('bg-slate-900', 'text-white');
                el.classList.add('bg-white', 'text-slate-700');
            });
            button.classList.add('bg-slate-900', 'text-white');
            button.classList.remove('bg-white', 'text-slate-700');

            state.type = button.getAttribute('data-media-filter') || '';
            state.page = 1;
            listMedia().catch(() => {});
        });
    });

    searchInput.addEventListener('input', debouncedSearch);
    prevBtn.addEventListener('click', () => {
        if (state.page > 1) {
            state.page -= 1;
            listMedia().catch(() => {});
        }
    });
    nextBtn.addEventListener('click', () => {
        if (state.page < state.totalPages) {
            state.page += 1;
            listMedia().catch(() => {});
        }
    });

    uploadBtn.addEventListener('click', () => {
        if (window.AdminMediaPicker) {
            window.AdminMediaPicker.open({
                allowSelection: false,
                allowedTypes: ['image', 'video', 'file'],
                onClosed: () => listMedia().catch(() => {}),
            });
        }
    });

    listMedia().catch(() => {
        empty.classList.remove('hidden');
        empty.textContent = 'Unable to load media library.';
    });
})();
</script>

<?php
$content = ob_get_clean();
require VIEW_PATH . '/layouts/admin.php';
?>

