document.onreadystatechange = () => {
    if (document.readyState === 'complete') {
        import('./formFields').then(src => src.Init())
    }
};


$('.modal').on('shown.bs.modal', function () {
    let $shownInput = $(this).find('input, select, textarea').not(':hidden').first();
    if ($shownInput.length) {
        return $shownInput.trigger('focus');
    }
    let $wysiwyg = $(this).find('textarea').first().prev('.wysiwyg-editorWrapper').get(0);
    if ($wysiwyg) {
        $wysiwyg.dispatchEvent(new Event('customFocus'));
    }
});

$(document.activeElement).on("keydown", function (e) {
    // Ctrl-Enter pressed
    if (e.key === 'Enter' && e.ctrlKey) {
        let $parentForm = $(e.target).closest('form');
        // if ($parentForm.data('can-submit')) {
        $parentForm.submit();
        // }
    }
});


