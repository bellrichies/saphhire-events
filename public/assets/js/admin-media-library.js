(function () {
    const API = window.AdminMediaConfig || {
        list: '/admin/media/list',
        upload: '/admin/media/upload',
        delete: '/admin/media/delete',
    };

    const formatSize = (bytes) => {
        if (!Number.isFinite(bytes) || bytes < 0) return '0 B';
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        if (bytes < 1024 * 1024 * 1024) return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        return (bytes / (1024 * 1024 * 1024)).toFixed(1) + ' GB';
    };

    const getTokenMeta = () => document.querySelector('meta[name="admin-csrf-token"]');
    const getCsrfToken = () => getTokenMeta()?.content || '';
    const setCsrfToken = (value) => {
        const tokenMeta = getTokenMeta();
        if (tokenMeta && value) {
            tokenMeta.content = value;
        }
    };

    class MediaPicker {
        constructor() {
            this.state = {
                page: 1,
                totalPages: 1,
                type: '',
                search: '',
                allowedTypes: ['image', 'video', 'file'],
                targetInput: null,
                allowSelection: true,
                onClosed: null,
            };
            this.modal = null;
            this.searchDebounce = null;
            this.dragDepth = 0;
        }

        open(options = {}) {
            this.state.page = 1;
            this.state.search = '';
            this.state.type = '';
            this.state.allowedTypes = Array.isArray(options.allowedTypes) && options.allowedTypes.length > 0
                ? options.allowedTypes
                : ['image', 'video', 'file'];
            this.state.targetInput = options.targetInput || null;
            this.state.allowSelection = options.allowSelection !== false;
            this.state.onClosed = typeof options.onClosed === 'function' ? options.onClosed : null;

            this.mount();
            this.loadList().catch(() => this.renderEmpty('Failed to load media'));
        }

        close() {
            if (this.modal) {
                this.modal.remove();
                this.modal = null;
            }
            this.dragDepth = 0;
            if (typeof this.state.onClosed === 'function') {
                this.state.onClosed();
            }
        }

        mount() {
            if (this.modal) {
                this.modal.remove();
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'fixed inset-0 z-[10010] flex items-center justify-center p-4';
            wrapper.innerHTML = `
                <div class="absolute inset-0 bg-black/60" data-media-close></div>
                <div class="relative w-full max-w-6xl max-h-[92vh] bg-white rounded-2xl border border-slate-200 shadow-2xl flex flex-col overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-slate-800">Media Library</h3>
                        <button type="button" class="h-9 w-9 rounded-full border border-slate-300 text-slate-600 hover:bg-slate-100" data-media-close>&times;</button>
                    </div>
                    <div class="p-4 border-b border-slate-200 flex flex-col md:flex-row gap-3 md:items-center md:justify-between">
                        <div class="flex gap-2" data-media-filters>
                            <button type="button" data-type="" class="px-3 py-1.5 rounded-lg text-sm border border-slate-300 bg-slate-900 text-white">All</button>
                            <button type="button" data-type="image" class="px-3 py-1.5 rounded-lg text-sm border border-slate-300">Images</button>
                            <button type="button" data-type="video" class="px-3 py-1.5 rounded-lg text-sm border border-slate-300">Videos</button>
                            <button type="button" data-type="file" class="px-3 py-1.5 rounded-lg text-sm border border-slate-300">Files</button>
                        </div>
                        <div class="flex gap-2 w-full md:w-auto">
                            <input type="search" data-media-search placeholder="Search media..." class="flex-1 md:w-72 px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#0F3D3E]/20">
                            <button type="button" data-media-upload-btn class="px-3 py-2 rounded-lg text-sm font-medium text-white bg-[#0F3D3E] hover:bg-[#0d3334]">Upload</button>
                        </div>
                    </div>
                    <div class="flex-1 overflow-auto p-4 relative" data-media-dropzone>
                        <input type="file" data-media-upload class="hidden" multiple>
                        <div class="hidden absolute inset-4 z-10 rounded-2xl border-2 border-dashed border-[#0F3D3E] bg-[#0F3D3E]/6 backdrop-blur-sm items-center justify-center text-center px-6" data-media-drop-overlay>
                            <div>
                                <p class="text-base font-semibold text-[#0F3D3E]">Drop files to upload</p>
                                <p class="mt-1 text-sm text-slate-600">Upload multiple images, videos, or files at once.</p>
                            </div>
                        </div>
                        <div data-media-grid class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"></div>
                        <p data-media-empty class="hidden py-12 text-center text-sm text-slate-500">No media found.</p>
                    </div>
                    <div class="px-4 py-3 border-t border-slate-200 flex items-center justify-between">
                        <button type="button" data-media-prev class="px-3 py-1.5 rounded-lg border border-slate-300 text-sm disabled:opacity-50">Previous</button>
                        <span data-media-page class="text-sm text-slate-600">Page 1</span>
                        <button type="button" data-media-next class="px-3 py-1.5 rounded-lg border border-slate-300 text-sm disabled:opacity-50">Next</button>
                    </div>
                </div>
            `;

            document.body.appendChild(wrapper);
            this.modal = wrapper;
            this.bindEvents();
        }

        bindEvents() {
            if (!this.modal) return;
            const closeTargets = this.modal.querySelectorAll('[data-media-close]');
            closeTargets.forEach((el) => el.addEventListener('click', () => this.close()));

            const filterButtons = Array.from(this.modal.querySelectorAll('[data-media-filters] [data-type]'));
            filterButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    filterButtons.forEach((item) => {
                        item.classList.remove('bg-slate-900', 'text-white');
                    });
                    button.classList.add('bg-slate-900', 'text-white');
                    this.state.type = button.getAttribute('data-type') || '';
                    this.state.page = 1;
                    this.loadList().catch(() => this.renderEmpty('Failed to load media'));
                });
            });

            const search = this.modal.querySelector('[data-media-search]');
            search.addEventListener('input', () => {
                if (this.searchDebounce) clearTimeout(this.searchDebounce);
                this.searchDebounce = setTimeout(() => {
                    this.state.search = search.value.trim();
                    this.state.page = 1;
                    this.loadList().catch(() => this.renderEmpty('Failed to load media'));
                }, 250);
            });

            const uploadInput = this.modal.querySelector('[data-media-upload]');
            const uploadBtn = this.modal.querySelector('[data-media-upload-btn]');
            const dropzone = this.modal.querySelector('[data-media-dropzone]');
            uploadBtn.addEventListener('click', () => uploadInput.click());
            uploadInput.addEventListener('change', async () => {
                const files = uploadInput.files ? Array.from(uploadInput.files).filter(Boolean) : [];
                if (files.length === 0) return;
                await this.uploadFiles(files, uploadBtn);
                uploadInput.value = '';
            });

            const setDropActive = (active) => {
                const overlay = this.modal?.querySelector('[data-media-drop-overlay]');
                if (!overlay) return;
                overlay.classList.toggle('hidden', !active);
                overlay.classList.toggle('flex', active);
            };

            if (dropzone) {
                dropzone.addEventListener('dragenter', (event) => {
                    event.preventDefault();
                    this.dragDepth += 1;
                    setDropActive(true);
                });

                dropzone.addEventListener('dragover', (event) => {
                    event.preventDefault();
                    if (event.dataTransfer) {
                        event.dataTransfer.dropEffect = 'copy';
                    }
                    setDropActive(true);
                });

                dropzone.addEventListener('dragleave', (event) => {
                    event.preventDefault();
                    this.dragDepth = Math.max(0, this.dragDepth - 1);
                    if (this.dragDepth === 0) {
                        setDropActive(false);
                    }
                });

                dropzone.addEventListener('drop', async (event) => {
                    event.preventDefault();
                    this.dragDepth = 0;
                    setDropActive(false);
                    const files = event.dataTransfer?.files ? Array.from(event.dataTransfer.files).filter(Boolean) : [];
                    if (files.length === 0) return;
                    await this.uploadFiles(files, uploadBtn);
                });
            }

            const prev = this.modal.querySelector('[data-media-prev]');
            const next = this.modal.querySelector('[data-media-next]');
            prev.addEventListener('click', () => {
                if (this.state.page > 1) {
                    this.state.page -= 1;
                    this.loadList().catch(() => this.renderEmpty('Failed to load media'));
                }
            });
            next.addEventListener('click', () => {
                if (this.state.page < this.state.totalPages) {
                    this.state.page += 1;
                    this.loadList().catch(() => this.renderEmpty('Failed to load media'));
                }
            });
        }

        async loadList() {
            if (!this.modal) return;
            const params = new URLSearchParams({
                page: String(this.state.page),
                per_page: '20',
            });
            if (this.state.type) params.set('type', this.state.type);
            if (this.state.search) params.set('search', this.state.search);

            const response = await fetch(`${API.list}?${params.toString()}`);
            const data = await response.json();
            const items = Array.isArray(data.items) ? data.items : [];
            this.state.totalPages = Number(data.pagination?.total_pages || 1);

            const filtered = items.filter((item) => this.state.allowedTypes.includes(item.media_type));
            this.renderItems(filtered);
        }

        renderEmpty(message) {
            const grid = this.modal?.querySelector('[data-media-grid]');
            const empty = this.modal?.querySelector('[data-media-empty]');
            const page = this.modal?.querySelector('[data-media-page]');
            const prev = this.modal?.querySelector('[data-media-prev]');
            const next = this.modal?.querySelector('[data-media-next]');

            if (!grid || !empty || !page || !prev || !next) return;
            grid.innerHTML = '';
            empty.textContent = message || 'No media found.';
            empty.classList.remove('hidden');
            page.textContent = `Page ${this.state.page} of ${this.state.totalPages}`;
            prev.disabled = this.state.page <= 1;
            next.disabled = this.state.page >= this.state.totalPages;
        }

        renderItems(items) {
            if (!this.modal) return;
            const grid = this.modal.querySelector('[data-media-grid]');
            const empty = this.modal.querySelector('[data-media-empty]');
            const page = this.modal.querySelector('[data-media-page]');
            const prev = this.modal.querySelector('[data-media-prev]');
            const next = this.modal.querySelector('[data-media-next]');

            grid.innerHTML = '';

            items.forEach((item) => {
                const card = document.createElement('article');
                card.className = 'rounded-lg border border-slate-200 overflow-hidden bg-white';

                let preview = '<div class="h-28 bg-slate-100 flex items-center justify-center text-xs text-slate-500 uppercase">File</div>';
                if (item.media_type === 'image') {
                    preview = `<img src="${item.public_url}" alt="${item.original_name || 'media'}" class="h-28 w-full object-cover">`;
                } else if (item.media_type === 'video') {
                    preview = `<video src="${item.public_url}" class="h-28 w-full object-cover" muted playsinline></video>`;
                }

                card.innerHTML = `
                    ${preview}
                    <div class="p-2.5">
                        <p class="text-[11px] font-medium text-slate-800 truncate" title="${(item.original_name || '').replace(/"/g, '&quot;')}">${item.original_name || 'Untitled'}</p>
                        <p class="text-[10px] text-slate-500 mt-1">${item.media_type.toUpperCase()} · ${formatSize(item.size_bytes || 0)}</p>
                        <div class="flex gap-2 mt-2">
                            ${this.state.allowSelection ? '<button type="button" data-select class="px-2 py-1 text-[11px] rounded border border-[#0F3D3E] text-[#0F3D3E] hover:bg-[#0F3D3E] hover:text-white">Select</button>' : ''}
                            <button type="button" data-copy class="px-2 py-1 text-[11px] rounded border border-slate-300 hover:bg-slate-50">Copy</button>
                            <button type="button" data-delete class="px-2 py-1 text-[11px] rounded border border-red-200 text-red-600 hover:bg-red-50">Delete</button>
                        </div>
                    </div>
                `;

                card.querySelector('[data-copy]').addEventListener('click', async () => {
                    await navigator.clipboard.writeText(item.public_url);
                });

                const selectButton = card.querySelector('[data-select]');
                if (selectButton) {
                    selectButton.addEventListener('click', () => {
                        if (this.state.targetInput) {
                            this.state.targetInput.value = item.public_url;
                            this.state.targetInput.dispatchEvent(new Event('input', { bubbles: true }));
                            this.state.targetInput.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                        this.close();
                    });
                }

                card.querySelector('[data-delete]').addEventListener('click', async () => {
                    const confirmed = await window.AdminToastConfirm?.show({
                        title: 'Delete Media',
                        message: 'Delete this media item? This cannot be undone.',
                        confirmText: 'Delete',
                    });
                    if (!confirmed) return;

                    const formData = new FormData();
                    formData.set('id', String(item.id));
                    formData.set('_csrf_token', getCsrfToken());

                    const response = await fetch(API.delete, {
                        method: 'POST',
                        body: formData,
                    });
                    const result = await response.json();
                    if (result.csrf_token) setCsrfToken(result.csrf_token);
                    if (!response.ok) {
                        alert(result.error || 'Unable to delete media');
                        return;
                    }
                    await this.loadList();
                });

                grid.appendChild(card);
            });

            empty.classList.toggle('hidden', items.length > 0);
            page.textContent = `Page ${this.state.page} of ${this.state.totalPages}`;
            prev.disabled = this.state.page <= 1;
            next.disabled = this.state.page >= this.state.totalPages;
        }

        async uploadFiles(files, uploadButton = null) {
            if (!Array.isArray(files) || files.length === 0) {
                return;
            }

            const originalLabel = uploadButton ? uploadButton.textContent : '';
            if (uploadButton) {
                uploadButton.disabled = true;
                uploadButton.textContent = files.length > 1 ? `Uploading ${files.length} files...` : 'Uploading...';
            }

            const failures = [];

            for (const file of files) {
                const formData = new FormData();
                formData.set('file', file);
                formData.set('_csrf_token', getCsrfToken());

                const response = await fetch(API.upload, {
                    method: 'POST',
                    body: formData,
                });
                const data = await response.json();
                if (data.csrf_token) setCsrfToken(data.csrf_token);

                if (!response.ok) {
                    failures.push({
                        name: file.name,
                        error: data.error || 'Upload failed',
                    });
                }
            }

            if (uploadButton) {
                uploadButton.disabled = false;
                uploadButton.textContent = originalLabel || 'Upload';
            }

            await this.loadList();

            if (failures.length > 0) {
                const message = failures
                    .slice(0, 3)
                    .map((failure) => `${failure.name}: ${failure.error}`)
                    .join('\n');
                alert(message + (failures.length > 3 ? '\nMore files failed to upload.' : ''));
            }
        }
    }

    const picker = new MediaPicker();
    window.AdminMediaPicker = picker;

    const inferAllowedTypes = (input) => {
        const name = (input.getAttribute('name') || '').toLowerCase();
        if (name === 'media_url') {
            return ['image', 'video'];
        }
        return ['image'];
    };

    const attachMediaButtons = () => {
        const inputs = Array.from(document.querySelectorAll('input[name="image_url"], input[name="media_url"]'));
        inputs.forEach((input) => {
            if (input.dataset.mediaPickerBound === '1') return;
            input.dataset.mediaPickerBound = '1';

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'mt-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium border border-slate-300 text-slate-700 hover:bg-slate-100';
            button.innerHTML = '<i class="fas fa-photo-video"></i> Media Library';
            button.addEventListener('click', () => {
                picker.open({
                    targetInput: input,
                    allowedTypes: inferAllowedTypes(input),
                    allowSelection: true,
                });
            });

            input.insertAdjacentElement('afterend', button);
        });
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', attachMediaButtons);
    } else {
        attachMediaButtons();
    }
})();
