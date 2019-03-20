@push('scripts')
    <script data-comment="wysiwyg_Editor initialize">
               window.aw = aw || {};
               aw.textEditors = aw.textEditors || [];
               aw.initTextEditor = function (el) {
                   aw.initActiveTextEditor(el)
               };
        </script>
@endpush

@includeIf($packageVariables->get('helpBlades').'.wysiwyg_Editors._'.($editor??'quill'))
