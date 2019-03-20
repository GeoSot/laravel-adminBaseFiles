//$(document).on("keydown", "input:focus", function (e) {
$(document.activeElement).on("keydown", function (e) {
    // Ctrl-Enter pressed
    if ((e.keyCode === 10 || e.keyCode === 13) && e.ctrlKey) {
        let $parentForm = $(e.target).closest('form');
        // if ($parentForm.data('can-submit')) {
        $parentForm.submit();
        // }
    }
});