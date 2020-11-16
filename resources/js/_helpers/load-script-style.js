const getScriptElement = (SRC) => {
    let script = document.createElement('script');
    script.async = true;
    script.defer = true;
    script.type = 'text/javascript';
    script.src = '//' + SRC;
    script.id = SRC;
    // script.crossorigin='anonymous';
    return script;
}
const getStyleElement = (SRC) => {
    let link = document.createElement('link');
    link.rel = 'stylesheet';
    link.type = 'text/css';
    link.media = 'all';
    link.href = '//' + SRC;
    link.id = SRC;
    return link;
}

const sanitizeSrc = (SRC) => SRC.replace(/^https?:\/\//i, '').replace(/^\/\//i, '')


const loadGeneric = (scriptSrc, type = '') => {
    return new Promise((resolve, reject) => {
        if (!scriptSrc) {
            reject();
        }
        let SRC = sanitizeSrc(scriptSrc)

        const existingScript = document.getElementById(SRC);
        if (existingScript) {
            resolve();
            return;
        }

        let $el;
        if (type === 'script') {
            $el = getScriptElement(SRC);
            document.body.appendChild($el);
        } else {
            $el = getStyleElement(SRC);
            let head = document.getElementsByTagName('head')[0];
            head.appendChild($el);
        }

        $el.onload = () => {
            resolve();
        };
        $el.error = () => {
            reject();
        };
        $el.addEventListener('load', resolve);
        $el.addEventListener('error', reject);

    });

}
const loadScript = (src) => {
    return loadGeneric(src, 'script')
}

const loadStyle = (src) => {
    return loadGeneric(src, 'style');
}

module.exports = {loadScript, loadStyle};

