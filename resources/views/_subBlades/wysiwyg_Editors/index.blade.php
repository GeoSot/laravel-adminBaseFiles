@push('scripts')
    <script data-comment="wysiwyg_Editor initialize">
        window.BaseAdmin = BaseAdmin || {};
        BaseAdmin.textEditors = BaseAdmin.textEditors || [];
        BaseAdmin.initTextEditor = function (el) {
            BaseAdmin.initActiveTextEditor(el)
        };
    </script>
@endpush

@includeIf($packageVariables->get('blades').'_subBlades.wysiwyg_Editors._'.($editor??'quill'))
