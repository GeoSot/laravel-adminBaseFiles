document.addEventListener("DOMContentLoaded", function () {
    let lazyImages = document.querySelectorAll("img.js-lazy");
    let lazyImageWrappers = document.querySelectorAll(".js-lazyBg");

    lazyLoad(lazyImages, function (img) {
        img.src = img.dataset.src;
        img.srcset = img.dataset.srcset || '';
        img.classList.remove("js-lazy");
    });

    lazyLoad(lazyImageWrappers, function (wrapper) {
        let bgImg = wrapper.dataset.img;
        wrapper.style.backgroundImage = " url('" + bgImg + "')";
        wrapper.classList.remove("js-lazyBg");
    });

});

lazyLoad = function (items, callBack) {
    if (typeof callBack !== 'function') {
        return;
    }
    if ('IntersectionObserver' in window) {

        const imageObserver = new IntersectionObserver((entries, imgObserver) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    const lazyImage = entry.target;
                    callBack(lazyImage);
                    imgObserver.unobserve(lazyImage);
                }
            })
        }, {rootMargin: '100px 0px 100px 0px'});
        items.forEach((v) => {
            imageObserver.observe(v);
        })

    } else {
        Array.from(items).forEach(image => callBack(image));
    }

};



