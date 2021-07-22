let $;
const instances = new Map();
const selectors = {
    trigger: '[data-toggle="timePicker"]',
}
const init = () => {
    import('jquery').then(src => {
        $ = src.default
        $(document).on('focusin', `${selectors.trigger} input`, function (e) {
            new internalInit(this);
        });

        // $(function () {
        //     $(selectors.trigger).each(function (e) {
        //         new internalInit(this);
        //     });
        // });
    });

}

const internalInit = function (el) {
    const _this = this;
    this.$wrapper = ($(el).is(selectors.trigger)) ? $(el) : $(el).parents(selectors.trigger);
    this.$hiddenInput = this.$wrapper.find('[type="hidden"]');
    let element = this.$hiddenInput.get(0);
    if (instances.has(element)) {
        return
        // return instances.get(element)
    } else {
        instances.set(element, _this);
    }
    this.$hoursInput = _this.$wrapper.find('input').not('[type=hidden]').first();
    this.$minutesInput = _this.$wrapper.find('input').not('[type=hidden]').last();
    $(this.$hiddenInput).off('change.formFields');
    $(this.$hoursInput).off('input.formFields');
    $(this.$minutesInput).off('input.formFields');

    this.totalMinutes = 0;
    this.minutes = 0;
    this.hours = 0;

    let initializeFields = () => {
        _this.totalMinutes = parseInt(_this.$hiddenInput.val()) || 0;
        this.minutes = this.getTotalMinutes();
        this.hours = this.getTotalHours();
        _this.setTimeToInputs();
    };

    this.getTimeForHumans = function () {
        return ("0" + this.getTotalHours()).slice(-2) + ':' + ("0" + this.getTotalMinutes()).slice(-2);
    };

    this.getTotalMinutes = () => this.totalMinutes % 60 | 0
    this.getTotalHours = () => this.totalMinutes / 60 | 0
    this.getTimeToMinutes = () => this.totalMinutes

    let updateTotalMinutes = () =>{
        this.totalMinutes = this.minutes + (this.hours * 60);
    };
    this.setTimeToInputs = () => {
        _this.$hiddenInput.val(_this.totalMinutes);
        _this.$hoursInput.val(this.getTotalHours());
        _this.$minutesInput.val(this.getTotalMinutes());
    };


    $(this.$hoursInput).on('input.formFields', function (e) {
        _this.hours = parseInt($(this).val());
        updateTotalMinutes();
        _this.setTimeToInputs();
    });
    $(this.$minutesInput).on('input.formFields', function (e) {
        let minVal = parseInt($(this).val());
        if (minVal === 60) {
            _this.hours++;
            minVal = 0;
        }
        if (minVal === -1) {
            minVal = 0;
            if (_this.hours > 0) {
                _this.hours--;
                minVal = 59;
            }
        }
        _this.minutes = minVal;
        updateTotalMinutes();
        _this.setTimeToInputs();
    });

    $(this.$hiddenInput).on('change.formFields', function (e) {
        initializeFields();
    });
    initializeFields();
}


export {init};
