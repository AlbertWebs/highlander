@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof tinymce === 'undefined') return;
    tinymce.init({
        selector: 'textarea.tinymce-editor',
        height: 300,
        menubar: false,
        branding: false,
        plugins: 'link lists autoresize',
        toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link | removeformat',
        entity_encoding: 'raw',
        convert_urls: false,
        resize: true,
        autoresize_bottom_margin: 24,
    });
});
</script>
@endpush
