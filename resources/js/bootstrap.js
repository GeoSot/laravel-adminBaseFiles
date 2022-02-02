import('jquery').then(jquery => {
    window.Popper = import('popper.js').then(src => src.default);
    window.$ = window.jQuery = jquery.default;


    import('bootstrap').then(src => {
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="toast"]').toast('show');
    });


    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
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

//
// window.envClient = (endpoint, customConfig) => {
//     const data= customConfig && customConfig.data
//     let headers = {
//         'Accept': 'application/json',
//         "X-CSRF-Token": '{{csrf_token()}}'
//     }
//     if (data) {
//         headers['Content-Type'] = 'application/json'
//         customConfig.body = JSON.stringify(customConfig.data)
//     }
//
//     const config = {
//         ...customConfig,
//         headers: headers,
//     }
//
//     return window
//         .fetch(endpoint, config)
//         .then(async response => {
//             const data = await response.json()
//             if (response.ok) {
//                 return data
//             }
//             envAlert('danger', data.message);
//             return Promise.reject(data)
//         })
// };
