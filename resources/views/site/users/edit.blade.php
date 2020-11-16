@extends($packageVariables->get('siteLayout'))

@php($record= $viewVals->get('record') )
@php($roles= $viewVals->get('extraValues')->get('roles') )
@section('documentTitle')@lang($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.general.singular' ): {{$record->full_name}}@endsection

@section('content')

    <div class="">
        <h4 class="d-inline-block">Roles:</h4>
        @foreach($roles as $role)
            <span class="badge badge-dark">{!! $role->display_name !!}</span>
        @endforeach
    </div>

    <section id="user_{{$record->id}}" class="row justify-content-center">

        @if(\Laravel\Fortify\Features::enabled(\Laravel\Fortify\Features::updateProfileInformation()))
            @php($form= $viewVals->get('extraValues')->get('form') )
            <div class="col-12 col-md-6 col-lg-4">
                {!! form($form) !!}
            </div>
        @endif
        @if(\Laravel\Fortify\Features::enabled(\Laravel\Fortify\Features::updatePasswords()))
            <span class="my-5 col-md-12 col-lg"></span>

            @php($form2= $viewVals->get('extraValues')->get('form2') )
            <div class="col-12 col-md-6 col-lg-4 mb-5">
                {!! form($form2) !!}
            </div>
        @endif
    </section>

@endsection
