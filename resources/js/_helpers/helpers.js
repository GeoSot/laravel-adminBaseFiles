import {execute} from "./jQuery-helper";
import {loadScript, loadStyle} from './load-script-style'

const debug = (tag, arg, title) => {

    let styling = 'background: #444; color: #1bfff6; font-size:12px; border-radius:15px;';
    if (Laravel.debug) {
        title = (title) ? title : 'BaseAdmin Debug';
        tag = (tag) ? tag : '';
        console.info("%c " + title + ": ", styling, tag, arg);
        console.log('');
    }
}

export default {
    loadScript: loadScript,
    loadStyle: loadStyle,
    debug: debug,
    jQuery: {
        execute: execute
    },
    uuid: () => {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        )
    }
}
