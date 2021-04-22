let url = new URL(document.currentScript.src);
__webpack_public_path__ = url.href.substring(0, url.href.indexOf('js'));

import Vue from 'vue';

window.Vue = Vue;
if (process.env.NODE_ENV === 'production') {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;
}

//
// const app = new Vue({
//     el: '#app'
// });

import '../app'

    import('../_partials/offcanvas').then(src => src.init());
    import('../_admin/indexPages');
    import('pace-progress').then((src) => src.start({target: 'header.js-mainHeader'}));

window.addEventListener('DOMContentLoaded', (event) => {
    if (document.querySelector("#js-uppy-dashboard-container")) {
        import('../_partials/uppy');
    }
})
