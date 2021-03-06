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
    _this.$wrapper = ($(el).is(selectors.trigger)) ? $(el) : $(el).parents(selectors.trigger);
    _this.$hiddenInput = this.$wrapper.find('[type="hidden"]');
    let element = _this.$hiddenInput.get(0);
    if (instances.has(element)) {
        return
        // return instances.get(element)
    } else {
        instances.set(element, _this);
    }
    _this.$hoursInput = _this.$wrapper.find('input').not('[type=hidden]').first();
    _this.$minutesInput = _this.$wrapper.find('input').not('[type=hidden]').last();
    $(this.$hiddenInput).off('input.formFields');
    $(this.$hoursInput).off('input.formFields');
    $(this.$minutesInput).off('input.formFields');

    _this.totalMinutes = 0;
    _this.minutes = 0;
    _this.hours = 0;

    let initializeFields = function () {
        _this.totalMinutes = parseInt(_this.$hiddenInput.val()) || 0;
        let time = _this.getTimeSeparate();
        _this.minutes = time.minutes;
        _this.hours = time.hours;
        _this.setTimeToInputs();
    };

    this.getTimeForHumans = function () {
        let time = _this.getTimeSeparate();
        return ("0" + time.hours).slice(-2) + ':' + ("0" + time.minutes).slice(-2);
    };


    this.getTimeSeparate = function () {
        return {
            hours: _this.totalMinutes / 60 | 0,
            minutes: _this.totalMinutes % 60 | 0
        }
    };
    this.getTimeToMinutes = function () {
        return _this.totalMinutes;
    };

    let updateTotalMinutes = function () {
        _this.totalMinutes = _this.minutes + (_this.hours * 60);
    };
    this.setTimeToInputs = () => {
        let time = _this.getTimeSeparate();
        _this.$hiddenInput.val(_this.totalMinutes);
        _this.$hoursInput.val(time.hours);
        _this.$minutesInput.val(time.minutes);
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

    $(this.$hiddenInput).on('input.formFields', function (e) {
        initializeFields();
    });
    initializeFields();


}


export {init};
