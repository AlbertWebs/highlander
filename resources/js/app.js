import './bootstrap';

import AOS from 'aos';
import 'aos/dist/aos.css';

import Alpine from 'alpinejs';

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

/** Read visible label text from plan-my-safari form controls for the review step. */
function planSafariFieldTrim(form, name) {
    const el = form.querySelector(`[name="${name}"]`);
    if (!el) {
        return '';
    }

    return String(el.value ?? '').trim();
}

function planSafariSelectText(form, name) {
    const el = form.querySelector(`select[name="${name}"]`);
    if (!el || el.value === '') {
        return '—';
    }
    const opt = el.options[el.selectedIndex];

    return (opt?.textContent ?? '').replace(/\s+/g, ' ').trim() || el.value;
}

function planSafariCheckboxGroupText(form, inputName) {
    const texts = [];
    for (const inp of form.querySelectorAll('input[type="checkbox"]')) {
        if (inp.name !== inputName || !inp.checked) {
            continue;
        }
        const label = inp.closest('label');
        const span = label?.querySelector('span');
        texts.push((span?.textContent ?? '').replace(/\s+/g, ' ').trim() || inp.value);
    }

    return texts.length ? texts.join(', ') : '—';
}

function planSafariRadioGroupText(form, name) {
    for (const inp of form.querySelectorAll('input[type="radio"]')) {
        if (inp.name !== name || !inp.checked) {
            continue;
        }
        const label = inp.closest('label');
        const span = label?.querySelector('span');

        return (span?.textContent ?? '').replace(/\s+/g, ' ').trim() || inp.value;
    }

    return '—';
}

function initScrollAnimations() {
    AOS.init({
        duration: 800,
        easing: 'ease-out',
        once: true,
        offset: 48,
        delay: 0,
        disable: () => window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        anchorPlacement: 'top-bottom',
    });
}

