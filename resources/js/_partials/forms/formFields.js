BaseAdmin.forms = BaseAdmin.forms || {};
BaseAdmin.forms.initializedClass = 'formField-initialized';


BaseAdmin.forms.fields = {
    Instances: {
        timePickers: {}
    },
    imagePicker: function () {
        let classes = {
            noFile: 'fileinput-new',
            hasFile: ' fileinput-exists'
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
        let triggers = {
            input: '[data-trigger="fileinput"]',
            remove: '[data-dismiss="fileinput"]',
            removeWrapper: '[data-remove="fileinput"]',
        };


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
                    toastr.error(el.invalidMessage);
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

    },

    singleDatePicker: function () {
        const parentSelector = '[data-toggle="calendar"]';
        const clearSelector = '[data-clear="calendar"]';

        function initDateTimeOPicker($el = null) {


            $(parentSelector).not('.' + BaseAdmin.forms.initializedClass).each(function (i, elem) {
                if ($(elem).find('input[name$="_formatted"]').first().is(':disabled')) {
                    return;
                }
                $(elem).addClass(BaseAdmin.forms.initializedClass);
                let val = $(elem).find('input').not('*_formatted').first().val();
                let value = (val) ? moment(val) : moment();
                $(elem).daterangepicker({
                    //parentEl:$parent,
                    showDropdowns: true,
                    singleDatePicker: true,
                    startDate: value,
                    // autoUpdateInput: false,
                    timePicker: ($(elem).attr('data-locale').match(/H:|h:|mm/g) != null),
                    timePicker24Hour: true,
                    minYear: 1930,
                    locale: {
                        format: $(elem).attr('data-locale')
                    }

                }, function (start, end, label) {
                    let $parent = $(this.element);
                    let $input = $('[name="' + $parent.data('name') + '"]');
                    let $formatted_input = $('[name="' + $parent.data('name') + '_formatted"]');

                    if ($parent.data('name').indexOf(']') > 0) {//If is inside array
                        $formatted_input = $('[name="' + $parent.data('name').replace(']', '_formatted]') + '"]');
                    }

                    $formatted_input.val(start.format($parent.attr('data-locale')));
                    $input.val(start.format('YYYY-MM-DD HH:mm:ss'));
                });
                if ($el) {
                    $el.click();
                }
            });


        }

        $(document).on('click', parentSelector + ':not(.' + BaseAdmin.forms.initializedClass + ')', function () {
            initDateTimeOPicker($(this));
        });
        initDateTimeOPicker();
        $(document).on('click', clearSelector, function () {
            $(parentSelector).find('input').each(function () {
                $(this).val('');
            })
        });
    },
    DateRangePicker: function () {
        const parentSelector = '[data-toggle="dateRangeCalendar"]';
        const clearSelector = '[data-clear="dateRangeCalendar"]';

        function initDateRangePicker() {


            $(parentSelector).not('.' + BaseAdmin.forms.initializedClass).addClass(BaseAdmin.forms.initializedClass).each(function (i, elem) {
                if ($(elem).find('input[name$="_formatted"]').first().is(':disabled')) {
                    return;
                }
                let val = $(elem).find('input').not('*_formatted').first().val();
                let value = (val) ? moment(val) : moment();
                $(elem).daterangepicker({
                    //parentEl:$parent,
                    showDropdowns: true,
                    autoApply: true,
                    startDate: value,
                    // autoUpdateInput: false,
                    timePicker: ($(elem).attr('data-locale').match(/H:|h:|mm/g) != null),
                    timePicker24Hour: true,
                    minYear: 1930,
                    locale: {
                        format: $(elem).attr('data-locale')
                    },
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    linkedCalendars: false,
                    alwaysShowCalendars: true,

                }, function (start, end, label) {
                    let $parent = $(this.element);
                    let $inputStart = $('[name="' + $parent.data('name') + '[start]"]');
                    let $inputEnd = $('[name="' + $parent.data('name') + '[end]"]');
                    let $formatted_input = $('[name="' + $parent.data('name') + '_formatted"]');

                    if ($parent.data('name').indexOf(']') > 0) {//If is inside array
                        $formatted_input = $('[name="' + $parent.data('name').replace(']', '_formatted]') + '"]');
                    }
                    $formatted_input.val(start.format($parent.attr('data-locale')) + ' - ' + end.format($parent.attr('data-locale')));

                    $inputStart.val(start.format('YYYY-MM-DD HH:mm:ss'));
                    $inputEnd.val(end.format('YYYY-MM-DD HH:mm:ss'));
                });
            });


        }

        $(document).on('click', parentSelector + ':not(.' + BaseAdmin.forms.initializedClass + ')', function () {
            initDateRangePicker($(this))
        });
        initDateRangePicker();
        $(document).on('click', clearSelector, function () {
            $(parentSelector).find('input').each(function () {
                $(this).val('');
            })
        });
    }
    , multiplyField: function () {
        $(document).on('click', '.js-addToCollection', function (e) {
            e.preventDefault();
            let $container = $(this).closest('.js-collectionContainer');
            let $itemsContainer = $container.find('.js-collectionItems');
            //let count = $itemsContainer.children().length;
            let counter = parseInt($(this).attr('data-initial-count')) + 1;

            let proto = $(this).data('prototype').replace(/__NAME__/g, counter);
            $(this).attr('data-initial-count', counter);
            $itemsContainer.append(proto);
            $itemsContainer.find('textarea.withEditor').each(function () {
                BaseAdmin.initTextEditor(this);
            });
        });
    }, timePicker: function () {


        let timePicker_OBJ = function (el) {
            const _this = this;
            _this.$wrapper = ($(el).is('[data-toggle="timePicker"]')) ? $(el) : $(el).parents('[data-toggle="timePicker"]');
            _this.$hiddenInput = this.$wrapper.find('[type="hidden"]');
            let name = _this.$hiddenInput.attr('name');
            // if (Object.keys(BaseAdmin.forms.fields.Instances.timePickers).length && BaseAdmin.forms.fields.Instances.timePickers[name] !== 'undefined') {
            //     return BaseAdmin.forms.fields.Instances.timePickers[name];
            // } else {
            BaseAdmin.forms.fields.Instances.timePickers[name] = _this;
            // }
            _this.$hoursInput = _this.$wrapper.find('input').not('[type=hidden]').first();
            _this.$minutesInput = _this.$wrapper.find('input').not('[type=hidden]').last();
            $(this.$hiddenInput).off('change.formFields');
            $(this.$hoursInput).off('change.formFields');
            $(this.$minutesInput).off('change.formFields');

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


            $(this.$hoursInput).on('change.formFields', function (e) {
                _this.hours = parseInt($(this).val());
                updateTotalMinutes();
                _this.setTimeToInputs();
            });
            $(this.$minutesInput).on('change.formFields', function (e) {
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

        };

        $(document).on('click', '[data-toggle="timePicker"] input', function (e) {
            new timePicker_OBJ(this);
        });

        $(function () {
            $('[data-toggle="timePicker"]').each(function (e) {
                new timePicker_OBJ(this);
            });
        });

    }, colorPicker: function () {
        $('[data-toggle="colorPicker"]').each(function (e) {
            $(this).colorpicker();
        });
    }, sortable: function () {
        $('.sortableWrapper').each(function () {
            let sortable = Sortable.create(this, {
                group: BaseAdmin.uuid(),
                handle: ".sortingHandler",
                draggable: ".sortableItem",
            });
        });
    }, removeParent: function () {
        $(document).on('click', '[data-toggle="removeParent"]', function (e) {
            $(this).closest($(this).data('target')).remove();
        });
    }
};

BaseAdmin.forms.fields.Init = function () {
    BaseAdmin.forms.fields.singleDatePicker();
    BaseAdmin.forms.fields.DateRangePicker();
    BaseAdmin.forms.fields.imagePicker();
    BaseAdmin.forms.fields.multiplyField();
    BaseAdmin.forms.fields.timePicker();
    BaseAdmin.forms.fields.colorPicker();
    BaseAdmin.forms.fields.removeParent();
    BaseAdmin.forms.fields.sortable();
};


