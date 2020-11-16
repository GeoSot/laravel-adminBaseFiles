@extends($packageVariables->get('siteLayout'))

@section('content')

    @component('baseAdmin::auth._card')
        @slot('title')
            {{ __('Login') }}
        @endslot

        {!! form($form) !!}


        <div class="d-flex flex-wrap">
            @if (Route::has('register'))
                <a class="" href="{{route('register')}}">{{ __('Register') }}</a>
            @endif
            @if (Route::has('password.request'))
                <a  class="ml-auto" href="{{route('password.request',['email'=>old('email',request('email'))])}}">{{ __('Forgot Your Password?') }}</a>
            @endif
        </div>
    @endcomponent

@endsection
