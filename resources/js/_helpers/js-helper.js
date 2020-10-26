let eventName = 'jsIsLoaded';
let isLoadedEvent = new CustomEvent(eventName);


const isLoaded = () => {
    return window.BaseAdmin && BaseAdmin.ajaxLoadWrappers !== undefined && BaseAdmin.forms !== undefined
}

const triggerIsLoaded = () => {
    document.dispatchEvent(isLoadedEvent);
}

const execute = (callback) => {

    if (isLoaded()) {
        callback(BaseAdmin);
        return;
    }
    document.addEventListener(eventName, () => {
        callback(BaseAdmin);
    });
}

module.exports = {triggerIsLoaded, execute}
