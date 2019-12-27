window.BaseAdmin = window.BaseAdmin || {};


let $tableForm = $('#tableForm');
let translatedWords = $('.js-translatedWords');


BaseAdmin.listingActions = {
    ChangeEnableDisableStatus: function (url, type, data, show_message, keyword, callback = null) {

        data.status = (keyword === 'enable') ? 1 : 0;

        BaseAdmin.makeAjax(url, type, data, show_message, callback);
    },
    allOtherActions: function (url, type, data, show_message, keyword, callback = null) {
        //   console.log(keyword)
        swal({
            title: $('#confirm_' + keyword).text(),
            text: $('#confirm_' + keyword + '_msg').text(),
            icon: 'warning',
            buttons: true,
            //dangerMode : true,
        }).then(function (continueAction) {
            if (continueAction) {
                BaseAdmin.makeAjax(url, type, data, show_message, callback);
            }
        });
    },
    HandleAction: function (elem) {
        let items = $('[id^=record_]:checked').map(function () {
            return $(this).val();
        }).get();
        if (items.length === 0) {
            return toastr.warning(jQuery('#no_record_selected_msg').text(), jQuery('#no_record_selected').text());
        }
        let url = $(elem).data('url');
        let type = $(elem).data('method');
        let data = {
            ids: items
        };
        let show_message = 0;
        let keyword = $(elem).data('keyword');
        if (keyword === 'enable' || keyword === 'disable') {
            return BaseAdmin.listingActions.ChangeEnableDisableStatus(url, type, data, show_message, keyword);
        } else {
            return BaseAdmin.listingActions.allOtherActions(url, type, data, show_message, keyword);
        }

    }
};
$('[data-toggle="listing-actions"]').click(function () {
    BaseAdmin.listingActions.HandleAction(this);
});
$('[data-change="js-submit-form"]').change(function () {
    if ($(this).is('[name="trashed"]')) {
        $('[name="trashed"]').val($(this).val());
    }
    $tableForm.submit();
});


$('[data-click="js-submit-form"]').click(function () {
    $tableForm.submit();
});
$('[data-sort]').click(function () {
    $('[name="sort"]').val($(this).data('sort'));
    $('[name="order_by"]').val($(this).data('order'));

    $tableForm.submit();
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

    BaseAdmin.listingActions.ChangeEnableDisableStatus(url, type, data, 0, keyword, function (responseData, textStatus) {

        if (textStatus !== 'success') {
            return;
        }

        if (keyword === 'enable') {
            $btn.data('keyword', 'disable');
            $btn.removeClass('btn-outline-danger').addClass('btn-outline-success');
            $btn.find('.fa').removeClass('fa-close').addClass('fa-check');

        } else {
            $btn.data('keyword', 'enable');
            $btn.removeClass('btn-outline-success').addClass('btn-outline-danger');
            $btn.find('.fa').removeClass('fa-check').addClass('fa-close');
        }
    })

});

