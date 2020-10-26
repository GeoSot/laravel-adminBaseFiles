/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
const Swal = require('sweetalert2')

window.Swal = Swal;


window.addEventListener('DOMContentLoaded', (event) => {
    require('./_partials/forms/_index');

    if (document.querySelectorAll(".lazyload").length) {
        require('./_partials/lazyImages');
    }
    if (document.querySelectorAll(".UppyModalOpenerBtn, .UppyDashboardContainer").length) {
        require('./_partials/uppy');
    }
    jsHelper.base.triggerIsLoaded()
});

