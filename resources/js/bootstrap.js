window.axios = () => import('axios').then(src => src);
window.axios.defaults = window.axios.defaults || {};
window.axios.defaults.headers = window.axios.defaults.headers || {};
window.axios.defaults.headers.common = window.axios.defaults.headers.common || [];
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}


import('jquery').then(jquery => {
    window.Popper = import('popper.js').then(src => src.default);
    window.$ = window.jQuery = jquery.default;


    import('bootstrap').then(src => {
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="toast"]').toast('show');
    });


    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token.content
            }
        });
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }
    import('./_partials/forms/_index');

})

