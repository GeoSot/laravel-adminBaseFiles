let url = new URL(document.currentScript.src);
__webpack_public_path__ = url.href.substring(0, url.href.indexOf('js'));

import Vue from 'vue';
import '../app'

window.Vue = Vue;
if (process.env.NODE_ENV === 'production') {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;
}

Vue.component('media-library', () => import('./../components/mediaLibrary.vue'))
Vue.component('uppy', () => import('./../components/uppy.vue'))
const app = new Vue({
    el: '#app'
});


import('../_partials/offcanvas').then(src => src.init());
import('../_admin/indexPages');
import('pace-progress').then((src) => src.start({ target: 'header.js-mainHeader' }));
