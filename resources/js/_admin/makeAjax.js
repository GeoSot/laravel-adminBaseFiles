const makeAjax = (url, method = 'get', data = {}, show_message = 0, callback = null) => {
    return import('jquery').then(src => {
        let $ = src.default
        return $.ajax({
            url: url,
            type: method,
            data: data,
            dataType: 'JSON',
        }).done(function (data) {
            if (data.flag === 'error' || show_message == 1) {
                return showMessage(data);
            }
            if (typeof callback !== 'function') {
                location.reload();
            }
        }).fail(function (jqXHR, textStatus) {
            data = jqXHR.responseJSON;
            return showMessage(data)

        }).always(function (data, textStatus) {
            if (typeof callback === 'function') {
                callback(data, textStatus);
            }
        })

    })
};


const showMessage = (data) => {
    import('sweetalert2').then(src => {
        src.default.fire({
            title: data.title ? data.title : 'Error!',
            text: data.message,
            timer: 1500,
            icon: 'error',
            timerProgressBar: true
        })
    })
}

export {makeAjax}

