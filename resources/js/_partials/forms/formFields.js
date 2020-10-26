BaseAdmin.forms = BaseAdmin.forms || {};
BaseAdmin.forms.initializedClass = 'formField-initialized';


BaseAdmin.forms.fields = {
    Instances: {
        timePickers: {}
    },

    multiplyField: function () {
        $(document).on('click', '.js-addToCollection', function (e) {
            e.preventDefault();
            let $container = $(this).closest('.js-collectionContainer');
            let $itemsContainer = $container.find('.js-collectionItems');
            //let count = $itemsContainer.children().length;
            let counter = parseInt($(this).attr('data-initial-count')) + 1;

            let proto = $(this).data('prototype').replace(/__NAME__/g, counter);
            $(this).attr('data-initial-count', counter);
            $itemsContainer.append(proto);
            $itemsContainer.find('textarea.withEditor').each(function () {
                BaseAdmin.initTextEditor(this);
            });
        });
    }, colorPicker: function () {
        $('[data-toggle="colorPicker"]').each(function (e) {
            $(this).colorpicker();
        });
    }, select2: function ($el) {
        if (!$el instanceof jQuery) {
            return;
        }
        $($el).find('select').each(function () {
            let $sel = $(this);
            if ($sel.is('[multiple]') || $sel.hasClass('select2')) {
                $sel.select2();
            }
        });


    }, sortable: function () {
        $('.sortableWrapper').each(function () {
            let sortable = Sortable.create(this, {
                group: jsHelper.uuid(),
                handle: ".sortingHandler",
                draggable: ".sortableItem",
            });
        });
    }, removeParent: function () {
        $(document).on('click', '[data-toggle="removeParent"]', function (e) {
            $(this).closest($(this).data('target')).remove();
        });
    }
};

BaseAdmin.forms.fields.Init = function () {
    if (document.querySelectorAll('[data-toggle="dateRangeCalendar"], [data-toggle="calendar"]').length) {

        window.moment = require('moment');
        require('bootstrap-daterangepicker');
        const DateRange = require('./fields/DateRange').default()
        const SingleDateRange = require('./fields/SingleDateRange').default()
    }

    const TimePicker = require('./fields/TimePicker').default()
    const ImagePicker = require('./fields/ImagePicker').default()

    BaseAdmin.forms.fields.multiplyField();

    if (document.querySelectorAll('[data-toggle="colorPicker"]').length) {
        require('bootstrap-colorpicker');
        BaseAdmin.forms.fields.colorPicker();
    }

    BaseAdmin.forms.fields.removeParent();
    if (document.querySelectorAll('.sortableWrapper').length) {
        window.Sortable = require('sortablejs');
        BaseAdmin.forms.fields.sortable();
    }
    if (document.querySelectorAll('select').length) {
        const select2 = require('select2');

        $.fn.select2.defaults.set("theme", "bootstrap");
        $.fn.select2.defaults.set("width", '100%');
        $.fn.select2.defaults.set("dropdownAutoWidth", true);
        BaseAdmin.forms.fields.select2($('body'));
    }

};


