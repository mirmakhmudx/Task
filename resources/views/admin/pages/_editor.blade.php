{{-- Summernote vizual muharrir --}}
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
<script>
    window.addEventListener('load', function () {
        $('#content-editor').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            callbacks: {
                onImageUpload: function (files) {
                    var editor = $(this);
                    var data = new FormData();
                    data.append('file', files[0]);
                    $.ajax({
                        url: '{{ route('admin.pages.upload-image') }}',
                        method: 'POST',
                        data: data,
                        processData: false,
                        contentType: false,
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        success: function (res) {
                            editor.summernote('insertImage', res.url);
                        },
                        error: function () {
                            alert('Rasm yuklashda xatolik (hajm 2MB dan oshmasin).');
                        }
                    });
                }
            }
        });
    });
</script>
