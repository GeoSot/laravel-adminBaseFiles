
window.Headroom = require('headroom.js');

class InitStickyElement {

    constructor(el) {
        this.el = el;
    }

    handleElement(makeItFix) {
        let _ = this;
        if (!_.el) {
            return;
        }
        let el = _.el;
        let st = el.style;
        if (window.innerWidth < 768 || makeItFix === false) {
            let init = 'initial';
            st.position = init;
            st.top = init;
            st.zIndex = init;
            st.width = init;
            el.parentElement.style.height = init;
            return;
        }
        let fixedHeights = 0;
        document.querySelectorAll(".fixed-top").forEach(function (el) {
            fixedHeights += el.offsetHeight
        });
        el.parentElement.style.height = el.offsetHeight + 'px';
        st.position = 'fixed';
        st.top = fixedHeights + 'px';
        st.zIndex = '1000';
        st.width = el.parentElement.offsetWidth + 'px';
    }

    initHeadRoom() {
        let _ = this;
        if (!_.el) {
            return;
        }
        let headroom = new Headroom(_.el, {
            offset: 60,
            tolerance: {up: 5, down: 0},
            // callback when pinned, `this` is headroom object
            onPin: function () {
                _.handleElement(true);
            },
            // callback when unpinned, `this` is headroom object
            onUnpin: function () {
                _.handleElement(true);
            },
            // callback when above offset, `this` is headroom object
            onTop: function () {
                _.handleElement(false);
            },
            // callback when below offset, `this` is headroom object
            onNotTop: function () {
            },
            // callback when at bottom of page, `this` is headroom object
            onBottom: function () {
            },
            // callback when moving BaseAdminay from bottom of page, `this` is headroom object
            onNotBottom: function () {
            }
        });
        headroom.init();
    }

    init() {
        let _ = this;
        _.initHeadRoom();
        window.addEventListener("resize", (function (e) {
            _.handleElement(true);
        }));
    }


}


let elem = document.querySelector(".js-buttonActions");

new InitStickyElement(elem).init();


