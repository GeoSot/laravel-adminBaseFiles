window.aw = window.aw || {};
aw.ajaxLoadWrappers = function (wrappersToReload) {
    let wrappersArray = wrappersToReload.split(',');

    if ($(wrappersArray[0]).length) {
        $(wrappersArray[0]).load(location.href + ' ' + wrappersArray[0] + ' > *', function (response) {
            wrappersArray.shift();

            $.each(wrappersArray, function (key, selector) {
                let $selector = $(selector);
                $selector.html($(response).find(selector).html());
                $selector.trigger('aw.ajaxLoadWrappers');
                aw.handleSelectInputs($selector);
            });
        });
    }
};
