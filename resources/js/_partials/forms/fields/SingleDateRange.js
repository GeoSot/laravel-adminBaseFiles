let $;
const formsInitializedClass = BaseAdmin.forms.initializedClass;

const selectors = {
    parent: '[data-toggle="calendar"]',
    clear: '[data-clear="calendar"]'
}

const init = () => {
    import('jquery').then(src => {
        $ = src.default
        $(document).on('click', selectors.parent + ':not(.' + formsInitializedClass + ')', function () {
            internalInit($(this));
        });
        internalInit();
        $(document).on('click', selectors.clear, function () {
            $(selectors.parent).find('input').each(function () {
                $(this).val('');
            })
        });
    });
}

const internalInit = function ($el = null) {

    $(selectors.parent).not('.' + formsInitializedClass).addClass(formsInitializedClass).each(function (i, elem) {
        if ($(elem).find('input[name$="_formatted"]').first().is(':disabled')) {
            return;
        }
        $(elem).addClass(BaseAdmin.forms.initializedClass);
        let val = $(elem).find('input').not('*_formatted').first().val();
        let value = (val) ? moment(val) : moment();
        $(elem).daterangepicker({
            //parentEl:$parent,
            showDropdowns: true,
            singleDatePicker: true,
            startDate: value,
            autoApply: true,
            // autoUpdateInput: false,
            drops: "auto",
            timePicker: ($(elem).attr('data-locale').match(/H:|h:|mm/g) != null),
            timePicker24Hour: true,
            minYear: 1930,
            locale: {
                format: $(elem).attr('data-locale')
            }

        }, _callback);
    });
    if ($el) {
        $el.click();
    }

}

const _callback = function (start, end, label) {
    let $parent = $(this.element);
    let $input = $('[name="' + $parent.data('name') + '"]');
    let $formatted_input = $('[name="' + $parent.data('name') + '_formatted"]');

    if ($parent.data('name').indexOf(']') > 0) {//If is inside array
        $formatted_input = $('[name="' + $parent.data('name').replace(']', '_formatted]') + '"]');
    }

    $formatted_input.val(start.format($parent.attr('data-locale')));
    $input.val(start.format('YYYY-MM-DD HH:mm:ss'));
}


export {init};

