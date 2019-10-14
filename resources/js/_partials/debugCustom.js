export default {
    function(tag, arg, title) {

        let styling = 'background: #444; color: #1bfff6; font-size:12px; border-radius:15px;';
        if (Laravel.debug) {
            title = (title) ? title : 'BaseAdmin Debug';
            tag = (tag) ? tag : '';
            console.info("%c " + title + ": ", styling, tag, arg);
            console.log('');
        }
    }
}
