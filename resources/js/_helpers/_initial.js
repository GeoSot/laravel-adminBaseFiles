import helpers from './helpers'


window.jsHelper = helpers;
window.BaseAdmin = window.BaseAdmin || {};
window.BaseAdmin.ajaxLoadWrappers = (wrappersToReload) =>
    import('../_partials/ajaxLoadWrappers').then(src => {
        src.ajaxLoadWrappers(wrappersToReload)
    });

window.BaseAdmin.makeAjax = (url, method, data, show_message, callback) =>
    import('../_admin/makeAjax').then(src => {
        src.makeAjax(url, method, data, show_message, callback)
    });

window.BaseAdmin.forms = window.BaseAdmin.forms || {};
window.BaseAdmin.forms.ajaxifyFormOnModal = (formSelector, modalSelector, wrapperToReload, destroyOnClose = false) =>
    import('jquery').then(src => {
        let $ = src.default
        import( '../_partials/forms/ajaxForms').then((src) => {
            src.ajaxifyFormOnModal(formSelector, modalSelector, wrapperToReload, destroyOnClose = false)
        });
    });


window.BaseAdmin.forms.ajaxify = (formSelector) => import( '../_partials/forms/ajaxForms').then(src => src.ajaxify(formSelector));

