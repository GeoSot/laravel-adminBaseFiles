/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
if (process.env.MIX_APP_ENV === 'production') {
    Vue.config.devtools = false;
    Vue.config.debug = false;
    Vue.config.silent = true;

}

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});

window.BaseAdmin = window.BaseAdmin || {};

//Generates Unique IDs
BaseAdmin.uuid = () => {
    return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    )
};

BaseAdmin.debug = require('./_partials/debugCustom');

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
});

window.swal = require('sweetalert');
window.toastr = require('toastr');
toastr.options = {
    "closeButton": true,
    "debug": false,
    "positionClass": "toast-bottom-right",
    "onclick": null,
    "showDuration": "1000",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "swing",
    "showMethod": "slideDown",
    "hideMethod": "hide"
};


window.moment = require('moment');
require('bootstrap-daterangepicker');
const select2 = require('select2');

$.fn.select2.defaults.set("theme", "bootstrap");
$.fn.select2.defaults.set("width", '100%');
$.fn.select2.defaults.set("dropdownAutoWidth", true);
// dropdownParent: $(this).parent()


BaseAdmin.handleSelectInputs = ($el) => {
    if ($el instanceof jQuery) {
        $($el).find('select').each(function () {
            let $sel = $(this);
            if ($sel.is('[multiple]') || $sel.hasClass('select2')) {
                $sel.select2();
            }
        });
    }
};
BaseAdmin.handleSelectInputs($('body'));


require('./_partials/forms/_index');
require('./_partials/lazyImages');
