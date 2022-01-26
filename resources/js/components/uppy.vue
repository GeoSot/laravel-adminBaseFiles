<template>
    <div>

    </div>
</template>

<script>
import { Core, Dashboard, Tus } from 'uppy'
import ImageEditor from "@uppy/image-editor";
import '@uppy/core/dist/style.css'
import '@uppy/dashboard/dist/style.css'
import '@uppy/image-editor/dist/style.css';

export default {
    props: {
        options: Object,
        isShown: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            instance: null,
        }
    },
    watch: {
        isShown: function (newState, oldState) {
            if (newState && !this.instance) {
                this.initializeUppy()
            }
        }
    },
    computed: {
        endpoint() {
            return this.options.endpoint
        },
        restrictions() {
            return this.options.restrictions
        }
    },
    beforeDestroy() {
        this.instance.close()
    },
    methods: {
        computeWidth() {
            let el = this.$el
            const getWidth = el => el.getBoundingClientRect().width

            while (!getWidth(el)) {
                el = el.parentNode
                console.log(el)
            }
            const width = getWidth(el);
            return width * .95
        },
        registerDashboard() {
            const width = this.computeWidth();
            this.instance.use(Dashboard, {
                // trigger: '.js-uppy-trigger-btn',
                inline: true,
                target: this.$el,
                replaceTargetContent: true,
                showProgressDetails: true,
                width: width,
                height: width / 1.7,
                metaFields: [
                    { id: 'name', name: 'Name', placeholder: 'file name' },
                    { id: 'caption', name: 'Caption', placeholder: 'describe what the image is about' },
                    { id: 'keywords', name: 'Keywords' }],
                browserBackButtonClose: true,
                // showLinkToFileUploadResult: false,
            });
        },
        registerTus() {
            this.instance.use(Tus, {
                endpoint: this.endpoint,
                withCredentials: true,
                headers: this.getHeaders(),
                resume: true,
                retryDelays: [0],//[0, 1000, 3000, 5000],
            });
        },
        getHeaders() {
            return {
                'X-CSRF-Token': document.head.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        },
        registerEvents() {
            this.instance.on('complete', result => {
                if (result.failed.length) {
                    return;
                }
                const event = new CustomEvent("uppy-complete", {
                    detail: {
                        result: result
                    }
                });
                document.dispatchEvent(event);
            });

            this.instance.on('upload-success', (file, data, uploadUrl) => {
                this.instance.setFileState(file.id, { uploadURL: 'http://www.blah.com' });
            });

            this.instance.on('upload-error', (file, error) => {
                /* this.instance.info({
                      message: error,
                      details: 'test error message',
                  }, 'error', 10000)*/
            })

            this.instance.on('error', (error) => {
                console.log('uppy error', error)
            })
        },
        initializeUppy() {
            this.instance = new Core({
                debug: process.env.NODE_ENV !== 'production',
                autoProceed: false,
                allowMultipleUploads: true,
                restrictions: this.restrictions
            })
            this.registerDashboard();
            this.instance.use(ImageEditor, { target: Dashboard });
            this.registerTus();
            this.registerEvents();
        }

    },
    mounted() {
        if (this.isShown && !this.instance) {
            this.initializeUppy()
        }

    },
}
</script>

