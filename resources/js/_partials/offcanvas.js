$(function () {
    'use strict';
    $('[data-toggle="offcanvas"]').each(function () {
        let $el = {
            header: $('.js-mainHeader'),
            el: $(this),
            parent: $($(this).data('parent')),
            target: $($(this).data('target'))
        };
        alignOffCanvas($el);
        $el.el.on('click', function () {
            manageClasses($el);
        });
        $(window).resize(function () {
            alignOffCanvas($el);
        });
    });
});


function alignOffCanvas($el) {
    let height = $el.header.height() + 'px';
    if ($el.header.css("position") === "fixed") {
        $el.parent.css('padding-top', height);
    }
    if ($el.target.css("position") === "fixed") {
        $el.target.css('top', height);
        $el.target.css('margin-left', 0);
    } else {
        $el.target.css('top', 0);
    }
}

function manageClasses($el) {
    let isToggled = $el.el.hasClass('open');

    if (isToggled) {
        $el.el.removeClass('open');
        $el.parent.removeClass('open');
        $el.target.removeClass('open');
        if ($el.target.css("position") !== "fixed") {
            $el.target.css('margin-left', 0);
        }
    } else {
        $el.el.addClass('open');
        $el.parent.addClass('open');
        $el.target.addClass('open');
        if ($el.target.css("position") !== "fixed") {
            // console.log($el.target.width());
            // console.log($el.target.outerWidth(true));
            $el.target.css('margin-left', -$el.target.outerWidth(true) + 'px');
        }
    }
}
