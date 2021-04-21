import './_helpers/_initial'

let url = new URL(document.currentScript.src);
__webpack_public_path__ = url.href.substring(0, url.href.indexOf('js'));

import ('./bootstrap')

import('sweetalert2').then(src => {
    window.Swal = src.default
})

import('./_partials/lazyImages');

