require('../app');


window.BaseAdmin = window.BaseAdmin || {};


require('../_partials/offcanvas');
require('../_admin/indexPages');
require('../_admin/stickButtonsBarsOnTop');
const pace = require('pace-progress');
pace.start({target:'header.js-mainHeader'});


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
            return toastr[data.flag](data.message, data.title, {timeOut: 15000});
        }
        if (typeof callback !== 'function') {
            location.reload();
        }
    }).fail(function (jqXHR, textStatus) {
        data = jqXHR.responseJSON;
        return toastr['error'](data.message, data.title?data.title:'Error', {timeOut: 15000});

    }).always(function (data, textStatus) {
        if (typeof callback === 'function') {
            callback(data, textStatus);
        }
    })
};

