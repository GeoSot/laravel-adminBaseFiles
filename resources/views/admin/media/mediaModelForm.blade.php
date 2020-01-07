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
        $record=$viewVals->get('record');
        $fields=$form->getFields();
    @endphp
    {!!  form_start($form) !!}

    <div class="row">
        <div class="col-xl-5 col-md-6 col-12 mb-3 order-0 order-xl-0">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.first')] )
                {!! form_rows($form, array_keys(\Illuminate\Support\Arr::except($fields,['order']))) !!}
            @endcomponent
        </div>

        @hasSection ('mediaModelExtraContent')
            <div class="col-xl-3 col-md-6 col-12 mb-3  order-2 order-xl-3">
                @yield('mediaModelExtraContent')
            </div>
        @endif


        <div class="col-xl-4 col-12 mb-3  order-3 order-xl-2">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.second')] )
                @php( $staticValues=['collection_name','file','disk','thumb','size_mb','mime_type','extension'])
                @foreach($staticValues as $key)
                    <div class="mb-3">
                        <span class="text-muted">@lang("{$viewVals->get('modelLang')}.fields.$key"):</span>
                        {{data_get($record,$key)}}
                    </div>
                @endforeach
                @if($record && $record->ownerModel)
                    <div class="pt-3 mb-4 border-top"><span class="font-weight-bold text-muted ">@lang("{$viewVals->get('modelLang')}.fields.ownerModel.title") :</span>
                        {!! $record->ownerModel->getDashboardLink(class_basename($record->ownerModel)." (ID: {$record->ownerModel->getKey()})") !!}
                    </div>
                @endif
                {!! form_row($form->order) !!}

                @if($record && $record->custom_properties)
                    <div class="mt-5 small">
                        <span class="text-muted">@lang("{$viewVals->get('modelLang')}.fields.custom_properties"):</span>
                        @json($record->custom_properties)
                    </div>
                @endif
            @endcomponent
        </div>

    </div>

    {!!form_end($form, true); !!}
@endsection


