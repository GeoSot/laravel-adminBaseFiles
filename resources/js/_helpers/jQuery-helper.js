const getJquery = () => {
    return window.$ || window.jQuery;
}

const isJqLoaded = () => {
    return getJquery() !== undefined
}

const execute = (callback) => {
    if (isJqLoaded()) {
        callback(getJquery());
        return;
    }
    import('jquery').then(src => {
        let $ = src.default

        callback($);
    });
}

export {execute}
