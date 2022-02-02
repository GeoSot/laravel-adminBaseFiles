const $tableForm = () => $('#tableForm');


const ChangeEnableDisableStatus = function (url, type, data, show_message, keyword, callback = null) {

    data.status = (keyword === 'enable') ? 1 : 0;

    import ('../_admin/makeAjax').then(src => {
        src.makeAjax(url, type, data, show_message, callback);
    })
};
const allOtherActions = function (url, type, data, show_message, keyword, callback = null) {
    return showMessage({
        title: $('#confirm_' + keyword).text(),
        text: $('#confirm_' + keyword + '_msg').text(),
        icon: 'warning',
    }, (continueAction) => {
        if (continueAction.isConfirmed) {
            import ('../_admin/makeAjax').then(src => {
                src.makeAjax(url, type, data, show_message, callback);
            })
        }
    })
};

const HandleAction = function (elem) {
    let items = $('[id^=record_]:checked').map(function () {
        return $(this).val();
    }).get();
    if (items.length === 0) {
        return showMessage({
            title: $('#no_record_selected').text(),
            text: $('#no_record_selected_msg').text(),
            icon: 'warning',
        });
    }
    let url = $(elem).data('url');
    let type = $(elem).data('method');
    let data = {
        ids: items
    };
    let show_message = 0;
    let keyword = $(elem).data('keyword');
    let redirectCallBack = null;
    let redirect = $(elem).data('after_save_redirect_to');

    if (redirect) {
        redirectCallBack = function () {
            window.location.href = redirect;
        }
    }

    if (keyword === 'enable' || keyword === 'disable') {
        return ChangeEnableDisableStatus(url, type, data, show_message, keyword, redirectCallBack);
    } else {
        return allOtherActions(url, type, data, show_message, keyword, redirectCallBack);
    }

}

import('jquery').then(src => {
    let $ = src.default

    $('[data-toggle="select-all"]').change(function (e) {
        let targets = $(this).data('target');
        $('[data-select-all="' + targets + '"]').prop('checked', $(this).prop("checked"));
    });

    $('[data-toggle="listing-actions"]').click(function () {
        HandleAction(this);
    });
    $('[data-change="js-submit-form"]').change(function () {
        if ($(this).is('[name="trashed"]')) {
            $('[name="trashed"]').val($(this).val());
        }
        $tableForm().submit();
    });


    $('[data-click="js-submit-form"]').click(function () {
        $tableForm().submit();
    });
    $('[data-sort]').click(function () {
        $('[name="sort"]').val($(this).data('sort'));
        $('[name="order_by"]').val($(this).data('order'));

        $tableForm().submit();
    });

//AJAX TOGGLE ENABLE / DISABLE
    $('[data-toggle="listing-actions-status"]').click(function () {
        const $btn = $(this);
        let url = $btn.data('url');
        let type = $btn.data('method');
        let keyword = $btn.data('keyword');
        let data = {
            onlyJson: true,
            ids: [$btn.data('id')]
        };

        ChangeEnableDisableStatus(url, type, data, 0, keyword, function (responseData, textStatus) {

            if (textStatus !== 'success') {
                return;
            }

            if (keyword === 'enable') {
                $btn.data('keyword', 'disable');
                $btn.removeClass('btn-outline-danger').addClass('btn-outline-success');
                $btn.find('.fas').removeClass('fa-times').addClass('fa-check');

            } else {
                $btn.data('keyword', 'enable');
                $btn.removeClass('btn-outline-success').addClass('btn-outline-danger');
                $btn.find('.fas').removeClass('fa-check').addClass('fa-times');
            }
        })

    });
});


const showMessage = (data, callback) => {
    import('sweetalert2').then(src => {
        src.default.fire({
            title: data.title || '',
            text: data.text,
            timer: 2000,
            icon: data.icon,
            timerProgressBar: true
        }).then(function (continueAction) {
            if (typeof callback === 'function') {
                callback(continueAction);
            }
        });
    })
}
