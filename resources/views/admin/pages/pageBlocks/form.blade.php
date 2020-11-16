@extends($packageVariables->get('adminLayout'))

@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals]  )
@endcomponent

@section('content')
    @php(/* @var \Illuminate\Support\Collection $viewVals */ $form=$viewVals->get('extraValues')->get('form'))

    {!!  form_start($form) !!}

    <div class="row">
        <div class=" col-xl-3 col-lg-4 col-md-6 col-12 mb-3 order-1">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.first')] )
                @if ($viewVals->get('action')=='edit'and !$viewVals->get('record')->hasOneImage())
                    <div class="text-right">
                        <span class="badge-success badge-pill badge">@lang($viewVals->get('modelLang').'.fields.has_multiple_images')</span>
                    </div>
                @endif
                {!! form_until($form, 'layout') !!}
            @endcomponent
        </div>
        <div class="col-xl-3 col-lg-12 col-md-6 col-12 mb-3 order-1 order-lg-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.second')] )
                @if ($viewVals->get('record') && $viewVals->get('record')->pageArea )
                    <div class="text-right">
                        {!!  $viewVals->get('record')->pageArea->frontConfigs->getAdminLink(__($viewVals->get('modelLang').'.general.pageAreaLink'),false,['class'=>'mb-3 btn btn-sm btn-outline-admin']) !!}
                    </div>
                @endif
                {!! form_until($form, 'background_color') !!}
            @endcomponent
        </div>
        @if ($viewVals->get('action')=='edit')
            <div class="col-xl-6 col-lg-8 col-12 mb-3 order-2">
                @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('modelLang').'.formTitles.content')] )
                    {!!form_until($form, 'images'); !!}
                @endcomponent
            </div>
        @endif
    </div>
    {!!form_end($form, true); !!}
@endsection