document.addEventListener('alpine:init', () => {
    /** Hide when any hero MP4 can play, or Vimeo iframe has loaded (searches parent section). */
    Alpine.data('heroVideoLoading', () => ({
        loading: true,
        init() {
            const section = this.$el.closest('section');
            if (!section) {
                this.loading = false;
                return;
            }
            const finish = () => {
                this.loading = false;
            };
            const videos = section.querySelectorAll('video');
            if (videos.length > 0) {
                let settled = false;
                const done = () => {
                    if (settled) {
                        return;
                    }
                    settled = true;
                    finish();
                };
                videos.forEach((v) => {
                    v.addEventListener('canplay', done, { once: true });
                    v.addEventListener('error', done, { once: true });
                    if (v.readyState >= 3) {
                        done();
                    }
                });
                window.setTimeout(done, 12000);
                return;
            }
            const iframe = section.querySelector('iframe[src*="vimeo"], iframe[src*="player.vimeo"]');
            if (iframe) {
                iframe.addEventListener('load', () => {
                    window.setTimeout(finish, 500);
                });
                window.setTimeout(finish, 10000);
                return;
            }
            finish();
        },
    }));

    Alpine.data('heroCarousel', ({ videos, intervalSec }) => ({
        current: 0,
        videos,
        intervalSec: intervalSec ?? 10,
        timer: null,
        init() {
            this.$nextTick(() => this.sync());
            this.timer = window.setInterval(() => {
                if (!this.videos?.length) {
                    return;
                }
                this.current = (this.current + 1) % this.videos.length;
                this.sync();
            }, this.intervalSec * 1000);
        },
        sync() {
            this.$el.querySelectorAll('.hero-bg-video').forEach((v, i) => {
                if (i === this.current) {
                    v.play().catch(() => {});
                } else {
                    v.pause();
                    try {
                        v.currentTime = 0;
                    } catch (e) {
                        /* ignore */
                    }
                }
            });
        },
        goTo(i) {
            this.current = i;
            this.sync();
        },
    }));

    Alpine.data('planSafariForm', (arrival, departure, children) => ({
        arrival: arrival ?? '',
        departure: departure ?? '',
        children: Number(children) || 0,
        get tripDays() {
            if (!this.arrival || !this.departure) {
                return null;
            }
            const a = new Date(this.arrival);
            const d = new Date(this.departure);
            if (Number.isNaN(a.getTime()) || Number.isNaN(d.getTime()) || d <= a) {
                return null;
            }
            return Math.round((d - a) / 86400000);
        },
    }));

    Alpine.data('planSafariWizard', (arrival, departure, children, initialStep) => ({
        totalSteps: 6,
        step: (() => {
            const s = Number(initialStep);
            if (Number.isFinite(s) && s >= 1 && s <= 6) {
                return s;
            }

            return 1;
        })(),
        arrival: arrival ?? '',
        departure: departure ?? '',
        children: Number(children) || 0,
        reviewName: '',
        reviewEmail: '',
        reviewPhone: '',
        reviewCountry: '',
        reviewContactMethod: '',
        reviewDates: '',
        reviewFlexible: '',
        reviewTripDuration: '',
        reviewParty: '',
        reviewChildrenAges: '',
        reviewGroupType: '',
        reviewDestinations: '',
        reviewOtherDestination: '',
        reviewExperiences: '',
        reviewAccommodation: '',
        reviewRoom: '',
        reviewTransport: '',
        reviewAirportPickup: '',
        reviewBudget: '',
        reviewActivities: '',
        reviewSpecialRequests: '',
        init() {
            this.$nextTick(() => {
                if (this.step === this.totalSteps) {
                    this.syncReview();
                }
            });
        },
        get tripDays() {
            if (!this.arrival || !this.departure) {
                return null;
            }
            const a = new Date(this.arrival);
            const d = new Date(this.departure);
            if (Number.isNaN(a.getTime()) || Number.isNaN(d.getTime()) || d <= a) {
                return null;
            }
            return Math.round((d - a) / 86400000);
        },
        syncReview() {
            const form = this.$root;
            const yes = form.dataset.yesWord || 'Yes';
            const no = form.dataset.noWord || 'No';
            const adultsWord = form.dataset.adultsWord || 'Adults';
            const childrenWord = form.dataset.childrenWord || 'Children';
            const daysWord = form.dataset.daysWord || 'days';

            this.reviewName = planSafariFieldTrim(form, 'full_name') || '—';
            this.reviewEmail = planSafariFieldTrim(form, 'email') || '—';
            this.reviewPhone = planSafariFieldTrim(form, 'phone') || '—';
            this.reviewCountry = planSafariFieldTrim(form, 'country') || '—';
            this.reviewContactMethod = planSafariSelectText(form, 'contact_method');

            const ad = planSafariFieldTrim(form, 'arrival_date');
            const dd = planSafariFieldTrim(form, 'departure_date');
            this.reviewDates = ad && dd ? `${ad} → ${dd}` : '—';
            this.reviewFlexible = form.querySelector('input[name="flexible_dates"]')?.checked ? yes : no;

            const td = this.tripDays;
            this.reviewTripDuration = td !== null ? `${td} ${daysWord}` : '—';

            const adults = planSafariFieldTrim(form, 'adults');
            const ch = planSafariFieldTrim(form, 'children');
            const partyParts = [];
            if (adults !== '') {
                partyParts.push(`${adults} ${adultsWord}`);
            }
            if (Number(ch) > 0) {
                partyParts.push(`${ch} ${childrenWord}`);
            }
            this.reviewParty = partyParts.length ? partyParts.join(', ') : '—';

            this.reviewChildrenAges = planSafariFieldTrim(form, 'children_ages') || '—';
            this.reviewGroupType = planSafariSelectText(form, 'group_type');

            this.reviewDestinations = planSafariCheckboxGroupText(form, 'destinations[]');
            this.reviewOtherDestination = planSafariFieldTrim(form, 'other_destination') || '—';
            this.reviewExperiences = planSafariCheckboxGroupText(form, 'experience_types[]');

            this.reviewAccommodation = planSafariSelectText(form, 'accommodation_type');
            this.reviewRoom = planSafariSelectText(form, 'room_type');
            this.reviewTransport = planSafariSelectText(form, 'transport_type');
            this.reviewAirportPickup = form.querySelector('input[name="airport_pickup"]')?.checked ? yes : no;

            this.reviewBudget = planSafariRadioGroupText(form, 'budget_range');
            this.reviewActivities = planSafariCheckboxGroupText(form, 'activities[]');

            const notes = planSafariFieldTrim(form, 'special_requests');
            this.reviewSpecialRequests = notes || '—';
        },
        validateStep() {
            const pane = this.$root.querySelector(`[data-wizard-step="${this.step}"]`);
            if (!pane) {
                return true;
            }
            const controls = pane.querySelectorAll('input, select, textarea');
            for (const el of controls) {
                if (!el.name || el.name === 'wizard_step' || el.name === '_token') {
                    continue;
                }
                if (el.closest('.pointer-events-none')) {
                    continue;
                }
                if (el.willValidate && !el.checkValidity()) {
                    el.reportValidity();

                    return false;
                }
            }

            return true;
        },
        next() {
            if (!this.validateStep()) {
                return;
            }
            if (this.step < this.totalSteps) {
                this.step += 1;
                if (this.step === this.totalSteps) {
                    this.$nextTick(() => this.syncReview());
                }
            }
        },
        prev() {
            if (this.step > 1) {
                this.step -= 1;
            }
        },
        handleSubmit(ev) {
            if (this.step < this.totalSteps) {
                ev.preventDefault();

                return;
            }
            ev.preventDefault();
            const form = ev.target;
            let firstInvalid = null;
            for (const el of form.querySelectorAll('input, select, textarea')) {
                if (!el.name || el.name === 'wizard_step' || el.name === '_token' || el.closest('.pointer-events-none')) {
                    continue;
                }
                if (el.willValidate && !el.checkValidity()) {
                    firstInvalid = el;
                    break;
                }
            }
            if (firstInvalid) {
                firstInvalid.reportValidity();
                const pane = firstInvalid.closest('[data-wizard-step]');
                if (pane) {
                    const s = Number(pane.dataset.wizardStep);
                    if (Number.isFinite(s)) {
                        this.step = s;
                    }
                }

                return;
            }
            this.$refs.submitBtn.disabled = true;
            form.submit();
        },
    }));

    Alpine.data('fileImagePreview', (serverUrl) => ({
        server: serverUrl ?? null,
        preview: serverUrl ?? null,
        blob: null,
        pick(event) {
            if (this.blob) {
                URL.revokeObjectURL(this.blob);
                this.blob = null;
            }
            const file = event.target.files?.[0];
            if (!file) {
                this.preview = this.server;

                return;
            }
            this.blob = URL.createObjectURL(file);
            this.preview = this.blob;
        },
    }));

    Alpine.data('testimonialCarousel', ({ total }) => ({
        current: 0,
        total: total ?? 0,
        next() {
            if (this.total <= 1) {
                return;
            }
            this.current = (this.current + 1) % this.total;
        },
        prev() {
            if (this.total <= 1) {
                return;
            }
            this.current = (this.current - 1 + this.total) % this.total;
        },
        goTo(i) {
            this.current = i;
        },
    }));

    Alpine.data('galleryDropzone', ({
        uploadUrl,
        maxFiles = 30,
        adminGalleryBase = '',
        labels = {},
    }) => ({
        rows: [],
        defaultCategoryId: '',
        dragging: false,
        uploading: false,
        uploadProgress: 0,
        uploadStatus: '',
        lastMessage: '',
        error: '',
        maxFiles,
        uploadUrl,
        adminGalleryBase,
        labels,
        addFiles(fileList) {
            const incoming = Array.from(fileList || []).filter((f) => f.type.startsWith('image/'));
            const room = this.maxFiles - this.rows.length;
            for (let i = 0; i < Math.min(room, incoming.length); i++) {
                const file = incoming[i];
                this.rows.push({
                    _id: `${Date.now()}-${i}-${Math.random().toString(36).slice(2, 9)}`,
                    file,
                    title: '',
                    categoryId: this.defaultCategoryId ? String(this.defaultCategoryId) : '',
                    preview: URL.createObjectURL(file),
                });
            }
        },
        removeRow(_id) {
            const row = this.rows.find((r) => r._id === _id);
            if (row?.preview) {
                URL.revokeObjectURL(row.preview);
            }
            this.rows = this.rows.filter((r) => r._id !== _id);
        },
        clearAll() {
            this.rows.forEach((r) => {
                if (r.preview) {
                    URL.revokeObjectURL(r.preview);
                }
            });
            this.rows = [];
        },
        escapeHtml(s) {
            return String(s ?? '').replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]);
        },
        prependUploadedCards(items) {
            const grid = document.getElementById('admin-gallery-grid');
            if (!grid || !items?.length || !this.adminGalleryBase) {
                return;
            }
            grid.querySelectorAll('[data-gallery-empty]').forEach((el) => el.remove());
            const base = this.adminGalleryBase.replace(/\/$/, '');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const L = this.labels || {};
            const confirmArg = JSON.stringify(L.deleteConfirm || 'Delete?');
            for (const item of [...items].reverse()) {
                const title = this.escapeHtml(item.title || `#${item.id}`);
                const catHtml = item.category_name
                    ? `<p class="mt-0.5 truncate text-xs text-primary">${this.escapeHtml(item.category_name)}</p>`
                    : '';
                const activeLabel = item.is_active ? this.escapeHtml(L.on || 'On') : this.escapeHtml(L.off || 'Off');
                const srcAttr = String(item.url)
                    .replace(/&/g, '&amp;')
                    .replace(/"/g, '&quot;');
                const card = document.createElement('div');
                card.className = 'overflow-hidden rounded-xl border border-secondary/50 bg-white shadow-soft';
                card.innerHTML = `
                    <div class="aspect-[4/3] overflow-hidden bg-neutral-100">
                        <img src="${srcAttr}" alt="" class="h-full w-full object-cover" loading="lazy" width="400" height="300">
                    </div>
                    <div class="flex items-start justify-between gap-2 border-t border-secondary/40 p-3 text-sm">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-ink">${title}</p>
                            ${catHtml}
                        </div>
                        <form method="POST" action="${base}/${item.id}/toggle" class="inline">
                            <input type="hidden" name="_token" value="${this.escapeHtml(csrf)}">
                            <input type="hidden" name="_method" value="PATCH">
                            <button type="submit" class="shrink-0 text-xs font-medium text-primary">${activeLabel}</button>
                        </form>
                    </div>
                    <div class="flex gap-2 border-t border-secondary/30 p-2">
                        <a href="${base}/${item.id}/edit" class="text-xs font-medium text-primary">${this.escapeHtml(L.edit || 'Edit')}</a>
                        <form action="${base}/${item.id}" method="post" class="inline" onsubmit="return confirm(${confirmArg})">
                            <input type="hidden" name="_token" value="${this.escapeHtml(csrf)}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-xs text-red-600">${this.escapeHtml(L.delete || 'Delete')}</button>
                        </form>
                    </div>
                `;
                grid.prepend(card);
            }
        },
        submit() {
            if (!this.rows.length || this.uploading) {
                return;
            }
            this.uploading = true;
            this.error = '';
            this.uploadProgress = 0;
            this.uploadStatus = this.labels?.uploading || 'Uploading…';
            this.lastMessage = '';
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const fd = new FormData();
            fd.append('_token', token);
            this.rows.forEach((row) => {
                fd.append('images[]', row.file);
                fd.append('titles[]', row.title ?? '');
                fd.append('gallery_category_ids[]', row.categoryId === '' || row.categoryId == null ? '' : String(row.categoryId));
            });

            const xhr = new XMLHttpRequest();
            xhr.open('POST', this.uploadUrl);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', token);

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable && e.total > 0) {
                    this.uploadProgress = Math.min(100, Math.round((e.loaded / e.total) * 100));
                }
            });

            xhr.onload = () => {
                let data = {};
                try {
                    data = JSON.parse(xhr.responseText || '{}');
                } catch {
                    data = {};
                }
                if (xhr.status === 422 && data.errors) {
                    const first = Object.values(data.errors)[0];
                    this.error = Array.isArray(first) ? first[0] : 'Validation failed';
                    this.uploading = false;
                    this.uploadProgress = 0;
                    this.uploadStatus = '';

                    return;
                }
                if (xhr.status < 200 || xhr.status >= 300) {
                    this.error = data.message || 'Upload failed';
                    this.uploading = false;
                    this.uploadProgress = 0;
                    this.uploadStatus = '';

                    return;
                }
                this.clearAll();
                this.lastMessage = data.message || '';
                this.uploadProgress = 100;
                this.uploadStatus = '';
                if (Array.isArray(data.items) && data.items.length) {
                    this.prependUploadedCards(data.items);
                }
                setTimeout(() => {
                    this.uploading = false;
                    this.uploadProgress = 0;
                    this.uploadStatus = '';
                    const grid = document.getElementById('admin-gallery-grid');
                    if (grid) {
                        grid.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }, 0);
            };

            xhr.onerror = () => {
                this.error = 'Upload failed';
                this.uploading = false;
                this.uploadProgress = 0;
                this.uploadStatus = '';
            };

            xhr.send(fd);
        },
    }));

    let tourItineraryRowId = 0;

    Alpine.data('tourItineraryEditor', (initialDays) => ({
        days: [],
        init() {
            const raw = Array.isArray(initialDays) ? initialDays : [];
            this.days =
                raw.length > 0
                    ? raw.map((d) => ({
                          _id: ++tourItineraryRowId,
                          title: typeof d.title === 'string' ? d.title : '',
                          body: typeof d.body === 'string' ? d.body : '',
                          existing_image: typeof d.existing_image === 'string' ? d.existing_image : '',
                          initialExisting: typeof d.existing_image === 'string' ? d.existing_image : '',
                          initialPreview: typeof d.image_preview === 'string' ? d.image_preview : '',
                          previewUrl: typeof d.image_preview === 'string' ? d.image_preview : '',
                          _blobUrl: null,
                      }))
                    : [];
        },
        addDay() {
            this.days.push({
                _id: ++tourItineraryRowId,
                title: '',
                body: '',
                existing_image: '',
                initialExisting: '',
                initialPreview: '',
                previewUrl: '',
                _blobUrl: null,
            });
        },
        removeDay(index) {
            const day = this.days[index];
            if (day?._blobUrl) {
                URL.revokeObjectURL(day._blobUrl);
            }
            this.days.splice(index, 1);
        },
        onItineraryImageChange(event, day) {
            const file = event.target.files?.[0];
            if (day._blobUrl) {
                URL.revokeObjectURL(day._blobUrl);
                day._blobUrl = null;
            }
            if (!file) {
                day.existing_image = day.initialExisting || '';
                day.previewUrl = day.initialPreview || '';

                return;
            }
            day.existing_image = '';
            day._blobUrl = URL.createObjectURL(file);
            day.previewUrl = day._blobUrl;
        },
        moveUp(index) {
            if (index < 1) {
                return;
            }
            const next = [...this.days];
            [next[index - 1], next[index]] = [next[index], next[index - 1]];
            this.days = next;
        },
        moveDown(index) {
            if (index >= this.days.length - 1) {
                return;
            }
            const next = [...this.days];
            [next[index], next[index + 1]] = [next[index + 1], next[index]];
            this.days = next;
        },
    }));
});

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    initScrollAnimations();

    window.addEventListener(
        'resize',
        () => {
            if (!prefersReducedMotion()) {
                AOS.refresh();
            }
        },
        { passive: true }
    );
});
