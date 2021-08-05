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
    $(this.$hoursInput).off('change.formFields');
    $(this.$minutesInput).off('change.formFields');

    this.totalMinutes = 0;
    this.minutes = 0;
    this.hours = 0;

    let initializeFields = () => {
        const totalMinutes = parseInt(_this.$hiddenInput.val()) || 0;
        this.minutes = totalMinutes % 60;
        this.hours = Math.floor(totalMinutes / 60);
        _this.$hoursInput.val(this.hours);
        _this.$minutesInput.val(this.minutes);
    };

    this.getTimeForHumans = function () {
        return ("0" + this.getHours()).slice(-2) + ':' + ("0" + this.getMinutes()).slice(-2);
    };

    this.getMinutes = () => this.minutes
    this.getHours = () => this.hours
    this.getTimeToMinutes = () => this.hours * 60 + this.minutes

    $(this.$hoursInput).on('change.formFields', function (e) {
        _this.hours = parseInt($(this).val());
        _this.$hiddenInput.val(_this.getTimeToMinutes());
    });
    $(this.$minutesInput).on('change.formFields', function (e) {
        let minVal = parseInt($(this).val());
        let nowVal = minVal

        if (minVal > 59) {
            const hours = Math.floor(minVal / 60)
            _this.hours += hours;
            nowVal %= Math.abs(minVal) || 0;
        }

        if (minVal === -1) {
            if (_this.hours > 0) {
                _this.hours--
            }
            nowVal = 59;
        }
        if (minVal < -1) {
            minVal=Math.abs(minVal)
            const hours = Math.floor(minVal / 60)
            _this.hours -= hours;
            if (_this.hours < 0) {
                _this.hours = 0
            }
            nowVal = minVal % 60 ;
        }

        _this.minutes = nowVal;
        _this.$hiddenInput.val(_this.getTimeToMinutes());
        _this.$minutesInput.val(_this.minutes);
        _this.$hoursInput.val(_this.getHours());
    });

    $(this.$hiddenInput).on('change.formFields', function (e) {
        initializeFields();
    });
    initializeFields();
}


export {init};
