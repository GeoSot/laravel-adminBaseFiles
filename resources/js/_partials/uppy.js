import {Core, Dashboard,Tus, Url} from 'uppy'

import 'uppy/dist/uppy.min.css'
import '@uppy/image-editor/dist/style.css'

const endPoint = Laravel.uppy.endpoint;
const headers = {
    'X-CSRF-Token': document.head.querySelector('meta[name="csrf-token"]')
}


const ImageEditor = require('@uppy/image-editor')

const uppy = new Core({
    debug: Laravel.debug,
    autoProceed: false,
    allowMultipleUploads: true,
    restrictions: Laravel.uppy.restrictions
})
uppy.use(Dashboard, {
    // trigger: '.UppyModalOpenerBtn',
    inline: Laravel.uppy.inline,
    target: '.UppyDashboardContainer',
    replaceTargetContent: true,
    showProgressDetails: true,
    note: 'Images and video only, 2–3 files, up to 1 MB',
    height: Laravel.uppy.height,
    metaFields: Laravel.uppy.metaFields,
    browserBackButtonClose: true
});
uppy.use(ImageEditor, {target: Dashboard});
// uppy.use(Uppy.FileInput, {endpoint: endPoint})
uppy.use(Tus, {endpoint: endPoint, headers: headers})
uppy.use(Url, {
    endpoint: endPoint,
    headers: headers,
    companionUrl: 'https://companion.uppy.io/',
})

// Function for displaying uploaded files
const onUploadSuccess = (elForUploadedFiles) =>
    (file, response) => {
        const url = response.uploadURL
        const fileName = file.name

        const li = document.createElement('li')
        const a = document.createElement('a')
        a.href = url
        a.target = '_blank'
        a.appendChild(document.createTextNode(fileName))
        li.appendChild(a)

        document.querySelector(elForUploadedFiles).appendChild(li)
    }

uppy.on('complete', result => {
    console.log('successful files:', result.successful)
    console.log('failed files:', result.failed)
}).on('upload-success', onUploadSuccess('.example-one .uploaded-files ol'))

uppy.getFiles().forEach(file => {

    if(file.source == "remote") {
        // source = remote is how I "mark" them previoulsy
        this.uppy.setFileState(file.id, {
            progress: { uploadComplete: true, uploadStarted: false }
        });
    }

});
