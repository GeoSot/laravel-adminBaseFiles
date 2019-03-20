@extends($packageVariables->get('adminLayout'))

@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals]    )
@endcomponent

@section('content')
    @php
        $form=$viewVals->get('extraValues')->get('form');
        $fields=$form->getFields();
    @endphp
    {!!  form_start($form) !!}

    <div class="row">
        <div class="col-md-6 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($packageVariables->get('nameSpace').$viewVals->get('baseLang').'.formTitles.first')] )
                {!! form_rows($form, array_keys(\Illuminate\Support\Arr::except($fields,['order','notes','related_settings']))) !!}
            @endcomponent
        </div>

        @if(\Illuminate\Support\Arr::has($fields,'notes'))
            <div class="col-md-6 col-12 mb-3">
                @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($packageVariables->get('nameSpace').$viewVals->get('baseLang').'.formTitles.third')]   )
                    @if( \Illuminate\Support\Arr::has($fields,'order') and !$form->getField('order')->isRendered())
                        {!! form_row($form->order) !!}
                    @endif
                    {!! form_row($form->notes) !!}
                @endcomponent
             </div>
        @endif
        @if(\Illuminate\Support\Arr::has($fields,'related_settings'))
            <div class="col-md-6 col-12 mb-3">
                @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($packageVariables->get('nameSpace').$viewVals->get('baseLang').'.formTitles.relatedSettings')]   )
                    {!! form_row($form->related_settings) !!}
                @endcomponent
             </div>
        @endif
    </div>



    {!!form_end($form, true); !!}
@endsection
