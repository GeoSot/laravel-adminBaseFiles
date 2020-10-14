/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
window.BaseAdmin = window.BaseAdmin || {};
const swal = require('sweetalert');
const toastr = require('toastr');

require('./bootstrap')

window.swal = swal;
window.toastr = toastr;
window.moment = require('moment');
require('bootstrap-daterangepicker');


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


//Generates Unique IDs
BaseAdmin.uuid = () => {
    return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    )
};

BaseAdmin.debug = require('./_helpers/debugCustom');


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


window.addEventListener('DOMContentLoaded', (event) => {
    require('./_partials/forms/_index');
    require('./_partials/lazyImages');

});

