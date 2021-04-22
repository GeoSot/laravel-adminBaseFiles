import {Core, Dashboard, Tus} from 'uppy'
import ImageEditor from '@uppy/image-editor'

const target = '#js-uppy-dashboard-container';
const $target = document.querySelector(target);

if ($target) {


    const headers = {
        'X-CSRF-Token': document.head.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json'
    }

    const options = JSON.parse($target.dataset.options);

    const endPoint = () => options.endPoint;

    const uppy = new Core({
        debug: process.env.NODE_ENV !== 'production',
        autoProceed: false,
        allowMultipleUploads: true,
        restrictions: options.restrictions
    })
    uppy.use(Dashboard, {
        // trigger: '.js-uppy-trigger-btn',
        inline: true,
        target: target,
        replaceTargetContent: true,
        showProgressDetails: true,
        height: 500,
        metaFields: [
            {id: 'name', name: 'Name', placeholder: 'file name'},
            {id: 'caption', name: 'Caption', placeholder: 'describe what the image is about'},
            {id: 'keywords', name: 'Keywords'}],
        browserBackButtonClose: true,
        // showLinkToFileUploadResult: false,
    });
    uppy.use(ImageEditor, {target: Dashboard});

    uppy.use(Tus, {
        endpoint: endPoint(),
        withCredentials: true,
        headers: headers,
        resume: true,
        autoRetry: true,
        retryDelays: [0],//[0, 1000, 3000, 5000],
    });

    uppy.on('complete', result => {

        if (result.failed.length) {
            return;
        }
        var event = new CustomEvent("uppy-complete", {
            detail: {
                result: result
            }
        });
        document.dispatchEvent(event);
    });
    uppy.on('upload-success', (file, data, uploadUrl) => {
        uppy.setFileState(file.id, {uploadURL: 'http://www.blah.com'});
    });
    uppy.on('upload-error', (file, error) => {
        /*  uppy.info({
              message: error,
              details: 'test error message',
          }, 'error', 10000)*/
    })

    uppy.on('error', (error) => {
        console.log('uppy error', error)
    })
}
