@push('scripts')

    <script defer data-comment="wysiwyg_Editor quill">
        let quilToolbarOptions = [
            ['bold', 'italic', 'underline'],        // toggled buttons
            ['blockquote', 'link'],//'code-block'
            [{'size': ['small', false, 'large', 'huge']}, {'header': [1, 2, 3, 4, 5, 6, false]}],  // custom dropdown

            // custom button values
            [{'list': 'ordered'}, {'list': 'bullet'}],
            // [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
            [{'align': []}, {'indent': '-1'}, {'indent': '+1'}],          // outdent/indent

            [{'color': []}, {'background': []}],          // dropdown with defaults from theme

            ['clean']                                         // remove formatting button
        ];

        BaseAdmin.initActiveTextEditor = function (el) {
            const _this = this;
            _this.el = el;
            _this.editorWrapper = null;
            _this.editor = null;
            if (_this.el.classList.contains('js-wysiwyg-initialized')) {
                return;
            }
            _this.el.style.display = 'none';

            let getWrapper = function () {
                _this.editorWrapper = document.createElement("div");
                let item = document.createElement("div");
                item.setAttribute("id", _this.getId());
                //wrapper.setAttribute("class", 'form-control p-0 js-wysiwyg-initialized h-100 clearfix');
                _this.editorWrapper.setAttribute("class", 'd-block w-100 bg-white wysiwyg-editorWrapper');
                _this.editorWrapper.appendChild(item);
                _this.el.parentNode.insertBefore(_this.editorWrapper, _this.el);
                item.innerHTML = el.value;
                return item;
            };
            this.getId = function () {
                return _this.el.id + '_editor_' + BaseAdmin.uuid();
            };
            this.getOptions = function () {
                let options = {
                    theme: 'snow',
                    modules: {
                        toolbar: quilToolbarOptions
                    },
                };
                // https://codepen.io/anon/pen/rrzpGx
                if (_this.el.readonly) {
                    options.readOnly = true;
                }
                return options;
            };
            this.activateEvents = function () {
                _this.editor.on('editor-change', function (eventName, ...args) {
                    _this.el.value = _this.editor.root.innerHTML
                    // _this.el.value  =quill.root.innerHTML
                    // console.log(JSON.stringify(quill.getContents()))
                });

                _this.el.closest('form').addEventListener('reset', function () {
                    _this.editor.setText('');
                });

                _this.editorWrapper.addEventListener('customFocus', function () {
                    _this.editor.focus();
                });
            };
            _this.editor = new Quill(getWrapper(), _this.getOptions());
            BaseAdmin.textEditors.push(_this.editor);
            _this.activateEvents();

            _this.el.classList.add('js-wysiwyg-initialized');
        };
        document.addEventListener("DOMContentLoaded", function (e) {
            let elements = document.querySelectorAll('textarea.withEditor');
            if (elements.length) {
                window.loader.loadStyle('//cdn.quilljs.com/latest/quill.snow.css')
                window.loader.loadScript('//cdn.quilljs.com/latest/quill.min.js').then(function () {
                        for (let i = 0; i < elements.length; i++) {
                            new BaseAdmin.initActiveTextEditor(elements[i])
                        }
                    })
                }
            }
        );

    </script>
@endpush


