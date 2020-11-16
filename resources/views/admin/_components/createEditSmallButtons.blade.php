@php
    use Illuminate\Support\Collection;
  /**
  * @var Collection $viewVals
  */
          $btnsLang=$viewVals->get('baseLang').'.button';
          $baseRoute=$viewVals->get('baseRoute');
@endphp

@push('topBar')
    <section class="d-flex  px-3 mt-1">
        @include($packageVariables->get('blades').'admin._components.revisions')

        @if($viewVals->get('options')->get('modelIsTranslatable'))
            <div class="ml-auto">
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
            </div>
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
    <script defer data-comment="formLanguageButtons">


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
