@extends($packageVariables->get('siteLayout'))

@section('content')

    @component('baseAdmin::auth._card')
        @slot('title')
            {{ __('Reset Password') }}
        @endslot
        {!! form($form) !!}

        <div class="text-right">
            <a href="{{route('password.request',['email'=>$form->getField('email')->getOption('value')])}}">{{ __('Send Password Reset Link') }}</a>
        </div>
    @endcomponent

@endsection
