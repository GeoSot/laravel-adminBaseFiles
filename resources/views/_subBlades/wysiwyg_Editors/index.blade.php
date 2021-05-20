@push('scripts')
    <script defer data-comment="wysiwyg_Editor initialize">
        window.BaseAdmin = window.BaseAdmin || {};
        BaseAdmin.textEditors = BaseAdmin.textEditors || [];
        document.addEventListener('baseAdmin:initTextEditor', ev => {
            BaseAdmin.initActiveTextEditor(ev.detail)
        });
        document.addEventListener('baseAdmin:ajaxLoadWrappers', ev => {
            Array.from(ev.detail.querySelectorAll('textarea.withEditor')).forEach(el => {
                BaseAdmin.initActiveTextEditor(el)
            });
        });
    </script>
@endpush

@includeIf($packageVariables->get('blades').'_subBlades.wysiwyg_Editors._'.($editor??'quill'))
