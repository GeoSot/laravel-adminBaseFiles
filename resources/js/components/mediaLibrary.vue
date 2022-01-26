<template>
    <div class="d-inline-block">
        <button @click="isShown=true" type="button" :class="['btn btn-outline-primary btn-sm  ml-auto',{'mb-1':isGrouped===false}]">
         <span v-if="isMediaLibrary" class="text-muted">
             <i class="fas fa-photo-video"></i>
           </span>
            <span v-if="!isMediaLibrary" class="text-muted">
           <i class="fas fa-upload"></i>
         </span>
        </button>
        <div :class="['modal',{'show':isShown} ,{'d-block':isShown}]" tabindex="-1" role="dialog" aria-labelledby="..." aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-content">

                        <div class="modal-header">
                            <div class="modal-title h5">
                            <span v-if="isMediaLibrary" class="text-muted">
                                Media Library
                            </span>
                                <span v-else class="text-muted">
                               Upload File/s
                            </span>
                                <button class="btn btn-outline-info ml-2 px-3" type="button" :disabled="isMediaLibrary" @click="isMediaLibrary=true"
                                        title="Media Library">
                                    <i class="fas fa-photo-video"></i>
                                </button>
                                <button class="btn btn-outline-info ml-2 px-3" type="button" :disabled="!isMediaLibrary" @click="isMediaLibrary=false"
                                        title="Upload File/s">
                                    <i class="fas fa-upload"></i>
                                </button>
                            </div>
                            <button type="button" class="close" @click="isShown=false" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>


                        <div class="modal-body">
                            <uppy :options="this.uppyOptions" :is-shown="isMediaLibrary!==true"
                                  :class="['justify-content-center',{'d-flex':!isMediaLibrary}]"
                                  v-show="isMediaLibrary!==true">
                                >
                            </uppy>


                            <div class="mx-n2 mx-md-2">


                                <div v-if="isMediaLibrary" class="card">
                                    <div class="card-body p-0">
                                        <div class="p-3 ">
                                            <input v-model.trim="searchText" class="form-control" v-on:keyup="search" placeholder="Search...">
                                        </div>
                                        <div class="container">
                                            <div class="row row-cols-xl-6 row-cols-lg-4 row-cols-md-3 row-cols-2  ">
                                                <div class="col text-center mb-2" v-for="medium in media" v-bind:key="medium.id" :id="medium.id">
                                                    <div @dblclick="pickAndClose(medium)" @click="chooseMedium(medium)"
                                                         :class="['mouse-pointer py-1',{'border-primary':isPicked(medium)}]"
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

                        </div>


                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" v-if="choices.length>0 && isMediaLibrary" @click="pick">
                                Pick Selected
                            </button>
                            <button type="button" class="btn btn-secondary" @click="isShown=false">Close</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>

export default {
    name: 'media-library',
    props: {
        mediaRoute: String,
        inputName: String,
        multiple: Boolean,
        isLibrary: Boolean,
        isGrouped: Boolean,
        uppyOptions: Object,
    },
    data: function () {
        return {
            isShown: false,
            input: {
                previewClass: '.fileinput-preview',
                wrapperClass: '.fileinput',
                buttonsClass: '.buttons',
            },
            isMediaLibrary: this.isLibrary,
            choices: [],
            pagination: [],
            media: [],
            searchText: '',
        }
    },
    mounted: function () {
        this.makeAjax();
        document.addEventListener('uppy-complete', (ev) => this.uploadFinished(ev))
    },
    watch: {
        isShown: function (newState, oldState) {
            if (newState) {
                const backDrop = document.createElement('div');
                backDrop.classList.add('modal-backdrop', 'show')
                document.body.appendChild(backDrop)
                return
            }
            this.searchText = '';
            this.makeAjax();
            document.querySelector('.modal-backdrop').remove()
        }
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
        },
        pickAndClose(medium) {
            this.chooseMedium(medium);
            this.pick();
        },
        pick() {
            if (this.multiple) {
                let btn = this.$el.closest('.js-collectionContainer').querySelector('.js-addToCollection');
                this.choices.forEach(id => {
                    btn.click();
                    let inputs = document.querySelectorAll('input[name^="' + this.inputName + '"]');
                    let inputInstance = Array.from(inputs).pop();
                    this.fillHtml(inputInstance, this.media.find((medium) => medium.id === id))
                });

            } else {
                this.choices.forEach(id => {
                    let inputInstance = document.querySelector('input[name="' + this.inputName + '"]');
                    this.fillHtml(inputInstance, this.media.find((medium) => medium.id === id))
                });
            }
            this.choices = [];
            this.isShown = false
        },
        fillHtml(inputInstance, medium) {
            // inputInstance.value = medium.id;
            let preview = inputInstance.parentNode.querySelector(this.input.previewClass)
            if (preview) {
                let fileClasses = preview.dataset.imgclass;
                preview.innerHTML = '<img src="' + medium.url + '" class="' + fileClasses + '"/>';
            }

            let buttonsWrapper = inputInstance.parentNode.querySelector(this.input.buttonsClass);

            if (buttonsWrapper.querySelector('.js-show')) {
                buttonsWrapper.querySelector('.js-show').href = medium.url;
            } else {
                let classString = this.isGrouped ? '' : 'mb-1 ml-1';
                buttonsWrapper.insertAdjacentHTML('beforeend', `<a class="${classString} js-show btn btn-secondary btn-sm align-middle" role="button" href="${medium.url}" target="_blank"><i class="fas fa-eye"></i></a>`)
            }
           document.dispatchEvent(new CustomEvent('baseAdmin.fileInput.changed',{
               bubbles: true,
               cancelable: false,
                detail:{
                    addInputVal: medium.id,
                    input: inputInstance,
                    filled:true,
                    name:`${medium.filename}.${medium.extension}`
                }
            }))


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
            setTimeout(() => {
                this.makeAjax(null, { keyword: this.searchText })
            }, 100);
        },
        makeAjax(url = null, dt = {}) {
            let _this = this;
            url = url || this.mediaRoute;
            let data = Object.assign({ only_data: true, num_of_items: 10, extra_filters: { the_file_exists: true } }, dt);
            BaseAdmin.makeAjax(url, 'GET', data, 0, function (data, textStatus) {
                _this.media = data.records.data;
                _this.pagination = data.records.links;
            });
        }
    },

}


</script>
