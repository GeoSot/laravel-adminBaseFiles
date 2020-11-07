const classes = {
    noFile: 'fileinput-new',
    hasFile: ' fileinput-exists'
};

const triggers = {
    input: '[data-trigger="fileinput"]',
    remove: '[data-dismiss="fileinput"]',
    removeWrapper: '[data-remove="fileinput"]',
};


let fileInput_OBJ = function (el) {
    this.$wrapper = $(el).parents('[data-toggle="imageInput"], [data-toggle="fileInput"]');
    this.$input = this.$wrapper.find('input:file');
    this.isImage = (this.$wrapper.attr('data-toggle') === 'imageInput');
    this.$removeInput = this.$wrapper.find('input[name="remove_' + this.$input.attr('name') + '"]');
    this.$thumbWrapper = this.$wrapper.find('.fileinput-preview');
    this.invalidMessage = this.$wrapper.find('.fileinput-invalidMsg').text();
    this.$filename = this.$wrapper.find('.fileinput-filename');


    this.changeInputAndText = function (isFilled, name) {
        if (!isFilled) {
            this.$input.val('');
            if (this.$filename.length) {
                this.$filename.text('')
            }
            this.$wrapper.addClass(classes.noFile).removeClass(classes.hasFile);
        }
        if (isFilled) {
            if (this.$filename.length) {
                this.$filename.text(name)
            }
            this.$wrapper.removeClass(classes.noFile).addClass(classes.hasFile);
            this.$removeInput.val(null)
        }
    }
};


const init = () => {

    $(document).on('click', triggers.removeWrapper, function (e) {
        let el = new fileInput_OBJ(this);
        el.$wrapper.remove();
    });
    $(document).on('click', triggers.input, function (e) {
        let el = new fileInput_OBJ(this);
        el.$input.click();
    });
    $(document).on('click', triggers.remove, function (e) {
        let el = new fileInput_OBJ(this);
        el.$thumbWrapper.empty();
        el.changeInputAndText(false);
        el.$removeInput.val(el.$removeInput.data('id'));
    });

    $(document).on('change', '[data-toggle="imageInput"] input:file, [data-toggle="fileInput"] input:file', function () {
        let el = new fileInput_OBJ(this);
        if (this.files.length > 0) {
            let file = this.files[0];
            let allowedExtensions = /(jpg|jpeg|png|gif)$/i;
            if (!allowedExtensions.exec(file.type) && el.isImage) {
                Swal.fire('Oops...', el.invalidMessage, 'error');
                el.changeInputAndText(false);
                return;
            }

            if (el.isImage) {
                let reader = new FileReader();
                reader.onload = function () {
                    el.$thumbWrapper.html('<img src="' + reader.result + '" class="' + el.$thumbWrapper.attr('data-imgclass') + '">');
                    el.changeInputAndText(true, file.name);
                };
                reader.readAsDataURL(event.target.files[0]);
            } else {
                el.changeInputAndText(true, file.name);
            }
        }
    });


}
export default init;
