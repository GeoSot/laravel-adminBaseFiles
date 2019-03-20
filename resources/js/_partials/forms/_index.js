window.aw = window.aw || {};
window.aw.forms = window.aw.forms || {};


require('bootstrap-colorpicker');
window.Sortable = require('sortablejs');
require('./formFields');
require('./ajaxForms');
require('./formSubmitByKeyboard');

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
