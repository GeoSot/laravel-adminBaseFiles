@extends($packageVariables->get('siteLayout'))

@php($record= $viewVals->get('record') )
@php($form= $viewVals->get('extraValues')->get('form') )
@push('topBar')
    <h1 class="h3"> @lang($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.general.singular' ): {{$record->full_name}} </h1>
@endpush

@section('content')
    <section id="user_{{$record->id}}" class="row justify-content-center">
     <div class="col-12 col-md-8 col-lg-6">
            {!! form($form) !!}
     </div>
    </section>

@endsection
