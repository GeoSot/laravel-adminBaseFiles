export const ajaxLoadWrappers = wrappersToReload => {
    import('jquery').then(src => {
        let $ = src.default
        let wrappersArray = wrappersToReload.split(',');

        if ($(wrappersArray[0]).length) {
            $(wrappersArray[0]).load(location.href + ' ' + wrappersArray[0] + ' > *', function (response) {
                wrappersArray.shift();

                $.each(wrappersArray, function (key, selector) {
                    let $selector = $(selector);
                    $selector.html($(response).find(selector).html());
                    triggerAjaxLoadEvent($selector)
                });
            });
        }
    });
};


export const triggerAjaxLoadEvent = target => {
    let ev = new CustomEvent('baseAdmin:ajaxLoadWrappers', {detail: target});
    document.dispatchEvent(ev)
}
