@extends($packageVariables->get('adminLayout'))
@php
    use Illuminate\Support\Collection;
     /**
     * @var Collection $packageVariables
     * @var Collection $viewVals
     */
@endphp

@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals] )
    @if ($viewVals->get('record') )
        @slot('after')
           {!!  $viewVals->get('record')->page->getDashBoardLink(__($viewVals->get('modelLang').'.general.pageLink'),false,['class'=>'ml-auto btn btn-sm btn-outline-admin']) !!}
        @endslot
    @endif

@endcomponent

@section('content')
    @php($form=$viewVals->get('extraValues')->get('form'))

    {!!  form_start($form) !!}

    <div class="row">
        <div class="col-xl-4  col-lg-5 col-md-6 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.first')] )
                {!! form_until($form, 'slug') !!}
            @endcomponent
        </div>
        <div class="col-xl-5 col-lg-7 col-md-6 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.second')] )
                {!! form_until($form, 'images') !!}
            @endcomponent
        </div>

        @include($viewVals->get('baseView').'._blocks',['class'=>'col-xl-3 col-12 mb-3'])
        <div class=" col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.third')] )
                {!!form_until($form, 'notes'); !!}
            @endcomponent
        </div>
    </div>
    {!!form_end($form, true); !!}
@endsection
