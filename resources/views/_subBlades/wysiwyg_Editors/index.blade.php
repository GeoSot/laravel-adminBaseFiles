@push('scripts')
    <script defer data-comment="wysiwyg_Editor initialize">
        window.BaseAdmin = window.BaseAdmin || {};
        BaseAdmin.textEditors = BaseAdmin.textEditors || [];
        document.addEventListener('baseAdmin:initTextEditor', (el) => {
            BaseAdmin.initActiveTextEditor(el)
        });

    </script>
@endpush

@includeIf($packageVariables->get('blades').'_subBlades.wysiwyg_Editors._'.($editor??'quill'))
