let eventName = 'jqIsLoaded';
let isLoadedEvent = new CustomEvent(eventName);

const getJquery = ()=>{
    return window.$ || window.jQuery;
}

const isJqLoaded = ()=>{
    // @ts-ignore
    return getJquery() !== undefined && jQuery.fn.popover !== undefined
}

const triggerIsLoaded = ()=>{
    document.dispatchEvent(isLoadedEvent);
}


const execute = (callback)=>{

    if (isJqLoaded())
    {
        callback(getJquery());
        return;
    }
    document.addEventListener(eventName, ()=>{
        callback(getJquery());
    });
}

module.exports = {triggerIsLoaded, execute}
