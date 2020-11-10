@component($packageVariables->get('blades').'_subBlades._components.modal',['id'=>'mediaLibraryModal','animation'=>'','dialogClass' =>'modal-xl modal-dialog-scrollable'] )


    @slot('triggerBtn')
        <a href="#" data-toggle="modal" data-target="#mediaLibraryModal" role="button" class="btn btn-outline-primary btn-sm mb-1 ml-auto ">
            @if($library??true)
                @lang("baseAdmin::admin/media/mediumGallery.modal.libButton")
            @else
                @lang("baseAdmin::admin/media/mediumGallery.modal.uploadButton")
            @endif
        </a>
    @endslot

    @slot('title')
        <span v-if="isMediaLibrary" class="text-muted">
            @lang("baseAdmin::admin/media/mediumGallery.modal.title")
        </span>
        <span v-else class="text-muted">
            @lang("baseAdmin::admin/media/mediumGallery.modal.upload")
        </span>
        <button class="btn btn-outline-info ml-2 px-3" :disabled="isMediaLibrary" @click="isMediaLibrary=true" title="@lang("baseAdmin::admin/media/mediumGallery.modal.title")">
            <i class="fas fa-photo-video"></i>
        </button>
        <button class="btn btn-outline-info ml-2 px-3"  :disabled="!isMediaLibrary" @click="isMediaLibrary=false" title="@lang("baseAdmin::admin/media/mediumGallery.modal.upload")">
            <i class="fas fa-upload"></i>
        </button>
    @endslot
    @slot('footer')
        <button type="button" class="btn btn-success" v-if="choices.length>0" @click="pick">
            @lang("baseAdmin::admin/media/mediumGallery.modal.pickAndClose")
        </button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang("baseAdmin::admin/media/mediumGallery.modal.close")</button>
    @endslot


    <div class="js-uppy-dashboard-container" v-show="isMediaLibrary!==true">

    </div>



    <div class="mx-n2 mx-md-2">


        <div v-if="isMediaLibrary===true" class="card">
            <div class="card-body p-0">
                <div class="p-3 ">
                    <input v-model.trim="searchText" class="form-control" v-on:keyup="search" placeholder="Search...">

                </div>
                <div class="container">
                    <div class="row row-cols-xl-6 row-cols-lg-4 row-cols-md-3 row-cols-2  ">
                        <div class="col text-center mb-2" v-for="medium in media" v-bind:key="medium.id" :id="medium.id">
                            <div @dblclick="pickAndClose(medium)" @click="chooseMedium(medium)" :class="['mouse-pointer py-1',{'border-primary':isPicked(medium)}]"
                                 style="border: 2px solid transparent">
                                <div v-html="medium.thumb_html"></div>
                                <div class="small text-muted" v-html="mediumTitle(medium)"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-2">
                    <ul class="pagination pagination-sm justify-content-end m-0">
                        <li v-for="page in pagination" :class="['page-item',{active:page.active}, {disabled:page.url===null}]">
                            <a v-if="!page.active" class="page-link" :href="page.url" v-html="page.label" v-on:click.prevent="makeAjax(page.url)"></a>
                            <span v-else class="page-link" v-html="page.label"></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endcomponent

@push('scripts')

    <script>
        var app2 = new Vue({
            el: '#mediaLibraryModal',

            data: {
                input: {
                    name: '{{$inputName}}',
                    previewClass: '.fileinput-preview',
                    wrapperClass: '.fileinput',
                    buttonsClass: '.buttons',
                },
                choices: [],
                multiple: @json($multiple ?? false),
                isMediaLibrary:  @json($library ?? true),
                pagination: [],
                media: [],
                searchText: '',
            },
            mounted: function () {
                $('#mediaLibraryModal').on('show.bs.modal', (e) => {
                    this.makeAjax();
                });
                document.addEventListener('uppy-complete', (ev) => this.uploadFinished(ev))
            },
            methods: {
                uploadFinished(ev) {
                    this.makeAjax();
                    setTimeout(() => this.isMediaLibrary = true, 1500)
                },
                isPicked(medium) {
                    return this.choices.includes(medium.id);
                },
                mediumTitle(medium) {
                    return medium.title ? medium.title : medium.filename
                }, pickAndClose(medium) {
                    this.chooseMedium(medium);
                    this.pick();
                }, pick() {
                    let _this = this;
                    if (this.multiple) {
                        let btn = document.querySelector('[data-target="#' + this.$el.id + '"]');
                        _this.choices.forEach(id => {
                            btn.parentNode.querySelector('.js-addToCollection').click();
                            let inputs = document.querySelectorAll('input[name^="' + this.input.name + '"]');
                            let inputInstance = Array.prototype.slice.call(inputs).pop();
                            this.fillHtml(inputInstance, _this.media.find((medium) => medium.id === id))
                        });

                    } else {
                        _this.choices.forEach(id => {
                            let inputInstance = document.querySelector('input[name="' + this.input.name + '"]');
                            this.fillHtml(inputInstance, _this.media.find((medium) => medium.id === id))
                        });
                    }
                    _this.choices = [];
                    $('#mediaLibraryModal').modal('hide');
                },
                fillHtml(inputInstance, medium) {
                    inputInstance.value = medium.id;
                    let preview = inputInstance.parentNode.querySelector(this.input.previewClass)
                    let fileClasses = preview.dataset.imgclass;
                    preview.innerHTML = '<img src="' + medium.url + '" class="' + fileClasses + '"/>';

                    let buttonsWrapper = inputInstance.parentNode.querySelector(this.input.buttonsClass);

                    if (buttonsWrapper.querySelector('[href="' + medium.url + '" ]')) {
                        buttonsWrapper.querySelector('[href="' + medium.url + '" ]').href = medium.url;
                    } else {
                        buttonsWrapper.innerHTML += ' <a class=" btn btn-secondary btn-sm align-middle mb-1" role="button" href="' + medium.url + '" target="_blank"><i class="fas fa-eye"></i></a>'
                    }

                },
                chooseMedium(medium) {
                    let mediumId = medium.id;

                    if (this.isPicked(medium)) {
                        let index = this.choices.indexOf(mediumId);
                        this.choices.splice(index, 1);
                    } else {
                        if (!this.multiple) {
                            this.choices = [];
                        }
                        this.choices.push(mediumId)
                    }

                },
                search() {
                    this.makeAjax(null, {keyword: this.searchText})
                },
                makeAjax(url = null, dt = {}) {
                    let _this = this;
                    url = url || '{{route('admin.media.index')}}';
                    let data = Object.assign({only_data: true, extra_filters: {the_file_exists: true}}, dt);
                    BaseAdmin.makeAjax(url, 'GET', data, 0, function (data, textStatus) {
                        _this.media = data.records.data;
                        console.log(_this.media)
                        _this.pagination = data.records.links;
                        lazySizes.loader.checkElems();
                    });
                }
            },
        })


    </script>
@endpush
