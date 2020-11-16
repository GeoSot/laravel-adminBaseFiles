@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/4.7.13/tinymce.min.js"></script>
    <script defer data-comment="wysiwyg_Editor tinymce">

        let toolbar = ' undo redo  | cut copy paste pastetext | bold  styleselect  | bullist  hr  link ';
        let menubar = false;
        if (location.pathname.replace('/', '').split('/')[0] == 'admin') {
            toolbar = '  bold forecolor styleselect  | bullist numlist outdent indent | hr  codesample charmap link | code';
            menubar = 'edit  format table tools ';
            inline = false;
        }
        tinymce.init({
            selector: 'textarea.withEditor',
            //height: 500,
            plugins: [
                'advlist autolink textcolor lists link image charmap autolink searchreplace visualblocks code fullscreen  media  table contextmenu paste    hr '
            ],
            branding: false,
            menubar: menubar,
            // insert_button_items: 'image link | inserttable',
            //toolbar: ' undo redo  | cut copy paste pastetext | bold forecolor styleselect  | bullist numlist outdent indent | hr  codesample charmap link | code',
            toolbar: toolbar,
            init_instance_callback: function (editor) {
                //  tinymce.DOM.addClass(editor.id,  'form-control');

                editor.editorContainer.classList.add("form-control");
                if (typeof wysiwyg_ReadyCallback == 'function') {
                    wysiwyg_ReadyCallback(editor, editor.id)
                }
            },
            browser_spellcheck: true,
            gecko_spellcheck: false,
            // images_upload_url: 'postAcceptor.php',

        });

    </script>
@endpush

