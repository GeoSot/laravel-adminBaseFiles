
<div class="col-md-6 col-12 mb-3">
    @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('modelLang').'.formTitles.value')] )
        {!! form_until($form, 'value') !!}
    @endcomponent
</div>


<div class="col-md-6 col-12 mb-3">
    @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.first')] )
        <div class="text-right">
            <button data-toggle="enableDevFields" type="button" class="btn btn-outline-secondary btn-sm" title=" @lang($viewVals->get('modelLang').'.buttons.enableDevFieldsDesc')">
                @lang($viewVals->get('modelLang').'.buttons.enableDevFields')
            </button>
        </div>
        {!! form_until($form, 'group') !!}
    @endcomponent
</div>

@if($form->has('model_type'))
    <div class="col-md-6 col-12 mb-3">
        @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('modelLang').'.formTitles.relatedModel')] )
            <div class="text-right">
                <button data-toggle="enableDevFields" type="button" class="btn btn-outline-secondary btn-sm"
                        title=" @lang($viewVals->get('modelLang').'.buttons.enableDevFieldsDesc')">
                    @lang($viewVals->get('modelLang').'.buttons.enableDevFields')
                </button>
            </div>
            {!! form_until($form,'model_id') !!}
        @endcomponent
    </div>
@endif

<div class="col-md-6 col-12 mb-3">
    @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.third')]   )
        {!! form_row($form->notes) !!}
    @endcomponent
</div>
@push('scripts')
    <script defer data-comment="enableDevFields">
        $(document).on('click', '[data-toggle="enableDevFields"]', function (e) {

            $(this).parents('.card-body').find('input , select').each(function (i, el) {
                let $el = $(el);
                // if ($el.is('[disabled]')) {
                $el.removeAttr('disabled');
                $el.removeAttr('readonly');
                // } else {
                //     $el.attr('disabled', true);
                // }
            })
        });
    </script>

@endpush
