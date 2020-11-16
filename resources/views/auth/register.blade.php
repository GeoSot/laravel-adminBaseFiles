@extends($packageVariables->get('siteLayout'))

@section('content')

    @component('baseAdmin::auth._card')
        @slot('title')
            {{ __('Register') }}
        @endslot
        {!! form($form) !!}
    @endcomponent

@endsection

