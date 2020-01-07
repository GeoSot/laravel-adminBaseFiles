@prepend('topBar')
    <!--com_createEditButtons -->
    @php
        use Illuminate\Support\Collection;
      /**
      * @var Collection $viewVals
      */
          $action=$viewVals->get('action');
              $btnsLang=$viewVals->get('baseLang').'.button';
              $baseRoute=$viewVals->get('baseRoute');
              $params=$viewVals->get('params');
              $options=$viewVals->get('options')->get("{$action}Actions");
              $modelClass=lcfirst($viewVals->get('modelClassShort'));
              $allowToHandle=$viewVals->get('record')?$viewVals->get('record')->allowedToHandle():true
    @endphp
    <section class="button_actions card js-buttonActions shadow-sm">
        <div class=" card-body  py-2 d-flex align-items-center flex-wrap">

            @isset($before) {!!$before !!} @endisset

            @if(in_array('save',$options) and auth()->user()->can('admin.update-'.$modelClass) and $allowToHandle)
                <button id="save" class="btn btn-success  m-1" data-value="save" onclick="submitForm(this)">
                    <span class="btn-label"><i class="fa fa-floppy-o"></i></span>
                    <span class=""> @lang("{$btnsLang}.save")</span>
                </button>
            @endif
            @if(in_array('saveAndClose',$options) and auth()->user()->can('admin.update-'.$modelClass) and $allowToHandle)
                <button id="save_and_close" class="btn btn-light  m-1" data-value="back" onclick="submitForm(this)">
                    <span class="btn-label"><i class="fa fa-check"></i></span>
                    <span class=""> @lang("{$btnsLang}.saveAndClose")</span>
                </button>
            @endif
            @if(in_array('saveAndNew',$options) and auth()->user()->can('admin.update-'.$modelClass) and $allowToHandle)
                <button class="btn btn-light m-1" data-value="new" onclick="submitForm(this)">
                    <span class="btn-label"><i class="fa fa-plus"></i></span>
                    <span class=""> @lang("{$btnsLang}.saveAndNew")</span>
                </button>
            @endif
            @if(in_array('makeNewCopy',$options) and auth()->user()->can('admin.create-'.$modelClass) and $allowToHandle)
                <button class="btn btn-primary  m-1" data-value="makeCopy" onclick="submitForm(this)">
                    <span class="btn-label"><i class="fa fa-plus"></i></span>
                    <span class=""> @lang("{$btnsLang}.makeCopy")</span>
                </button>
            @endif

            {!! $slot !!}


            <button onclick="cancelButtonClicked(this)" id="back" data-value="back" class="btn btn-danger  m-1">
                <span class="btn-label"><i class="fa fa-reply"></i></span>
                <span class=""> @lang("{$btnsLang}.cancel")</span>
            </button>


            @isset($after) {!!  $after  !!} @endisset

        </div>
    </section>
    <!--com_createEditButtons END-->

@endprepend


@push('topBar')
    <section class="d-flex justify-content-end px-3 mt-1">
        @if($viewVals->get('options')->get('modelIsTranslatable'))
            @if(1)
                <select class="form-control custom-select  custom-select-sm w-auto m-1 " id="formLanguages" name="formLanguages" data-change="js-form-languages"
                        onchange="formLanguageChanged(this)">
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <option
                            value="{{$localeCode}}" {!! ( LaravelLocalization::getCurrentLocale()==$localeCode) ? 'selected="selected"':'' !!}> {{Arr::get($properties,'name')}}</option>
                    @endforeach

                </select>
            @else
                <div class="js-form-languages d-flex">
                    <span>@lang($btnsLang=$viewVals->get('baseLang').'.internationalization.formAvailableLanguages'):</span>
                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        <div class="mx-1 @if($errors->has($localeCode.'.*')) border-bottom border-danger @endif ">
                            <button onclick="formLanguageClicked(this)"
                                    class="btn-sm btn btn-light  @if( LaravelLocalization::getCurrentLocale()==$localeCode) active @endif "
                                    value="{{$localeCode}}"> {{Arr::get($properties,'name')}}</button>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </section>
@endpush

@push('styles')
    <style>
        .form-group-translation[group-translation]:not([group-translation="{{LaravelLocalization::getCurrentLocale()}}"]) {
            display: none;
        }
    </style>
@endpush
@push('scripts')
    <script defer data-comment="formSubmitButtons formLanguageButtons">
        function cancelButtonClicked() {
            let $hiddenRedirectInput = $('input[name="after_save_redirect_to"]');

            let url = '{!!  getCachedRouteAsLink($viewVals->get('baseRoute').'.index') !!}';
            if ($hiddenRedirectInput.length && $hiddenRedirectInput.val()) {
                url = $hiddenRedirectInput.val();
            }
            location.href = url;
        }

        function submitForm(el) {
            let val = el.getAttribute('data-value');
            let $form = $("#mainForm");
            document.querySelector('[name="after_save"]').value = val;
            if (val === 'makeCopy') {
                $form.find('[disabled="disabled"]').each(function () {
                    $(this).prop('disabled', false);
                })
            }
            //console.log(el.getAttribute('data-value'))
            // document.getElementById('mainForm').submit();
            $form.submit();
        }

        function formLanguageChanged(el) {
            let val = el.value;
            let selectedLang = '[group-translation="' + val + '"]';
            let otherLanguages = '.form-group-translation[group-translation]:not(' + selectedLang + ')';
            document.querySelectorAll(otherLanguages).forEach(function (el) {
                el.style.display = "none";
            });
            document.querySelectorAll(selectedLang).forEach(function (el) {
                el.style.display = "inherit";
            });
        }

        function formLanguageClicked(el) {

            $(el).addClass('active');
            $('.js-form-languages button').not(el).removeClass('active');
            let val = el.value;
            let selectedLang = '[group-translation="' + val + '"]';
            let otherLanguages = '.form-group-translation[group-translation]:not(' + selectedLang + ')';
            document.querySelectorAll(otherLanguages).forEach(function (el) {
                el.style.display = "none";
            });
            document.querySelectorAll(selectedLang).forEach(function (el) {
                el.style.display = "inherit";
            });
        }


    </script>
@endpush
