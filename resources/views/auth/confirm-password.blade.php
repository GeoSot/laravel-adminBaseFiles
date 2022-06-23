@extends($packageVariables->get('siteLayout'))

@section('content')
    @component('baseAdmin::auth._card')
        @slot('title')
            {{ __('Confirm Password') }}
        @endslot
        {!! form($form) !!}

        @if (Route::has('password.request'))
            <div class="text-right">
            <a class="btn btn-link" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
            </div>
        @endif

    @endcomponent
@endsection
