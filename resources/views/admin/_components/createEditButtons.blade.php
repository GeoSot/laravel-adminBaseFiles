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

            @if(in_array('save',$options) and auth()->user()->isAbleTo('admin.update-'.$modelClass) and $allowToHandle)
                <button id="save" class="btn btn-success  m-1" data-value="save" onclick="submitForm(this)">
                    <span class="btn-label"><i class="far fa-save"></i></span>
                    <span class=""> @lang("{$btnsLang}.save")</span>
                </button>
            @endif
            @if(in_array('saveAndClose',$options) and auth()->user()->isAbleTo('admin.update-'.$modelClass) and $allowToHandle)
                <button id="save_and_close" class="btn btn-light  m-1" data-value="back" onclick="submitForm(this)">
                    <span class="btn-label"><i class="fas fa-check"></i></span>
                    <span class=""> @lang("{$btnsLang}.saveAndClose")</span>
                </button>
            @endif
            @if(in_array('saveAndNew',$options) and auth()->user()->isAbleTo('admin.update-'.$modelClass) and $allowToHandle)
                <button class="btn btn-light m-1" data-value="new" onclick="submitForm(this)">
                    <span class="btn-label"><i class="fas fa-plus"></i></span>
                    <span class=""> @lang("{$btnsLang}.saveAndNew")</span>
                </button>
            @endif
            @if(in_array('makeNewCopy',$options) and auth()->user()->isAbleTo('admin.create-'.$modelClass) and $allowToHandle)
                <button class="btn btn-primary  m-1" data-value="makeCopy" onclick="submitForm(this)">
                    <span class="btn-label"><i class="fas fa-plus"></i></span>
                    <span class="">@lang("{$btnsLang}.makeCopy")</span>
                </button>
            @endif

            {!! $slot !!}


            <button onclick="cancelButtonClicked(this)" id="back" data-value="back" class="btn btn-danger  m-1">
                <span class="btn-label"><i class="fas fa-reply"></i></span>
                <span class=""> @lang("{$btnsLang}.cancel")</span>
            </button>


            @isset($after) {!!  $after  !!} @endisset

            @if($viewVals->get('record') && in_array('delete',$options) && auth()->user()->isAbleTo('admin.delete-'.$modelClass) && $allowToHandle)
                <button id="delete-items" data-toggle="listing-actions" data-method="DELETE" data-keyword="delete" class="btn btn-danger m-1 ml-auto"
                        data-after_save_redirect_to="{{$viewVals->get('record')->frontConfigs->getRoute('index','admin')}}"
                        type="button" data-url="{{ $viewVals->get('record')->frontConfigs->getRoute('delete','admin') }}">
                    <span class="btn-label"><i class="far fa-trash-alt"></i></span>
                    <span class="btn_text"> @lang("{$btnsLang}.delete")</span>
                    <input id="record_{{$viewVals->get('record')->id}}" type="checkbox" class=""hidden checked  value="{{$viewVals->get('record')->id}}"/>

                </button>
            @endif

        </div>
    </section>
    <!--com_createEditButtons END-->

@endprepend
@include($packageVariables->get('blades').'admin._includes.listingHiddenMessages')
@include($packageVariables->get('blades').'admin._components.createEditSmallButtons')

@push('scripts')
    <script defer data-comment="formSubmitButtons">
        function cancelButtonClicked() {
            let $hiddenRedirectInput = $('input[name="after_save_redirect_to"]');

            let url = '{!!  \GeoSot\BaseAdmin\Helpers\Base::getCachedRouteAsLink($viewVals->get('baseRoute').'.index') !!}';
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

        {{--jsHelper.jQuery.execute(() => {--}}
        {{--    new BaseAdmin.forms.ajaxify("form#mainForm").onSubmit(function (instance, jqxhr) {--}}
        {{--        jqxhr.done(function (data) {--}}
        {{--            // jsHelper.debug('form#mainForm', instance, 'ajaxifyForm newPostForm');--}}

        {{--            Swal.fire('{{  __('message.success_title') }}', data.responseText);--}}
        {{--            BaseAdmin.ajaxLoadWrappers('#app, .js-scripts, #js-notifications');--}}
        {{--        });--}}
        {{--    });--}}
        {{--})--}}

    </script>
@endpush
