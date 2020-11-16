 import 'lazysizes'


document.addEventListener('lazybeforeunveil', (evt) => {
    let bg = evt.target.getAttribute('data-bg');
    if (bg) {
        evt.target.style.backgroundImage = 'url(' + bg + ')';
        // delete evt.target.removeAttribute('bg');
    }
})
document.addEventListener('lazyloaded', (evt) => {
    delete evt.target.dataset.src;
    delete evt.target.dataset.bg;
});

