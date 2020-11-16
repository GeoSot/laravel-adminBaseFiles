let $;
BaseAdmin.forms = BaseAdmin.forms || {};
BaseAdmin.forms.initializedClass = 'formField-initialized';

const multiplyField = function () {
    $(document).on('click', '.js-addwToCollection', function (e) {
        e.preventDefault();
        let $container = $(this).closest('.js-collectionContainer');
        let $itemsContainer = $container.find('.js-collectionItems');
        //let count = $itemsContainer.children().length;
        let counter = parseInt($(this).attr('data-initial-count')) + 1;

        let proto = $(this).data('prototype').replace(/__NAME__/g, counter);
        $(this).attr('data-initial-count', counter);
        $itemsContainer.append(proto);
        $itemsContainer.find('textarea.withEditor').each(function () {
            let ev = new CustomEvent('baseAdmin:initTextEditor', {detail: this});
            document.dispatchEvent(ev);
            // window.BaseAdmin.initTextEditor(this);
        });
        $(document).trigger('multiplyField', {counter: counter, elem: proto});
    });
};
const initColorPicker = function () {
    $('[data-toggle="colorPicker"]').each(function (e) {
        $(this).colorpicker();
    });
};
const initSelect2 = function ($el) {

    $.fn.select2.defaults.set("theme", "bootstrap");
    $.fn.select2.defaults.set("width", '100%');
    $.fn.select2.defaults.set("dropdownAutoWidth", true);


    document.addEventListener('baseAdmin:ajaxLoadWrappers', () => {
        $($el).find('select').each(function () {
            let $sel = $(this);
            if ($sel.is('[multiple]') || $sel.hasClass('select2')) {
                $sel.select2();
            }
        });
    })
    let ev = new CustomEvent('baseAdmin:ajaxLoadWrappers', {detail: null});
    document.dispatchEvent(ev)


};
const initSortable = function () {
    $('.sortableWrapper').each(function () {
        let sortable = Sortable.create(this, {
            group: jsHelper.uuid(),
            handle: ".sortingHandler",
            draggable: ".sortableItem",
        });
    });
};
const removeParent = function () {
    $(document).on('click', '[data-toggle="removeParent"]', function (e) {
        $(this).closest($(this).data('target')).remove();
    });
}


const Init = () => {
    import('jquery').then(src => {
        $ = src.default

        multiplyField();
        removeParent();
        if (document.querySelectorAll('[data-toggle="dateRangeCalendar"], [data-toggle="calendar"]').length) {

            window.moment = import('moment').then((src) => {
                window.moment = src.default;
                import('bootstrap-daterangepicker').then(src => {
                    const DateRange = import('./fields/DateRange').then(src => src.init())
                    const SingleDateRange = import('./fields/SingleDateRange').then(src => src.init())
                });
            });

        }
        if (document.querySelectorAll('[data-toggle="timePicker"]').length) {
            const TimePicker = import('./fields/TimePicker').then(src => src.init())
        }

        if (document.querySelectorAll('[data-toggle="imageInput"], [data-toggle="fileInput"]').length) {
            const ImagePicker = import('./fields/ImagePicker').then(src => src.init())
        }


        if (document.querySelectorAll('[data-toggle="colorPicker"]').length) {
            import('bootstrap-colorpicker').then(src => {
                initColorPicker();
            })
        }


        if (document.querySelectorAll('.sortableWrapper').length) {
            window.Sortable = import('sortablejs').then(() => {
                initSortable();
            });

        }
        if (document.querySelectorAll('select').length) {
            import('select2').then(() => {
                initSelect2($('body'));
            })

        }
    });
};

export {Init};


