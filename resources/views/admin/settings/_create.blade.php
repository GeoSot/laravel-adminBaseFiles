
<div class="col-md-6 col-12 mb-3">
    @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.first')] )
            {!! form_until($form, 'model_type') !!}
    @endcomponent
</div>

