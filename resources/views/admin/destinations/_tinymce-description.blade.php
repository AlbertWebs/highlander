@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#dest_description',
            height: 380,
            menubar: false,
            plugins: 'link lists code',
            toolbar: 'undo redo | styles | bold italic | bullist numlist | link | code',
            entity_encoding: 'raw',
        });
    }
});
</script>
@endpush
