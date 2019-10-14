window.BaseAdmin = window.BaseAdmin || {};
BaseAdminajaxLoadWrappers = function (wrappersToReload) {
    let wrappersArray = wrappersToReload.split(',');

    if ($(wrappersArray[0]).length) {
        $(wrappersArray[0]).load(location.href + ' ' + wrappersArray[0] + ' > *', function (response) {
            wrappersArray.shift();

            $.each(wrappersArray, function (key, selector) {
                let $selector = $(selector);
                $selector.html($(response).find(selector).html());
                $selector.trigger('BaseAdminajaxLoadWrappers');
                BaseAdminhandleSelectInputs($selector);
            });
        });
    }
};
