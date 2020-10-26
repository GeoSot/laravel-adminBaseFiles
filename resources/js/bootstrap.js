window.jsHelper = {
    loader: require('./_helpers/load-script-style'),
    jQuery: require('./_helpers/jQuery-helper'),
    base: require('./_helpers/js-helper'),
    debug: require('./_helpers/debugCustom'),
    uuid : () => {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        )
    }
}
window.BaseAdmin = window.BaseAdmin || {};
// try {
//     window.Popper = require('popper.js').default;
//     window.$ = window.jQuery = require('jquery');
//
//     require('bootstrap');
// } catch (e) {
// }

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
//
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
//
window.$ = window.jQuery = require('jquery')

window.Popper = require('popper.js').default;

require('bootstrap');


$('[data-toggle="popover"]').popover();
$('[data-toggle="tooltip"]').tooltip();
$('.toast').toast('show');

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    // window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': token.content
        }
    });
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}


jsHelper.jQuery.triggerIsLoaded()


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });




/*

import('jquery').then(src=>{
    window.Popper = import('popper.js').then(src=>src.default);
    window.$ = window.jQuery = src.default;
    import('../components/offcanvas');

    import('bootstrap').then(src=>{
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="toast"]').toast('show');

        JQueryHelperDynamic().then(src=>{
            src.triggerJqIsLoaded()
        });

        import('./navbar').then(src=>{
            src.init('js-navigation-menu')
        });

    });

})
*/
