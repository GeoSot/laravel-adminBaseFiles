require('../app');

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

// var Turbolinks = require("turbolinks");
// /Turbolinks.start();


window.BaseAdmin = window.BaseAdmin || {};


window.addEventListener('DOMContentLoaded', (event) => {
    require('../_partials/offcanvas');
    require('../_admin/indexPages');
    const pace = require('pace-progress');
    pace.start({target: 'header.js-mainHeader'});
});

require('../_partials/ajaxLoadWrappers');


$('[data-toggle="select-all"]').change(function (e) {
    let targets = $(this).data('target');
    $('[data-select-all="' + targets + '"]').prop('checked', $(this).prop("checked"));
});


BaseAdmin.makeAjax = function (url, type, data, show_message, callback) {
    return jQuery.ajax({
        url: url,
        type: type,
        data: data,
        dataType: 'JSON',
    }).done(function (data) {
        if (data.flag === 'error' || show_message == 1) {
            return  Swal.fire({
                title: data.title ? data.title : 'Error!',
                text: data.message,
                timer: 15000,
                icon: data.flag,
                timerProgressBar: true
            })
        }
        if (typeof callback !== 'function') {
            location.reload();
        }
    }).fail(function (jqXHR, textStatus) {
        data = jqXHR.responseJSON;
        return  Swal.fire({
            title: data.title ? data.title : 'Error!',
            text: data.message,
            timer: 15000,
            icon: 'error',
            timerProgressBar: true
        })

    }).always(function (data, textStatus) {
        if (typeof callback === 'function') {
            callback(data, textStatus);
        }
    })
};

