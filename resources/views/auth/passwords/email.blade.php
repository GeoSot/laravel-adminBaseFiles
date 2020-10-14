@extends($packageVariables->get('siteLayout'))

@section('content')

    @component('baseAdmin::auth._card')
        @slot('title')
            {{ __('Reset Password') }}
        @endslot
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        {!! form($form) !!}
        <div class="text-right">
            <a href="{{route('login')}}" class="btn btn-link">{{__('Login')}}</a>
        </div>
    @endcomponent
@endsection
