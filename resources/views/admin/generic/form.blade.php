@extends($packageVariables->get('adminLayout'))
@php
    use Illuminate\Support\Collection;
     /**
     * @var Collection $packageVariables
     * @var Collection $viewVals
     * @var \Kris\LaravelFormBuilder\Form $form
     */
@endphp
@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals]    )   @endcomponent

@section('content')
    @php
        $form=$viewVals->get('extraValues')->get('form');
        $fields=$form->getFields();
    @endphp
    {!!  form_start($form) !!}

    <div class="row">
        <div class="col-md-6 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.first')] )
                {!! form_rows($form, array_keys(Arr::except($fields,['order','notes','related_settings']))) !!}
            @endcomponent
        </div>

        @if(Arr::has($fields,'notes') or Arr::has($fields,'order') )
            <div class="col-md-6 col-12 mb-3">
                @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.third')]   )
                    @foreach (['order','notes'] as $field)
                        @if( Arr::has($fields,$field) and !$form->getField($field)->isRendered())
                            {!! form_row($form->{$field}) !!}
                        @endif
                    @endforeach
                @endcomponent
            </div>
        @endif
        @if(Arr::has($fields,'related_settings'))
            <div class="col-md-6 col-12 mb-3">
                @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.relatedSettings')]   )
                    {!! form_row($form->related_settings) !!}
                @endcomponent
            </div>
        @endif
    </div>



    {!!form_end($form, true); !!}
@endsection
