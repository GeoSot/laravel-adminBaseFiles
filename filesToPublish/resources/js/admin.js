require('../app');
// var Turbolinks = require("turbolinks");
// /Turbolinks.start();


window.BaseAdmin = window.BaseAdmin || {};


require('../_partials/offcanvas');
require('../_admin/indexPages');
require('../_admin/stickButtonsBarsOnTop');
const pace = require('pace-progress');
pace.start({target: 'header.js-mainHeader'});


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
            return Swal.fire({
                title: data.title ? data.title : 'Error!',
                text: data.message,
                timer: 15000,
                icon: data.flag,
                timerProgressBar: true
            });
        }
        if (typeof callback !== 'function') {
            location.reload();
        }
    }).fail(function (jqXHR, textStatus) {
        data = jqXHR.responseJSON;
        return Swal.fire({
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

