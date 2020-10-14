@extends($packageVariables->get('siteLayout'))

@php($record= $viewVals->get('record') )
@php($form= $viewVals->get('extraValues')->get('form') )
@php($form2= $viewVals->get('extraValues')->get('form2') )
@php($roles= $viewVals->get('extraValues')->get('roles') )
@push('topBar')
    <h1 class="h3"> @lang($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.general.singular' ): {{$record->full_name}} </h1>
@endpush

@section('content')

    <div class="">
        <h4 class="d-inline-block">Roles:</h4>
        @foreach($roles as $role)
            <span class="badge badge-dark">{!! $role->display_name !!}</span>
        @endforeach
    </div>

    <section id="user_{{$record->id}}" class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            {!! form($form) !!}
        </div>
        <span class="my-5 col-md-12 col-lg"></span>
        <div class="col-12 col-md-6 col-lg-4 mb-5">
            {!! form($form2) !!}
        </div>
    </section>

@endsection
