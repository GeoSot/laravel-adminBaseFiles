@extends($packageVariables->get('adminLayout'))
@php
    use Illuminate\Support\Collection;
     /**
     * @var Collection $packageVariables
     * @var Collection $viewVals
     */
@endphp
@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals]    )

    @if ($viewVals->get('record') && $link=$viewVals->get('record')->getPreviewLink())
        @slot('after')
            <a class="btn btn-outline-info btn-sm ml-auto" target="_blank" href="{{$link}}">
                <span class="btn-label"><i class="fas fa-chain"></i></span>
                @lang($viewVals->get('modelLang').'.general.view')
            </a>
        @endslot
    @endif

@endcomponent

@section('content')
    @php($form=$viewVals->get('extraValues')->get('form'))

    {!!  form_start($form) !!}

    <div class="row">
        <div class="col-xl-3  col-lg-5 col-md-6 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.first')] )
                {!! form_until($form, 'slug') !!}
            @endcomponent
        </div>
        <div class="col-xl-6 col-lg-7 col-md-6 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('modelLang').'.formTitles.metaTags')] )
                {!! form_until($form, 'images') !!}
            @endcomponent
        </div>

        @include($viewVals->get('baseView').'._pageAreas',['class'=>'col-xl-3 col-12 mb-3'])
        <div class=" col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['show'=>false,'title'=>__($viewVals->get('modelLang').'.formTitles.additionalScripts')] )
                {!!form_until($form,'javascript'); !!}
            @endcomponent
        </div>

        <div class=" col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['show'=>false,'title'=>__($viewVals->get('baseLang').'.formTitles.third')] )
                {!!form_until($form, 'notes'); !!}
            @endcomponent
        </div>
    </div>
    {!!form_end($form, true); !!}
@endsection
