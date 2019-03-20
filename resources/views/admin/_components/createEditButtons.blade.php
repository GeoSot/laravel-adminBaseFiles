@push('topBar')
    <!--com_createEditButtons -->
    @php
        $action=$viewVals->get('action');
            $btnsLang=$viewVals->get('baseLang').'.button';
            $baseRoute=$viewVals->get('baseRoute');
            $params=$viewVals->get('params');
            $options=$viewVals->get('options')->get("{$action}Actions");
            $modelClass=lcfirst($viewVals->get('modelClassShort'));
            $allowToHandle=$viewVals->get('record')?$viewVals->get('record')->allowedToHandle():true;

    @endphp
    <section class="button_actions card js-buttonActions shadow-sm">
		<div class=" card-body  py-2 d-flex align-items-center flex-wrap">

			@isset($before) {!!$before !!} @endisset

            @if(in_array('save',$options) and auth()->user()->can('admin.update-'.$modelClass) and $allowToHandle)
                <button id="save" class="btn btn-success  m-1" data-value="save" onclick="submitForm(this)">
					<span class="btn-label"><i class="fa fa-floppy-o"></i></span>
					<span class=""> @lang($packageVariables->get('nameSpace')."{$btnsLang}.save")</span>
				</button>
            @endif
            @if(in_array('saveAndClose',$options) and auth()->user()->can('admin.update-'.$modelClass) and $allowToHandle)
                <button id="save_and_close" class="btn btn-light  m-1" data-value="back" onclick="submitForm(this)">
					<span class="btn-label"><i class="fa fa-check"></i></span>
					<span class=""> @lang($packageVariables->get('nameSpace')."{$btnsLang}.saveAndClose")</span>
				</button>
            @endif
            @if(in_array('saveAndNew',$options) and auth()->user()->can('admin.update-'.$modelClass) and $allowToHandle)
                <button class="btn btn-light m-1" data-value="new" onclick="submitForm(this)">
						<span class="btn-label"><i class="fa fa-plus"></i></span>
						<span class=""> @lang($packageVariables->get('nameSpace')."{$btnsLang}.saveAndNew")</span>
					</button>
            @endif
            @if(in_array('makeNewCopy',$options) and auth()->user()->can('admin.create-'.$modelClass) and $allowToHandle)
                <button class="btn btn-primary  m-1" data-value="makeCopy" onclick="submitForm(this)">
						<span class="btn-label"><i class="fa fa-plus"></i></span>
						<span class=""> @lang($packageVariables->get('nameSpace')."{$btnsLang}.makeCopy")</span>
					</button>
            @endif

            {!! $slot !!}


            <button onclick="cancelButtonClicked(this)" id="back" data-value="back" class="btn btn-danger  m-1">
						<span class="btn-label"><i class="fa fa-reply"></i></span>
						<span class=""> @lang($packageVariables->get('nameSpace')."{$btnsLang}.cancel")</span>
					</button>


            @isset($after) {!!  $after  !!} @endisset

	</div>
</section>
    <!--com_createEditButtons END-->

@endpush
@push('scripts')
    <script data-comment="formSubmitButtons">
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
	</script>
@endpush
