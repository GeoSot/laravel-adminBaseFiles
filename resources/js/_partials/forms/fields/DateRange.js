const formsInitializedClass = BaseAdmin.forms.initializedClass;

const selectors = {
    parent: '[data-toggle="dateRangeCalendar"]',
    clear: '[data-clear="dateRangeCalendar"]'
}


const init = () => {
    $(document).on('click', selectors.parent + ':not(.' + formsInitializedClass + ')', function () {
        internalInit($(this))
    });
    internalInit();
    $(document).on('click', selectors.clear, function () {
        $(selectors.parent).find('input').each(function () {
            $(this).val('');
        })
    });
}

const internalInit = () => {

    $(selectors.parent).not('.' + formsInitializedClass).addClass(formsInitializedClass).each(function (i, elem) {
        if ($(elem).find('input[name$="_formatted"]').first().is(':disabled')) {
            return;
        }
        let val = $(elem).find('input').not('*_formatted').first().val();
        let value = (val) ? moment(val) : moment();
        $(elem).daterangepicker({
            //parentEl:$parent,
            showDropdowns: true,
            autoApply: true,
            startDate: value,
            // autoUpdateInput: false,
            timePicker: ($(elem).attr('data-locale').match(/H:|h:|mm/g) != null),
            timePicker24Hour: true,
            minYear: 1930,
            locale: {
                format: $(elem).attr('data-locale')
            },
            ranges: getRanges(),
            linkedCalendars: false,
            alwaysShowCalendars: true,

        }, function (start, end, label) {
            callback(start, end, label);
        });
    });

}

const callback = (start, end, label) => {
    let $parent = $(this.element);
    let $inputStart = $('[name="' + $parent.data('name') + '[start]"]');
    let $inputEnd = $('[name="' + $parent.data('name') + '[end]"]');
    let $formatted_input = $('[name="' + $parent.data('name') + '_formatted"]');

    if ($parent.data('name').indexOf(']') > 0) {//If is inside array
        $formatted_input = $('[name="' + $parent.data('name').replace(']', '_formatted]') + '"]');
    }
    $formatted_input.val(start.format($parent.attr('data-locale')) + ' - ' + end.format($parent.attr('data-locale')));

    $inputStart.val(start.format('YYYY-MM-DD HH:mm:ss'));
    $inputEnd.val(end.format('YYYY-MM-DD HH:mm:ss'));
}

const getRanges = () => {
    return {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    };
}

export default init;

